# Тестовое задание от работодателя

Необходимо:

Реализовать api для библиотеки книг.

Функциональные требования:

1. Единый поиск по названию книги, автору
2. Возможность оценки книги/автора
3. Отображение общего каталога книг - с возможностью сортировки по оценкам
4. Отображение общего списка авторов с возможностью получения всех книг автора
5. Crud для авторов и книг

Технические требования:

PHP 7.4 (Исходный код должен соответствовать PSR-4, PSR-12)
Для хранения записей необходимо использовать любую SQL БД
Обращения к БД должны производиться через любую ORM или PDO
Любые изменения в схеме БД должны производиться при помощи миграций
Должна присутствовать инструкция по развертке приложения
Использование фреймворка - опционально (laravel/symfony)
Тесты Интеграционные на основные эндпоинты
Тестовое задание необходимо разместить в github/gitlab/bitbucket(выбрать одно)
Авторизация API - опционально (JWT или сессии)
Формат ответа API - JSON (Можно выбрать стандарт JSON-API (​ https://jsonapi.org/​ ) или свой
формат)

Будет плюсом:

Наличие скрипта по деплою приложения
Наличие Dockerfile для сборки приложения


# SPA (Laravel + JWT + Vue JS + Vuex + Docker + Elasticsearch + Amazon)

Инструкция по установке

Устаноаить docker, docker-compose;

Открыть в PHPStorm.
- В терминале из папки TZ запустить команду - make docker-build
- Проверить чтобы все контейнеры запустились - sudo docker-compose ps
- Если nginx-api не запустился, ещё раз - make docker-build

Далее переходим в папку TZ/api...
- Применяем миграции - sudo docker-compose exec api-php-cli php artisan migrate
- Делаем reindex elasticsearch - sudo docker-compose exec api-php-cli php artisan search:init; php artisan search:reindex

Далее переходим в папку TZ/frontend...
Запускаем - sudo docker-compose exec frontend-nodejs npm run watch и можем вность изменения (Ctrl + S сохранить)
Воде бы всё... регистрируемся и добавляем книги, ищем в поиске, оцениваем и т.д... - http://localhost:8080

Изображения сохраняются в Amazon:

- прописать настройки в api/env;
- прописать настройку pathApiStorage в папке - frontend/src/store/modules/customizes_img.js
- настроить cropper под нужный размер изображения...

*Подтверждение регистрации и восстановление пароля добавить настройки SMTP в api/env:
*Все нужные команды в ниже...

1. Команды Docker для api :

- команда ребилда docker-compose.yml при изменениях (стартуем приложение, команда 1)
sudo docker-compose up --build -d

- проверка состояния контейнеров - sudo docker-compose ps

- если nginx daemon off, ещё раз перебилдить - sudo docker-compose up --build -d

- остановка всех контейнеров - sudo docker-compose down

- команда для обновления package.json в контейнере api-nodejs
sudo docker-compose exec api-nodejs npm install


2. Команды Docker для frontend :

- sudo docker-compose exec frontend-nodejs npm run watch

- sudo docker-compose exec frontend-nodejs npm install


3. Команда для миграций из api
sudo docker-compose exec api-php-cli php artisan migrate


5. Пользовательские команды php artisan (список всех доступных команд - php artisan list):

Изменения роли пользователя (0 - пользователь(по умолчению), 1-адинистратор, 2-модератор)
sudo docker-compose exec api-php-cli php artisan user:role {email} {role}
