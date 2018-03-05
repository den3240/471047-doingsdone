<?php
// Создаем список пользователь
$sql = 'SELECT `id`, `name`, `email`, `password` FROM users';
$result = mysqli_query($con, $sql);
$users_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

$new_user = $_POST;
$new_user_email = $_POST['email'];
$new_user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$new_user_name = trim($_POST['name']);

$required = ['email', 'password', 'name'];
$errors = [];
foreach ($required as $key) {
  if (empty($_POST[$key])) {
    $errors[$key] = 'Заполните это поле';
  }
}
if (!filter_var($new_user_email, FILTER_VALIDATE_EMAIL)) {
 $errors['email'] = 'Email некорректный';
} elseif ($user_valid = searchUserByEmail($new_user_email, $users_list)) {
  $errors['email'] = 'Такое пользователь уже существует';
} else {
 $new_user_email = $_POST['email'];
}
if (count($errors)) {
 $register_form = include_template('templates/register.php', ['new_user' => $new_user, 'errors' => $errors]);
} else {
   $sql = "INSERT INTO `users` (`name`, `email`, `password`, `contacts`) VALUES (?, ?, ?, NULL)";
   $stmt = db_get_prepare_stmt($con, $sql, [$new_user_name, $new_user_email, $new_user_password]);
   $res = mysqli_stmt_execute($stmt);
   if ($res) {
     header("Location: index.php?login");
   } else {
     $error = mysqli_error($con);
     $page_content = include_template('templates/error.php', ['error' => $error]);
   }
}
