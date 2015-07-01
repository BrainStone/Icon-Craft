#! /bin/bash

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