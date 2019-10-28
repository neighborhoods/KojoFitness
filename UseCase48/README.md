# Defines the fitness of Kōjō for
 - A single job running on PHP 7.3 for the first time
 - Replicates [UseCase46](/UseCase46/README.md) in doing:
   - emits different log messages based on random numbers. Useful for resting monitoring and alerting infrastructure.
 
 ## Setup UseCase48
 
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
 docker-compose exec kojo_fitness bash -c 'cd UseCase48; composer install';
 docker-compose exec kojo_fitness bash -c 'cd UseCase48; composer update';
 docker-compose exec kojo_fitness bash -c 'cd UseCase48; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd UseCase48; php ./bin/setup-worker.php';
 
 
 # Run Kōjō to delete the messages and colorize the events
 docker-compose exec kojo_fitness bash -c 'cd UseCase48; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/' |  awk '{ 
  gsub("new_worker", "\033[1;36m&\033[0m"); 
  gsub("working", "\033[1;33m&\033[0m"); 
  gsub("complete_success", "\033[1;32m&\033[0m"); 
  gsub("critical", "\033[5;101m&\033[0m"); 
  gsub("Panicking", "\033[5;95m&\033[0m"); 
  gsub("TypeError", "\033[5;102m&\033[0m"); 
  gsub("LogicException", "\033[5;102m&\033[0m"); 
  print }';
  
 # After you see "working" events stop and a few "complete_success" then all messages have been deleted. Press ctrl+c
 
 # Delete the Kōjō Tables and clear redis
 docker-compose exec kojo_fitness bash -c 'cd UseCase48; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
 docker-compose exec redis redis-cli flushall;
 ```
