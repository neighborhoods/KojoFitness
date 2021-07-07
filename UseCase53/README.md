# Defines the fitness of Kōjō for
Emitting log messages of known formats, volumes, etc.

## Setup UseCase53

### Running Kōjō
Setting up the containers using docker-compose:

```bash
# Get the latest repo
git clone git@github.com:neighborhoods/KojoFitness.git;
cd KojoFitness;
git checkout master;
git pull;

# Start the containers
docker-compose build --no-cache && docker-compose up -d;

# Create a database
docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh;

# Prepare the Kōjō runtime and register jobs
docker-compose exec kojo_fitness bash -c 'cd UseCase53; rm -f composer.lock; composer install';
docker-compose exec kojo_fitness bash -c 'cd UseCase53; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
docker-compose exec kojo_fitness bash -c 'cd UseCase53; php ./bin/initialize-userspace-tables.php; php ./bin/set-up-workers.php';

# Run Kōjō
docker-compose exec kojo_fitness bash -c 'cd UseCase53; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/';

# at this point there are no workers, use the bin/schedule-* scripts to introduce one type of worker to pull work off the queue
# if you want to try a different type of worker, stop kojo, run bin/clear-jobs-and-work.php to reset everything, then
# bin/create-work.php to add work

# Delete the Kōjō Tables and clear redis
docker-compose exec kojo_fitness bash -c 'cd UseCase53; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
docker-compose exec redis redis-cli flushall;
```
