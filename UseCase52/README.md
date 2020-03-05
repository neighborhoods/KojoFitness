# Defines the fitness of Kōjō for
- Interactions between job state change logging and userspace transactions

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
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
docker-compose exec kojo_fitness bash -c 'cd UseCase52; php ./bin/create-userspace-table.php; php ./bin/setup-workers.php; php ./bin/schedule-producer-job.php';

# Run Kōjō
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/';

# If everything works as expected, you should see two "successfully consumed" messages from the Consumer
# You should also see the following rows in the kojo_job_state_changelog (8 total):
#     Producer (waiting -> working), (working -> complete_success)
#     Consumer 1 (new -> waiting), (waiting -> working), (working -> complete_success)
#     Consumer 2 (new -> waiting), (waiting -> working), (working -> complete_success)
# If something goes wrong, you may see "consumed unexpected event" messages, or different rows in kojo_job_state_changelog
# Afterward, kojo will just idle, and you can CTRL+C to stop it

# Delete the Kōjō Tables and clear redis
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
docker-compose exec redis redis-cli flushall;
```
