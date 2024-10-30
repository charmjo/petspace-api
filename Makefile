setup:
	$(MAKE) migrate
	$(MAKE) clear
	$(MAKE) seed

migrate:
	php artisan migrate

seed:
	php artisan db:seed

run:
	php artisan serve

clear:
	php artisan config:clear
	php artisan cache:clear
	php artisan optimize