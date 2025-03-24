#!/bin/bash

# This script automatically syncs this repo with the htdocs/fullstack-test-starter dir

while inotifywait -r -e modify,create,delete /home/danny/Desktop/Repos/fullstack-test-starter/backend; do
    sudo rsync -av --delete /home/danny/Desktop/Repos/fullstack-test-starter/backend/ /opt/lampp/htdocs/fullstack-test-starter/
    sudo chmod -R 755 /opt/lampp/htdocs/
    sudo chown -R daemon:daemon /opt/lampp/htdocs/
done
