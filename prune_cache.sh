#! /bin/bash

lockdir=/var/lock/icon-craft

while ! mkdir "$lockdir" > /dev/null 2>&1
do
  sleep 10
done

trap 'rm -r "'"$lockdir"'"' EXIT

# Find all files that haven't been used in at least 30 days and delete them.
time find /var/www/icon-craft/cache/render/ -type f -mtime +30 -exec rm -v {} \;
