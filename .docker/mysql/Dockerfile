FROM mysql:5.6

MAINTAINER vitams

ADD my.cnf /etc/mysql/conf.d/my.cnf

RUN apt-get update \
    && apt-get install -y wget

CMD ["mysqld"]

WORKDIR "/var/www"

EXPOSE 3306
