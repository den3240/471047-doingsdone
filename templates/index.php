<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.html" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="index.php?t_filter=all" class="tasks-switch__item <?php if ($_GET['t_filter'] == 'all' || !isset($_GET['t_filter'])) : ?>tasks-switch__item--active<? endif; ?>">Все задачи</a>
        <a href="index.php?t_filter=today" class="tasks-switch__item <?php if ($_GET['t_filter'] == 'today') : ?>tasks-switch__item--active<? endif; ?>">Повестка дня</a>
        <a href="index.php?t_filter=tomorrow" class="tasks-switch__item <?php if ($_GET['t_filter'] == 'tomorrow') : ?>tasks-switch__item--active<? endif; ?>">Завтра</a>
        <a href="index.php?t_filter=overdue" class="tasks-switch__item <?php if ($_GET['t_filter'] == 'overdue') : ?>tasks-switch__item--active<? endif; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <a href="?show_completed">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden" type="checkbox" <?php if($show_complete_tasks == 1): ?>checked<?php endif; ?> >
            <span class="checkbox__text">Показывать выполненные</span>
        </a>
    </label>
</div>

<table class="tasks">
  <?php foreach ($task_list as $key => $task): ?>
    <?php if(($show_complete_tasks == 1 && $task['complete_date'] !== NULL) || $task['complete_date'] === NULL): ?>
    <tr class="tasks__item task <?php if ($task['status'] === 'Да') :?>task--completed <?php elseif(date_check($task['deadline'])) :?>task--important<?php endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($task['complete_date'] !== NULL) :?>checked<?php endif; ?>>
                <span class="checkbox__text"><?= htmlspecialchars($task['name']); ?></span>
            </label>
        </td>

        <td class="task__file">
            <?php if (isset($path)) : ?><a class="download-link" href="<?=$path;?>"></a><?php endif; ?>
        </td>

        <td class="task__date">
          <?php
            if(!$task['deadline']) {
              echo "Нет";
            }else{
              echo htmlspecialchars(date('d.m.y', strtotime($task['deadline'])));
            }
          ?>
        </td>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>
