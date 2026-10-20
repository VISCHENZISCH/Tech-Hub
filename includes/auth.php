<?php
/**
 * Tech Hub - Système d'Authentification Sécurisé
 * 
 * Gestion de l'inscription (register), connexion (login),
 * vérification de mot de passe (BCRYPT) et sessions.
 */

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    session_set_cookie_params([
        'lifetime' => 86400 * 7,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Génère ou récupère le jeton CSRF de la session active
 */
function getCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valide un jeton CSRF soumis
 */
function verifyCsrfToken(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Enregistre un nouvel utilisateur en base de données
 */
function registerUser(string $name, string $email, string $password): array {
    $name = trim($name);
    $email = trim(strtolower($email));

    if (empty($name) || empty($email) || empty($password)) {
        return ['success' => false, 'error' => 'Tous les champs marqués sont obligatoires.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Veuillez saisir une adresse email valide.'];
    }

    if (strlen($password) < 8) {
        return ['success' => false, 'error' => 'Le mot de passe doit comporter au moins 8 caractères.'];
    }

    try {
        $pdo = getDbConnection();

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Cette adresse email est déjà associée à un compte.'];
        }

        // Hachage sécurisé du mot de passe
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        // Insertion du nouvel utilisateur
        $insert = $pdo->prepare('
            INSERT INTO users (name, email, password_hash, role, created_at)
            VALUES (:name, :email, :password_hash, :role, CURRENT_TIMESTAMP)
        ');
        $insert->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash,
            'role' => 'user'
        ]);

        $userId = (int)$pdo->lastInsertId();

        $user = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'user',
            'logged_at' => time()
        ];

        // Protection contre la fixation de session
        session_regenerate_id(true);
        $_SESSION['user'] = $user;

        return ['success' => true, 'user' => $user];
    } catch (Exception $e) {
        error_log('Erreur inscription Tech Hub : ' . $e->getMessage());
        return ['success' => false, 'error' => 'Une erreur technique est survenue lors de la création du compte.'];
    }
}

/**
 * Connecte un utilisateur existant avec vérification du mot de passe
 */
function loginUser(string $email, string $password, bool $remember = false): array {
    $email = trim(strtolower($email));

    if (empty($email) || empty($password)) {
        return ['success' => false, 'error' => 'Veuillez saisir votre email et votre mot de passe.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Veuillez saisir une adresse email valide.'];
    }

    // Protection anti brute-force : limitation à 5 tentatives erronées par minute
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $rateKey = 'login_attempts_' . md5($ip . '_' . $email);
    if (!empty($_SESSION[$rateKey]) && $_SESSION[$rateKey]['count'] >= 5) {
        $elapsed = time() - $_SESSION[$rateKey]['time'];
        if ($elapsed < 60) {
            $lockoutRemaining = 60 - $elapsed;
            return [
                'success' => false,
                'error' => "Trop de tentatives échouées. Veuillez patienter {$lockoutRemaining}s avant de réessayer."
            ];
        } else {
            unset($_SESSION[$rateKey]);
        }
    }

    try {
        $pdo = getDbConnection();

        $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $userRow = $stmt->fetch();

        if (!$userRow || !password_verify($password, $userRow['password_hash'])) {
            // Incrémentation du compteur d'échecs
            if (empty($_SESSION[$rateKey])) {
                $_SESSION[$rateKey] = ['count' => 1, 'time' => time()];
            } else {
                $_SESSION[$rateKey]['count']++;
                $_SESSION[$rateKey]['time'] = time();
            }
            return ['success' => false, 'error' => 'Identifiants incorrects. Veuillez vérifier votre email et mot de passe.'];
        }

        // Connexion réussie : réinitialisation du compteur d'échecs
        unset($_SESSION[$rateKey]);

        // Mise à jour de la date de dernière connexion
        $updateStmt = $pdo->prepare('UPDATE users SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id');
        $updateStmt->execute(['id' => $userRow['id']]);

        // Protection contre la fixation de session
        session_regenerate_id(true);

        // Mise en session
        $user = [
            'id' => (int)$userRow['id'],
            'name' => $userRow['name'],
            'email' => $userRow['email'],
            'role' => $userRow['role'] ?? 'user',
            'logged_at' => time()
        ];

        $_SESSION['user'] = $user;

        // Gestion du cookie Remember Me si demandé
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $tokenStmt = $pdo->prepare('UPDATE users SET remember_token = :token WHERE id = :id');
            $tokenStmt->execute(['token' => hash('sha256', $token), 'id' => $userRow['id']]);
            setcookie('remember_token', $userRow['id'] . ':' . $token, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        return ['success' => true, 'user' => $user];
    } catch (Exception $e) {
        error_log('Erreur connexion Tech Hub : ' . $e->getMessage());
        return ['success' => false, 'error' => 'Une erreur technique est survenue lors de la connexion.'];
    }
}

/**
 * Récupère l'utilisateur connecté ou null
 */
function getCurrentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Vérifie si un utilisateur est connecté
 */
function isLoggedIn(): bool {
    return !empty($_SESSION['user']['id']) || !empty($_SESSION['user']['email']);
}

/**
 * Déconnecte l'utilisateur
 */
function logoutUser(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
