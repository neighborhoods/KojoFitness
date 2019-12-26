# Defines the fitness of Kōjō for
- Basic functionality in the 5.x branch

## Setup UseCase50

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
docker-compose exec kojo_fitness bash -c 'cd UseCase50; rm -f composer.lock; composer install';
docker-compose exec kojo_fitness bash -c 'cd UseCase50; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
docker-compose exec kojo_fitness bash -c 'cd UseCase50; php ./bin/setup-worker.php';

# Run Kōjō
docker-compose exec kojo_fitness bash -c 'cd UseCase50; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/';

# If everything works as expected, you should see messages with event_type "userspace_*"
# with job_id 1, then 2, etc.
# You should NOT see messages with event_type "execution_environment_crash"
# Also, if you inspect kojo_job, you should see a growing number of "panicked" jobs
# Since this is 5.x, you should no longer see the following top-level keys in the log messages:
# process_id, process_path, kojo_job, and kojo_process

# Delete the Kōjō Tables and clear redis
docker-compose exec kojo_fitness bash -c 'cd UseCase50; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
docker-compose exec redis redis-cli flushall;
```
