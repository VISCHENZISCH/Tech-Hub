# Tech-Hub

 **Plateforme Avancée de Veille Technologique, Supervision Matérielle en Temps Réel & Espace Membre Sécurisé.**

Tech Hub est une application web moderne construite en **PHP natif ultra-performant** (sans framework lourd), combinant un design **Cyberpunk / Glassmorphism** soigné, une suite de surveillance Linux en direct et un système d'authentification robuste conforme aux normes **OWASP**.

---

## Fonctionnalités Clés

- **Télémétrie Matérielle & Supervision Linux en Temps Réel :**
  - Mesure instantanée de la charge CPU, fréquence en GHz, cœurs et sondes thermiques.
  - Taux d'utilisation de la RAM et Swap.
  - Surveillance des débits réseau (Rx/Tx) via lecture directe de `/proc/net/dev`.
  - Liste en direct du Top 10 des processus système Linux avec identification automatique des services (Nginx, MySQL, SSH, Docker, Python...).
  - Fréquence de rafraîchissement dynamique (1s, 2s, 5s ou Direct).

- **Authentification & Gestion Utilisateurs Sécurisée :**
  - Inscription (`register.php`) et Connexion (`login.php`) avec design split-screen glassmorphism et sélecteur de fond dynamique.
  - Hachage fort **BCRYPT** avec facteur de coût 12.
  - Protection **anti-CSRF** par jetons cryptographiques stricts.
  - Protection **anti-bruteforce (Rate Limiting)** avec temporisation automatique.
  - Protection contre la fixation de session (`session_regenerate_id`).
  - Cookies de session sécurisés (`HttpOnly`, `SameSite=Lax`).

- **Architecture de Base de Données Hybride :**
  - Support natif **MySQL / MariaDB** (schéma optimisé avec index et contraintes d'intégrité).
  - Mécanisme de fallback automatique vers **SQLite** garantissant une disponibilité immédiate sans interruption.

- **Design System & Expérience Utilisateur :**
  - Interface sombre élégante (Apple Smoked Glass & Cyberpunk Glow).
  - Typographie moderne avec les polices **Saira** et **Inter**.
  - Bibliothèque d'icônes SVG vectorielles natives (sans dépendance externe).

---

## Structure du Projet

```text
Tech Hub/
├── api/
│   └── system-stats.php       # Endpoint JSON de télémétrie temps réel
├── assets/
│   ├── css/style.css          # Design system complet (glassmorphism, animations)
│   ├── js/
│   │   ├── app.js             # Interactions, modales et navigation
│   │   └── dashboard.js       # Moteur de rafraîchissement télémétrique AJAX
│   └── images/                # Assets graphiques et fonds immersifs
├── config/
│   └── database.php           # Paramètres de connexion MySQL / MariaDB
├── data/
│   ├── content.php            # Référentiel de données (Roadmaps, Techs, Sécurité)
│   └── techhub.sqlite         # Base SQLite de secours
├── database/
│   └── schema.sql             # Schéma SQL de création de la table users
├── includes/
│   ├── auth.php               # Moteur d'authentification, sessions & CSRF
│   ├── db.php                 # Singleton PDO et auto-migration
│   ├── system_metrics.php     # Collecteur système Linux (/proc & /sys)
│   ├── icons.php              # Moteur de rendu SVG natif
│   ├── navigation.php         # Barre de navigation responsive
│   ├── hero.php               # Section Hero cinématique
│   ├── cybersecurity.php      # Pôle Cybersécurité & Architecture
│   ├── roadmaps.php           # Parcours d'apprentissage et guides
│   ├── technologies.php       # Grille de technologies & frameworks
│   ├── infrastructure.php     # Architecture cloud et serveurs
│   └── footer.php             # Pied de page et liens
├── .htaccess                  # Règles de sécurité et d'optimisation Apache
├── DEPLOYMENT.md              # Guide complet de déploiement Ubuntu Server
├── init_db.php                # Script de diagnostic et vérification base de données
├── index.php                  # Page d'accueil principale
├── dashboard.php              # Tableau de bord de télémétrie
├── login.php                  # Espace connexion
├── register.php               # Espace inscription
└── logout.php                 # Déconnexion propre
```

---

## Démarrage Rapide en Local

### 1. Prérequis
- PHP 8.1 ou version ultérieure avec extensions `pdo`, `pdo_sqlite` (et optionnellement `pdo_mysql`).

### 2. Lancer le serveur de développement
```bash
php -S 127.0.0.1:8001
```

Ouvrez ensuite votre navigateur sur : [http://127.0.0.1:8001/](http://127.0.0.1:8001/)

### 3. Tester la base de données
Exécutez le script d'initialisation :
```bash
php init_db.php
```

---

## 🛠️ Déploiement en Production sur Ubuntu Server (Apache)

Consultez le guide exhaustif étape par étape : **[DEPLOYMENT.md](DEPLOYMENT.md)**.

En résumé :
1. Installer la pile LAMP :
   ```bash
   sudo apt update && sudo apt install -y apache2 mariadb-server php libapache2-mod-php php-mysql
   ```
2. Importer la base :
   ```bash
   sudo mysql < database/schema.sql
   ```
3. Activer les modules Apache et le VirtualHost :
   ```bash
   sudo a2enmod rewrite headers deflate expires ssl
   ```
4. Configurer le certificat SSL gratuit :
   ```bash
   sudo certbot --apache -d votredomaine.com
   ```

---

## Sécurité

Ce projet intègre les meilleures pratiques de cybersécurité :
- **Protection Anti-CSRF** sur tous les formulaires POST.
- **Protection Anti-Bruteforce** avec verrouillage temporaire (Rate Limiting).
- **Requêtes Préparées PDO** (anti-SQL Injection).
- **Protection des dossiers sensibles** via `.htaccess` (blocage direct de `/config`, `/database`, `/data`).
- **En-têtes HTTP sécurisés** (`X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`).

---

## Auteur & Licence

Développé par **Félix Tovignan** ([@VISCHENZISCH](https://github.com/VISCHENZISCH)).  
Licence MIT - Libre d'utilisation et de modification.
