# Тестовый проект по рассылке
*Рассылает загруженный .pdf файл контактам из бд.
Программа сама выбирает как отправить файл,
в зависимости от данных заполненых в поле **contact**,
либо по почте либо в телеграмме*

## Подготовка:
PHP - 8.2.12
MariaDB - 10.4.32 (устанавливается с XAMPP)
Apache - 2.4.58 (Win64) (устанавливается с XAMPP)

### Установка XAMPP и создание таблицы в БД
XAMPP — это бесплатный и открытый пакет программного обеспечения
* скачиваем с офф сата 
https://www.apachefriends.org/download.html
* Устанавливи следуя инструкции
* После запусти XAMPP Control Panel и включи Apache и MySQL.
* Открой в браузере phpMyAdmin, перейдя по адресу 
http://localhost/phpmyadmin.
* В интерфейсе phpMyAdmin выбери вкладку "Базы данных", напиши имя **'download_db'** и нажми создать
* Выбери БД, перейди на вкладку создание таблицы, введи название **'recipients'** 
(именно такое используется в db.php) и создай таблицу с 3 полями, 
**id, fullname, contact**
* После заполнения всех необходимых полей нажми "Сохранить".

### Установка
* Клонируй репозиторий
> git clone https://github.com/MemphisAton/Test-Send-pdf.git
* Установи зависимости проекта через Composer
> composer install

### Создание .env
* Замените название .example_env на .env
* Добавьте данные по инструкции ниже:

### Настройка SMTP на примере Яндекса
можно сделать на люмой почте

* Регистрация почтового ящика на Яндексе
> mail.yandex.ru.

* Получение данных для SMTP:
>Адрес SMTP сервера: smtp.yandex.ru
Порт: 465 (для SSL) или 587 (для STARTTLS) 
Имя пользователя: твой полный email адрес на Яндексе
Пароль: пароль от твоего ящика Яндекс.Почты

* Использование данных в проекте: 
>Вставь полученные данные в .env

### Создание Telegram бота и получение токена

* В Telegram найди пользователя с именем **BotFather** или 
перейди по ссылке **t.me/BotFather.**
* Создание бота: Напиши **/newbot** и следуй инструкциям BotFather 
для создания нового бота. В процессе тебе нужно будет выбрать имя и 
уникальный username для твоего бота.
* Получение токена: По завершении настройки BotFather предоставит 
тебе токен для доступа к Telegram Bot API. Сохрани этот токен — 
он тебе понадобится для программного взаимодействия с твоим ботом.
* Использование данных в проекте:
>Вставь полученные данные в .env

## Использование

* Загружаем файл
при необходимости проверяем содержимое или удаляем
* Заполняем БД данными 
* нажимаем отправить

## Развертывание
*не сегодня*

## Авторы
* tg: **@MemphisaAton**
* email: **MemphisAton@gmail.com**

## Лицензия
*не сегодня*