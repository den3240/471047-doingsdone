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
  $p_add = '';
  $register_form = '';
  $error = '';

  // Создаем список пользователь
  $sql = 'SELECT `id`, `name`, `email`, `password` FROM users';
  $result = mysqli_query($con, $sql);
  $users_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

  if (isset($_COOKIE['showcompl'])) {
    $show_complete_tasks = $_COOKIE['showcompl'];
  } else {
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

  $page_content = include_template('templates/guest.php', []);

  // Проверяем наличие сессии
  if (isset($_SESSION['user_valid'])) {
      $username = $_SESSION['user_valid']['name'];
      $user_id = $_SESSION['user_valid']['id'];
      $filtered_tasks = null;
      $active_session = isset($_SESSION['user_valid']);


      // Выборка файлов
      $sql = 'SELECT id, file FROM tasks WHERE `user_id` = ?';
      $res = mysqli_prepare($con, $sql);
      $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
      mysqli_stmt_execute($stmt);
      $file_result = mysqli_stmt_get_result($stmt);
      if ($file_result) {
        $file_path = mysqli_fetch_all($file_result, MYSQLI_ASSOC);
      } else {
        $error = mysqli_error($con);
        $content = include_template('templates/error.php', ['error' => $error]);
      }


      // Выборка заданий
      $sql = 'SELECT * FROM tasks WHERE `user_id` = ?';
      $res = mysqli_prepare($con, $sql);
      $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
      mysqli_stmt_execute($stmt);
      $task_result = mysqli_stmt_get_result($stmt);

      if($task_result) {
        $task_list = mysqli_fetch_all($task_result, MYSQLI_ASSOC);
      } else {
        $error = mysqli_error($con);
        $content = include_template('templates/error.php', ['error' => $error]);
      }

      // Выборка проектов
      $sql = 'SELECT `id`, `name` FROM projects WHERE `user_id` = ?';
      $res = mysqli_prepare($con, $sql);
      $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
      mysqli_stmt_execute($stmt);
      $categ_result = mysqli_stmt_get_result($stmt);

      if ($categ_result) {
          $categories = mysqli_fetch_all($categ_result, MYSQLI_ASSOC);
      } else {
          $error = mysqli_error($con);
          $content = include_template('templates/error.php', ['error' => $error]);
      }

      $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $task_list, 'file_path' => $file_path, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);

      if (isset($_GET['category_id'])) {
        // (int)$category_id = $_GET['category_id'];
        foreach ($categories as $key => $category) {
          if (!isset($category['id'])) {
            http_response_code(404);
            $page_content = include_template('templates/error.php', ['error_text' => "404"]);
          } else {
            if ($_GET['category_id'] !== "all_p") {
              if ($_GET['category_id'] == $category['id']) {
                $filter_category = $category['id'];
                $filtered_tasks = array_filter($task_list, function($element) use ($filter_category) {
                  return $element['project_id'] == $filter_category;
                });
                $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filtered_tasks, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
              }
            }
          }
        }
      }
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

    $task = $_POST;
    $task_name = trim($_POST['name']);
    $task_category = $_POST['project'];
    $task_date = date("Y.m.d", strtotime($_POST['date']));
    $category_id = 0;

    foreach ($categories as $key => $category) {
      if ($task_category == $category['name']) {
        $category_id = $category['id'];
      }
    }

    $required = ['name', 'project'];
    $errors = [];
    foreach ($required as $key) {
      if (empty($_POST[$key])) {
        $errors[$key] = 'Заполните это поле';
      }
   }
   if (!$category_id) {
     $errors['project'] = 'Проект не найден';
   }

   if (isset($_FILES['preview']['name'])) {
      $tmp_name = $_FILES['preview']['tmp_name'];
      $path = $_FILES['preview']['name'];

      move_uploaded_file($tmp_name, '' . $path);
   } else {
     $path = NULL;
   }

   if (count($errors)) {
     $task_add = include_template('templates/task_add.php', ['task' => $task, 'errors' => $errors, 'categories' => $categories, 'task_category' => $task_category]);
   } else {
       if ($_POST['date'] == '') {
         $sql = 'INSERT INTO `tasks` (`name`, `file`, `deadline`, `user_id`, `project_id`) VALUES (?, ?, NULL, ?, ?)';
         $stmt = db_get_prepare_stmt($con, $sql, [$task_name, $path, $user_id, $category_id]);
         $res = mysqli_stmt_execute($stmt);
       } else {
         $sql = 'INSERT INTO `tasks` (`name`, `file`, `deadline`, `user_id`, `project_id`) VALUES (?, ?, ?, ?, ?)';
         $stmt = db_get_prepare_stmt($con, $sql, [$task_name, $path, $task_date, $user_id, $category_id]);
         $res = mysqli_stmt_execute($stmt);
       }

       if ($res) {
         header("Location: index.php");
       } else {
         $error = mysqli_error($con);
         echo $error;
         $page_content = include_template('templates/error.php', ['error' => $error]);
       }
   }
  }

  // Показываем форму добавление проекта
  if (isset($_GET['p_add']) && isset($_SESSION['user_valid'])) {
    $p_add = include_template('templates/project_add.php', ['project' => [], 'errors' => [], 'username' => $_SESSION['user_valid']['name']]);
  } elseif (!isset($_SESSION['user_valid']) && isset($_GET['p_add'])) {
    $auth_form = include_template('templates/auth_form.php', ['errors' => []]);
  }

  // Обработка формы добавление проекта
  if (isset($_POST['proj_add_btn'])) {

    $project = $_POST;
    $project_name = '';
    if (isset($_POST['name'])) {
      $project_name = trim($_POST['name']);
    }

    $errors = [];
    if (empty($project_name)) {
      $errors['name'] = 'Заполните это поле';
    } else {
      foreach ($categories as $key => $category) {
        if ($category['name'] == $project_name) {
          $errors['name'] = 'Проект с таким именем уже есть';
        }
      }
    }



   if (count($errors)) {
     $task_add = include_template('templates/project_add.php', ['project' => $project, 'errors' => $errors]);
   } else {
       $sql = "INSERT INTO `projects` (`name`, `user_id`) VALUES (?, ?)";
       $stmt = db_get_prepare_stmt($con, $sql, [$project_name, $user_id]);
       $res = mysqli_stmt_execute($stmt);

       if ($res) {
         header("Location: index.php");
       } else {
         $error = mysqli_error($con);
         $page_content = include_template('templates/error.php', ['error' => $error]);
       }
   }
  }

  // Фильтрация заданий
  if (isset($_GET['t_filter'])) {
    $t_filter = $_GET['t_filter'];
    if ($t_filter != 'all') {
      if ($t_filter == 'today') {
        if ($_GET['category_id'] == 'all_p' || !isset($_GET['category_id'])) {
          $sql = 'SELECT * FROM `tasks` WHERE `deadline` = CURDATE() AND `user_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
          mysqli_stmt_execute($stmt);
          $filter_day = mysqli_stmt_get_result($stmt);
          if ($filter_day) {
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filter_day, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
          }
        } else {
          $sql = 'SELECT * FROM `tasks` WHERE `deadline` = CURDATE() AND `user_id` = ? AND `project_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $_GET['category_id']]);
          mysqli_stmt_execute($stmt);
          $filter_day = mysqli_stmt_get_result($stmt);
          if ($filter_day) {
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filter_day, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
          }
        }

      } elseif ($t_filter == 'tomorrow') {
        if ($_GET['category_id'] == 'all_p' || !isset($_GET['category_id'])) {
          $sql = 'SELECT * FROM `tasks` WHERE `deadline` = ADDDATE(CURDATE(), INTERVAL 1 DAY) AND `user_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
          mysqli_stmt_execute($stmt);
          $filter_day = mysqli_stmt_get_result($stmt);
          if ($filter_day) {
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filter_day, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
          }
        } else {
          $sql = 'SELECT * FROM `tasks` WHERE `deadline` = ADDDATE(CURDATE(), INTERVAL 1 DAY) AND `user_id` = ? AND `project_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $_GET['category_id']]);
          mysqli_stmt_execute($stmt);
          $filter_day = mysqli_stmt_get_result($stmt);
          if ($filter_day) {
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filter_day, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
          }
        }
      } elseif ($t_filter == 'overdue') {

        if ($_GET['category_id'] == 'all_p' || !isset($_GET['category_id'])) {
          $sql = 'SELECT * FROM `tasks` WHERE `deadline` < NOW() AND `user_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
          mysqli_stmt_execute($stmt);
          $filter_day = mysqli_stmt_get_result($stmt);
          if ($filter_day) {
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filter_day, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
          }
        } else {
          $sql = 'SELECT * FROM `tasks` WHERE `deadline` < NOW() AND `user_id` = ? AND `project_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $_GET['category_id']]);
          mysqli_stmt_execute($stmt);
          $filter_day = mysqli_stmt_get_result($stmt);
          if ($filter_day) {
            $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $filter_day, 'show_complete_tasks' => $show_complete_tasks, 'username' => $_SESSION['user_valid']['name']]);
          }
        }
      }
    }
  }


  // Обработка формы входа
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
  }

  // Обработка формы регистрации
  if (isset($_POST['registration'])) {
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
  }

  // Изменение состояния задания
  if (isset($_GET['set_done'])) {
    $task_id = $_GET['set_done'];
    $sql = 'SELECT `complete_date` FROM tasks WHERE `user_id` = ? AND `id` = ?';
    $res = mysqli_prepare($con, $sql);
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $task_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
      $task_compl_date = mysqli_fetch_all($res, MYSQLI_ASSOC);
      foreach ($task_compl_date as $key => $compl_date) {
        if ($compl_date['complete_date'] !== NULL) {
          $sql = 'UPDATE `tasks` SET `complete_date` = NULL WHERE `id` = ? AND `user_id` = ?';
          $stmt = db_get_prepare_stmt($con, $sql, [$task_id, $user_id]);
          $res = mysqli_stmt_execute($stmt);

          if ($res) {
            header('Location:' . $_SERVER["HTTP_REFERER"]);
          } else {
            $error = mysqli_error($con);
            $page_content = include_template('templates/error.php', ['error' => $error]);
          }

        } else {
          $sql = 'UPDATE `tasks` SET `complete_date` = NOW() WHERE `id` = ? AND `user_id` = ?';
          $res = mysqli_prepare($con, $sql);
          $stmt = db_get_prepare_stmt($con, $sql, [$task_id, $user_id]);
          $res = mysqli_stmt_execute($stmt);

          if ($res) {
            header('Location:' . $_SERVER["HTTP_REFERER"]);
          } else {
            $error = mysqli_error($con);
            $page_content = include_template('templates/error.php', ['error' => $error]);
          }
        }
      }
    } else {
      $error = mysqli_error($con);
      $page_content = include_template('templates/error.php', ['error' => $error]);
    }
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
    'p_add' => $p_add,
    'auth_form' => $auth_form,
    'username' => $username,
    'register_form' => $register_form,
    'error' => $error,
    'active_session' => $active_session
  ]);

  print($layout_content);
?>
