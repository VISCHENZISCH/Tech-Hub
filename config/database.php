<?php
/**
 * Tech Hub - Configuration de la Base de Données MySQL
 * 
 * Définition des paramètres de connexion à MySQL/MariaDB.
 * Vous pouvez modifier ces valeurs directement ou via des variables d'environnement.
 */

return [
    'driver' => 'mysql',
    
    // Paramètres MySQL / MariaDB
    'mysql' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'database' => getenv('DB_NAME') ?: 'techhub_db',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            1002 => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci" // 1002 = PDO::MYSQL_ATTR_INIT_COMMAND
        ]
    ],

    // Fallback SQLite de secours automatique si pdo_mysql n'est pas encore installé
    'sqlite' => [
        'path' => __DIR__ . '/../data/techhub.sqlite',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    ]
];
