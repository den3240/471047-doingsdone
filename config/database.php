<?php

if (!file_exists('config/config.php')) {
  $error = mysqli_connect_error($con);
  $page_content = include_template('templates/error.php', ['error' => $error]);
}

require_once('config/config.php');
$con = mysqli_connect($host, $db_user, $db_password, $database);
mysqli_set_charset($con, "utf8");

?>
