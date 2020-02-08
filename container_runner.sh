#!/bin/bash
url=$1
countPage=$2
countProject=$3
startPage=1
step=$(( countPage / countProject ))
for (( i=1; i <= $countProject; i++ ))
do
  project_number=$(( i - 1 ))
  cd ~/reviews_project_$project_number
  if [ $i -eq $countProject ]
  then
  finalPage=$countPage
  else
  finalPage=$(( startPage + step - 1 ))
  fi
  sudo docker-compose run -d --rm app ./runner $url $startPage $finalPage
  startPage=$(( finalPage + 1 ))
done