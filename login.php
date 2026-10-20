<?php
/**
 * Tech Hub - Page de Connexion
 * Authentification native PHP avec design glassmorphism split-screen
 */

session_start();

require_once __DIR__ . '/includes/icons.php';
require_once __DIR__ . '/includes/auth.php';
$data = require __DIR__ . '/data/content.php';

$error = null;
$success = null;

if (isset($_GET['logged_out'])) {
    $success = 'Vous avez été déconnecté avec succès.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrfToken)) {
        $error = 'Requête invalide ou session expirée (échec CSRF). Veuillez recharger la page.';
    } elseif (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez saisir une adresse email valide.';
    } else {
        $res = loginUser($email, $password, $remember);
        if ($res['success']) {
            $success = 'Connexion réussie ! Ouverture du centre de supervision…';
            header('Refresh: 1; URL=dashboard.php');
        } else {
            $error = $res['error'] ?? 'Identifiants incorrects.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= htmlspecialchars($data['site']['title']) ?></title>
    
    <!-- Favicon (SVG Shield) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2322d3ee' stroke-width='2'><path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/></svg>">
    
    <!-- Google Fonts (Saira & Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Design System Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="auth-page" id="authPage" data-bg="lofoten">
        <!-- Top Bar avec Switcher de Fond en SVG -->
        <header class="auth-navbar">
            <a href="index.php" class="brand-logo" aria-label="Tech Hub Accueil">
                <div class="logo-icon-box">
                    <?= render_icon('shield', 'logo-icon', 1.75) ?>
                </div>
                <div class="logo-text">
                    <span class="logo-text-accent">Tech</span> Hub
                </div>
            </a>

            <!-- Switcher de Fond Unsplash avec icônes SVG -->
            <div class="auth-bg-switcher" role="group" aria-label="Changer l'image de fond">
                <span class="auth-bg-label">Fond :</span>
                <button type="button" class="bg-thumb-btn active" data-bg="lofoten" title="Lac & Montagnes">
                    <?= render_icon('waves', 'switcher-icon', 2) ?>
                    <span>Lac</span>
                </button>
                <button type="button" class="bg-thumb-btn" data-bg="misty" title="Vallée de Yosemite">
                    <?= render_icon('tree', 'switcher-icon', 2) ?>
                    <span>Vallée</span>
                </button>
                <button type="button" class="bg-thumb-btn" data-bg="sunset" title="Falaises au Coucher du Soleil">
                    <?= render_icon('sun', 'switcher-icon', 2) ?>
                    <span>Falaises</span>
                </button>
                <button type="button" class="bg-thumb-btn" data-bg="night" title="Ciel Étoilé">
                    <?= render_icon('moon', 'switcher-icon', 2) ?>
                    <span>Nuit</span>
                </button>
                <button type="button" class="bg-thumb-btn" data-bg="mountain" title="Panorama Alpin">
                    <?= render_icon('mountain', 'switcher-icon', 2) ?>
                    <span>Alpin</span>
                </button>
            </div>
        </header>

        <!-- Main Split Wrapper -->
        <main class="auth-split-wrapper">
            <!-- Colonne Gauche : Titre & Ambition -->
            <section class="auth-hero-column">
                <h1 class="auth-hero-headline">
                    EXPLOREZ DE<br>
                    NOUVEAUX HORIZONS
                </h1>


                <p class="auth-hero-description">
                    Embarquez dans une aventure où chaque domaine de l'informatique moderne est à votre portée : cybersécurité offensive, architectures cloud et développement fullstack d'élite.
                </p>

                <div class="auth-hero-pills">
                    <div class="hero-pill">
                        <?= render_icon('shield', 'w-4 h-4', 2) ?>
                        <span>Cybersécurité</span>
                    </div>
                    <div class="hero-pill">
                        <?= render_icon('trending-up', 'w-4 h-4', 2) ?>
                        <span>25+ Roadmaps</span>
                    </div>
                    <div class="hero-pill">
                        <?= render_icon('server', 'w-4 h-4', 2) ?>
                        <span>Cloud & DevOps</span>
                    </div>
                </div>
            </section>

            <!-- Colonne Droite : Carte Verre Givré -->
            <section class="auth-card-column">
                <div class="auth-card-frosted">
                    <!-- Alertes -->
                    <?php if ($error): ?>
                        <div class="auth-alert error" role="alert">
                            <?= render_icon('alert-circle', 'w-5 h-5 flex-shrink-0', 2) ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="auth-alert success" role="alert">
                            <?= render_icon('check', 'w-5 h-5 flex-shrink-0', 2.5) ?>
                            <div><?= htmlspecialchars($success) ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de Connexion -->
                    <form method="POST" action="login.php" novalidate>
                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

                        <!-- Email -->
                        <div class="auth-field-group">
                            <label for="login_email" class="auth-field-label">Adresse Email</label>
                            <input 
                                type="email" 
                                id="login_email" 
                                name="email" 
                                class="auth-field-input" 
                                placeholder="nom@exemple.com" 
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                required 
                                autofocus
                            >
                        </div>

                        <!-- Mot de passe -->
                        <div class="auth-field-group">
                            <label for="login_password" class="auth-field-label">Mot de Passe</label>
                            <div class="auth-password-wrapper">
                                <input 
                                    type="password" 
                                    id="login_password" 
                                    name="password" 
                                    class="auth-field-input has-toggle" 
                                    placeholder="••••••••••••" 
                                    required
                                >
                                <button type="button" class="auth-pwd-toggle-btn" id="togglePwdBtn" aria-label="Afficher le mot de passe">
                                    <?= render_icon('eye', 'w-5 h-5', 1.75) ?>
                                </button>
                            </div>
                            <div class="auth-forgot-row">
                                <a href="#" class="auth-forgot-text" onclick="alert('Un lien de réinitialisation vous a été envoyé par email.'); return false;">
                                    Mot de passe oublié ?
                                </a>
                            </div>
                        </div>

                        <!-- Bouton Se Connecter -->
                        <button type="submit" class="auth-btn-primary" id="loginSubmitBtn">
                            SE CONNECTER
                        </button>
                    </form>

                    <!-- Séparateur -->
                    <div class="auth-divider" aria-hidden="true">
                        <span class="auth-divider-line"></span>
                        <span class="auth-divider-text">ou</span>
                        <span class="auth-divider-line"></span>
                    </div>

                    <!-- Connexion avec Google -->
                    <button type="button" class="auth-btn-google" onclick="alert('Authentification avec Google (simulation)');">
                        <?= render_icon('google') ?>
                        <span>Continuer avec Google</span>
                    </button>

                    <!-- Lien vers Inscription -->
                    <div class="auth-switch-footer">
                        Nouveau sur Tech Hub ? <a href="register.php" class="auth-switch-link">Créer un compte</a>
                    </div>
                </div>
            </section>
        </main>

        <!-- Mini Footer -->
        <footer class="auth-mini-footer">
            <span>&copy; <?= date('Y') ?> Tech Hub. Tous droits réservés.</span>
            <span>Sécurité &bull; Performance &bull; Excellence</span>
        </footer>
    </div>

    <script>
        // Affichage / masquage du mot de passe
        const toggleBtn = document.getElementById('togglePwdBtn');
        const pwdInput = document.getElementById('login_password');
        if (toggleBtn && pwdInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = pwdInput.getAttribute('type') === 'password';
                pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleBtn.style.color = isPassword ? '#2563eb' : '#64748b';
            });
        }

        // Sélecteur d'arrière-plan avec sauvegarde localStorage
        const authPage = document.getElementById('authPage');
        const bgBtns = document.querySelectorAll('.bg-thumb-btn');
        const savedBg = localStorage.getItem('techhub_auth_bg') || 'lofoten';

        function applyBg(bgKey) {
            if (!authPage) return;
            authPage.setAttribute('data-bg', bgKey);
            bgBtns.forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-bg') === bgKey);
            });
            localStorage.setItem('techhub_auth_bg', bgKey);
        }

        applyBg(savedBg);

        bgBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                applyBg(btn.getAttribute('data-bg'));
            });
        });
    </script>
</body>
</html>
