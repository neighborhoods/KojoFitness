# Defines the fitness of Kōjō for
 - A single worker that sometimes requests a retry instead of doing its job
 
 ## Setup Function45
 
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
 docker-compose exec kojo_fitness bash -c 'cd Function45; composer install';
 docker-compose exec kojo_fitness bash -c 'cd Function45; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function45; php ./bin/setup-worker.php';
 
 # Create messages for Kōjō to delete
 docker-compose exec kojo_fitness bash -c 'cd Function45; php ./bin/create-messages.php';
 
 # Run Kōjō to delete the messages and colorize the events
 docker-compose exec kojo_fitness bash -c 'cd Function45; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/' |\
  awk '{ gsub("new_worker", "\033[1;36m&\033[0m"); gsub("working", "\033[1;33m&\033[0m"); gsub("complete_success", "\033[1;32m&\033[0m"); gsub("complete_failed", "\033[1;35m&\033[0m"); print }';
 
 # If you see "complete_failed" events, then Kōjō isn't handling retry requests properly
 
 # After you see "working" events stop and a few "complete_success" then all messages have been deleted. Press ctrl+c
 
 # Delete the Kōjō Tables and clear redis
     docker-compose exec kojo_fitness bash -c 'cd Function45; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
     docker-compose exec redis redis-cli flushall;
 ```
