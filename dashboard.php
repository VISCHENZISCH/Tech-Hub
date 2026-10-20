<?php
/**
 * Tech Hub — Centre de Télémétrie & Supervision Système
 * Récupération directe des métriques matérielles et processus Linux en temps réel.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/icons.php';
require_once __DIR__ . '/includes/system_metrics.php';

$currentUser = getCurrentUser();
$isAuth = isLoggedIn();
$userName = trim((string) ($currentUser['name'] ?? 'Opérateur'));
$userEmail = trim((string) ($currentUser['email'] ?? 'analyste@techhub.io'));
$userRole = trim((string) ($currentUser['role'] ?? 'Opérateur'));
$userInitial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($userName, 0, 1)) : strtoupper(substr($userName, 0, 1));

$data = require __DIR__ . '/data/content.php';

// Récupération des données réelles du serveur pour le rendu initial immédiat
$cpuUsage = getCpuUsage();
$cpuInfo = getCpuInfo();
$memInfo = getMemoryInfo();
$storageInfo = getStorageInfo();
$netRates = getNetworkRates();
$sysInfo = getSystemInfo();
$topProcesses = getTopProcesses(10);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Moniteur Système - <?= htmlspecialchars($data['site']['title']) ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2322d3ee' stroke-width='2'><path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-shell">
        <!-- Sidebar Navigation (Apple Smoked Glass) -->
        <aside class="dashboard-sidebar" aria-label="Navigation du centre de supervision">
            <a class="dashboard-brand" href="index.php" aria-label="Retour à l'accueil Tech Hub">
                <span class="dashboard-logo"><?= render_icon('shield', '', 1.9) ?></span>
                <span><b>Tech</b> Hub</span>
            </a>

            <div class="dashboard-section-label">Supervision Matérielle</div>
            <nav class="dashboard-nav">
                <a class="dashboard-nav-link active" href="dashboard.php" aria-current="page">
                    <?= render_icon('cpu', '', 1.8) ?>
                    <span>Vue Télémétrie</span>
                </a>
                <a class="dashboard-nav-link" href="#activite">
                    <?= render_icon('activity', '', 1.8) ?>
                    <span>Charge & Flux</span>
                </a>
                <a class="dashboard-nav-link" href="#processus">
                    <?= render_icon('server', '', 1.8) ?>
                    <span>Processus</span>
                </a>
                <a class="dashboard-nav-link" href="#stockage">
                    <?= render_icon('database', '', 1.8) ?>
                    <span>Disque & I/O</span>
                </a>
            </nav>

            <div class="dashboard-sidebar-bottom">
                <a class="dashboard-nav-link" href="index.php">
                    <?= render_icon('layout-dashboard', '', 1.8) ?>
                    <span>Accueil Tech Hub</span>
                </a>
                <?php if ($isAuth): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.55rem 0.75rem; background: rgba(255, 255, 255, 0.04); border-radius: 8px; margin-top: 0.5rem; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div style="display: flex; align-items: center; gap: 0.55rem; min-width: 0;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: #06b6d4; color: #041019; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; flex-shrink: 0;">
                            <?= htmlspecialchars($userInitial) ?>
                        </span>
                        <div style="min-width: 0; line-height: 1.2;">
                            <div style="font-size: 0.82rem; font-weight: 600; color: #f8fafc; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($userName) ?></div>
                            <div style="font-size: 0.7rem; color: #94a3b8;"><?= htmlspecialchars($userRole) ?></div>
                        </div>
                    </div>
                    <a href="logout.php" title="Se déconnecter" style="color: #ef4444; display: inline-flex; align-items: center; padding: 5px; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.15)'" onmouseout="this.style.background='transparent'">
                        <?= render_icon('log-out', '', 1.6) ?>
                    </a>
                </div>
                <?php else: ?>
                <a class="dashboard-nav-link" href="login.php" style="margin-top: 0.5rem; color: #38bdf8;">
                    <?= render_icon('log-in', '', 1.8) ?>
                    <span>Connexion</span>
                </a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Main Dashboard Viewport -->
        <main class="dashboard-main">
            <!-- Header avec informations temps réel et contrôles de fréquence -->
            <header class="dashboard-header">
                <div>
                    <p class="dashboard-eyebrow">
                        <span class="status-dot"></span>
                        <span><?= htmlspecialchars($sysInfo['hostname']) ?> · IP <?= htmlspecialchars($sysInfo['ip']) ?></span>
                    </p>
                    <h1>Moniteur Système en Direct</h1>
                    <p class="dashboard-header-copy">
                        <?= htmlspecialchars($sysInfo['os']) ?> · Uptime: <strong id="headerUptime" style="color: #ffffff;"><?= htmlspecialchars($sysInfo['uptime_formatted']) ?></strong>
                    </p>
                </div>
                <div class="dashboard-header-actions">
                    <div class="range-tabs" role="group" aria-label="Fréquence d'actualisation">
                        <button class="active" type="button" data-interval="2000">2s</button>
                        <button type="button" data-interval="1000">1s</button>
                        <button type="button" data-interval="5000">5s</button>
                    </div>
                    <button class="live-toggle" id="liveToggle" type="button" aria-pressed="true">
                        <span class="live-dot"></span>
                        <span class="live-toggle-label">Direct</span>
                    </button>
                    <button class="refresh-button" id="refreshBtn" type="button" aria-label="Actualiser les métriques">
                        <?= render_icon('refresh-cw', '', 1.8) ?>
                    </button>
                </div>
            </header>

            <!-- 4 Cartes Métriques (Gris Sombre Transparent - Smoked Glass) -->
            <section class="dashboard-metrics" aria-label="Indicateurs clés du serveur">
                <!-- 1. Processeur (CPU) -->
                <article class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon cyan"><?= render_icon('cpu', '', 1.8) ?></span>
                        <span class="pill-badge" id="cpuTemp"><?= $cpuInfo['temperature_c'] !== null ? $cpuInfo['temperature_c'] . ' °C' : 'Actif' ?></span>
                    </div>
                    <p>Processeur (CPU)</p>
                    <strong id="cpuVal"><?= $cpuUsage ?> %</strong>
                    <div class="glass-bar" style="margin-top: 0.65rem;">
                        <div class="glass-bar-fill" id="cpuBar" style="width: <?= min(100, max(3, $cpuUsage)) ?>%;"></div>
                    </div>
                    <div class="metric-badges-row">
                        <span class="pill-badge" id="cpuCores"><?= $cpuInfo['cores'] ?> cœurs</span>
                        <span class="pill-badge" id="cpuGhz"><?= $cpuInfo['frequency_ghz'] ?> GHz</span>
                    </div>
                </article>

                <!-- 2. Mémoire RAM -->
                <article class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon violet"><?= render_icon('database', '', 1.8) ?></span>
                        <span class="pill-badge purple" id="ramAvail">Dispo: <?= $memInfo['available_gb'] ?> GB</span>
                    </div>
                    <p>Mémoire Vive (RAM)</p>
                    <strong id="ramVal"><?= $memInfo['used_gb'] ?> GB</strong>
                    <div class="glass-bar" style="margin-top: 0.65rem;">
                        <div class="glass-bar-fill purple" id="ramBar" style="width: <?= min(100, max(3, $memInfo['percent'])) ?>%;"></div>
                    </div>
                    <div class="metric-badges-row">
                        <small id="ramTotal">sur <?= $memInfo['total_gb'] ?> GB (<?= $memInfo['percent'] ?>%)</small>
                        <span class="pill-badge" id="swapVal">Swap: <?= $memInfo['swap_used_mb'] ?> MB</span>
                    </div>
                </article>

                <!-- 3. Stockage Système -->
                <article class="metric-card" id="stockage">
                    <div class="metric-card-top">
                        <span class="metric-icon green"><?= render_icon('server', '', 1.8) ?></span>
                        <span class="pill-badge" id="diskFree">Libre: <?= $storageInfo['free_gb'] ?> GB</span>
                    </div>
                    <p>Disque Système (/)</p>
                    <strong id="diskVal"><?= $storageInfo['used_gb'] ?> GB</strong>
                    <div class="glass-bar" style="margin-top: 0.65rem;">
                        <div class="glass-bar-fill amber" id="diskBar" style="width: <?= min(100, max(3, $storageInfo['percent'])) ?>%;"></div>
                    </div>
                    <div class="metric-badges-row">
                        <small>sur <?= $storageInfo['total_gb'] ?> GB</small>
                        <span class="pill-badge positive"><?= $storageInfo['percent'] ?>% occupé</span>
                    </div>
                </article>

                <!-- 4. Débit Réseau & I/O -->
                <article class="metric-card">
                    <div class="metric-card-top">
                        <span class="metric-icon blue"><?= render_icon('activity', '', 1.8) ?></span>
                        <span class="pill-badge positive">En ligne</span>
                    </div>
                    <p>Trafic Réseau (I/O)</p>
                    <strong id="netRxVal">↓ <?= $netRates['rx_formatted'] ?></strong>
                    <div class="glass-bar" style="margin-top: 0.65rem;">
                        <div class="glass-bar-fill green" style="width: 100%;"></div>
                    </div>
                    <div class="metric-badges-row">
                        <span id="netTxVal" style="color: #cbd5e1; font-size: 0.72rem; font-weight: 500;">↑ <?= $netRates['tx_formatted'] ?></span>
                        <span class="pill-badge" id="procCountPill"><?= $sysInfo['process_count'] ?> proc.</span>
                    </div>
                </article>
            </section>

            <!-- Grille Centrale : Graphique Dynamique & Détails Matériels -->
            <section class="dashboard-content-grid" id="activite">
                <!-- Panneau Graphique de Charge en Temps Réel -->
                <article class="dashboard-panel traffic-panel">
                    <div class="panel-header">
                        <div>
                            <h2>Activité Système en Direct</h2>
                            <p>Évolution continue de la charge CPU et de l'occupation mémoire (dernières 60s)</p>
                        </div>
                        <div class="traffic-legend">
                            <span><i class="legend-dot cyan"></i> CPU : <b id="cpuLegendVal" style="color: #67e8f9; margin-left: 0.2rem;"><?= $cpuUsage ?> %</b></span>
                            <span><i class="legend-dot violet"></i> RAM : <b id="ramLegendVal" style="color: #d8b4fe; margin-left: 0.2rem;"><?= $memInfo['percent'] ?> %</b></span>
                        </div>
                    </div>

                    <div class="traffic-chart" role="img" aria-label="Graphique d'activité système temps réel">
                        <svg id="liveChartSvg" viewBox="0 0 760 200" preserveAspectRatio="none" aria-hidden="true">
                            <defs>
                                <linearGradient id="chart-cpu-grad" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.32"/>
                                    <stop offset="100%" stop-color="#22d3ee" stop-opacity="0"/>
                                </linearGradient>
                                <linearGradient id="chart-ram-grad" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="#a855f7" stop-opacity="0.22"/>
                                    <stop offset="100%" stop-color="#a855f7" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <g class="chart-grid">
                                <line x1="0" y1="15" x2="760" y2="15"/>
                                <line x1="0" y1="65" x2="760" y2="65"/>
                                <line x1="0" y1="115" x2="760" y2="115"/>
                                <line x1="0" y1="165" x2="760" y2="165"/>
                            </g>
                            <!-- RAM Surface & Line -->
                            <path id="chartRamFill" fill="url(#chart-ram-grad)" d="M0 200 Z"/>
                            <path id="chartRamLine" class="network-line" d="M0 200"/>
                            <!-- CPU Surface & Line -->
                            <path id="chartCpuFill" fill="url(#chart-cpu-grad)" d="M0 200 Z"/>
                            <path id="chartCpuLine" class="alerts-line" d="M0 200"/>
                            <!-- Glowing current point -->
                            <circle id="chartPoint" class="chart-highlight" cx="760" cy="100" r="5"/>
                        </svg>
                        <div class="chart-labels">
                            <span>-60 s</span>
                            <span>-45 s</span>
                            <span>-30 s</span>
                            <span>-15 s</span>
                            <span style="color: #67e8f9; font-weight: 700;">Direct</span>
                        </div>
                    </div>
                </article>

                <!-- Panneau Télémétrie & Spécifications Matérielles -->
                <article class="dashboard-panel sensor-panel">
                    <div class="panel-header">
                        <div>
                            <h2>Spécifications & Télémétrie</h2>
                            <p>Architecture matérielle détectée</p>
                        </div>
                    </div>

                    <div class="spec-overview">
                        <div class="spec-item">
                            <span class="spec-label">Processeur</span>
                            <strong class="spec-val" title="<?= htmlspecialchars($cpuInfo['model']) ?>">
                                <?= htmlspecialchars($cpuInfo['model']) ?>
                            </strong>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Cœurs logiques</span>
                            <strong class="spec-val"><?= $cpuInfo['cores'] ?> threads actifs (hyper-threading)</strong>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Température du die</span>
                            <div>
                                <span class="pill-badge <?= ($cpuInfo['temperature_c'] ?? 50) > 75 ? 'danger' : 'positive' ?>" id="tempBadge">
                                    <?= $cpuInfo['temperature_c'] !== null ? $cpuInfo['temperature_c'] . ' °C · Normal' : 'En ligne (Cloud/VM)' ?>
                                </span>
                            </div>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Charge système (Load Avg)</span>
                            <strong class="spec-val" id="loadAvgPills">
                                <?= implode(' · ', $cpuInfo['load_avg']) ?>
                            </strong>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Mémoire en cache</span>
                            <strong class="spec-val" id="cachedMem"><?= $memInfo['cached_gb'] ?> GB mis en cache</strong>
                        </div>
                    </div>
                </article>
            </section>

            <!-- Panneau Processus en Direct (Dark Smoked Glass) -->
            <section class="dashboard-panel alerts-panel" id="processus">
                <div class="panel-header alerts-panel-header">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <h2>Processus Actifs en Temps Réel</h2>
                            <span class="pill-badge" id="procCountBadge"><?= $sysInfo['process_count'] ?> processus</span>
                        </div>
                        <p>Applications et services système classés par consommation CPU & RAM.</p>
                    </div>
                    <div class="alerts-actions">
                        <label class="alert-search">
                            <span class="sr-only">Filtrer un processus</span>
                            <?= render_icon('search', '', 1.7) ?>
                            <input id="processSearch" type="search" placeholder="Filtrer (ex: chrome, spotify, php)..." autocomplete="off">
                            <kbd>⌘ K</kbd>
                        </label>
                    </div>
                </div>

                <div class="alert-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>PID</th>
                                <th>Nom du processus</th>
                                <th>Utilisateur</th>
                                <th>Consommation CPU</th>
                                <th>Mémoire RAM</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody id="processesTable">
                            <?php foreach ($topProcesses as $proc): ?>
                            <?php 
                                $isHigh = $proc['cpu'] > 20; 
                                $isMed = $proc['cpu'] > 8;
                                $cpuCls = $isHigh ? 'danger' : ($isMed ? 'warning' : '');
                            ?>
                            <tr data-search="<?= htmlspecialchars($proc['pid'] . ' ' . strtolower($proc['name'])) ?>">
                                <td><code><?= htmlspecialchars($proc['pid']) ?></code></td>
                                <td class="proc-name">
                                    <span class="proc-icon"><?= render_process_icon($proc['name']) ?></span>
                                    <strong><?= htmlspecialchars($proc['name']) ?></strong>
                                </td>
                                <td><span class="proc-user"><?= htmlspecialchars($proc['user']) ?></span></td>
                                <td>
                                    <div class="proc-meter-cell">
                                        <span class="pill-badge <?= $cpuCls ?>"><?= $proc['cpu'] ?>%</span>
                                        <div class="proc-mini-bar">
                                            <div class="fill <?= $cpuCls ?>" style="width: <?= min(100, $proc['cpu'] * 2) ?>%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="proc-meter-cell">
                                        <span><?= $proc['mem'] ?>%</span>
                                        <div class="proc-mini-bar">
                                            <div class="fill purple" style="width: <?= min(100, $proc['mem'] * 4) ?>%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="proc-status-pill">Actif</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="alert-empty" id="processEmpty" hidden>
                        Aucun processus ne correspond à cette recherche.
                    </div>
                </div>

                <div class="alerts-footer">
                    <span id="procTotalPill"><?= $sysInfo['process_count'] ?> processus actifs au total</span>
                    <span style="color: rgba(255, 255, 255, 0.45); font-size: 0.68rem;">
                        Synchronisation continue via le noyau Linux
                    </span>
                </div>
            </section>
        </main>
    </div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>
