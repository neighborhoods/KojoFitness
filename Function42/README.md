# Defines the fitness of Kōjō for
 - Throw a TypeError deep in the worker and check that Kōjō logs useful info to indicate that fact
 
 ## Setup Function42
 
 ### Running Kōjō
 Setting up the container:
 
 Make sure you have pulled the latest version of Mason from Master
 ```bash
 git clone git@github.com:neighborhoods/KojoFitness.git;
 cd KojoFitness;
 git checkout 4.x;
 git pull;
 cd ../Mason;
 docker-compose build --no-cache kojo_fitness nginx && docker-compose up -d;
 
 # Create a database
 touch data/pgsql/dumps/kojo_fitness.sql;  
 docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh;
 
 # Prepare Kōjō
 docker-compose exec kojo_fitness bash -c 'cd Function42; composer install';
 docker-compose exec kojo_fitness bash -c 'cd Function42; composer update';
 docker-compose exec kojo_fitness bash -c 'cd Function42; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function42; php ./bin/setup-worker.php';
 
 # Create messages for Kōjō to delete
 docker-compose exec kojo_fitness bash -c 'cd Function42; php ./bin/create-messages.php';
 
 # Run Kōjō to delete the messages and colorize the events
 docker-compose exec kojo_fitness bash -c 'cd Function42; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/' |\
  awk '{ 
  gsub("new_worker", "\033[1;36m&\033[0m"); 
  gsub("working", "\033[1;33m&\033[0m"); 
  gsub("complete_success", "\033[1;32m&\033[0m"); 
  gsub("critical", "\033[5;101m&\033[0m"); 
  gsub("Panicking", "\033[5;95m&\033[0m"); 
  gsub("TypeError", "\033[5;102m&\033[0m"); 
  print }';
  
 # After you see "working" events stop and a few "complete_success" then all messages have been deleted. Press ctrl+c
 
 # Delete the Kōjō Tables and clear redis
 docker-compose exec kojo_fitness bash -c 'cd Function42; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
 docker-compose exec redis redis-cli flushall;
 ```
