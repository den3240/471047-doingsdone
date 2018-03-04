<?php
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
