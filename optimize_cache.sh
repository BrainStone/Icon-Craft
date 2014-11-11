dir="/var/www/icon-craft/cache/render/" && files=($(find $dir -iname "*png" | sort)) && [ -n "$files" ] && oldsize=$(du -bsc ${files[@]} | tail -1 | cut -f1) && numfiles=${#files[*]} && echo  "Files to be optimized:" && printf -- '%s\n' "${files[@]}" && echo && time echo ${files[@]} | xargs -r --max-procs=4 -n1 sh -c 'echo -e "Optimizing\t\t${1:'${#dir}'} ..." && (optipng -o7 "$1" && advpng -z -4 "$1" && advdef -z -4 "$1" && pngcrush "$1" "$1.optimized" && rm "$1") > /dev/null && echo -e "Finished optimizing\t${1:'${#dir}'}"' - && echo && files=("${files[@]/%/.optimized}") && size=$(du -bsc ${files[@]} | tail -1 | cut -f1) && permille=$((1000 - ((size * 1000) / oldsize))) && echo -e "\n\nFiles:\t\t$numfiles\nOld Size:\t$oldsize\nNew Size:\t$size\nReduced by:\t$(($oldsize - $size)) ($((permille / 10)).$((permille % 10))%)"