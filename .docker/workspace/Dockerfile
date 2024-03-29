###########################################################################
# Workspace image
###########################################################################
ARG TARGET_PHP_VERSION=8.1
FROM php:${TARGET_PHP_VERSION}-cli-buster as workspace

ARG SERVICE_DIR="./.docker/workspace"
ARG SHARED_DIR="./.docker/shared"
ARG BUILD_DIR="/opt/build"
COPY ${SHARED_DIR} ${BUILD_DIR}

#--------------------------------------------------------------------------
# Setup
#--------------------------------------------------------------------------

RUN apt-get update && apt-get install -y \
    bash \
    git \
    gpg \
    wget \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

#--------------------------------------------------------------------------
# Set Timezone
#--------------------------------------------------------------------------

ARG TZ=UTC
ENV TZ ${TZ}

RUN ${BUILD_DIR}/scripts/set-timezone.sh "${TZ}"

#--------------------------------------------------------------------------
# non-root user + access
#--------------------------------------------------------------------------

ARG APP_USER=app
ARG APP_GROUP=app
ARG APP_USER_ID=1000
ARG APP_GROUP_ID=1000
ARG WORKDIR="/app"

RUN addgroup --gid ${APP_GROUP_ID} ${APP_USER} \
  	&& adduser --uid ${APP_USER_ID} -gid ${APP_GROUP_ID} --disabled-password --gecos "" ${APP_USER} \
    && ${BUILD_DIR}/scripts/create-user-dir.sh ${APP_USER_ID} ${APP_GROUP_ID} "${WORKDIR}"

#--------------------------------------------------------------------------
# PHP extras
#--------------------------------------------------------------------------

# https://github.com/mlocati/docker-php-extension-installer/releases
ARG PHP_EXTENSION_INSTALLER_VERSION=1.5.42
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/${PHP_EXTENSION_INSTALLER_VERSION}/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions curl json mbstring intl xdebug-^3.1 opcache

# php config
#COPY ${SERVICE_DIR}/config/php/conf.d/*  /usr/local/etc/php/conf.d/

#--------------------------------------------------------------------------
# Composer
#--------------------------------------------------------------------------

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

#--------------------------------------------------------------------------
# Github
#--------------------------------------------------------------------------

COPY --from=ghcr.io/supportpal/github-gh-cli:2.39.2 /usr/bin/gh /usr/bin/gh

#--------------------------------------------------------------------------
# Project
#--------------------------------------------------------------------------

# Execute below as non-root user
USER ${APP_USER}

# Set workdir
WORKDIR ${WORKDIR}

# Create composer directory
RUN mkdir /home/${APP_USER}/.composer

# Make sure PATH contains project bin and project composer bin
ENV PATH="${WORKDIR}/bin:${WORKDIR}/vendor/bin:${WORKDIR}/tools/bin:${PATH}"
