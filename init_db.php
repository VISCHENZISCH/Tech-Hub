<?php
/**
 * Tech Hub — Script d'Initialisation et Diagnostic Base de Données
 * 
 * Exécution en CLI : php init_db.php
 * Ou directement via le navigateur web.
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: text/plain; charset=utf-8');

echo "====================================================\n";
echo "Tech Hub *-* Diagnostic & Initialisation Base de Données\n";
echo "====================================================\n\n";

$config = require __DIR__ . '/config/database.php';
$hasPdoMysql = extension_loaded('pdo_mysql');

echo "1. Modules PHP détectés :\n";
echo "   - PDO disponible : " . (extension_loaded('pdo') ? "OUI (OK)" : "NON (Requis)") . "\n";
echo "   - Extension pdo_mysql : " . ($hasPdoMysql ? "OUI (OK)" : "NON (Actuellement non activée)") . "\n";
echo "   - Extension pdo_sqlite : " . (extension_loaded('pdo_sqlite') ? "OUI (Fallback disponible)" : "NON") . "\n\n";

if (!$hasPdoMysql) {
    echo " NOTE : Pour activer MySQL natif sous Linux (Debian / Ubuntu / Parrot) :\n";
    echo "   Exécutez dans votre terminal :\n";
    echo "   sudo apt update && sudo apt install -y php-mysql\n\n";
}

echo "2. Configuration MySQL configurée dans config/database.php :\n";
echo "   - Hôte     : " . $config['mysql']['host'] . "\n";
echo "   - Port     : " . $config['mysql']['port'] . "\n";
echo "   - Base     : " . $config['mysql']['database'] . "\n";
echo "   - Utilisateur : " . $config['mysql']['username'] . "\n\n";

echo "3. Test de connexion PDO :\n";
try {
    $pdo = getDbConnection();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    echo "   Connexion réussie via le pilote : [" . strtoupper($driver) . "]\n\n";

    // Vérification de la table users
    if ($driver === 'mysql') {
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        $exists = $stmt->fetch();
    } else {
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        $exists = $stmt->fetch();
    }

    if ($exists) {
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "4. Table 'users' :\n";
        echo "   Table 'users' opérationnelle !\n";
        echo "   Nombre d'utilisateurs enregistrés : " . $count . "\n";
    }

    echo "\n====================================================\n";
    echo "Système d'authentification prêt à l'emploi !\n";
    echo "====================================================\n";
} catch (Exception $e) {
    echo "   Erreur de connexion : " . $e->getMessage() . "\n";
}
