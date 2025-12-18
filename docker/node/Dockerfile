FROM node:18 AS build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY webpack.config.js tailwind.config.js postcss.config.js ./
COPY themes/opatrimoine ./themes/opatrimoine
RUN mkdir -p ./themes/opatrimoine/assets