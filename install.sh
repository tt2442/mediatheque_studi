docker-compose down
docker-compose up -d
docker exec slcp bash -c "yarn install --force"
docker exec slcp bash -c "yarn build"
docker exec slcp bash -c "composer install"
docker exec slcp bash -c "npm install --force"
docker exec slcp bash -c "yarn build"
docker exec slcp bash -c "symfony console m:migr"
docker exec slcp bash -c "symfony console d:m:m"
docker exec slcp bash -c "yes Y | symfony console d:f:l"
