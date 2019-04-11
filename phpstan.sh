#!/bin/sh

./vendor/bin/phpstan analyse -a ./_code_quality/PHPStan/autoload.php --level=max --memory-limit=-1