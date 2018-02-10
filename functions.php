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


?>
