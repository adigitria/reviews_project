
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
