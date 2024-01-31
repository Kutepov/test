СБОРКА
------------


Разверните проект, используя следующую команду:

~~~
$ docker-compose up --build
~~~

Развернется development окружение.

Чтобы развернуть production окружение, нужно собрать образ, используя аргумент ENVIRONMENT:
~~~
$ docker-compose build --build-arg ENVIRONMENT=prod
$ docker-compose up -d
~~~