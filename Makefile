env ?= .env
include $(env)

init:
	docker-compose --env-file .env up -d
	docker-compose exec -T php composer install --no-interaction
	docker-compose exec -T php php ./bin/console doctrine:migrations:migrate --no-interaction
	echo "OK"

up:
	docker-compose --env-file .env up

upd:
	docker-compose --env-file .env up -d
	echo "OK"

updb:
	docker-compose --env-file .env up -d --build
	echo "OK"

ps:
	docker-compose --env-file .env ps

stop:
	docker-compose --env-file .env stop
	echo "OK"

down:
	docker-compose --env-file .env down
	echo "OK"

downv:
	docker-compose --env-file .env down -v
	echo "OK"

in:
	docker-compose exec php-fpm bash
