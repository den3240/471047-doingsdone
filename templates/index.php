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
        <a href="?show_completed">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden" type="checkbox" <?php if($show_complete_tasks == 1): ?>checked<?php endif; ?> >
            <span class="checkbox__text">Показывать выполненные</span>
        </a>
    </label>
</div>

<table class="tasks">
  <?php foreach ($task_list as $key => $task): ?>
    <?php if(($show_complete_tasks == 1 && $task['status'] === 'Да') || $task['status'] === "Нет"): ?>
    <tr class="tasks__item task <?php if ($task['status'] === 'Да') :?>task--completed <?php elseif(date_check($task['date'])) :?>task--important<?php endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($task['status'] === 'Да') :?>checked<?php endif; ?>>
                <span class="checkbox__text"><?= htmlspecialchars($task['title']); ?></span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#"></a>
        </td>

        <td class="task__date">
          <?php
            if(!$task['date']) {
              echo "Нет";
            }else{
              echo htmlspecialchars($task['date']);
            }
          ?>
        </td>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>
