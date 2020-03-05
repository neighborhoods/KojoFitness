# Defines the fitness of Kōjō for
- Automatically running outstanding migrations on execution environment startup

## Setup UseCase52

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
docker-compose exec kojo_fitness bash -c 'cd UseCase52; rm -f composer.lock; composer install';
docker-compose exec kojo_fitness bash -c 'cd UseCase52; php ./bin/pre-migrate.php';
# this is omitted to make it explicit that we're not running the migrations manually, the server will handle that
# docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';

# Run Kōjō
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/';

# If everything works as expected, kojo will just idle. You can CTRL+C to stop it, then inspect the database to make
# sure that tables not created in ./bin/pre-migrate.php exist due to the auto-migrating logic in the server process

# Delete the Kōjō Tables and clear redis
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
docker-compose exec redis redis-cli flushall;
```
