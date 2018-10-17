# Defines the fitness of Kōjō for
 - Gaining more control over DLCs by specifying a maximum number of iterations they may run before returning control to Kōjō
 
 ## Setup Function43
 
 ### Running Kōjō
 Setting up the containers using docker-compose:
 
 ```bash
 # Get the latest repo
 git clone git@github.com:neighborhoods/KojoFitness.git;
 cd KojoFitness;
 git checkout 4.x;
 git pull;
 
 #start the containers
 docker-compose build --no-cache && docker-compose up -d;
 
 # Create a database
 touch data/pgsql/dumps/kojo_fitness.sql;
 docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh;
 
 # Prepare Kōjō
 docker-compose exec kojo_fitness bash -c 'cd Function43; composer install';
 docker-compose exec kojo_fitness bash -c 'cd Function43; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function43; php ./bin/setup-worker.php';
 
 # Create messages for Kōjō to delete
 docker-compose exec kojo_fitness bash -c 'cd Function43; php ./bin/create-messages.php';
 
 # Run Kōjō to delete the messages and colorize the events
 docker-compose exec kojo_fitness bash -c 'cd Function43; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/' |\
  awk '{ gsub("new_worker", "\033[1;36m&\033[0m"); gsub("working", "\033[1;33m&\033[0m"); gsub("complete_success", "\033[1;32m&\033[0m"); print }';
 
 # Once you start seeing "working" events, you can run the following command in another terminal to disable the job type
 # Alternatively, you can edit the job type directly in your favorite database GUI
 
 # docker-compose exec pgsql psql -U kojo -d kojo_fitness -c "UPDATE kojo_job_type SET is_enabled = false WHERE type_code = 'capped_iteration_dlcp_example'"
 
 # After a slight delay you should stop seeing "working" events
 # At that point you can run the following command (again, in another terminal) to re-enable the job and continue processing messages
 
 # Delete the Kōjō Tables and clear redis
 docker-compose exec kojo_fitness bash -c 'cd Function43; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
 docker-compose exec redis redis-cli flushall;
 ```
