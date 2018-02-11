<?php

function include_template($path, $data) {
  if (!file_exists($path)) {
    return '';
  }
  extract($data);
  ob_start();
  require_once $path;

  return ob_get_clean();
}



function task_counting ($task_list = [], $project_name = "Все") {
  if ($project_name == "Все"){
    return count($task_list);
  }
  $project_count = 0;
  foreach ($task_list as $key => $value) {
    if ($value['category'] === $project_name) {
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


?>
