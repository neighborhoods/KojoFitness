.PHONY: build start stop clean
build:
#	docker-compose build --no-cache && docker-compose up -d
#	docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh
#	docker-compose exec kojo_fitness bash -c 'cd UseCase52; rm -f composer.lock; composer install'
	docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:setup:install /var/www/html/kojo_fitness.neighborhoods.com/UseCase52/src/V1/Environment/'
	docker-compose exec kojo_fitness bash -c 'cd UseCase52; php ./bin/setup-worker.php'
start:
	docker-compose up -d
	docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo process:pool:server:start /var/www/html/kojo_fitness.neighborhoods.com/UseCase52/src/V1/Environment/'
stop:
	docker-compose down
clean:
	docker-compose down
	docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:tear_down:uninstall /var/www/html/kojo_fitness.neighborhoods.com/UseCase52/src/V1/Environment/'
	docker-compose exec redis redis-cli flushall