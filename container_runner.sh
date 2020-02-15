#!/bin/bash

url=$1
countPage=$2
startProjectNumber=$(( $3 - 1 ))
countProject=$4
finalProjectNumber=$(( startProjectNumber + countProject))
startPage=1
step=$(( countPage / countProject ))
for (( i=$startProjectNumber; i < $finalProjectNumber; i++ ))
do
  project_number=$(( i ))
  currentCountProjects=$(( i + 1 ))
  cd ~/reviews_project_$project_number
  if [ $currentCountProjects -eq $finalProjectNumber ]
  then
  finalPage=$countPage
  else
  finalPage=$(( startPage + step - 1 ))
  fi
#  echo "sudo docker-compose run -d --rm app ./runner $url $startPage $finalPage"
  sudo docker-compose run -d --rm app ./runner $url $startPage $finalPage
  startPage=$(( finalPage + 1 ))
done