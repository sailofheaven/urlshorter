# urlshorter

## Установка и запуск
- git clone https://github.com/sailofheaven/urlshorter.git
- cd urlshorter
- composer install
- клонируем файл .env.example и удаляем из названия .example
- php artisan key:generate
- меняем настройки подключения БД в .env
- php artisan migrate
- php artisan serve
- после регистрируемся POST /api/v1/users/me Поля: email,password

## Описание API
### Ресурс, предоставляющий работу с пользователями.

- `POST /api/v1/users` - регистрация пользователя (авторизация не требуется).
- `GET /api/v1/users/me` - получение информации о текущем авторизованном пользователе.

### Ресурс, предоставляющий работу с короткими ссылками пользователя
- `POST /api/v1/users/me/shorten_urls` - создание новой короткой ссылки 
- `GET /api/v1/users/me/shorten_urls` - получение всех созданных коротких ссылок пользователя
- `GET /api/v1/users/me/shorten_urls/{id}` - получение информации о конкретной короткой ссылке пользователя (также включить количество переходов)
- `DELETE /api/v1/users/me/shorten_urls/{id}` - удаление короткой ссылки пользователя

### Отчеты
- `GET /api/v1/users/me/shorten_urls/{id}/[days,hours,min]?from_date=0000-00-00;to_date=0000-00-00` - получение временного графика количества переходов с группировкой по дням, часам, минутам.
- `GET /api/v1/users/me/shorten_urls/{id}/referers` - получение топа из 20 сайтов иcточников переходов

### Ресурс, предоставляющий работу с короткими ссылками (авторизация не требуется)
- `GET /api/v1/shorten_urls/{hash}` - переход по ссылке (302 redirect)
