# Установка
* docker-compose up -d
* sh install/files.sh
* создать файл конфигурации `.env` по аналогии с `.env.template`
* docker-compose exec -u 1000 php-fpm composer install
* sh install/database.sh

# Использование команд
* docker-compose exec -u 1000 php-fpm php ./vendor/bin/jedi

# Подключение к БД
* Требуется прописать доступы к БД в settings битрикса. Хост БД будет "mysql"
* Для админа установить пароль после $USER->Authorize()

# env
* В файле конфигурации `.env` прописать настройки БД из `install/database.sh`