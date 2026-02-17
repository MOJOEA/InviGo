FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Configure Apache virtual host for reg.localhost
RUN echo '<VirtualHost *:80>\n\
    ServerName reg.localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    </VirtualHost>' > /etc/apache2/sites-available/reg.localhost.conf

RUN a2ensite reg.localhost.conf

# Expose port 80
EXPOSE 80
