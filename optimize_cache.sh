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

if [ -t 0 ]; then
  interactive=1
fi

function imageOptimizer() {
  echo ${files[@]} | xargs -r --max-procs=4 -n1 sh -c 'if [ -n "'$interactive'" ]; then echo -e "Optimizing\t\t${1:'${#dir}'} ..."; fi && (optipng -o7 "$1" && advpng -z -4 "$1" && advdef -z -4 "$1" && pngcrush "$1" "$1.optimized" && rm "$1") > /dev/null 2>&1 && if [ -n "'$interactive'" ]; then echo -e "Finished optimizing\t${1:'${#dir}'}"; fi' -
}

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/humanReadable.sh"

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
