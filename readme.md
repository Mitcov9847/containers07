# Лабораторная работа №7: Создание многоконтейнерного приложения

## Цель работы
Ознакомиться с работой многоконтейнерного приложения на базе docker-compose.

## Задание
Создать PHP-приложение на базе трех контейнеров: nginx, php-fpm, mariadb, используя docker-compose.

## Подготовка
Для выполнения данной работы необходимо иметь установленный Docker.

---

## Выполнение

### 1. Создание репозитория

1. Создайте репозиторий с именем `containers06` и скопируйте его себе на компьютер.

```bash
   git init containers07
    ```
2. Сайт на PHP
В директории containers07 создайте директорию mounts/site:

```bash
mkdir -p containers07/mounts/site
 ```
![image](https://github.com/user-attachments/assets/2e5e151b-0147-4c74-aa43-d3fa7e8fe608)


3. Конфигурационные файлы
3.1. Создание файла .gitignore
В корне проекта создайте файл .gitignore и добавьте в него строки:

``` bash
mounts/site/*
 ```

# 3.2. Создание конфигурации для Nginx
В директории containers07 создайте файл nginx/default.conf со следующим содержимым:

``` nginx
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
 ```
![image](https://github.com/user-attachments/assets/c789ee89-262e-4a94-99e9-dcf65f440a51)

#  3.3. Создание docker-compose.yml
В директории containers06 создайте файл docker-compose.yml со следующим содержимым:

```yaml
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
 ```
![image](https://github.com/user-attachments/assets/a485eb8d-cfc9-4e52-a741-26611f515fff)

#  3.4. Создание файла mysql.env 
В корне проекта создайте файл mysql.env и добавьте в него строки:

```env
MYSQL_ROOT_PASSWORD=secret
MYSQL_DATABASE=app
MYSQL_USER=user
MYSQL_PASSWORD=secret
 ```
![image](https://github.com/user-attachments/assets/6ebf1759-2ce5-40fb-85c7-921e853f9867)

# 4. Запуск и тестирование
Для запуска контейнеров используйте команду:

```bash
docker-compose up -d
 ```
![image](https://github.com/user-attachments/assets/b5cbe54f-864b-4b48-863d-9ee56f18f42c)

![image](https://github.com/user-attachments/assets/ce0b1690-079e-41d1-b76a-99db545be3c3)


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
