#!/bin/bash

# This script automatically syncs this repo with the htdocs/scandi dir

while inotifywait -r -e modify,create,delete /home/danny/Desktop/Repos/fullstack-test-starter; do
    rsync -av --delete /home/danny/Desktop/Repos/fullstack-test-starter/ /opt/lampp/htdocs/scandi/
done
