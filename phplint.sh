#!/bin/sh

find . -name \*.php -not -path "./vendor/*" \
    | xargs -n 1 php -l -d short_open_tag=Off
