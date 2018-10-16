# Defines the fitness of Kōjō for
 - Two side-by-side servers that should both be able to do work at the same time
 
 ## Setup Function41
 
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
 touch data/pgsql/dumps/kojo_fitness_v1.sql;  
 touch data/pgsql/dumps/kojo_fitness_v2.sql;  
 docker-compose exec pgsql /docker-entrypoint-initdb.d/init.sh 'kojo_fitness_v1 kojo_fitness_v2';
 
 # Prepare Kōjō
 docker-compose exec kojo_fitness bash -c 'cd Function41; composer install';
 docker-compose exec kojo_fitness bash -c 'cd Function41; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function41; ./vendor/bin/kojo db:setup:install $PWD/src/V2/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function41; php ./bin/setup-v1-worker.php';
 docker-compose exec kojo_fitness bash -c 'cd Function41; php ./bin/setup-v2-worker.php';
 
 # Create messages for Kōjō to delete
 docker-compose exec kojo_fitness bash -c 'cd Function41; php ./bin/create-v1-messages.php'|\
   awk '{ 
   gsub("namespace-lock-v1", "\033[46m&\033[0m"); 
   gsub("namespace-lock-v2", "\033[45m&\033[0m");
   print }';
 docker-compose exec kojo_fitness bash -c 'cd Function41; php ./bin/create-v2-messages.php'|\
   awk '{ 
   gsub("namespace-lock-v1", "\033[46m&\033[0m"); 
   gsub("namespace-lock-v2", "\033[45m&\033[0m");
   print }';
 
 # Run Kōjō to delete the messages and colorize the events
 # Look for a mix of orange and cyan (v1) and blue (v2) messages
 docker-compose exec kojo_fitness bash -c 'cd Function41; touch kojo_v1.out'; 
 docker-compose exec kojo_fitness bash -c 'cd Function41; touch kojo_v2.out'; 
 docker-compose exec -T -d kojo_fitness bash -c 'cd Function41; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/ >> kojo_v1.out'; 
 docker-compose exec -T -d  kojo_fitness bash -c 'cd Function41; ./vendor/bin/kojo process:pool:server:start $PWD/src/V2/Environment/ >> kojo_v2.out'; 
 docker-compose exec kojo_fitness bash -c 'cd Function41; tail -f kojo_*.out'|\
  awk '{ 
  gsub("namespace_lock_v1", "\033[46m&\033[0m"); 
  gsub("namespace_lock_v2", "\033[45m&\033[0m"); 
  gsub("new_worker", "\033[1;36m&\033[0m"); 
  gsub("working", "\033[1;33m&\033[0m"); 
  gsub("complete_success", "\033[1;32m&\033[0m"); 
  print }';
  
 # After you see "working" events stop and a few "complete_success" then all messages have been deleted. Press ctrl+c
 
 # Delete the Kōjō Tables and clear redis
 docker-compose exec kojo_fitness bash -c 'pkill -9 -f kojo';
 docker-compose exec kojo_fitness bash -c 'cd Function41; rm -f kojo_*.out';
 docker-compose exec kojo_fitness bash -c 'cd Function41; rm -rf /tmp/neighborhoods';
 docker-compose exec kojo_fitness bash -c 'cd Function41; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd Function41; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V2/Environment/';
 docker-compose exec redis redis-cli flushall;
 ```
