FROM yiisoftware/yii2-php:8.1-fpm

RUN apt-get update
RUN apt-get install -y wget git unzip htop

WORKDIR /var/www/html
CMD ["/bin/bash", "./start.sh"]