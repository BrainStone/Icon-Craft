#! /bin/bash

lockdir=/var/lock/icon-craft

if mkdir "$lockdir" > /dev/null 2>&1
then
  trap 'rm -r "'"$lockdir"'"' EXIT
else
  exit 1
fi

dir="/var/www/icon-craft/cache/render/"
files=($(find $dir -iname "*png" | sort))
PATH="$PATH:/usr/local/bin"

function imageOptimizer() {
  echo ${files[@]} | xargs -r --max-procs=4 -n1 sh -c 'echo -e "Optimizing\t\t${1:'${#dir}'} ..." && (optipng -o7 "$1" && advpng -z -4 "$1" && advdef -z -4 "$1" && pngcrush "$1" "$1.optimized" && rm "$1") > /dev/null && echo -e "Finished optimizing\t${1:'${#dir}'}"' -
}

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

if [ -n "$files" ]
then
  oldsize=$(du -bsc ${files[@]} | tail -1 | cut -f1)
  numfiles=${#files[*]}
  
  echo "Files to be optimized:"
  printf -- '%s\n' "${files[@]}"
  echo
  
  time imageOptimizer
  
  files=("${files[@]/%/.optimized}")
  size=$(du -bsc ${files[@]} | tail -1 | cut -f1)
  permille=$((1000 - ((size * 1000) / oldsize)))
  
  echo -e "\n\n\nFiles:\t\t$numfiles\nOld Size:\t$(humanReadable $oldsize)\nNew Size:\t$(humanReadable $size)\nReduced by:\t$(humanReadable $(($oldsize - $size))) ($((permille / 10)).$((permille % 10))%)"
fi