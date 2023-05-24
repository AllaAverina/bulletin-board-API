<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Доска объявлений
Создание минимально жизнеспособного REST API доски объявлений в учебных целях.

## Функционал
* Получение списков всех тегов, постов и пользователей
* Поиск в списках
* Регистрация пользователя
* Зарегистрированный пользователь может:
    * Добавить новые посты
    * Прокомментировать существующие посты
    * Удалить и отредактировать свои посты и комментарии

## Требования
* [PHP 8.1+](https://www.php.net/)
* [Laravel 10](https://laravel.com/)
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/)

## Документация

#### Аутентификация

<details>
    <summary>
        <code>POST</code>
        <code>/api/v1/auth/register</code>
        <small>Зарегистрироваться</small>
    </summary>

##### Body
|   Name   |   Type   | Data type |           Description            |
|----------|----------|-----------|----------------------------------|
| name     | required | string    | Имя                              |
| nickname | required | string    | Человеко-понятный идентификатор  |
| email    | required | string    | Email адрес                      |
| password | required | string    | Пароль                           |
| password_confirmed  | required  | string| Подтверждение пароля     |

##### Success Response
##### HTTP Code: <code>201</code> <code>CREATED</code>
```
{
    "access_token",
    "token_type": "Bearer"
}
```
</details>

<details>
    <summary>
        <code>POST</code>
        <code>/api/v1/auth/login</code>
        <small>Войти</small>
    </summary>

##### Body
|   Name   |   Type   | Data type |   Description    |
|----------|----------|-----------|------------------|
| email    | required | string    | Email адрес      |
| password | required | string    | Пароль           |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "access_token",
    "token_type":"Bearer"
}
```
</details>

<details>
    <summary>
        <code>POST</code>
        <code>/api/v1/auth/logout</code>
        <small>Выйти</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Success Response
##### HTTP Code: <code>204</code> <code>NO CONTENT</code>
</details>

------------------------------------------------------------------------------
#### Профиль

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/profile</code>
        <small>Получить профиль аутентифицированного пользователя</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": {
        "user_id",
        "name",
        "nickname",
        "email",
        "created_at",
        "updated_at",
        "posts_count",
        "comments_count"
    }
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/profile/posts</code>
        <small>Получить профиль и посты аутентифицированного пользователя</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Params
|   Name   |   Type   | Data type |          Description          |
|----------|----------|-----------|-------------------------------|
| per_page | optional | int       | Количество постов на странице |
| page     | optional | int       | Номер страницы                |

##### Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "post_id",
            "title",
            "price",
            "description",
            "created_at",
            "updated_at",
            "user_id",
            "user_nickname",
            "tags": [
                {
                    "tag_id",
                    "name",
                    "slug",
                },
                ...
            ],
            "comments_count"
        }
    ],
    "links": {...},
    "meta": {...},
    "user": {
        "user_id",
        "name",
        "nickname",
        "email",
        "created_at",
        "updated_at"
    }
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/profile/comments</code>
        <small>Получить профиль и комментарии аутентифицированного пользователя</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Params
|   Name   |   Type   | Data type |            Description              |
|----------|----------|-----------|-------------------------------------|
| per_page | optional | int       | Количество комментариев на странице |
| page     | optional | int       | Номер страницы                      |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "comment_id",
            "body",
            "created_at":,
            "updated_at",
            "post_id",
            "user_id",
            "user_nickname"
        },
        ...
    ],
    "links": {...},
    "meta": {...},
    "user": {
        "user_id",
        "name",
        "nickname",
        "email",
        "created_at",
        "updated_at"
    }
}
```
</details>

------------------------------------------------------------------------------
#### Пользователи

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/users</code>
        <small>Получить всех пользователей</small>
    </summary>

##### Params
|   Name   |   Type   | Data type |              Description             |
|----------|----------|-----------|--------------------------------------|
| q        | optional | string    | Поисковый запрос                     |
| per_page | optional | int       | Количество пользователей на странице |
| page     | optional | int       | Номер страницы                       |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "user_id",
            "name",
            "nickname",
            "email",
            "created_at",
            "updated_at",
            "posts_count",
            "comments_count"
        },
        ...
    ],
    "links": {...},
    "meta": {...}
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/users/{nickname}</code>
        <small>Получить пользователя</small>
    </summary>

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": {
        "user_id",
        "name",
        "nickname",
        "email",
        "created_at",
        "updated_at",
        "posts_count",
        "comments_count"
    }
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/users/{nickname}/posts</code>
        <small>Получить пользователя и его посты</small>
    </summary>

