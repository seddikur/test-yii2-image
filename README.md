# Разработать прототип хостинга изображений.

## Инструменты для реализации задания:

- фреймворк Laravel/Yii2
- mysql
1. Реализовать форму для загрузки изображений.
   При загрузке изображений должны соблюдаться следующие правила:
- в 1 запрос, в одной форме, можно загружать до 5 файлов
- название каждого файла должно транслителироваться на английский язык и приводиться к нижнему регистру
- название каждого файла должно быть уникальным, и, если файл с таким названием существует, нужно задавать новому файлу уникальное название
- все файлы должны отправляться в одну директорию
- записывать в БД инфу о загруженных файлах: название файла, дата и время загрузки
2. Реализовать страницу просмотра информации об изображениях.
   На странице должны быть реализованы:
- вывод информации о загруженных файлах (название файла, дата и время загрузки)
- просмотр превью изображения
- возможность просмотра оригинального изображения
- сортировка по названию/дате и времени загрузки изображения
- возможность скачать файл в zip архиве
3. Реализовать API
- вывод информации о загруженных файлах в json
- получить данные о загруженном файле по id в json
  Код задания необходимо выложить на github/gitlab/bitbucket.
  Бонусом будет возможность просмотра результата в общем доступе (например vds)

# Порядок установки проекта

* Клонирование проекта `` git clone https://github.com/seddikur/test-yii2-image.git``
* Запуск Docker `` docker compose up -d ``
* Переход в контейнер  `` docker-compose exec -it php bash ``
* Запуск установки расширений yii2 `` composer install ``
* Запуск миграций `` php yii migrate ``

http://localhost:8000/v1/

получение списка всех картинок;

http://localhost:8000/api/v1/image/index

получение информации по картинке с id равным 11

http://localhost:8000/api/v1/image/view?id=11
