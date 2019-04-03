This example setups the following services
- Grafana: http://localhost:3001
  - username `admin` and the password `foobar`
- Kibana: http://localhost:5602
- Prometheus: http://localhost:9091/graph
- AlertManager: http://localhost:9094
- ElasticSearch: http://localhost:9201
  
1. Start the base docker containers in the `KojoFitnes` directory

```bash
if [ ! -d "UseCase46" ]; then
  echo "You are not in the base KojoFitness directory";
  exit;
fi

# Setup base environment (DB, Redis, Kōjō without server running)
docker-compose up -d;
docker-compose exec kojo_fitness bash -c 'cd UseCase46; composer update';

# Cleanup any old use cases
docker-compose exec kojo_fitness bash -c 'cd UseCase46; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
docker-compose exec redis redis-cli flushall;

# Create the new use case
docker-compose exec kojo_fitness bash -c 'cd UseCase46; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
docker-compose exec kojo_fitness bash -c 'cd UseCase46; php ./bin/setup-worker.php';

```

1. Switch to `elasticsearch-fluentd-example` directory and start the monitored Kōjō containers
  - When prompted to login to Grafana use the username `admin` and the password `foobar`
```bash
cd elasticsearch-fluentd-example;
docker-compose up -d
open http://localhost:3001/d/xfj0WF6ik/kojo_job-table-stats?orgId=1
docker-compose exec kojo_fitness bash -c 'cd UseCase46; php ./bin/create-messages.php';
```

Cleaning up containers and removing all monitoring data:
```bash
docker-compose down
docker volume rm $(docker volume ls --filter name=elasticsearch-fluentd-example -q)

cd ..
docker-compose down
```
