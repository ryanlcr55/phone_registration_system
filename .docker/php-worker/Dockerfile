FROM php:8-cli

RUN docker-php-ext-install -j$(nproc) bcmath

RUN apt-get update && apt-get install -y supervisor && apt-get clean
RUN mkdir -p /var/log/supervisor /var/workspace

RUN docker-php-ext-install pdo_mysql


RUN addgroup --gid 1000 phpworker
RUN adduser --disabled-login  --ingroup phpworker --uid 1000 phpworker


EXPOSE 9001

ENTRYPOINT ["supervisord", "-n"]

