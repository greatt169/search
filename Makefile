swagger-search-generate:
	docker-compose exec --user 1000 php-fpm java -jar codegen/swagger-codegen-cli.jar generate -i public/swagger/swagger.yaml -l php -o codegen/search --invoker-package SwaggerUnAuth
	docker-compose exec --user 1000 php-fpm composer update --working-dir codegen/search/SwaggerClient-php/