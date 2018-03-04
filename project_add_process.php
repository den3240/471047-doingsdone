<?php

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
