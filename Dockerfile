FROM php:cli

# Install zip
RUN apt-get update && \
    apt-get install -y \
    libzip-dev && \
    docker-php-ext-install zip

ADD ./ /app

WORKDIR /app