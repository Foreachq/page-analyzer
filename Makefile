start:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down

setup:
	composer install
	./vendor/bin/sail up -d
	cp -n .env.example .env|| true
	./vendor/bin/sail artisan key:gen --ansi
	./vendor/bin/sail artisan migrate
	./vendor/bin/sail artisan db:seed
	./vendor/bin/sail down

test:
	./vendor/bin/sail artisan test --coverage-clover build/logs/clover.xml

validate:
	./vendor/bin/sail composer validate

deploy:
	git push heroku

lint:
	./vendor/bin/sail composer exec --verbose phpcs -- --standard=PSR12 routes/web.php app/Http/Controllers app/Http/Requests app/Models app/Repositories app/Utils tests/Unit tests/Feature
