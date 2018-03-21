FROM composer:latest as builder

WORKDIR /app
COPY . /app
RUN composer install --no-interaction --classmap-authoritative --no-dev --prefer-dist
RUN cp .env.example .env

FROM alpine:latest

COPY --from=builder /app /app
VOLUME /app
