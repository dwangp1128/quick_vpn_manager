serve:
	php artisan serve --host=0.0.0.0 --port=9000
installSQLite:
	php artisan xboard:install

watchLog:
	tail -f storage/logs/laravel-2025-03-15.log

seed:
	php artisan db:seed --class=ServerCountriesTableSeeder