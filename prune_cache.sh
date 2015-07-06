#! /bin/bash

lockdir=/var/lock/icon-craft

while ! mkdir "$lockdir" > /dev/null 2>&1
do
  sleep 10
done

trap 'rm -r "'"$lockdir"'"' EXIT

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/humanReadable.sh"

size=$(find /var/www/icon-craft/cache/render/ -type f -mtime +30 | xargs -r du -bc | tail -1 | cut -f1)

if [ -n "$size" ]
then
  echo "Memory saved $(humanReadable $size)"

  # Find all files that haven't been used in at least 30 days and delete them.
  time find /var/www/icon-craft/cache/render/ -type f -mtime +30 -exec rm -v {} \;
  find /var/www/icon-craft/cache/render/ -type d -empty -exec rmdir {} \;
fi
