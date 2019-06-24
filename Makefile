swagger-search-generate:
	java -jar codegen/swagger-codegen-cli.jar generate -i public/swagger/swagger.yaml -l php -o codegen/search --invoker-package SwaggerUnAuth
	composer update --working-dir codegen/search/SwaggerClient-php/