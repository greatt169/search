MYSQL_ROOT_PASSWORD=$(grep MYSQL_ROOT_PASSWORD .env | cut -d '=' -f2)
MYSQL_ROOT_USER=$(grep MYSQL_ROOT_USER .env | cut -d '=' -f2)
MYSQL_USER=$(grep MYSQL_USER .env | cut -d '=' -f2)
MYSQL_DATABASE=$(grep MYSQL_DATABASE .env | cut -d '=' -f2)
MYSQL_CONTAINER_NAME=$(docker-compose ps -q mysql)

docker-compose exec mysql wget -c https://aeroidea.ru/upload/backend/bitrixdbdump.tar.gz -P ./install
docker-compose exec mysql tar -xvf ./install/bitrixdbdump.tar.gz -C ./install
cat ./install/bitrix.sql | docker exec -i $MYSQL_CONTAINER_NAME /usr/bin/mysql -u $MYSQL_ROOT_USER --password=$MYSQL_ROOT_PASSWORD $MYSQL_DATABASE