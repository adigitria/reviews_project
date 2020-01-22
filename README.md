
## Before work
```
cp config.php.dist config.php
```

## Build docker
```
docker-compose build
```

## Manual running
```
docker-compose run --rm app ./runner link 6
```
Where "6" - count of parsing pages

## Description of work
After running scripts you can find results of parsing process in directory **results/.**

Also, if want to check intermediate steps, you can find downloaded pages in directory **pages/** and 
html blocks of reviews in directory **reviews/.**

Besides, before running of parsing for every sites, make archive with previous results (put in **archive/** directory).
Each connect to site save in logs (directory **logs/**).