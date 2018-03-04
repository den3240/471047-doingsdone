<?php
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

// Фильтрация заданий по проектам
if (isset($_GET['category_id'])) {
  foreach ($categories as $key => $category) {
    if (!isset($category['id'])) {
      http_response_code(404);
      $page_content = include_template('templates/error.php', ['error_text' => "404"]);
    } else {
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
