#!/bin/bash

# This script automatically pulls the latest changes and syncs with server

# Pull the latest changes from the git repository
cd /home/ubuntu/fullstack-test-starter
git pull

cd /home/ubuntu/fullstack-test-starter/backend
composer update

# Sync the directories
sudo cp -r /home/ubuntu/fullstack-test-starter/backend/* /opt/lampp/htdocs/fullstack-test-starter/
