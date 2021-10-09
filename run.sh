export result=${PWD##*/}
docker-compose down --remove-orphans
docker-compose up -d
