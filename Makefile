start:
	php artisan serve

test:
	php artisan test

validate:
	composer validate

deploy:
	git push heroku

lint:
	composer exec --verbose phpcs -- --standard=PSR12 routes

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 routes
