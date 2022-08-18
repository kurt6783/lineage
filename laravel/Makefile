serve:		##Serves the environment into given port (8000 by default)
	@php artisan serve

update:
	composer install

phpcbf:
	phpcbf ./app

phpcs:
	phpcs ./app

clear:
	php artisan config:clear
	php artisan cache:clear
	php artisan view:clear
	php artisan route:clear
	rm -rf storage/logs/laravel.log

