<template>
  <div class="container">
    <div class="card card-default">
      <div class="card-header"><b>Инструкция:</b></div>
      <div class="card-body">
        Устаноаить docker, docker-compose;<br>
        Открыть в PHPStorm.<br>
        - В терминале из папки TZ запустить команду - make docker-build<br>
        - Проверить чтобы все контейнеры запустились - sudo docker-compose ps<br>
        - Если nginx-api не запустился, ещё раз - make docker-build<br><br>
        Далее переходим в папку TZ/api...<br>
        - Применяем миграции - sudo docker-compose exec api-php-cli php artisan migrate<br>
        - Делаем reindex elasticsearch - sudo docker-compose exec api-php-cli php artisan search:init; php artisan search:reindex<br><br>
        Далее переходим в папку TZ/frontend...<br>
        Запускаем - sudo docker-compose exec frontend-nodejs npm run watch и можем вность изменения (Ctrl + S сохранить)<br><br>
        Воде бы всё... регистрируемся и добавляем книги, ищем в поиске, оцениваем и т.д... - http://localhost:8080<br><br>
        Изображения сохраняются в Amazon:<br>
        - прописать настройки в api/env;<br>
        - прописать настройку pathApiStorage в папке - frontend/src/store/modules/customizes_img.js<br>
        - настроить cropper под нужный размер изображения...<br><br>
        *Подтверждение регистрации и восстановление пароля добавить настройки SMTP в api/env:<br>
        *Все нужные команды в TZ/README.txt
      </div>
    </div>
  </div>
</template>