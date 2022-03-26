start:
	php artisan serve

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed

test:
	php artisan test

validate:
	composer validate

deploy:
	git push heroku

lint:
	composer exec --verbose phpcs -- --standard=PSR12 routes/web.php app/Http/Controllers app/Http/Requests app/Models app/Repositories tests/Unit tests/Feature
