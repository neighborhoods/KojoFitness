#!/usr/bin/env bash

# Password that pgsql CLI will use
export PGPASSWORD=$POSTGRES_PASSWORD
export PGUSER=$POSTGRES_USER

# make sure postgres is ready to accept connections
tries=30
timer=5
echo "Testing connection to Postgres"
until pg_isready >/dev/null; do
    echo "Postgres is unavailable - sleeping for $timer seconds" >&2
    sleep $timer
    ((tries--))
    if [ $tries -le 0 ]; then
        echo "pgsql failed to accept connections in a reasonable amount of time!" >&2
        false
    fi
done
echo "connection established"

DATABASES=${1:-'kojo_fitness'}

for db in $DATABASES; do
    if [ -f /docker-entrypoint-initdb.d/dumps/$db.sql ]; then
        # Check if DB exists and prompt to delete
        if psql -d $db -c "SELECT 1" >/dev/null; then
            echo "$db already exists run DROP DATABASE $db;"
            read -p "Drop/delete $db and replace from file (y/n)" -n 1 -r
            echo    # move to a new line
            if [[ ! $REPLY =~ ^[Yy]$ ]]; then
                continue
            fi
            psql -c "DROP DATABASE $db"
            if [ $? -eq 0 ]; then
                  echo "Successfully dropped $db"
                else
                  echo "Could not drop $db" >&2
                  psql -d $db -c "REVOKE CONNECT ON DATABASE $db FROM public;"
                  psql -d $db -c "SELECT pid, pg_terminate_backend(pid)
                                    FROM pg_stat_activity
                                    WHERE datname = current_database()
                                    AND pid <> pg_backend_pid();"
                  psql -c "DROP DATABASE $db"
                  if [ $? -eq 0 ]; then
                    echo "Successfully dropped $db"
                  else
                    echo "Could not drop $db" >&2
                    exit 1
                  fi

            fi
        fi

        # Create new DB and user
        echo "creating $db"
        psql -c "CREATE DATABASE $db;"
        echo "assigning privileges to $db"
        psql -d $db -c "GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO $POSTGRES_USER;"

        # Import Data
        echo "importing data into $db"
        psql $db < /docker-entrypoint-initdb.d/dumps/$db.sql
        psql -d $db -c "GRANT CONNECT ON DATABASE $db TO public;"
        echo "$db complete"
    else
        echo "no dump for $db"
    fi
done
