#!/bin/bash
set -euo pipefail
cd -P -- "$(dirname -- "$0")/.." # From https://stackoverflow.com/a/17744637

# Most things in Docker will be run as root
export COMPOSER_ALLOW_SUPERUSER=1

while [[ $# -gt 1 ]]; do
    case "$1" in
        --xdebug)
            INSTALL_XDEBUG="$2"
            shift
            ;;
        --composer-install)
            COMPOSER_INSTALL="$2"
            shift
            ;;
        *)
            echo "Unknown option $1" 1>&2;
            exit 1
            ;;
    esac

    shift
done

if [ "$COMPOSER_INSTALL" = true ]; then
    echo "Installing composer dependencies..."
    composer install -a
fi

if [ "$INSTALL_XDEBUG" = true ]; then
    echo "Installing the xdebug extension..."
    # An xdebug version grater than xdebug-2.7.0alpha1 is required due to https://bugs.xdebug.org/bug_view_page.php?bug_id=00000938
    pecl install xdebug-2.7.0beta1
    docker-php-ext-enable xdebug
fi
