FROM php:8.3-fpm-alpine

# Installer les dépendances système nécessaires pour les extensions PHP
RUN apk add --no-cache \
        git \
        bash \
        unzip \
        libzip-dev \
        oniguruma-dev \
        postgresql-dev \
        icu-dev \
        curl \
        autoconf \
        gcc \
        g++ \
        make \
        libxml2-dev \
        zlib-dev

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo pdo_pgsql intl opcache zip ctype xml

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l’application
COPY . .

RUN git config --global --add safe.directory /var/www/html

# Installer les dépendances PHP (prod)
RUN composer install --optimize-autoloader


# Préparer cache et logs
RUN mkdir -p var/cache var/log && chmod -R 777 var/cache var/log

# Exposer le port FPM
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"]
