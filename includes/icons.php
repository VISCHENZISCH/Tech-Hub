<?php
/**
 * Helper to render SVG Lucide-style icons natively without external dependencies.
 */
function render_icon(string $name, string $classes = '', float $strokeWidth = 1.5): string {
    $attrs = 'class="' . htmlspecialchars($classes) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . $strokeWidth . '" stroke-linecap="round" stroke-linejoin="round"';

    switch (strtolower(trim($name))) {
        case 'shield':
            return '<svg ' . $attrs . '><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
            
        case 'trending-up':
        case 'trendingup':
            return '<svg ' . $attrs . '><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>';
            
        case 'code':
            return '<svg ' . $attrs . '><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
            
        case 'code-2':
        case 'code2':
            return '<svg ' . $attrs . '><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>';
            
        case 'server':
            return '<svg ' . $attrs . '><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>';
            
        case 'lock':
            return '<svg ' . $attrs . '><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
            
        case 'eye':
            return '<svg ' . $attrs . '><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
            
        case 'alert-triangle':
        case 'alerttriangle':
            return '<svg ' . $attrs . '><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>';
            
        case 'file-check':
        case 'filecheck':
            return '<svg ' . $attrs . '><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="m9 15 2 2 4-4"/></svg>';
            
        case 'network':
            return '<svg ' . $attrs . '><rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="9" y="2" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><path d="M12 12V8"/></svg>';
            
        case 'database':
            return '<svg ' . $attrs . '><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>';
            
        case 'cloud':
            return '<svg ' . $attrs . '><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>';
            
        case 'smartphone':
            return '<svg ' . $attrs . '><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><line x1="12" x2="12.01" y1="18" y2="18"/></svg>';
            
        case 'laptop':
            return '<svg ' . $attrs . '><path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16"/></svg>';
            
        case 'layers':
            return '<svg ' . $attrs . '><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>';
            
        case 'hard-drive':
        case 'harddrive':
            return '<svg ' . $attrs . '><line x1="22" x2="2" y1="12" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" x2="6.01" y1="16" y2="16"/><line x1="10" x2="10.01" y1="16" y2="16"/></svg>';
            
        case 'cpu':
            return '<svg ' . $attrs . '><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>';
            
        case 'mail':
            return '<svg ' . $attrs . '><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>';
            
        case 'github':
            return '<svg ' . $attrs . '><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/><path d="M9 18c-4.51 2-5-2-7-2"/></svg>';
            
        case 'linkedin':
            return '<svg ' . $attrs . '><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>';
            
        case 'arrow-right':
        case 'arrowright':
            return '<svg ' . $attrs . '><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
            
        case 'menu':
            return '<svg ' . $attrs . '><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';
            
        case 'x':
        case 'close':
            return '<svg ' . $attrs . '><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>';

        case 'check':
            return '<svg ' . $attrs . '><polyline points="20 6 9 17 4 12"/></svg>';

        case 'search':
            return '<svg ' . $attrs . '><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>';

        case 'user':
            return '<svg ' . $attrs . '><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';

        case 'user-plus':
            return '<svg ' . $attrs . '><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>';

        case 'log-in':
            return '<svg ' . $attrs . '><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>';

        case 'arrow-left':
            return '<svg ' . $attrs . '><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>';

        case 'key':
            return '<svg ' . $attrs . '><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>';

        case 'google':
            return '<svg class="' . htmlspecialchars($classes) . '" width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/></svg>';

        case 'waves':
            return '<svg ' . $attrs . '><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>';

        case 'tree':
        case 'trees':
            return '<svg ' . $attrs . '><path d="m12 2 4 6h-3l4 6h-3l4 6H5l4-6H6l4-6H7z"/><path d="M12 20v2"/></svg>';

        case 'sun':
            return '<svg ' . $attrs . '><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>';

        case 'moon':
            return '<svg ' . $attrs . '><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>';

        case 'mountain':
            return '<svg ' . $attrs . '><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>';

        case 'alert-circle':
            return '<svg ' . $attrs . '><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>';

        case 'activity':
            return '<svg ' . $attrs . '><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>';

        case 'layout-dashboard':
        case 'dashboard':
            return '<svg ' . $attrs . '><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>';

        case 'radio':
            return '<svg ' . $attrs . '><path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/><path d="M7.8 16.2a6 6 0 0 1 0-8.4"/><circle cx="12" cy="12" r="2"/><path d="M16.2 7.8a6 6 0 0 1 0 8.4"/><path d="M19.1 4.9c3.9 3.9 3.9 10.3 0 14.2"/></svg>';

        case 'refresh-cw':
        case 'refresh':
            return '<svg ' . $attrs . '><path d="M21 12a9 9 0 0 0-15.5-6.2L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 15.5 6.2L21 16"/><path d="M16 16h5v5"/></svg>';

        case 'bell':
            return '<svg ' . $attrs . '><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>';

        case 'log-out':
        case 'logout':
            return '<svg ' . $attrs . '><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>';

        case 'sliders-horizontal':
        case 'sliders':
            return '<svg ' . $attrs . '><line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="16" x2="16" y1="18" y2="22"/></svg>';

        case 'chevron-down':
            return '<svg ' . $attrs . '><polyline points="6 9 12 15 18 9"/></svg>';

        case 'terminal':
            return '<svg ' . $attrs . '><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>';

        case 'copy':
            return '<svg ' . $attrs . '><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>';

        default:
            return '<svg ' . $attrs . '><circle cx="12" cy="12" r="10"/></svg>';
    }
}

