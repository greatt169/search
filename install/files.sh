docker-compose exec php-fpm wget \
		--user loginarea \
		--password passarea \
		-c https://aeroidea.ru/upload/backend/secure/bitrix.tar.gz \
		-P ./public
docker-compose exec php-fpm tar -xvf public/bitrix.tar.gz -C ./public