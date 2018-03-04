<?php
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
