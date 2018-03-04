<?php
    $user = $_POST;
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $key) {
      if (empty($_POST[$key])) {
        $errors[$key] = 'Заполните это поле';
      }
   }

   if ($user_valid = searchUserByEmail($user_email, $users_list)) {
     if (password_verify($user['password'], $user_valid['password'])) {
       $_SESSION['user_valid'] = $user_valid;
     } else {
       $errors['password'] = 'Неверный пароль';
     }
  } elseif (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Такой пользователь уже существует';
  } else {
    $errors['email'] = 'Такой пользователь не найден';
  }

   if (count($errors)) {
     $auth_form = include_template('templates/auth_form.php', ['user' => $user, 'errors' => $errors]);
   } else {
     header("Location: index.php");
   }
