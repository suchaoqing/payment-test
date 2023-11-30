
FROM lcsbaroni/php:7.2


ARG APP_VERSION
ARG NEW_RELIC_APP_NAME
ARG NEW_RELIC_ENABLED
ARG NEW_RELIC_LICENSE_KEY
ARG XDEBUG_ENABLED

ENV APP_VERSION=$APP_VERSION
ENV XDEBUG_ENABLED=$XDEBUG_ENABLED

WORKDIR /www

# Copy application and env.sample.php
COPY . /www
RUN chmod 777 -R /www/storage/logs/
RUN chmod 777 -R /www/storage/framework/views/
COPY ./env/docker/crontab /etc/crontabs/docker

