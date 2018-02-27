<?php

if (!file_exists('config/config.php')) {
  echo 'Ошибка подключения к Базе Данных';
  exit();
}

require_once('config/config.php');
$con = mysqli_connect($host, $db_user, $db_password, $database);
if (!$con) {
  echo 'Ошибка подключения к Базе Данных';
  exit();
}
mysqli_set_charset($con, "utf8");
