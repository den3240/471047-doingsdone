<?php

  require_once 'functions.php';
  require 'userdata.php';

  $categories = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

  $task_list = [
      [
          'id' => 1,
          'title' => 'Собеседование в IT компании',
          'date' => '12.02.2018',
          'category' => $categories[3],
          'status' => 'Нет'
      ],
      [
          'id' => 2,
          'title' => 'Выполнить тестовое задание',
          'date' => '25.06.2018',
          'category' => $categories[3],
          'status' => 'Нет'
      ],
      [
          'id' => 3,
          'title' => 'Сделать задание первого раздела',
          'date' => '03.02.2018',
          'category' => $categories[2],
          'status' => 'Да'
      ],
      [
          'id' => 4,
          'title' => 'Встреча с другом',
          'date' => '10.02.2018',
          'category' => $categories[1],
          'status' => 'Нет'
      ],
      [
          'id' => 5,
          'title' => 'Купить корм для кота',
          'date' => false,
          'category' => $categories[4],
          'status' => 'Нет'
      ],
      [
          'id' => 6,
          'title' => 'Заказать пиццу',
          'date' => false,
          'category' => $categories[4],
          'status' => 'Нет'
      ]
  ];


  // setcookie("showcompl", 1, time()+3600, "/");
  if (isset($_COOKIE['showcompl'])) {
    // echo "1";
    $show_complete_tasks = $_COOKIE['showcompl'];
  } else {
    // echo "2";
    $show_complete_tasks = 1;
    setcookie("showcompl", $show_complete_tasks, time()+3600, "/");
  }




  if (isset($_GET['show_completed'])) {
    if ($_COOKIE['showcompl'] == 1) {
      $show_complete_tasks = 0;
    } else {
      $show_complete_tasks = 1;
    }
    setcookie("showcompl", $show_complete_tasks, time()+3600, "/");
    header('Location:' . $_SERVER["HTTP_REFERER"]);
  }




  if (isset($_GET['add'])) {
    $task_add = include_template('templates/task_add.php', ['task' => [], 'errors' => [], 'categories' => $categories, 'task_category' => '', 'username' => $_SESSION['$user_valid']['name']]);
  }




  if (isset($_POST['add_btn'])) {

    $task = $_POST;
    $task_name = $_POST['name'];
    $task_category = $_POST['project'];
    $task_date = date("d.m.Y", strtotime($_POST['date']));

    $required = ['name', 'project', 'date'];
    $errors = [];
    foreach ($required as $key) {
      if (empty($_POST[$key])) {
        $errors[$key] = 'Заполните это поле';
      }
   }

   if (isset($_FILES['preview']['name'])) {
      $tmp_name = $_FILES['preview']['tmp_name'];
      $path = $_FILES['preview']['name'];

      move_uploaded_file($tmp_name, '' . $path);
   }

   if (count($errors)) {
     $task_add = include_template('templates/task_add.php', ['task' => $task, 'errors' => $errors, 'categories' => $categories, 'task_category' => $task_category]);
   } else {
     array_unshift($task_list, ['title' => $task_name, 'date' => $task_date, 'category' => $task_category, 'status' => 'Нет']);
   }
  }



  session_start();
  if (isset($_SESSION['$user_valid'])) {
      $username = $_SESSION['$user_valid']['name'];
      $filtered_tasks = null;

      $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $task_list, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['$user_valid']['name']]);

      if (isset($_GET['category_id'])) {
        (int)$category_id = $_GET['category_id'];

        if (!isset($categories[$category_id])) {
          http_response_code(404);
          $page_content = include_template('templates/error.php', ['error_text' => "404"]);
        } else {
          if ($categories[$category_id] != "Все") {
            $filter_category = $categories[$category_id];
            $filtered_tasks = array_filter($task_list, function($element) use ($filter_category) {
              return $element['category'] == $filter_category;
            });
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filtered_tasks, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['$user_valid']['name']]);
          }
        }
      }
  } elseif (isset($_GET['login'])) {
    $auth_form = include_template('templates/auth_form.php', ['errors' => []]);

  } else {
      $page_content = include_template('templates/guest.php', []);
  }

  if (isset($_POST['login_btn'])) {

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


   if ($user_valid = searchUserByEmail($user_email, $users)) {
       if (password_verify($user['password'], $user_valid['password'])) {
         $_SESSION['$user_valid'] = $user_valid;
       }
       else {
         $errors['password'] = 'Неверный пароль';
       }
  } elseif (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Такой пользователь не найден';
  } else {
    $errors['email'] = 'Такой пользователь не найден';
  }

   if (count($errors)) {
     $auth_form = include_template('templates/auth_form.php', ['user' => $user, 'errors' => $errors]);
   } else {
     header("Location: index.php");
     exit();
   }
  }

  if (isset($_GET['exit'])) {
    session_destroy();
    header("Location: index.php");
  }




  $layout_content = include_template('templates/layout.php', [
  	'content' => $page_content,
  	'categories' => $categories,
  	'title' => 'Дела в Порядке',
    'task_list' => $task_list,
    'task_add' => $task_add,
    'auth_form' => $auth_form,
    'username' => $username
  ]);

  print($layout_content);
?>
