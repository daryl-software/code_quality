#!/bin/sh

phpcs -d memory_limit=-1 --standard=ruleset.xml --encoding=utf-8  --extensions=php --runtime-set ignore_warnings_on_exit 1 . --exclude=Generic.Metrics.CyclomaticComplexity
phpcs -d memory_limit=-1 --standard=ruleset.xml --encoding=utf-8  --extensions=php --cache --report=json --runtime-set ignore_warnings_on_exit 1 . --exclude=Generic.Metrics.CyclomaticComplexity | grep -q '^{"totals":{"errors":0,'