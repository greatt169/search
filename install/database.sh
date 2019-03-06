MYSQL_ROOT_PASSWORD=$(grep MYSQL_ROOT_PASSWORD .env | cut -d '=' -f2)
MYSQL_DATABASE=$(grep MYSQL_DATABASE .env | cut -d '=' -f2)
MYSQL_CONTAINER_NAME=$(docker-compose ps -q mysql)

docker-compose exec mysql wget -c https://aeroidea.ru/upload/backend/bitrixdbdump.tar.gz -P ./install
docker-compose exec mysql tar -xvf ./install/bitrixdbdump.tar.gz -C ./install
docker-compose exec mysql mysql -u mir -p$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE $MYSQL_DATABASE IF NOT EXISTS"
cat ./install/bitrix.sql | docker exec -i mir_mysql /usr/bin/mysql -u root --password=S8jXW3qYZy mir