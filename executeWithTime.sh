#! /bin/bash

function finish() {
  echo -e "\e[1;31m\n\nManueller Abbruch!\nSchließe den aktuellen Vorgang ab, um Schaden zu verhindern!\nDieser Vorgang kann nicht abgebrochen werden!!!\n\nUm den aktuellen Vorgang trotzdem zu beenden gib diesen Befehl ein: 'kill $pid'\e[0m"
  timeLoop

  exit 1
}

function executeWithTime() {
  trap finish INT
  
  local SearchForFlags=true
  local RESETTIMER=false
  SILENT=false
  OPTIND=1
  
  while $SearchForFlags; do
    SearchForFlags=false
    getopts ":sr" opt $1
  
    case $opt in
      r)
        SearchForFlags=true
        RESETTIMER=true
        
        shift
        OPTIND=1
        ;;
      s)
        SearchForFlags=true
        SILENT=true
        
        shift
        OPTIND=1
        ;;  
    esac
  done
  
  OPTIND=1
  
  if [ -z ${start+x} ] || $RESETTIMER; then
    start=$(date +%s%3N)
  fi

  if [ $# -gt 0 ] && [ "$1" == "-r" ]; then
    start=$(date +%s%3N)
    shift
  fi

  if [ $# -eq 0 ]; then
    exit 1;
  else
    local text="$1... "
    shift
  fi
  
  file="/tmp/$(date +%s%N | sha256sum | base64 -w0)"

  "$@" 2>&1 > "$file" &
  pid=$!

  timeLoop
  
  trap - INT
}

function timeLoop() {
  echo -en "\e[?25l\e[s"
  
  while [ -e /proc/$pid ]; do
    printContents
    
    local time=$(($(date +%s%3N) - start))
    local dots=$((time / 1000 % 4 - 4))

    printf "\r\e[39m[\e[1m%5i,%03i\e[22m]\t\e[1;30m${text::$dots}   \b\b\b\e[0m" $((time / 1000)) $((time % 1000))
  done
  
  printContents

  wait $pid
  ret=$?

  if [ $ret -eq 0 ]; then
    printf "\r\e[39m[\e[1m%5i,%03i\e[22m]\t\e[1;30m${text::-4}   \e[32m\u2713\e[0m\n\e[?25h" $((time / 1000)) $((time % 1000))
  else
    printf "\r\e[39m[\e[1m%5i,%03i\e[22m]\t\e[1;30m${text::-4}   \e[31mX (%i)\e[0m\n\e[?25h" $((time / 1000)) $((time % 1000)) $ret
  fi
  
  rm "$file"
}

function printContents() {
  if ! $SILENT && [ -s $file ]; then
    echo -en "\e[2K\e[u"
    
    cat "$file"
    > "$file"
        
    echo -en "\e[s"
    getCurPos
    
    if [ $col -gt 1 ]; then
      local rowOld=$row
      local colOld=$col
      
      echo
      
      getCurPos
      
      if [ $rowOld -eq $row ]; then
        echo -en "\e[$((rowOld - 1));${colOld}H\e[s\e[$row;${col}H"
      fi
    fi
  fi
}

function getCurPos() {
  echo -en "\e[6n"
  read -sdR pos
  pos=${pos#*[}
  
  row=${pos%%;*}
  col=${pos##*;}
}