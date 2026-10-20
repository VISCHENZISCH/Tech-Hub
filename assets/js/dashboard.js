/**
 * Tech Hub — Moniteur Système en Temps Réel (Dashboard)
 * Télémétrie directe du serveur Linux avec animation fluide et graphique temps réel
 */

document.addEventListener('DOMContentLoaded', () => {
  const dashboard = document.querySelector('.dashboard-body');
  if (!dashboard) return;

  // Historique glissant pour le graphique (30 points)
  const MAX_POINTS = 30;
  const cpuHistory = [];
  const ramHistory = [];

  // État du polling
  let pollIntervalMs = 2000;
  let isPaused = false;
  let pollTimer = null;
  let isFetching = false;

  // Éléments du DOM
  const cpuVal = document.getElementById('cpuVal');
  const cpuBar = document.getElementById('cpuBar');
  const cpuCores = document.getElementById('cpuCores');
  const cpuGhz = document.getElementById('cpuGhz');
  const cpuTemp = document.getElementById('cpuTemp');
  const cpuLegendVal = document.getElementById('cpuLegendVal');

  const ramVal = document.getElementById('ramVal');
  const ramTotal = document.getElementById('ramTotal');
  const ramBar = document.getElementById('ramBar');
  const ramAvail = document.getElementById('ramAvail');
  const swapVal = document.getElementById('swapVal');
  const ramLegendVal = document.getElementById('ramLegendVal');

  const diskVal = document.getElementById('diskVal');
  const diskBar = document.getElementById('diskBar');
  const diskFree = document.getElementById('diskFree');

  const netRxVal = document.getElementById('netRxVal');
  const netTxVal = document.getElementById('netTxVal');
  const procCountPill = document.getElementById('procCountPill');
  const procCountBadge = document.getElementById('procCountBadge');
  const procTotalPill = document.getElementById('procTotalPill');
  const headerUptime = document.getElementById('headerUptime');
  const loadAvgPills = document.getElementById('loadAvgPills');
  const tempBadge = document.getElementById('tempBadge');

  const chartSvg = document.getElementById('liveChartSvg');
  const cpuLine = document.getElementById('chartCpuLine');
  const cpuFill = document.getElementById('chartCpuFill');
  const ramLine = document.getElementById('chartRamLine');
  const ramFill = document.getElementById('chartRamFill');
  const chartPoint = document.getElementById('chartPoint');

  const procTable = document.getElementById('processesTable');
  const procSearch = document.getElementById('processSearch');
  const procEmpty = document.getElementById('processEmpty');
  const liveToggle = document.getElementById('liveToggle');
  const refreshBtn = document.getElementById('refreshBtn');

  // Initialisation de l'historique avec les données initiales
  const initCpu = parseFloat(cpuVal?.textContent) || 10;
  const initRam = parseFloat(ramBar?.style.width) || 60;
  for (let i = 0; i < MAX_POINTS; i++) {
    cpuHistory.push(initCpu);
    ramHistory.push(initRam);
  }
  renderChart();

  // Fonction de génération des chemins SVG (courbe lissée)
  function buildSvgPaths(data, width = 760, height = 200, bottomY = 200) {
    if (data.length < 2) return { line: '', fill: '' };
    const step = width / (MAX_POINTS - 1);
    const points = data.map((val, idx) => {
      const clamped = Math.max(0, Math.min(100, val));
      const x = idx * step;
      // Inversion Y : 100% -> y = 10, 0% -> y = 190
      const y = height - (clamped / 100) * (height - 20) - 10;
      return [x, y];
    });

    // Courbe de Bézier cubique naturelle
    let pathD = `M ${points[0][0].toFixed(1)} ${points[0][1].toFixed(1)}`;
    for (let i = 0; i < points.length - 1; i++) {
      const p0 = points[i];
      const p1 = points[i + 1];
      const cx1 = p0[0] + (p1[0] - p0[0]) * 0.45;
      const cy1 = p0[1];
      const cx2 = p0[0] + (p1[0] - p0[0]) * 0.55;
      const cy2 = p1[1];
      pathD += ` C ${cx1.toFixed(1)} ${cy1.toFixed(1)}, ${cx2.toFixed(1)} ${cy2.toFixed(1)}, ${p1[0].toFixed(1)} ${p1[1].toFixed(1)}`;
    }

    const fillD = `${pathD} V ${bottomY} H 0 Z`;
    return { line: pathD, fill: fillD, lastPoint: points[points.length - 1] };
  }

  function renderChart() {
    if (!cpuLine || !cpuFill) return;
    const cpuPaths = buildSvgPaths(cpuHistory, 760, 200, 200);
    const ramPaths = buildSvgPaths(ramHistory, 760, 200, 200);

    cpuLine.setAttribute('d', cpuPaths.line);
    cpuFill.setAttribute('d', cpuPaths.fill);

    if (ramLine && ramFill) {
      ramLine.setAttribute('d', ramPaths.line);
      ramFill.setAttribute('d', ramPaths.fill);
    }

    if (chartPoint && cpuPaths.lastPoint) {
      chartPoint.setAttribute('cx', cpuPaths.lastPoint[0].toFixed(1));
      chartPoint.setAttribute('cy', cpuPaths.lastPoint[1].toFixed(1));
    }
  }

  // Clignotement subtil du point de statut lors d'une mise à jour réussie
  function flashUpdateIndicator() {
    const dot = document.querySelector('.status-dot');
    if (dot) {
      dot.style.transform = 'scale(1.4)';
      dot.style.filter = 'brightness(1.5)';
      setTimeout(() => {
        dot.style.transform = '';
        dot.style.filter = '';
      }, 250);
    }
  }

  // Requête API pour actualiser les données réelles
  async function fetchLiveMetrics(force = false) {
    if (isFetching) return;
    if (isPaused && !force) return;
    isFetching = true;

    try {
      const res = await fetch('api/system-stats.php?t=' + Date.now(), { cache: 'no-store' });
      if (!res.ok) throw new Error('Erreur API ' + res.status);
      const data = await res.json();
      if (!data.success) return;

      updateDashboard(data);
      flashUpdateIndicator();
    } catch (err) {
      console.warn('Erreur télémétrie:', err);
    } finally {
      isFetching = false;
    }
  }

  function updateDashboard(data) {
    const cpu = data.cpu || {};
    const mem = data.memory || {};
    const storage = data.storage || {};
    const net = data.network || {};
    const sys = data.system || {};
    const procs = data.processes || [];

    // 1. CPU
    const cpuPercent = parseFloat(cpu.usage_percent) || 0;
    cpuHistory.push(cpuPercent);
    if (cpuHistory.length > MAX_POINTS) cpuHistory.shift();

    if (cpuVal) cpuVal.textContent = cpuPercent.toFixed(1) + ' %';
    if (cpuBar) {
      cpuBar.style.width = Math.min(100, Math.max(3, cpuPercent)) + '%';
      cpuBar.className = 'glass-bar-fill ' + (cpuPercent > 80 ? 'danger' : cpuPercent > 50 ? 'amber' : '');
    }
    if (cpuCores) cpuCores.textContent = (cpu.cores || 1) + ' cœurs';
    if (cpuGhz) cpuGhz.textContent = (cpu.frequency_ghz || 2.0) + ' GHz';
    if (cpuTemp) {
      cpuTemp.textContent = (cpu.temperature_c !== null && cpu.temperature_c !== undefined) 
        ? `${cpu.temperature_c} °C` 
        : 'Actif';
    }
    if (cpuLegendVal) cpuLegendVal.textContent = cpuPercent.toFixed(1) + ' %';

    // 2. RAM
    const ramPercent = parseFloat(mem.percent) || 0;
    ramHistory.push(ramPercent);
    if (ramHistory.length > MAX_POINTS) ramHistory.shift();

    if (ramVal) ramVal.textContent = (mem.used_gb || 0) + ' GB';
    if (ramTotal) ramTotal.textContent = `sur ${mem.total_gb || 0} GB (${ramPercent}%)`;
    if (ramBar) ramBar.style.width = Math.min(100, Math.max(3, ramPercent)) + '%';
    if (ramAvail) ramAvail.textContent = `Dispo: ${mem.available_gb || 0} GB`;
    if (swapVal) swapVal.textContent = `Swap: ${mem.swap_used_mb || 0} MB`;
    if (ramLegendVal) ramLegendVal.textContent = ramPercent.toFixed(1) + ' %';

    // 3. Disque
    if (diskVal) diskVal.textContent = (storage.used_gb || 0) + ' GB';
    if (diskBar) diskBar.style.width = Math.min(100, Math.max(3, storage.percent || 0)) + '%';
    if (diskFree) diskFree.textContent = `Libre: ${storage.free_gb || 0} GB`;

    // 4. Réseau & Télémétrie
    if (netRxVal) netRxVal.textContent = '↓ ' + (net.rx_formatted || '0 B/s');
    if (netTxVal) netTxVal.textContent = '↑ ' + (net.tx_formatted || '0 B/s');
    if (procCountBadge) procCountBadge.textContent = (sys.process_count || 100) + ' processus';
    if (procCountPill) procCountPill.textContent = (sys.process_count || 100) + ' processus';
    if (procTotalPill) procTotalPill.textContent = (sys.process_count || 100) + ' processus actifs au total';
    if (headerUptime) headerUptime.textContent = sys.uptime_formatted || '1h 00m';

    // 5. Spécifications & Température
    if (loadAvgPills && cpu.load_avg) {
      loadAvgPills.textContent = cpu.load_avg.join(' · ');
    }
    if (tempBadge) {
      if (cpu.temperature_c !== null && cpu.temperature_c !== undefined) {
        const t = cpu.temperature_c;
        const status = t > 80 ? 'Critique' : t > 68 ? 'Élevé' : 'Normal';
        const cls = t > 80 ? 'danger' : t > 68 ? 'warning' : 'positive';
        tempBadge.className = 'pill-badge ' + cls;
        tempBadge.textContent = `${t} °C · ${status}`;
      } else {
        tempBadge.className = 'pill-badge positive';
        tempBadge.textContent = 'En ligne (Cloud/VM)';
      }
    }

    // 6. Graphique
    renderChart();

    // 7. Table des processus
    renderProcessTable(procs);
  }

  function renderProcessTable(processes) {
    if (!procTable) return;
    const query = (procSearch?.value || '').trim().toLowerCase();

    let html = '';
    let visibleCount = 0;

    processes.forEach(p => {
      const match = !query || p.name.toLowerCase().includes(query) || String(p.pid).includes(query);
      const isHighCpu = p.cpu > 20;
      const isMedCpu = p.cpu > 8;
      const cpuBadgeClass = isHighCpu ? 'danger' : isMedCpu ? 'warning' : '';

      html += `
        <tr data-search="${p.pid} ${p.name.toLowerCase()}" ${match ? '' : 'hidden'}>
          <td><code>${p.pid}</code></td>
          <td class="proc-name">
            <span class="proc-icon">${getProcessSvg(p.name)}</span>
            <strong>${escapeHtml(p.name)}</strong>
          </td>
          <td><span class="proc-user">${escapeHtml(p.user || 'root')}</span></td>
          <td>
            <div class="proc-meter-cell">
              <span class="pill-badge ${cpuBadgeClass}">${p.cpu}%</span>
              <div class="proc-mini-bar"><div class="fill ${cpuBadgeClass}" style="width: ${Math.min(100, p.cpu * 2)}%"></div></div>
            </div>
          </td>
          <td>
            <div class="proc-meter-cell">
              <span>${p.mem}%</span>
              <div class="proc-mini-bar"><div class="fill purple" style="width: ${Math.min(100, p.mem * 4)}%"></div></div>
            </div>
          </td>
          <td><span class="proc-status-pill">Actif</span></td>
        </tr>
      `;
      if (match) visibleCount++;
    });

    procTable.innerHTML = html;
    if (procEmpty) procEmpty.hidden = visibleCount > 0;
  }

  function getProcessSvg(name) {
    const n = String(name || '').toLowerCase();
    const attrs = 'width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';

    // 1. Nginx
    if (n.includes('nginx')) {
      return `<svg ${attrs} stroke="#10b981"><polygon points="12 2 22 7.5 22 16.5 12 22 2 16.5 2 7.5 12 2"/><path d="M7 16V8l10 8V8"/></svg>`;
    }
    // 2. Apache / HTTPD
    if (n.includes('apache') || n.includes('httpd')) {
      return `<svg ${attrs} stroke="#f59e0b"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/></svg>`;
    }
    // 3. PHP / PHP-FPM
    if (n.includes('php')) {
      return `<svg ${attrs} stroke="#818cf8"><ellipse cx="12" cy="12" rx="10" ry="6"/><path d="M7 10h3a2 2 0 1 1 0 4H7v-4z"/><line x1="14" y1="10" x2="14" y2="14"/><line x1="17" y1="10" x2="17" y2="14"/></svg>`;
    }
    // 4. MySQL / MariaDB
    if (n.includes('mysql') || n.includes('mariadb')) {
      return `<svg ${attrs} stroke="#38bdf8"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.66 3.58 3 8 3s8-1.34 8-3V5"/><path d="M4 11v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6"/></svg>`;
    }
    // 5. PostgreSQL
    if (n.includes('postgres') || n.includes('postmaster')) {
      return `<svg ${attrs} stroke="#60a5fa"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.66 3.58 3 8 3s8-1.34 8-3V5"/><line x1="12" y1="8" x2="12" y2="22"/></svg>`;
    }
    // 6. Redis
    if (n.includes('redis')) {
      return `<svg ${attrs} stroke="#f87171"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/></svg>`;
    }
    // 7. Docker / Containerd
    if (n.includes('docker') || n.includes('containerd') || n.includes('runc')) {
      return `<svg ${attrs} stroke="#38bdf8"><path d="M2 13h20v2a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4v-2z"/><rect x="5" y="7" width="3" height="3"/><rect x="10" y="7" width="3" height="3"/><rect x="15" y="7" width="3" height="3"/><rect x="10" y="3" width="3" height="3"/></svg>`;
    }
    // 8. SSH Daemon
    if (n.includes('sshd') || n.includes('ssh')) {
      return `<svg ${attrs} stroke="#34d399"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16.5" r="1.2" fill="#34d399"/></svg>`;
    }
    // 9. Systemd & Init
    if (n.includes('systemd') || n.includes('init')) {
      return `<svg ${attrs} stroke="#94a3b8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>`;
    }
    // 10. Cron
    if (n.includes('cron')) {
      return `<svg ${attrs} stroke="#fbbf24"><circle cx="12" cy="12" r="9"/><polyline points="12 6 12 12 16 14"/></svg>`;
    }
    // 11. Python / Gunicorn / Uvicorn
    if (n.includes('python') || n.includes('gunicorn') || n.includes('uvicorn')) {
      return `<svg ${attrs} stroke="#38bdf8"><path d="M12 2C7 2 7.5 4.5 7.5 4.5L7.5 7h4.5v1H5s-3 .3-3 5S4.5 18 4.5 18H7v-2.5s-.2-2.5 2.5-2.5h5s2.5 0 2.5-2.5V4.5S17 2 12 2z"/><circle cx="9.5" cy="4.5" r=".7" fill="#38bdf8"/></svg>`;
    }
    // 12. Node / PM2 / Bun
    if (n.includes('node') || n.includes('npm') || n.includes('pm2') || n.includes('bun')) {
      return `<svg ${attrs} stroke="#4ade80"><polygon points="12 2 21 7 21 17 12 22 3 17 3 7 12 2"/><line x1="12" y1="2" x2="12" y2="22"/><polyline points="21 7 12 12 3 7"/></svg>`;
    }
    // 13. Shell / Terminal (bash, zsh, sh)
    if (n.includes('bash') || n.includes('zsh') || n.includes('sh') || n.includes('tmux')) {
      return `<svg ${attrs} stroke="#a78bfa"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>`;
    }
    // 14. TMOG / Télémétrie
    if (n.includes('tmog')) {
      return `<svg ${attrs} stroke="#22d3ee"><polyline points="2 12 5 12 8 4 12 20 16 8 19 12 22 12"/></svg>`;
    }
    // 15. Firewall / Sécurité (ufw, fail2ban)
    if (n.includes('fail2ban') || n.includes('ufw') || n.includes('iptables')) {
      return `<svg ${attrs} stroke="#f87171"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;
    }
    // 16. Logs / Syslog
    if (n.includes('syslog') || n.includes('journal')) {
      return `<svg ${attrs} stroke="#94a3b8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>`;
    }
    // 17. Spotify
    if (n.includes('spotify')) {
      return `<svg ${attrs} stroke="#22c55e"><circle cx="12" cy="12" r="10"/><path d="M8 14.5c2.5-1 5.5-1 8 0"/><path d="M7 11.5c3-1.2 7-1.2 10 0"/><path d="M6 8.5c3.5-1.5 8.5-1.5 12 0"/></svg>`;
    }
    // 18. Navigateurs (Chrome, Firefox)
    if (n.includes('chrome') || n.includes('firefox')) {
      return `<svg ${attrs} stroke="#38bdf8"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="21.17" y1="8" x2="12" y2="8"/><line x1="3.95" y1="6.06" x2="8.54" y2="14"/><line x1="10.88" y1="21.94" x2="15.46" y2="14"/></svg>`;
    }
    // 19. IDE / Code (Antigravity, VSCode)
    if (n.includes('ide') || n.includes('code') || n.includes('antigravity')) {
      return `<svg ${attrs} stroke="#38bdf8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>`;
    }
    // 20. Processus Générique / Kernel Thread
    return `<svg ${attrs} stroke="#94a3b8"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/></svg>`;
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, m => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[m]);
  }

  // Filtrage temps réel sur recherche
  if (procSearch) {
    procSearch.addEventListener('input', () => {
      const query = procSearch.value.trim().toLowerCase();
      let visible = 0;
      procTable?.querySelectorAll('tr').forEach(row => {
        const text = (row.dataset.search || row.textContent).toLowerCase();
        const matches = !query || text.includes(query);
        row.hidden = !matches;
        if (matches) visible++;
      });
      if (procEmpty) procEmpty.hidden = visible > 0;
    });

    document.addEventListener('keydown', e => {
      if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        procSearch.focus();
      }
    });
  }

  // Boutons d'intervalle (1s, 2s, 5s)
  const intervalBtns = document.querySelectorAll('[data-interval]');
  intervalBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      intervalBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      pollIntervalMs = parseInt(btn.dataset.interval, 10) || 2000;

      // Si le monitoring était en pause, réactiver immédiatement le direct
      if (isPaused) {
        isPaused = false;
        if (liveToggle) {
          liveToggle.setAttribute('aria-pressed', 'true');
          liveToggle.classList.remove('is-paused');
          const label = liveToggle.querySelector('.live-toggle-label');
          if (label) label.textContent = 'Direct';
        }
      }

      // Actualisation immédiate pour un retour visuel instantané
      fetchLiveMetrics(true);
      restartPolling();
    });
  });

  // Bouton Pause / Direct
  if (liveToggle) {
    liveToggle.addEventListener('click', () => {
      isPaused = !isPaused;
      liveToggle.setAttribute('aria-pressed', String(!isPaused));
      liveToggle.classList.toggle('is-paused', isPaused);
      const label = liveToggle.querySelector('.live-toggle-label');
      if (label) label.textContent = isPaused ? 'En pause' : 'Direct';

      if (!isPaused) {
        fetchLiveMetrics(true);
        restartPolling();
      } else {
        if (pollTimer) {
          clearInterval(pollTimer);
          pollTimer = null;
        }
      }
    });
  }

  // Bouton d'actualisation manuelle / Reset
  if (refreshBtn) {
    refreshBtn.addEventListener('click', async () => {
      refreshBtn.classList.add('is-refreshing');
      isFetching = false; // Réinitialise tout blocage éventuel

      // Si le monitoring était en pause, on le réactive
      if (isPaused) {
        isPaused = false;
        if (liveToggle) {
          liveToggle.setAttribute('aria-pressed', 'true');
          liveToggle.classList.remove('is-paused');
          const label = liveToggle.querySelector('.live-toggle-label');
          if (label) label.textContent = 'Direct';
        }
      }

      try {
        await fetchLiveMetrics(true);
      } finally {
        restartPolling();
        setTimeout(() => refreshBtn.classList.remove('is-refreshing'), 550);
      }
    });
  }

  function restartPolling() {
    if (pollTimer) clearInterval(pollTimer);
    if (!isPaused) {
      pollTimer = setInterval(() => fetchLiveMetrics(false), pollIntervalMs);
    }
  }

  // Démarrage du cycle de polling
  restartPolling();
});
