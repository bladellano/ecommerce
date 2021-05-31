<?php

define("URL_BASE","http://psmagnetica.com.br");

// Email configuration
define("MAIL_EMAIL","dellanosites@gmail.com");
define("MAIL_PASSWORD","@@Caio2019");
define("MAIL_HOST","smtp.gmail.com");
define("MAIL_NAME_FROM","PREVEN SAÚDE");
define("NAME_SITE",URL_BASE);

// Seo configuration
define("SITE", "PREVEN SAÚDE - Preservando a vida");
define("DESCRIPTION", "");
define("FB_PAGE", "");
define("FB_AUTHOR", "");
define("APP_ID", "");
define("URL_SITE", URL_BASE);
define("IMAGE", URL_BASE."/views/site/assets/images/logo.jpg");

// Database configuration
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "127.0.0.1",
    "port" => "3306",
    "dbname" => "db_ecommerce",
    "username" => "root",
    "passwd" => "root",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);


