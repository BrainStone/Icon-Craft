#! /bin/bash

lockdir=/var/lock/icon-craft

while ! mkdir "$lockdir" > /dev/null 2>&1
do
  sleep 10
done

trap 'rm -r "'"$lockdir"'"' EXIT

function humanReadable {
  if [ $1 -lt 1024 ]
  then
    printf "%4i       B\n"  $1
  else
    postfixes=(KiB MiB GiB TiB EiB PiB YiB ZiB)
    
    bytes=$1
    count=0
    
    while [ $bytes -ge 1048576 ]
    do
      bytes=$((bytes / 1024))
      count=$((count + 1))
    done
    
    printf "%4i,%03i %s\n" $((bytes / 1024)) $(((bytes % 1024) * 1000 / 1024)) ${postfixes[$count]}
  fi
}

size=$(find /var/www/icon-craft/cache/render/ -type f -mtime +30 | xargs -r du -bc | tail -1 | cut -f1)

if [ -n "$size" ]
then
  echo "Memory saved $(humanReadable $size)"

  # Find all files that haven't been used in at least 30 days and delete them.
  time find /var/www/icon-craft/cache/render/ -type f -mtime +30 -exec rm -v {} \;
fi
