#!/bin/bash
sleep 5


MONGO_HOST="localhost"
MONGO_PORT="27017"
MONGO_DB="database"
MONGO_USER="root"
MONGO_PASS="rootpassword"

mongosh  --host $MONGO_HOST --port $MONGO_PORT -u $MONGO_USER -p $MONGO_PASS --authenticationDatabase admin <<EOF  > /dev/null 2>&1
use $MONGO_DB
db.items.drop()
db.coins.drop()
EOF



for file in ./mongo-seed/*.json; do
    collection=$(basename "$file" .json)
    mongoimport --username root --password rootpassword --authenticationDatabase admin \
                --db database --collection "$collection" --file "$file" --jsonArray > /dev/null 2>&1
done
