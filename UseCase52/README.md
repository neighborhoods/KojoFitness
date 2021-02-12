# Defines the fitness of Kōjō for
Different approaches to "stripe-locked" work (i.e. each resource (listing, area, notification, etc.) is only being
used by one worker

Existing patterns demonstrated here are:
- Delegator/Worker
    - One delegator reads from the pool of work to do
    - The delegator dynamically schedules a job to do that particular piece of work
    - The delegator associated that new job's ID with the ID of the work to do in a table
    - For each unit of work, the delegator looks in the above-mentioned table to ensure nothing else is doing that work
- FIFO queues with message groups
    - AWS's SQS allows for multiple workers to pull messages from a queue without the risk of multiple workers
    doing the same work
- Whatever AreaManager does (throttled delegator/worker?)

This UseCase also tries out a new pattern, based on userspace mutexing:
1. A job type would be defined to run in parallel, with a `schedule_limit` of `N`
2. A number of those workers would spin up and read batches of work to do from a pool
3. For each unit of work in that batch, the worker would try to acquire a lock on the underlying "resource"
    - The underlying resource would need some unique identifier
4. If a worker is able to acquire that lock, it does the work. Otherwise it moves on to another piece of work
    - Inability to acquire a lock means some other worker is doing the work

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
docker-compose exec kojo_fitness bash -c 'cd UseCase52; php ./bin/initialize-userspace-tables.php; php ./bin/create-work.php; php ./bin/set-up-workers.php';

# Run Kōjō
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/';

# at this point there are no workers

# Delete the Kōjō Tables and clear redis
docker-compose exec kojo_fitness bash -c 'cd UseCase52; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
docker-compose exec redis redis-cli flushall;
```
