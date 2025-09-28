В папке "Cfg" должен быть создан файл Config.php.
В файл Config.php нужно вставить
<?php

    namespace Cfg;

    abstract class Config
    {
        /**
         * @var string
         */
        protected $server = ""; //Сервер
        protected $user = ""; //Пользователь
        protected $db_password = ""; //Пароль от базы данных
        protected $db_name = ""; //Имя базы данных
    }
    ?>
    
