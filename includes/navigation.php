<?php
/**
 * Navigation Bar Component
 */
?>
<header class="site-header" id="siteHeader">
    <div class="nav-shell">
        <nav class="nav-bar">
            <!-- Brand Logo -->
            <a href="#hero" class="brand-logo" aria-label="Tech Hub Accueil">
                <div class="logo-icon-box">
                    <div class="logo-glow"></div>
                    <?= render_icon('shield', 'logo-icon', 1.75) ?>
                </div>
                <div class="logo-text">
                    <span class="logo-text-accent">Tech</span> Hub
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <ul class="nav-menu">
                <?php foreach ($data['navigation'] as $item): ?>
                    <li>
                        <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- CTA Button: Espace Membre ou Dashboard -->
            <?php if (function_exists('isLoggedIn') && isLoggedIn()): 
                $activeUser = getCurrentUser();
                $displayName = htmlspecialchars(explode(' ', trim($activeUser['name'] ?? 'Membre'))[0]);
            ?>
                <a href="dashboard.php" class="nav-cta-btn" style="background: rgba(34, 211, 238, 0.15); border-color: rgba(34, 211, 238, 0.4);">
                    <?= render_icon('layout-dashboard', 'w-4 h-4', 2) ?>
                    <span><?= $displayName ?></span>
                </a>
            <?php else: ?>
                <a href="login.php" class="nav-cta-btn">
                    <?= render_icon('user', 'w-4 h-4', 2) ?>
                    <span>Espace Membre</span>
                </a>
            <?php endif; ?>

            <!-- Mobile Hamburger Toggle Button -->
            <button class="mobile-toggle-btn" id="mobileMenuBtn" aria-label="Menu principal" aria-expanded="false">
                <?= render_icon('menu', 'w-6 h-6', 2) ?>
            </button>
        </nav>

        <!-- Mobile Drawer Menu -->
        <div class="mobile-drawer glass-card" id="mobileDrawer">
            <ul class="mobile-nav-list">
                <?php foreach ($data['navigation'] as $item): ?>
                    <li>
                        <a href="<?= htmlspecialchars($item['href']) ?>" class="mobile-nav-link">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li style="padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 0.5rem;">
                    <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                        <a href="dashboard.php" class="mobile-nav-link" style="color: var(--cyan-400); font-weight: 600;">
                            Tableau de bord (<?= $displayName ?>)
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="mobile-nav-link" style="color: var(--cyan-400); font-weight: 600;">
                            Espace Membre (Connexion)
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</header>
