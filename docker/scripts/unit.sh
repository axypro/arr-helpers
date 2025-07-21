# Run unit tests (runs inside container)

cd /app && composer install --no-cache && ./vendor/bin/phpunit --bootstrap /app/tests/bootstrap.php --colors "$@"
