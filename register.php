<?php
/**
 * Tech Hub - Page d'Inscription
 * Création de compte en PHP natif avec design glassmorphism split-screen
 */

session_start();

require_once __DIR__ . '/includes/icons.php';
require_once __DIR__ . '/includes/auth.php';
$data = require __DIR__ . '/data/content.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrfToken)) {
        $error = 'Requête invalide ou session expirée (échec CSRF). Veuillez recharger la page.';
    } elseif (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Tous les champs marqués sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez saisir une adresse email valide.';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit comporter au moins 8 caractères.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (!$terms) {
        $error = 'Vous devez accepter les conditions d\'utilisation.';
    } else {
        $res = registerUser($name, $email, $password);
        if ($res['success']) {
            $success = 'Compte créé avec succès ! Ouverture du centre de supervision…';
            header('Refresh: 1; URL=dashboard.php');
        } else {
            $error = $res['error'] ?? 'Une erreur est survenue lors de l\'inscription.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - <?= htmlspecialchars($data['site']['title']) ?></title>
    
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
                    FORGEZ VOTRE<br>
                    AVENIR TECH
                </h1>

                <h2 class="auth-hero-tagline">
                    Là où les carrières technologiques prennent leur envol.
                </h2>

                <p class="auth-hero-description">
                    Rejoignez une communauté d'élite en cybersécurité, développement moderne et ingénierie cloud. Progressez avec des roadmaps interactives et des laboratoires pratiques.
                </p>

                <div class="auth-hero-pills">
                    <div class="hero-pill">
                        <?= render_icon('shield', 'w-4 h-4', 2) ?>
                        <span>Cybersécurité</span>
                    </div>
                    <div class="hero-pill">
                        <?= render_icon('code', 'w-4 h-4', 2) ?>
                        <span>100+ Technologies</span>
                    </div>
                    <div class="hero-pill">
                        <?= render_icon('user-plus', 'w-4 h-4', 2) ?>
                        <span>Accès Gratuit</span>
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

                    <!-- Formulaire d'Inscription -->
                    <form method="POST" action="register.php" novalidate>
                        <input type="hidden" name="action" value="register">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

                        <!-- Nom complet -->
                        <div class="auth-field-group">
                            <label for="reg_name" class="auth-field-label">Nom Complet</label>
                            <input 
                                type="text" 
                                id="reg_name" 
                                name="name" 
                                class="auth-field-input" 
                                placeholder="Sophie Laurent" 
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                                required 
                                autofocus
                            >
                        </div>

                        <!-- Email -->
                        <div class="auth-field-group">
                            <label for="reg_email" class="auth-field-label">Adresse Email</label>
                            <input 
                                type="email" 
                                id="reg_email" 
                                name="email" 
                                class="auth-field-input" 
                                placeholder="nom@exemple.com" 
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                required
                            >
                        </div>

                        <!-- Grille Mots de passe -->
                        <div class="auth-fields-row-2">
                            <div class="auth-field-group">
                                <label for="reg_password" class="auth-field-label">Mot de Passe</label>
                                <div class="auth-password-wrapper">
                                    <input 
                                        type="password" 
                                        id="reg_password" 
                                        name="password" 
                                        class="auth-field-input has-toggle" 
                                        placeholder="••••••••••••" 
                                        required
                                    >
                                    <button type="button" class="auth-pwd-toggle-btn" id="toggleRegPwdBtn" aria-label="Afficher le mot de passe">
                                        <?= render_icon('eye', 'w-5 h-5', 1.75) ?>
                                    </button>
                                </div>
                            </div>

                            <div class="auth-field-group">
                                <label for="reg_confirm_password" class="auth-field-label">Confirmer</label>
                                <input 
                                    type="password" 
                                    id="reg_confirm_password" 
                                    name="confirm_password" 
                                    class="auth-field-input" 
                                    placeholder="••••••••••••" 
                                    required
                                >
                            </div>
                        </div>

                        <!-- Conditions -->
                        <label class="auth-checkbox-row">
                            <input type="checkbox" name="terms" id="agreeTerms" <?= isset($_POST['terms']) ? 'checked' : '' ?> required>
                            <span>J'accepte les conditions d'utilisation et la politique de confidentialité.</span>
                        </label>

                        <!-- Bouton S'inscrire -->
                        <button type="submit" class="auth-btn-primary" id="registerSubmitBtn">
                            S'INSCRIRE
                        </button>
                    </form>

                    <!-- Séparateur -->
                    <div class="auth-divider" aria-hidden="true">
                        <span class="auth-divider-line"></span>
                        <span class="auth-divider-text">ou</span>
                        <span class="auth-divider-line"></span>
                    </div>

                    <!-- Inscription avec Google -->
                    <button type="button" class="auth-btn-google" onclick="alert('Inscription avec Google (simulation)');">
                        <?= render_icon('google') ?>
                        <span>Continuer avec Google</span>
                    </button>

                    <!-- Lien vers Connexion -->
                    <div class="auth-switch-footer">
                        Déjà inscrit ? <a href="login.php" class="auth-switch-link">Se connecter</a>
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
        const toggleBtn = document.getElementById('toggleRegPwdBtn');
        const pwdInput = document.getElementById('reg_password');
        const confirmPwdInput = document.getElementById('reg_confirm_password');
        if (toggleBtn && pwdInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = pwdInput.getAttribute('type') === 'password';
                pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                if (confirmPwdInput) {
                    confirmPwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                }
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
