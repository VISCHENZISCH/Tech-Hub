# Guide de Déploiement en Production : Tech Hub sur Ubuntu Server avec Apache

Ce document détaille l'intégralité des étapes nécessaires pour déployer l'application **Tech Hub** sur un serveur **Ubuntu 22.04 LTS ou 24.04 LTS** équipé du serveur web **Apache 2**, de **PHP 8.x** et de **MySQL / MariaDB**.

---

## Sommaire
1. [Architecture & Prérequis](#1-architecture--prérequis)
2. [Étape 1 : Mise à jour du système & Installation de la pile LAMP](#étape-1--mise-à-jour-du-système--installation-de-la-pile-lamp)
3. [Étape 2 : Configuration & Sécurisation de MySQL / MariaDB](#étape-2--configuration--sécurisation-de-mysql--mariadb)
4. [Étape 3 : Déploiement des fichiers du projet](#étape-3--déploiement-des-fichiers-du-projet)
5. [Étape 4 : Permissions & Droits Linux](#étape-4--permissions--droits-linux)
6. [Étape 5 : Configuration de l'application Tech Hub](#étape-5--configuration-de-lapplication-tech-hub)
7. [Étape 6 : Configuration du VirtualHost Apache](#étape-6--configuration-du-virtualhost-apache)
8. [Étape 7 : Sécurisation SSL / HTTPS avec Let's Encrypt (Certbot)](#étape-7--sécurisation-ssl--https-avec-lets-encrypt-certbot)
9. [Étape 8 : Pare-feu (UFW) & Sécurité Serveur](#étape-8--pare-feu-ufw--sécurité-serveur)
10. [Étape 9 : Vérifications & Télémétrie Système](#étape-9--vérifications--télémétrie-système)
11. [Commandes de Maintenance & Dépannage](#commandes-de-maintenance--dépannage)

---

## 1. Architecture & Prérequis

- **Système d'Exploitation :** Ubuntu Server 22.04 LTS ou 24.04 LTS (x86_64 ou ARM64)
- **Serveur Web :** Apache 2.4+ avec modules `rewrite`, `headers`, `deflate`, `expires`
- **Langage & Moteur :** PHP 8.1, 8.2, 8.3 ou 8.4 avec extensions `pdo`, `pdo_mysql`, `mbstring`, `json`, `curl`
- **Base de Données :** MariaDB 10.6+ ou MySQL 8.0+
- **Droits :** Accès SSH avec privilèges `sudo`
- **Réseau :** Un nom de domaine configuré vers l'adresse IP publique de votre serveur (ex: `techhub.votredomaine.com`)

---

## Étape 1 : Mise à jour du système & Installation de la pile LAMP

Connectez-vous à votre serveur en SSH :
```bash
ssh utilisateur@ip_de_votre_serveur
```

Mettez à jour l'index des paquets système :
```bash
sudo apt update && sudo apt upgrade -y
```

Installez Apache, MariaDB, PHP et les extensions requises :
```bash
sudo apt install -y apache2 \
                    mariadb-server \
                    php \
                    libapache2-mod-php \
                    php-mysql \
                    php-mbstring \
                    php-curl \
                    php-xml \
                    php-json \
                    git \
                    ufw \
                    unzip
```

Vérifiez que les services sont actifs et activés au démarrage :
```bash
sudo systemctl enable --now apache2
sudo systemctl enable --now mariadb
```

---

## Étape 2 : Configuration & Sécurisation de MySQL / MariaDB

### 1. Sécuriser l'installation initiale
Exécutez l'utilitaire interactif de sécurisation :
```bash
sudo mysql_secure_installation
```
- Répondez `Y` pour définir un mot de passe root ou utiliser unix_socket
- Supprimez les utilisateurs anonymes (`Y`)
- Désactivez la connexion root à distance (`Y`)
- Supprimez la base de test (`Y`)
- Rechargez les tables de privilèges (`Y`)

### 2. Créer la base de données et l'utilisateur dédié
Connectez-vous à MariaDB en root :
```bash
sudo mysql
```

Exécutez les instructions SQL suivantes (remplacez `MotDePasseTresSecurise2026!` par un mot de passe robuste de votre choix) :
```sql
-- Création de la base de données avec encodage complet UTF-8
CREATE DATABASE IF NOT EXISTS `techhub_db` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Création d'un utilisateur dédié pour l'application
CREATE USER 'techhub_user'@'localhost' IDENTIFIED BY 'MotDePasseTresSecurise2026!';

-- Attribution des privilèges stricts sur la base
GRANT ALL PRIVILEGES ON `techhub_db`.* TO 'techhub_user'@'localhost';

-- Application immédiate
FLUSH PRIVILEGES;
EXIT;
```

---

## Étape 3 : Déploiement des fichiers du projet

Créez le répertoire racine pour le site :
```bash
sudo mkdir -p /var/www/techhub
```

### Option A : Déploiement via Git (Recommandé)
```bash
sudo git clone https://github.com/VISCHENZISCH/tech-hub.git /var/www/techhub
```

### Option B : Déploiement manuel (SCP / RSYNC depuis votre machine locale)
Depuis votre terminal local :
```bash
rsync -avz --exclude '.git' "/home/vshacker/Desktop/ReactjsProjets/Tech Hub/" utilisateur@ip_de_votre_serveur:/tmp/techhub_upload/
```
Puis sur le serveur distant :
```bash
sudo cp -r /tmp/techhub_upload/* /var/www/techhub/
rm -rf /tmp/techhub_upload
```

### 3. Importer le schéma SQL de la table `users`
Depuis le répertoire du projet sur le serveur :
```bash
mysql -u techhub_user -p techhub_db < /var/www/techhub/database/schema.sql
```
*(Saisissez le mot de passe défini précédemment `MotDePasseTresSecurise2026!`)*

---

## Étape 4 : Permissions & Droits Linux

Sous Apache sur Ubuntu, l'utilisateur exécutant les scripts web est `www-data`. Configurez les permissions de manière stricte :

```bash
# Définir le propriétaire et le groupe
sudo chown -R www-data:www-data /var/www/techhub

# Permissions standards (755 pour les dossiers, 644 pour les fichiers)
sudo find /var/www/techhub -type d -exec chmod 755 {} \;
sudo find /var/www/techhub -type f -exec chmod 644 {} \;

# Rendre le dossier /data inscriptible si utilisation du fallback de sessions
sudo chmod 775 /var/www/techhub/data
```

---

## Étape 5 : Configuration de l'application Tech Hub

Éditez le fichier de configuration de base de données :
```bash
sudo nano /var/www/techhub/config/database.php
```

Vérifiez les valeurs de connexion MySQL :
```php
    'mysql' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'techhub_db',
        'username' => 'techhub_user',
        'password' => 'MotDePasseTresSecurise2026!',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            1002 => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    ],
```

Testez l'initialisation et la connectivité via CLI :
```bash
sudo -u www-data php /var/www/techhub/init_db.php
```
Le message `Connexion réussie via le pilote : [MYSQL]` et `Table 'users' opérationnelle` doit s'afficher.

---

## Étape 6 : Configuration du VirtualHost Apache

Activez les modules Apache nécessaires (réécriture, en-têtes, compression, cache) :
```bash
sudo a2enmod rewrite headers deflate expires ssl
```

Créez le fichier de configuration VirtualHost :
```bash
sudo nano /etc/apache2/sites-available/techhub.conf
```

Collez la configuration suivante (adaptez `ServerName` et `ServerAlias` avec votre domaine) :
```apache
<VirtualHost *:80>
    ServerName votredomaine.com
    ServerAlias www.votredomaine.com
    ServerAdmin webmaster@votredomaine.com

    DocumentRoot /var/www/techhub

    <Directory /var/www/techhub>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Protection renforcée des dossiers internes
    <Directory /var/www/techhub/config>
        Require all denied
    </Directory>
    <Directory /var/www/techhub/database>
        Require all denied
    </Directory>
    <Directory /var/www/techhub/data>
        Require all denied
    </Directory>

    # Journaux d'accès et d'erreurs
    ErrorLog ${APACHE_LOG_DIR}/techhub_error.log
    CustomLog ${APACHE_LOG_DIR}/techhub_access.log combined
</VirtualHost>
```

Activez le site et désactivez le site par défaut :
```bash
sudo a2dissite 000-default.conf
sudo a2ensite techhub.conf
```

Vérifiez la syntaxe de la configuration :
```bash
sudo apache2ctl configtest
```
*(Vous devez obtenir `Syntax OK`)*

Redémarrez Apache :
```bash
sudo systemctl restart apache2
```

---

## Étape 7 : Sécurisation SSL / HTTPS avec Let's Encrypt (Certbot)

Installez Certbot pour Apache :
```bash
sudo apt install -y certbot python3-certbot-apache
```

Générez et configurez automatiquement le certificat SSL :
```bash
sudo certbot --apache -d votredomaine.com -d www.votredomaine.com
```
- Indiquez votre adresse email pour les renouvellements
- Acceptez les conditions d'utilisation (`Y`)
- Choisissez de rediriger automatiquement tout le trafic HTTP vers HTTPS (`Redirect`)

Vérifiez le renouvellement automatique :
```bash
sudo certbot renew --dry-run
```

---

## Étape 8 : Pare-feu (UFW) & Sécurité Serveur

Activez le pare-feu intégré d'Ubuntu :
```bash
# Autoriser impérativement SSH avant d'activer le pare-feu !
sudo ufw allow OpenSSH

# Autoriser le trafic web HTTP (80) et HTTPS (443)
sudo ufw allow 'Apache Full'

# Activer le pare-feu
sudo ufw enable
```

Vérifiez le statut :
```bash
sudo ufw status verbose
```

### Optionnel : Protection anti-brute-force avec Fail2ban
```bash
sudo apt install -y fail2ban
sudo systemctl enable --now fail2ban
```

---

## Étape 9 : Vérifications & Télémétrie Système

Le centre de supervision (`dashboard.php`) lit en direct les fichiers de pseudo-système de fichiers Linux (`/proc/stat`, `/proc/meminfo`, `/proc/net/dev`, `/proc/uptime`).

Sous Ubuntu, l'utilisateur `www-data` a nativement accès en lecture à ces informations.

Pour permettre la détection de la température matérielle du processeur :
```bash
sudo apt install -y lm-sensors
sudo sensors-detect --auto
```

Accédez à votre application dans un navigateur :
- **Accueil :** `https://votredomaine.com/`
- **Inscription :** `https://votredomaine.com/register.php`
- **Connexion :** `https://votredomaine.com/login.php`
- **Dashboard :** `https://votredomaine.com/dashboard.php`
- **API Télémétrie :** `https://votredomaine.com/api/system-stats.php`

---

## Commandes de Maintenance & Dépannage

### 1. Consulter les logs en direct
```bash
# Logs d'erreur Apache
sudo tail -f /var/log/apache2/techhub_error.log

# Logs d'accès Apache
sudo tail -f /var/log/apache2/techhub_access.log

# Logs du service MariaDB
sudo journalctl -u mariadb -f
```

### 2. Redémarrer les services
```bash
sudo systemctl restart apache2
sudo systemctl restart mariadb
```

### 3. Sauvegarder la base de données (Backup)
```bash
mysqldump -u techhub_user -p techhub_db > ~/backup_techhub_$(date +%Y%m%d_%H%M%S).sql
```

### 4. Mettre à jour le projet en production
```bash
cd /var/www/techhub
sudo git pull origin main
sudo -u www-data php init_db.php
sudo systemctl reload apache2
```
