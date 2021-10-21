docker-compose down
docker-compose up -d
docker exec mediatheque_studi bash -c "yarn install --force"
docker exec mediatheque_studi bash -c "yarn build"
docker exec mediatheque_studi bash -c "composer install"
docker exec mediatheque_studi bash -c "npm install --force"
docker exec mediatheque_studi bash -c "yarn build"
docker exec mediatheque_studi bash -c "symfony console m:migr"
docker exec mediatheque_studi bash -c "symfony console d:m:m"
docker exec mediatheque_studi bash -c "yes Y | symfony console d:f:l"
