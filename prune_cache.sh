#! /bin/bash

# Find all files that haven't been used in at least 30 days and delete them.
find /var/www/icon-craft/cache/render/ -mtime +30 -exec rm {} \;
