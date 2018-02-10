<?php

  require_once 'functions.php';

  $show_complete_tasks = rand(0, 1);

  $categories = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

  $task_list = [
      [
          'title' => 'Собеседование в IT компании',
          'date' => '12.02.2018',
          'category' => $categories[3],
          'status' => 'Нет'
      ],
      [
          'title' => 'Выполнить тестовое задание',
          'date' => '25.06.2018',
          'category' => $categories[3],
          'status' => 'Нет'
      ],
      [
          'title' => 'Сделать задание первого раздела',
          'date' => '03.02.2018',
          'category' => $categories[2],
          'status' => 'Да'
      ],
      [
          'title' => 'Встреча с другом',
          'date' => '10.02.2018',
          'category' => $categories[1],
          'status' => 'Нет'
      ],
      [
          'title' => 'Купить корм для кота',
          'date' => false,
          'category' => $categories[4],
          'status' => 'Нет'
      ],
      [
          'title' => 'Заказать пиццу',
          'date' => false,
          'category' => $categories[4],
          'status' => 'Нет'
      ]
  ];
  

  $page_content = include_template('templates/index.php', ['categories' => $categories, 'task_list' => $task_list, 'show_complete_tasks' => $show_complete_tasks]);

  $layout_content = include_template('templates/layout.php', [
  	'content' => $page_content,
  	'categories' => $categories,
  	'title' => 'Дела в Порядке',
    'task_list' => $task_list
  ]);

  print($layout_content);

?>
