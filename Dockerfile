```dockerfile id="x4r8pc"
FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    zip \
    nodejs \
    npm

# Instalar extensiones PHP
RUN docker-php-ext-install zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar archivos
COPY . .

# Instalar dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias frontend
RUN npm install && npm run build

# Permisos
RUN chmod -R 777 storage bootstrap/cache

# Puerto Railway
EXPOSE 8080

# Iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
```
