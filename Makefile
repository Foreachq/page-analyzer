start:
	php artisan serve

test:
	php artisan test

validate:
	composer validate

deploy:
	git push heroku

lint:
	composer phpcs

lint-fix:
	composer phpcbf
