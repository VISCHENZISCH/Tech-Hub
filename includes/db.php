<?php
/**
 * Tech Hub - Gestionnaire de Connexion PDO (MySQL / SQLite Fallback)
 * 
 * Initialisation automatique des tables et gestion transparente des erreurs.
 */

function getDbConnection(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $config = require __DIR__ . '/../config/database.php';
    $useMysql = ($config['driver'] === 'mysql') && extension_loaded('pdo_mysql');

    if ($useMysql) {
        $mc = $config['mysql'];
        $dsn = "mysql:host={$mc['host']};port={$mc['port']};dbname={$mc['database']};charset={$mc['charset']}";

        try {
            $pdo = new PDO($dsn, $mc['username'], $mc['password'], $mc['options']);
            ensureUsersTableExists($pdo, 'mysql');
            return $pdo;
        } catch (PDOException $e) {
            // Si la base n'existe pas encore (code 1049), tentons de la créer automatiquement
            if ($e->getCode() == 1049 || strpos($e->getMessage(), 'Unknown database') !== false) {
                try {
                    $rootDsn = "mysql:host={$mc['host']};port={$mc['port']};charset={$mc['charset']}";
                    $rootPdo = new PDO($rootDsn, $mc['username'], $mc['password'], $mc['options']);
                    $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `{$mc['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                    
                    $pdo = new PDO($dsn, $mc['username'], $mc['password'], $mc['options']);
                    ensureUsersTableExists($pdo, 'mysql');
                    return $pdo;
                } catch (PDOException $createEx) {
                    // Erreur de permission création de base, bascule vers fallback
                    error_log("Tech Hub DB Creation Error: " . $createEx->getMessage());
                }
            } else {
                error_log("Tech Hub MySQL Connection Error: " . $e->getMessage());
            }
        }
    }

    // Fallback automatique vers SQLite si MySQL ou pdo_mysql n'est pas disponible
    $sc = $config['sqlite'];
    $dbDir = dirname($sc['path']);
    if (!is_dir($dbDir)) {
        @mkdir($dbDir, 0755, true);
    }

    $pdo = new PDO("sqlite:" . $sc['path'], null, null, $sc['options']);
    ensureUsersTableExists($pdo, 'sqlite');
    return $pdo;
}

/**
 * Garantit que la table users existe dans la base de données
 */
function ensureUsersTableExists(PDO $pdo, string $driver): void {
    if ($driver === 'mysql') {
        $sql = "
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(191) NOT NULL UNIQUE,
                `password_hash` VARCHAR(255) NOT NULL,
                `role` VARCHAR(50) NOT NULL DEFAULT 'user',
                `avatar` VARCHAR(255) DEFAULT NULL,
                `remember_token` VARCHAR(100) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `last_login_at` DATETIME DEFAULT NULL,
                INDEX `idx_users_email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    } else {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'user',
                avatar TEXT DEFAULT NULL,
                remember_token TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_login_at DATETIME DEFAULT NULL
            );
            CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
        ";
    }
    $pdo->exec($sql);
}
