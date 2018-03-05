<?php
  session_start();
  require_once 'functions.php';
  require_once 'config/database.php';
  require_once 'mysql_helper.php';

  $active_session = '';
  $task_add = '';
  $auth_form = '';
  $username = '';
  $categories = [];
  $task_list = [];
  $project = [];
  $project_add = '';
  $register_form = '';
  $error = '';


  // Проверяем наличие куки
  if (isset($_COOKIE['showcompl'])) {
    $show_complete_tasks = $_COOKIE['showcompl'];
  } else {
    $show_complete_tasks = 1;
    setcookie("showcompl", $show_complete_tasks, time()+3600, "/");
  }

  // Показываем/прячем выполененные задания
  if (isset($_GET['show_completed'])) {
    if ($_COOKIE['showcompl'] == 1) {
      $show_complete_tasks = 0;
    } else {
      $show_complete_tasks = 1;
    }
    setcookie("showcompl", $show_complete_tasks, time()+3600, "/");
    header('Location:' . $_SERVER["HTTP_REFERER"]);
  }

  $page_content = include_template('templates/guest.php', []);

  // Проверяем наличие сессии
  if (isset($_SESSION['user_valid'])) {
    include 'session_process.php';
  } elseif (isset($_GET['login'])) {
    $auth_form = include_template('templates/auth_form.php', ['errors' => []]);
  } else {
      if (!isset($_GET['register'])) {
        $page_content = include_template('templates/guest.php', []);
      } else {
        $page_content = include_template('templates/register.php', []);
      }
  }

  // Показываем форму добавление задачи
  if (isset($_GET['add']) && isset($_SESSION['user_valid'])) {
    $task_add = include_template('templates/task_add.php', ['task' => [], 'errors' => [], 'categories' => $categories, 'task_category' => '', 'username' => $_SESSION['user_valid']['name']]);
  } elseif (!isset($_SESSION['user_valid']) && isset($_GET['add'])) {
    $auth_form = include_template('templates/auth_form.php', ['errors' => []]);
  }

  // Обработка формы добаление задания
  if (isset($_POST['add_btn'])) {
    include 'task_add_process.php';
  }

  // Показываем форму добавление проекта
  if (isset($_GET['project_add']) && isset($_SESSION['user_valid'])) {
    $project_add = include_template('templates/project_add.php', ['project' => [], 'errors' => [], 'username' => $_SESSION['user_valid']['name']]);
  } elseif (!isset($_SESSION['user_valid']) && isset($_GET['project_add'])) {
    $auth_form = include_template('templates/auth_form.php', ['errors' => []]);
  }

  // Обработка формы добавление проекта
  if (isset($_POST['proj_add_btn'])) {
    include 'project_add_process.php';
  }

  // Фильтрация заданий
  if (isset($_GET['t_filter'])) {
    include 'filter_process.php';
  }

  // Обработка формы входа
  if (isset($_POST['login_btn'])) {
    include 'login_process.php';
  }

  // Обработка формы регистрации
  if (isset($_POST['registration'])) {
    include 'registration_process.php';
  }

  // Изменение состояния задания
  if (isset($_GET['set_done'])) {
    include 'task_complete_process.php';
  }

  // Выход из сессии
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
    'project' => $project,
    'project_add' => $project_add,
    'auth_form' => $auth_form,
    'username' => $username,
    'register_form' => $register_form,
    'error' => $error,
    'active_session' => $active_session
  ]);

  print($layout_content);
?>
