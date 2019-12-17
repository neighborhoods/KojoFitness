# Defines the fitness of Kōjō for
 - A single job that runs for ever. This is useful when wanting to look at the behavior of killing specific processes or disrupting parts of the environment.
 
 ## Setup UseCase47
 
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
 docker-compose exec kojo_fitness bash -c 'cd UseCase47; composer install';
 docker-compose exec kojo_fitness bash -c 'cd UseCase47; composer update';
 docker-compose exec kojo_fitness bash -c 'cd UseCase47; ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/';
 docker-compose exec kojo_fitness bash -c 'cd UseCase47; php ./bin/setup-worker.php';
 
 
 # Run Kōjō to delete the messages and colorize the events
 docker-compose exec kojo_fitness bash -c 'cd UseCase47; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/' |  awk '{ 
  gsub("new_worker", "\033[1;36m&\033[0m"); 
  gsub("working", "\033[1;33m&\033[0m"); 
  gsub("job_id", "\033[1;32m&\033[0m"); 
  gsub("process_id", "\033[1;34m&\033[0m"); 
  gsub("iterations", "\033[1;35m&\033[0m"); 
  gsub("critical", "\033[5;101m&\033[0m"); 
  gsub("Panicking", "\033[5;95m&\033[0m"); 
  gsub("TypeError", "\033[5;102m&\033[0m"); 
  gsub("LogicException", "\033[5;102m&\033[0m"); 
  print }';
  
 ```
 
 ### Usage
 Watch for JSON messages like:
 ```json
{
    "time": "Thu, 16 May 19 18:12:04.742934 UTC",
    "level": "notice",
    "process_id": "701",
    "process_path": "\/server[586]\/root[590]\/job[701]",
    "message": "I can do this forever",
    "context": {
        "event_type": "task_status",
        "top_level_status": "success",
        "use_case": "UseCase47",
        "job_id": 1,
        "iterations": 4,
        "nested_object": {
            "random_letters": "crmls",
            "random_word": "validation",
            "random_value": 19,
            "status": "success"
        }
    },
    "context_json_last_error": 0
}
```

Use the `process_id` to send various signals to that PID, or run the following command to see the PID for the job's listener:

```bash
root@3f4230e5e66b:/var/www/html/kojo_fitness.neighborhoods.com# ps -auxf
USER       PID %CPU %MEM    VSZ   RSS TTY      STAT START   TIME COMMAND
root       772  1.0  0.0  18144  3244 pts/0    Ss   18:13   0:00 bash
root       780  0.0  0.0  36636  2760 pts/0    R+   18:13   0:00  \_ ps -auxf
root       580  0.0  0.0  17956  2820 pts/2    Ss+  18:07   0:00 bash -c cd UseCase47; ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Env
root       586  4.0  0.4 263700 40280 pts/2    S+   18:07   0:14  \_ neighborhoods-kojo: /server[586]
root       590  0.2  0.2 263856 22864 pts/2    S+   18:08   0:00      \_ neighborhoods-kojo: /server[586]/root[590]
root       701  0.6  0.3 266036 27204 pts/2    S+   18:12   0:00          \_ neighborhoods-kojo: /server[586]/root[590]/job[701]
root       702  0.0  0.2 263856 20148 pts/2    S+   18:12   0:00          |   \_ neighborhoods-kojo: /server[586]/root[590]/job[701]/listener.mute
root       706  0.0  0.2 263856 19984 pts/2    S+   18:12   0:00          \_ neighborhoods-kojo: /server[586]/root[590]/listener.command[706]
```

Now you can kill the worker or mutex with the following commands:
- `kill -9 701` to force kill the job
- `kill -9 702` to force kill the mutex for the job

Note the behavior or job in the log messages going by. 

Now compare that to the behavior of graceful SIGTERM:
- `kill -15 701` to force kill the job
- `kill -15 702` to force kill the mutex for the job
 
 
 ### Tear down
 ```bash
 # Delete the Kōjō Tables and clear redis
 docker-compose exec kojo_fitness bash -c 'cd UseCase47; ./vendor/bin/kojo db:tear_down:uninstall $PWD/src/V1/Environment/';
 docker-compose exec redis redis-cli flushall;
 ```
