ARG DOCKER_PHP_VERSION=8.1
FROM php:${DOCKER_PHP_VERSION}-fpm-buster

LABEL maintainer="Aurélien Genois"
LABEL version="1.0.0"

# Set Working directory
WORKDIR /var/www/html

# Install OS packages
RUN apt-get update \
 && apt-get install -y --no-install-recommends -q \
    curl wget git git-flow sudo openssh-server nano \
    gnupg ca-certificates \
    build-essential libc6-amd64-cross unzip \
    libonig-dev \
    libzip-dev \
    libmagickwand-dev \
 && apt-get clean all

RUN ln -s /usr/x86_64-linux-gnu/lib64/ /lib64
ENV LD_LIBRARY_PATH="$LD_LIBRARY_PATH:/lib64:/usr/x86_64-linux-gnu/lib"

# Setup GIT ?
# ARG DOCKER_GIT_NAME="Docker Instance"
# ARG DOCKER_GIT_EMAIL="aurelien.genois@gmail.com"
# RUN git config --global user.email "${DOCKER_GIT_EMAIL}" \
#  && git config --global user.name "${DOCKER_GIT_NAME}"

# PHP Extensions
ARG DOCKER_PHP_EXTENSIONS="pdo pdo_mysql"
RUN docker-php-ext-install $DOCKER_PHP_EXTENSIONS
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# Install Oh My Zsh Terminal
RUN apt-get install -y zsh
RUN wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh || true
RUN git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k
RUN echo "source ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k/powerlevel10k.zsh-theme" >>~/.zshrc
RUN apt-get install -y zsh-autosuggestions

# Install composer for the application dependencies
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# # Remove old potential local folders
RUN rm -rf vendor

# # Create the new folders with docker user permissions
RUN mkdir vendor

# Expose ports
EXPOSE 9000 80 443