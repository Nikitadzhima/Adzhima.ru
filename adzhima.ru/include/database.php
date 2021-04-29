<?php

$link = mysqli_connect('localhost', 'n66414_dbuser', 'L9c&lqzRNYt=uy*<', 'n66414_db');

mysqli_set_charset($link, 'utf8');

if (mysqli_connect_errno()) {
    echo 'Ошибка в подключении базы данных ('.mysqli_connect_errno().'): '.mysqli_connect_error();
    exit();
}
