docker-up: memory
	sudo docker-compose up -d

docker-down:
	sudo docker-compose down

docker-build: memory
	sudo docker-compose up --build -d

test:
	sudo docker-compose exec php-cli vendor/bin/phpunit

assets-install:
	sudo docker-compose exec node yarn install

assets-rebuild:
	sudo docker-compose exec node npm rebuild node-sass --force

assets-dev:
	sudo docker-compose exec node yarn run dev

assets-watch:
	sudo docker-compose exec node yarn run watch

queue:
	sudo docker-compose exec php-cli php artisan queue:work

horizon:
	sudo docker-compose exec php-cli php artisan horizon

horizon-pause:
	sudo docker-compose exec php-cli php artisan horizon:pause

horizon-continue:
	sudo docker-compose exec php-cli php artisan horizon:continue

horizon-terminate:
	sudo docker-compose exec php-cli php artisan horizon:terminate

memory:
	sudo sysctl -w vm.max_map_count=262144

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache


#sudo chmod 777 storage -R - предоставить права на папку
#Запуск ElasticSearch - Поиск объявлений
#curl http://127.0.0.1:9201/_cat/health; - проверка запущен ли ElasticSearch
#curl http://127.0.0.1:9201/dating/_search?pretty=true - выводим массив всех объявлений в поиске
#php artisan search:init; php artisan search:reindex - команды на заполнение ElasticSearch данными по поиску