/**
 * Rendu SVG dédié pour les processus système et démons Linux / Ubuntu Server
 */
function render_process_icon(string $name): string {
    $n = strtolower(trim($name));
    $attrs = 'width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';

    // 1. Nginx
    if (strpos($n, 'nginx') !== false) {
        return '<svg ' . $attrs . ' stroke="#10b981"><polygon points="12 2 22 7.5 22 16.5 12 22 2 16.5 2 7.5 12 2"/><path d="M7 16V8l10 8V8"/></svg>';
    }
    // 2. Apache
    if (strpos($n, 'apache') !== false || strpos($n, 'httpd') !== false) {
        return '<svg ' . $attrs . ' stroke="#f59e0b"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/></svg>';
    }
    // 3. PHP / PHP-FPM
    if (strpos($n, 'php') !== false) {
        return '<svg ' . $attrs . ' stroke="#818cf8"><ellipse cx="12" cy="12" rx="10" ry="6"/><path d="M7 10h3a2 2 0 1 1 0 4H7v-4z"/><line x1="14" y1="10" x2="14" y2="14"/><line x1="17" y1="10" x2="17" y2="14"/></svg>';
    }
    // 4. MySQL / MariaDB
    if (strpos($n, 'mysql') !== false || strpos($n, 'mariadb') !== false) {
        return '<svg ' . $attrs . ' stroke="#38bdf8"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.66 3.58 3 8 3s8-1.34 8-3V5"/><path d="M4 11v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6"/></svg>';
    }
    // 5. PostgreSQL
    if (strpos($n, 'postgres') !== false || strpos($n, 'postmaster') !== false) {
        return '<svg ' . $attrs . ' stroke="#60a5fa"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.66 3.58 3 8 3s8-1.34 8-3V5"/><line x1="12" y1="8" x2="12" y2="22"/></svg>';
    }
    // 6. Redis
    if (strpos($n, 'redis') !== false) {
        return '<svg ' . $attrs . ' stroke="#f87171"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/></svg>';
    }
    // 7. Docker / Containerd
    if (strpos($n, 'docker') !== false || strpos($n, 'containerd') !== false || strpos($n, 'runc') !== false) {
        return '<svg ' . $attrs . ' stroke="#38bdf8"><path d="M2 13h20v2a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4v-2z"/><rect x="5" y="7" width="3" height="3"/><rect x="10" y="7" width="3" height="3"/><rect x="15" y="7" width="3" height="3"/><rect x="10" y="3" width="3" height="3"/></svg>';
    }
    // 8. SSH Daemon
    if (strpos($n, 'sshd') !== false || strpos($n, 'ssh') !== false) {
        return '<svg ' . $attrs . ' stroke="#34d399"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16.5" r="1.2" fill="#34d399"/></svg>';
    }
    // 9. Systemd & Init
    if (strpos($n, 'systemd') !== false || strpos($n, 'init') !== false) {
        return '<svg ' . $attrs . ' stroke="#94a3b8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';
    }
    // 10. Cron
    if (strpos($n, 'cron') !== false) {
        return '<svg ' . $attrs . ' stroke="#fbbf24"><circle cx="12" cy="12" r="9"/><polyline points="12 6 12 12 16 14"/></svg>';
    }
    // 11. Python / Gunicorn / Uvicorn
    if (strpos($n, 'python') !== false || strpos($n, 'gunicorn') !== false || strpos($n, 'uvicorn') !== false) {
        return '<svg ' . $attrs . ' stroke="#38bdf8"><path d="M12 2C7 2 7.5 4.5 7.5 4.5L7.5 7h4.5v1H5s-3 .3-3 5S4.5 18 4.5 18H7v-2.5s-.2-2.5 2.5-2.5h5s2.5 0 2.5-2.5V4.5S17 2 12 2z"/><circle cx="9.5" cy="4.5" r=".7" fill="#38bdf8"/></svg>';
    }
    // 12. Node / PM2 / Bun
    if (strpos($n, 'node') !== false || strpos($n, 'npm') !== false || strpos($n, 'pm2') !== false || strpos($n, 'bun') !== false) {
        return '<svg ' . $attrs . ' stroke="#4ade80"><polygon points="12 2 21 7 21 17 12 22 3 17 3 7 12 2"/><line x1="12" y1="2" x2="12" y2="22"/><polyline points="21 7 12 12 3 7"/></svg>';
    }
    // 13. Shell / Terminal (bash, zsh, sh)
    if (strpos($n, 'bash') !== false || strpos($n, 'zsh') !== false || strpos($n, 'sh') !== false || strpos($n, 'tmux') !== false) {
        return '<svg ' . $attrs . ' stroke="#a78bfa"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>';
    }
    // 14. TMOG / Télémétrie
    if (strpos($n, 'tmog') !== false) {
        return '<svg ' . $attrs . ' stroke="#22d3ee"><polyline points="2 12 5 12 8 4 12 20 16 8 19 12 22 12"/></svg>';
    }
    // 15. Firewall / Sécurité (ufw, fail2ban)
    if (strpos($n, 'fail2ban') !== false || strpos($n, 'ufw') !== false || strpos($n, 'iptables') !== false) {
        return '<svg ' . $attrs . ' stroke="#f87171"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    }
    // 16. Logs / Syslog
    if (strpos($n, 'syslog') !== false || strpos($n, 'journal') !== false) {
        return '<svg ' . $attrs . ' stroke="#94a3b8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>';
    }
    // 17. Spotify
    if (strpos($n, 'spotify') !== false) {
        return '<svg ' . $attrs . ' stroke="#22c55e"><circle cx="12" cy="12" r="10"/><path d="M8 14.5c2.5-1 5.5-1 8 0"/><path d="M7 11.5c3-1.2 7-1.2 10 0"/><path d="M6 8.5c3.5-1.5 8.5-1.5 12 0"/></svg>';
    }
    // 18. Navigateurs (Chrome, Firefox)
    if (strpos($n, 'chrome') !== false || strpos($n, 'firefox') !== false) {
        return '<svg ' . $attrs . ' stroke="#38bdf8"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="21.17" y1="8" x2="12" y2="8"/><line x1="3.95" y1="6.06" x2="8.54" y2="14"/><line x1="10.88" y1="21.94" x2="15.46" y2="14"/></svg>';
    }
    // 19. IDE / Code (Antigravity, VSCode)
    if (strpos($n, 'ide') !== false || strpos($n, 'code') !== false || strpos($n, 'antigravity') !== false) {
        return '<svg ' . $attrs . ' stroke="#38bdf8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
    }
    // 20. Processus Générique / Kernel Thread (kworker, gnome, etc.)
    return '<svg ' . $attrs . ' stroke="#94a3b8"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/></svg>';
}
