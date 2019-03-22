#!/bin/sh

phpcs -d memory_limit=-1 --standard=phpcs.xml --encoding=utf-8 --extensions=php --runtime-set ignore_warnings_on_exit 1 . --exclude=Generic.Metrics.CyclomaticComplexity
phpcs -d memory_limit=-1 --standard=phpcs.xml --encoding=utf-8 --extensions=php --runtime-set ignore_warnings_on_exit 1 . --exclude=Generic.Metrics.CyclomaticComplexity --cache --report=json | grep -q '^{"totals":{"errors":0,'