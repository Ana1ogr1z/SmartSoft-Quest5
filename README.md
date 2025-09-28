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

## Запуск проекта

Откройте в браузере: `http://localhost/Quest5/`
