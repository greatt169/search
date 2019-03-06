docker-compose exec php-fpm wget -c https://aeroidea.ru/upload/backend/bitrix.tar.gz -P ./public
docker-compose exec php-fpm tar -xvf public/bitrix.tar.gz -C ./public