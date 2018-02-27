<?php

function include_template($path, $data) {
  if (!file_exists($path)) {
    return '';
  }
  extract($data);
  ob_start();
  require $path;

  return ob_get_clean();
}



function task_counting ($task_list = [], $project_id) {
  if ($project_name == "Все"){
    return count($task_list);
  }
  $project_count = 0;
  foreach ($task_list as $key => $value) {
    if ($value['project_id'] == $project_id) {
      $project_count++;
    }
  }
  return $project_count;
}

function date_check($date) {
  date_default_timezone_set("Europe/Kiev");
  $end_date = strtotime($date);
  $days_left = floor(($end_date - strtotime("now")) / 86400);
  if($days_left <= 1 && $date) {
    return true;
  }else{
    return false;
  }
}

function searchUserByEmail($user_email, $users) {
  foreach ($users as $key => $value) {
    if ($user_email == $value['email']) {
      $true_user_mail = $value['email'];
      $true_user_pass = $value['password'];
      $user_name = $value['name'];
      $user_id = $value['id'];
      return $userdata = ['email' => $true_user_mail, 'password' => $true_user_pass, 'name' => $user_name, 'id' => $user_id];
    }
  }
  return $userdata = [];
}

?>
