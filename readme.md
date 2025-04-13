# Лабораторная работа №6: Создание многоконтейнерного приложения

## Цель работы
Ознакомиться с работой многоконтейнерного приложения на базе docker-compose.

## Задание
Создать PHP-приложение на базе трех контейнеров: nginx, php-fpm, mariadb, используя docker-compose.

## Подготовка
Для выполнения данной работы необходимо иметь установленный Docker.

Работа выполняется на базе лабораторной работы №5.

---

## Выполнение

### 1. Создание репозитория

1. Создайте репозиторий с именем `containers06` и скопируйте его себе на компьютер.

   ```bash
   git init containers06
2. Сайт на PHP
В директории containers06 создайте директорию mounts/site:

bash
Копировать
Редактировать
mkdir -p containers06/mounts/site
Перепишите сайт на PHP, созданный в рамках предыдущих лабораторных работ, в эту директорию. Убедитесь, что в этой директории есть файлы index.php, а также другие страницы, такие как about.php и contact.php, если они есть.

3. Конфигурационные файлы
3.1. Создание файла .gitignore
В корне проекта создайте файл .gitignore и добавьте в него строки:

bash
Копировать
Редактировать
# Ignore files and directories
mounts/site/*
# 3.2. Создание конфигурации для Nginx
В директории containers06 создайте файл nginx/default.conf со следующим содержимым:

nginx
Копировать
Редактировать
server {
    listen 80;
    server_name _;

    root /var/www/html;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass backend:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
#  3.3. Создание docker-compose.yml
В директории containers06 создайте файл docker-compose.yml со следующим содержимым:

yaml
Копировать
Редактировать
version: '3.9'

services:
  frontend:
    image: nginx:1.19
    volumes:
      - ./mounts/site:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    ports:
      - "80:80"
    networks:
      - internal

  backend:
    image: php:7.4-fpm
    volumes:
      - ./mounts/site:/var/www/html
    networks:
      - internal
    env_file:
      - mysql.env

  database:
    image: mysql:8.0
    env_file:
      - mysql.env
    networks:
      - internal
    volumes:
      - db_data:/var/lib/mysql

networks:
  internal: {}

volumes:
  db_data: {}
#  3.4. Создание файла mysql.env
В корне проекта создайте файл mysql.env и добавьте в него строки:

env
Копировать
Редактировать
MYSQL_ROOT_PASSWORD=secret
MYSQL_DATABASE=app
MYSQL_USER=user
MYSQL_PASSWORD=secret
3.5. Создание файла app.env
В корне проекта создайте файл app.env и добавьте в него строку:

env
Копировать
Редактировать
APP_VERSION=1.0.0
Чтобы добавить переменную окружения APP_VERSION для сервисов frontend и backend, нужно обновить docker-compose.yml, добавив ссылку на файл app.env в секции env_file для сервисов frontend и backend.

Пример обновленного docker-compose.yml:

yaml
Копировать
Редактировать
version: '3.9'

services:
  frontend:
    image: nginx:1.19
    volumes:
      - ./mounts/site:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    ports:
      - "80:80"
    networks:
      - internal
    env_file:
      - mysql.env
      - app.env

  backend:
    image: php:7.4-fpm
    volumes:
      - ./mounts/site:/var/www/html
    networks:
      - internal
    env_file:
      - mysql.env
      - app.env

  database:
    image: mysql:8.0
    env_file:
      - mysql.env
    networks:
      - internal
    volumes:
      - db_data:/var/lib/mysql

networks:
  internal: {}

volumes:
  db_data: {}
# 4. Запуск и тестирование
Для запуска контейнеров используйте команду:

bash
Копировать
Редактировать
docker-compose up -d
Проверьте работу сайта в браузере, перейдя по адресу http://localhost. Если отображается базовая страница Nginx, перезагрузите страницу.

### Ответы на вопросы
#### 1. В каком порядке запускаются контейнеры?
Контейнеры запускаются в следующем порядке:

database (MariaDB) — для запуска базы данных.

backend (PHP-FPM) — для обработки PHP-кода.

frontend (Nginx) — для обработки запросов от клиента и маршрутизации.

#### 2. Где хранятся данные базы данных?
Данные базы данных хранятся в Docker-томе db_data, который монтируется в контейнер MariaDB на путь /var/lib/mysql. Этот том сохраняет данные между перезапусками контейнеров.

#### 3. Как называются контейнеры проекта?
Контейнеры проекта называются:

frontend (Nginx)

backend (PHP-FPM)

database (MariaDB)

## 4. Как добавить файл app.env с переменной окружения APP_VERSION для сервисов backend и frontend?
Для добавления файла app.env с переменной окружения APP_VERSION нужно:

Создать файл app.env с переменной APP_VERSION.

Добавить ссылку на этот файл в секцию env_file для сервисов frontend и backend в файле docker-compose.yml.

# Выводы
В ходе выполнения работы была успешно создана и настроена многоконтейнерная среда для PHP-приложения с использованием Docker Compose. Работа была выполнена согласно заданию, и приложение было развернуто с тремя контейнерами: Nginx, PHP-FPM и MariaDB. Также была добавлена переменная окружения для версий приложения, что позволяет централизованно управлять версиями в разных сервисах.
