<?php
$t_filter = $_GET['t_filter'];
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
