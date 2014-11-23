#! /bin/bash

lockdir=/var/lock/icon-craft

while ! mkdir "$lockdir"
do
  sleep 10
done

trap 'rm -r "'"$lockdir"'"' EXIT

# Find all files that haven't been used in at least 30 days and delete them.
find /var/www/icon-craft/cache/render/ -mtime +30 -exec rm {} \;
