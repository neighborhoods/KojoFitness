#!/usr/bin/env bash

# make sure MySQL is ready to accept connections
tries=30
echo "about to start testing connections"
while ! echo 'SELECT 1' | mysql -uroot -p"$MYSQL_ROOT_PASSWORD" &> /dev/null; do
	(( tries-- ))
	if [ $tries -le 0 ]; then
		echo >&2 'mysqld failed to accept connections in a reasonable amount of time!'
		false
	fi
	sleep 2
done

echo "connection established"

DATABASES=${1:-'kojo_fitness'}

for db in $DATABASES; do
    echo "creating $db"
    mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $db";

    echo "assigning privileges to $db"
    mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "GRANT ALL PRIVILEGES ON $db.* TO '$MYSQL_USER'@'%' WITH GRANT OPTION;"

    if [ -f /docker-entrypoint-initdb.d/dumps/$db.sql ]; then
        echo "importing data into $db"
        mysql -u root -p"$MYSQL_ROOT_PASSWORD" $db < /docker-entrypoint-initdb.d/dumps/$db.sql
        echo "$db complete"
    else
        echo "no dump for $db"
    fi
done
