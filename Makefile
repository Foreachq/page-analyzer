shell:
	docker-compose run web bash

up:
	docker-compose up -d

down:
	docker-compose down

setup:
	cp -n .env.example .env|| true
	docker-compose up -d
	docker-compose run web php artisan migrate:refresh --force
	docker-compose run web php artisan key:gen --ansi
	docker-compose down

watch:
	docker-compose run web npm run watch

migrate:
	docker-compose run web php artisan migrate

test:
	docker-compose run web php artisan test

upload-test-coverage:
	docker-compose run web php artisan test --coverage-clover build/logs/clover.xml;
	docker-compose run web ../cc-reporter format-coverage ./build/logs/clover.xml -t clover --debug;
	docker-compose run web ../cc-reporter upload-coverage

validate:
	docker-compose run web composer validate

update:
	docker-compose run web composer update

deploy:
	git push heroku

lint:
	docker-compose run web composer exec --verbose phpcs -- --standard=PSR12 routes/web.php app/ tests/
