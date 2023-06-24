FROM nginx:stable

LABEL maintainer="Aurélien Genois"
LABEL version="1.0.0"

ARG DOCKER_PROJECT_NAME=localhost
ARG DOCKER_PROJECT_DIR=public

COPY ./default.conf /etc/nginx/conf.d/default.conf
RUN sed -i "s/\[PROJECT_NAME\]/$DOCKER_PROJECT_NAME.test/g" /etc/nginx/conf.d/default.conf
RUN sed -i "s/\[ROOT_DIR\]/$DOCKER_PROJECT_DIR/g" /etc/nginx/conf.d/default.conf
COPY ./nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html