##### Params
|   Name   |   Type   | Data type |          Description          |
|----------|----------|-----------|-------------------------------|
| per_page | optional | int       | Количество постов на странице |
| page     | optional | int       | Номер страницы                |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "post_id",
            "title",
            "price",
            "description",
            "created_at",
            "updated_at",
            "user_id",
            "user_nickname",
            "tags": [
                {
                    "tag_id",
                    "name",
                    "slug"
                },
                ...
            ],
            "comments_count"
        },
        ...
    ],
    "links": {...},
    "meta": {...},
    "user": {
        "user_id",
        "name",
        "nickname",
        "email",
        "created_at",
        "updated_at"
    }
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/users/{nickname}/comments</code>
        <small>Получить пользователя и его комментарии</small>
    </summary>

##### Params
|   Name   |   Type   | Data type |            Description              |
|----------|----------|-----------|-------------------------------------|
| per_page | optional | int       | Количество комментариев на странице |
| page     | optional | int       | Номер страницы                      |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "comment_id",
            "body",
            "created_at":,
            "updated_at",
            "post_id",
            "user_id",
            "user_nickname"
        },
        ...
    ],
    "links": {...},
    "meta": {...},
    "user": {
        "user_id",
        "name",
        "nickname",
        "email",
        "created_at",
        "updated_at"
    }
}
```
</details>

------------------------------------------------------------------------------

#### Теги
<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/tags</code>
        <small>Получить все теги</small>
    </summary>

##### Params
|   Name   |   Type   | Data type |   Description    |
|----------|----------|-----------|------------------|
| q        | optional | string    | Поисковый запрос |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "tag_id",
            "name",
            "slug",
            "post_count"
        },
        ...
    ]
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/tags/{slug}</code>
        <small>Получить тег и его посты</small>
    </summary>

##### Params
|   Name   |   Type   | Data type |          Description          |
|----------|----------|-----------|-------------------------------|
| per_page | optional | int       | Количество постов на странице |
| page     | optional | int       | Номер страницы                |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "post_id",
            "title",
            "price",
            "description",
            "created_at",
            "updated_at",
            "user_id",
            "user_nickname"
        },
    ],
    "links": {...},
    "meta": {...},
    "tag": {
        "tag_id",
        "name",
        "slug"
    }
}
```
</details>

------------------------------------------------------------------------------

#### Посты
<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/posts</code>
        <small>Получить все посты</small>
    </summary>


##### Params
|   Name   |   Type   | Data type |          Description          |
|----------|----------|-----------|-------------------------------|
| q        | optional | string    | Поисковый запрос              |
| per_page | optional | int       | Количество постов на странице |
| page     | optional | int       | Номер страницы                |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "post_id,
            "title",
            "price",
            "description",
            "created_at",
            "updated_at",
            "user_id",
            "user_nickname",
            "tags": [
                {
                    "tag_id",
                    "name",
                    "slug"
                },
                ...
            ],
            "comments_count"
        },
        ...
    ],
    "links": {...},
    "meta": {...}
}
```
</details>

<details>
    <summary>
        <code>GET</code>
        <code>/api/v1/posts/{id}</code>
        <small>Получить пост и его комментарии</small>
    </summary>

##### Params
|   Name   |   Type   | Data type |            Description              |
|----------|----------|-----------|-------------------------------------|
| per_page | optional | int       | Количество комментариев на странице |
| page     | optional | int       | Номер страницы                      |


##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": [
        {
            "comment_id",
            "body",
            "created_at",
            "updated_at",
            "post_id",
            "user_id",
            "user_nickname"
        },
        ...
    ],
    "links": {...},
    "meta": {...},
    "post": {
        "post_id",
        "title",
        "price",
        "description",
        "created_at",
        "updated_at",
        "user_id",
        "user_nickname",
        "tags": [
            {
                "tag_id",
                "name",
                "slug"
            },
            ...
        ]
    }
}
```
</details>

