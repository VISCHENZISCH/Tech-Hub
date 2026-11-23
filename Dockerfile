FROM php:8.2-apache

# Dépendances système requises (procps pour ps/top dans la télémétrie, SQLite, etc.)
RUN apt-get update && apt-get install -y --no-install-recommends \
    procps \
    libsqlite3-dev \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Activation des modules Apache requis par l'application (.htaccess, headers, compression, cache)
RUN a2enmod rewrite headers deflate expires

# Autoriser les directives .htaccess dans /var/www/html
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configuration PHP de production
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers sources du projet
COPY . /var/www/html/

# Configuration des permissions pour l'utilisateur Apache (www-data)
# Permet l'écriture dans data/ pour le fallback SQLite et les logs
RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/data

# Exposition du port HTTP
EXPOSE 80

# Démarrage d'Apache en avant-plan
CMD ["apache2-foreground"]
