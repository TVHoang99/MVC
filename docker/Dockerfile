# Sử dụng image PHP chính thức với Apache
FROM php:8.4-apache

# Cài đặt các tiện ích cần thiết
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cài đặt Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Kích hoạt mod_rewrite cho Apache
RUN a2enmod rewrite

# Sao chép file cấu hình Apache
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Cài đặt thư mục làm việc
WORKDIR /var/www/html

# Sao chép mã nguồn FuelPHP
COPY . /var/www/html

# Phân quyền
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html