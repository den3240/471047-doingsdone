<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.html" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <a href="/">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden" type="checkbox" <?php if($show_complete_tasks == 1): ?>checked<?php endif; ?> >
            <span class="checkbox__text">Показывать выполненные</span>
        </a>
    </label>
</div>

<?php

$task_list = [
    [
        'title' => 'Собеседование в IT компании',
        'date' => '01.06.2018',
        'category' => $categories[3],
        'status' => 'Нет'
    ],
    [
        'title' => 'Выполнить тестовое задание',
        'date' => '25.05.2018',
        'category' => $categories[3],
        'status' => 'Нет'
    ],
    [
        'title' => 'Сделать задание первого раздела',
        'date' => '21.04.2018',
        'category' => $categories[2],
        'status' => 'Да'
    ],
    [
        'title' => 'Встреча с другом',
        'date' => '22.04.2018',
        'category' => $categories[1],
        'status' => 'Нет'
    ],
    [
        'title' => 'Купить корм для кота',
        'date' => 'Нет',
        'category' => $categories[4],
        'status' => 'Нет'
    ],
    [
        'title' => 'Заказать пиццу',
        'date' => 'Нет',
        'category' => $categories[4],
        'status' => 'Нет'
    ]
];

?>

<table class="tasks">
  <?php foreach ($task_list as $key => $val): ?>
    <tr class="tasks__item task <?php if ($val['status'] === 'Да') :?>task--completed<?php endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox" checked>
                <a href="/"><span class="checkbox__text"><?=$val['title']; ?></span></a>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#"></a>
        </td>

        <td class="task__date"><?=$val['date']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>
