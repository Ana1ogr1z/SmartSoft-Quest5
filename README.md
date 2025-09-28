# Установка проекта

## Настройка базы данных

1. В папке `Cfg` создайте файл `Config.php`

2. Вставьте в него следующий код:

```php
<?php

namespace Cfg;

abstract class Config
{
    /**
     * @var string
     */
    protected $server = ""; // Сервер
    protected $user = ""; // Пользователь
    protected $db_password = ""; // Пароль от базы данных
    protected $db_name = ""; // Имя базы данных
}
?>
```

3. Заполните параметры подключения к вашей базе данных:

```php
protected $server = "localhost"; // Обычно localhost
protected $user = "ваш_пользователь"; 
protected $db_password = "ваш_пароль";
protected $db_name = "имя_вашей_базы_данных";
```

## Установка зависимостей

```bash
composer install
```

## Запуск проекта

Откройте в браузере: `http://localhost/Quest5/`
