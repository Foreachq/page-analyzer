up:
	docker-compose up -d

down:
	docker-compose down

setup:
	cp -n .env.example .env|| true
	docker-compose up -d
	docker-compose run web php artisan migrate:refresh --seed --force
	docker-compose down

watch:
	docker-compose run web npm run watch

migrate:
	docker-compose run web php artisan migrate

test:
	docker-compose run web php artisan test --coverage-clover build/logs/clover.xml

validate:
	docker-compose run web composer validate

update:
	docker-compose run web composer update

deploy:
	git push heroku

lint:
	docker-compose run web composer exec --verbose phpcs -- --standard=PSR12 routes/web.php app/Http/Controllers app/Http/Requests app/Models app/Repositories app/Services tests/Unit tests/Feature