<details>
    <summary>
        <code>POST</code>
        <code>/api/v1/posts</code>
        <small>Добавить новый пост</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Body
|    Name     |   Type   | Data type  |         Description          |
|-------------|----------|------------|------------------------------|
| title       | optional | string     | Заголовок                    |
| price       | optional | float      | Цена                         |
| description | optional | string     | Описание                     |
| tags[id]    | optional | array[int] | Массив идентификаторов тегов |

##### Success Response
##### HTTP Code: <code>201</code> <code>CREATED</code>
```
{
    "data": {
        "post_id",
        "title",
        "price",
        "description",
        "created_at",
        "updated_at",
        "user_id",
        "user_nickname",
        "tags": [
            {
                "tag_id",
                "name",
                "slug"
            },
            ...
        ]
    },
}
```
</details>

<details>
    <summary>
        <code>PUT</code>
        <code>/api/v1/posts/{id}</code>
        <small>Изменить пост</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Body
|    Name     |   Type   | Data type  |         Description          |
|-------------|----------|------------|------------------------------|
| title       | optional | string     | Заголовок                    |
| price       | optional | float      | Цена                         |
| description | optional | string     | Описание                     |
| tags[id]    | optional | array[int] | Массив идентификаторов тегов |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": {
        "post_id",
        "title",
        "price",
        "description",
        "created_at",
        "updated_at",
        "user_id",
        "user_nickname",
        "tags": [
            {
                "tag_id",
                "name",
                "slug"
            },
            ...
        ]
    },
}
```
</details>

<details>
    <summary>
        <code>DELETE</code>
        <code>/api/v1/posts/{id}</code>
        <small>Удалить пост</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Success Response
##### HTTP Code: <code>204</code> <code>NO CONTENT</code>
</details>

------------------------------------------------------------------------------
#### Комментарии

<details>
    <summary>
        <code>POST</code>
        <code>/api/v1/posts/{id}/comments</code>
        <small>Добавить новый комментарий к посту</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Body
|   Name   |   Type   | Data type |    Description    |
|----------|----------|-----------|-------------------|
| body     | required | string    | Текст комментария |

##### Success Response
##### HTTP Code: <code>201</code> <code>CREATED</code>
```
{
    "data": {
        "comment_id",
        "body",
        "created_at",
        "updated_at",
        "post_id",
        "user_id",
        "user_nickname",
    },
}
```
</details>

<details>
    <summary>
        <code>PUT</code>
        <code>/api/v1/comments/{id}</code>
        <small>Изменить комментарий</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Body
|   Name   |   Type   | Data type |    Description    |
|----------|----------|-----------|-------------------|
| body     | required | string    | Текст комментария |

##### Success Response
##### HTTP Code: <code>200</code> <code>OK</code>
```
{
    "data": {
        "comment_id",
        "body",
        "created_at",
        "updated_at",
        "post_id",
        "user_id",
        "user_nickname",
    }
}
```
</details>

<details>
    <summary>
        <code>DELETE</code>
        <code>/api/v1/comments/{id}</code>
        <small>Удалить комментарий</small>
    </summary>

##### Headers
|     Key       |     Value      |
|---------------|----------------|
| Authorization | Bearer {token} |

##### Success Response
##### HTTP Code: <code>204</code> <code>NO CONTENT</code>
</details>

------------------------------------------------------------------------------

## Запуск
1. Клонируйте этот репозиторий и перейдите в папку проекта:
```sh
git clone https://github.com/AllaAverina/bulletin-board-API
cd bulletin-board-API
```
2. Установите зависимости:
```sh
composer install
```
3. Запустите MySQL, измените параметры для подключения к базе данных в файле .env.example и выполните:
```sh
copy .env.example .env
```
4. Сгенерируйте ключ приложения:
```sh
php artisan key:generate
```
5. Выполните команду для запуска миграций:
```sh
php artisan migrate
```
Или если хотите заполнить базу данных фиктивными данными:
```sh
php artisan migrate --seed
```
6. Запустите веб-сервер:
```sh
php artisan serve
```
7. Откройте в браузере, например, http://localhost:8000/api/v1/posts

## Запуск тестов
Создайте новую базу данных для тестирования, измените параметры для подключения к ней в файле .env.testing и выполните:
```sh
php artisan test 
```
