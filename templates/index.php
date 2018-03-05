<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.html" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="index.php?t_filter=all<?= isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : '' ?>" class="tasks-switch__item <?php if ((isset($_GET['t_filter']) && $_GET['t_filter'] == 'all') || !isset($_GET['t_filter'])) : ?>tasks-switch__item--active<?php endif; ?>">Все задачи</a>
        <a href="index.php?t_filter=today<?= isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : '' ?>" class="tasks-switch__item <?php if (isset($_GET['t_filter']) && $_GET['t_filter'] == 'today') : ?>tasks-switch__item--active<?php endif; ?>">Повестка дня</a>
        <a href="index.php?t_filter=tomorrow<?= isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : '' ?>" class="tasks-switch__item <?php if (isset($_GET['t_filter']) && $_GET['t_filter'] == 'tomorrow') : ?>tasks-switch__item--active<?php endif; ?>">Завтра</a>
        <a href="index.php?t_filter=overdue<?= isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : '' ?>" class="tasks-switch__item <?php if (isset($_GET['t_filter']) && $_GET['t_filter'] == 'overdue') : ?>tasks-switch__item--active<?php endif; ?>">Просроченные</a>
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
    <?php if(($show_complete_tasks == 1 && !empty($task['complete_date'])) || empty($task['complete_date'])): ?>
    <tr class="tasks__item task <?php if (!empty($task['complete_date'])) :?>task--completed <?php elseif(date_check($task['deadline'])) :?>task--important<?php endif; ?>">
        <td class="task__select">
          <label class="checkbox task__checkbox">
            <a href="index.php?set_done=<?=$task['id'];?><?= isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : '' ?>">
              <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($task['complete_date'] !== NULL) :?>checked<?php endif; ?>>
              <span class="checkbox__text"><?= htmlspecialchars($task['name']); ?></span>
            </a>
          </label>
        </td>

        <td class="task__file">
            <?php if (!empty($task['file'])) : ?><a class="download-link" href="<?=$task['file'];?>"></a><?php endif; ?>
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
