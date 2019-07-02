swagger-search-generate:
	rm -rf codegen/search/SwaggerClient-php/lib
	docker-compose exec --user 1000 php-fpm java -jar codegen/swagger-codegen-cli.jar generate -i public/swagger/swagger.yaml -l php -o codegen/search --invoker-package SwaggerUnAuth

search-reindex:
	docker-compose exec --user 1000 php-fpm php artisan search:reindex