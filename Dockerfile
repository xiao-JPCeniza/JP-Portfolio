# Stage 1: Build frontend assets (Vite + Vue)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
COPY resources/views/app.blade.php ./resources/views/
RUN npm ci && npm run build

# Stage 2: PHP app
FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
