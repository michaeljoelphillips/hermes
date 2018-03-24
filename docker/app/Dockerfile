FROM composer:latest as builder

WORKDIR /app
COPY . /app
RUN composer install --no-interaction --classmap-authoritative --no-dev --prefer-dist
RUN cp .env.example .env

FROM alpine:latest

COPY --from=builder /app /app

RUN chown -R 1000:33 -R /app

VOLUME /app
