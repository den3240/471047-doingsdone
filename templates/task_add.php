<div class="modal">
  <a href="index.php"><button class="modal__close" type="button" name="button">Закрыть</button></a>

  <h2 class="modal__heading">Добавление задачи</h2>

  <form class="form"  action="index.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <?php $classname = isset($errors['name']) ? "form__input--error" : "";
            $value = isset($task['name']) ? $task['name'] : ""; ?>
      <label class="form__label" for="name">Название <sup>*</sup></label>

      <input class="form__input <?=$classname;?>" type="text" name="name" id="name" value="<?=$value;?>" placeholder="Введите название">
      <?php if (isset($errors['name'])): ?>
        <p class="form__message">Заполните это поле</p>
      <?php endif; ?>
    </div>

    <div class="form__row">
      <?php $classname = isset($errors['project']) ? "form__input--error" : "";
          $value = isset($task['project']) ? $task['project'] : ""; ?>
      <label class="form__label" for="project">Проект <sup>*</sup></label>
      <?php
        $add_index = 1;
        $category_count = count($categories);
      ?>
      <select class="form__input form__input--select" name="project" id="project">
        <option value="">Выбрать проект</option>
        <?php while($add_index < $category_count): ?>
        <option
          value="<?= $categories[$add_index]; ?>"
          <?= $categories[$add_index] == $task_category ? "selected" : '' ?>>
            <?= $categories[$add_index]; ?>
        </option>
        <?php $add_index++; ?>
        <?php endwhile; ?>
      </select>
      <?php if (isset($errors['project'])): ?>
        <p class="form__message">Заполните это поле</p>
      <?php endif; ?>
    </div>

    <div class="form__row">
      <?php $classname = isset($errors['date']) ? "form__input--error" : "";
          $value = isset($task['date']) ? $task['date'] : ""; ?>
      <label class="form__label" for="date">Дата выполнения</label>

      <input class="form__input form__input--date <?=$classname;?>" type="date" name="date" id="date" value="<?=$value;?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
      <?php if (isset($errors['date'])): ?>
        <p class="form__message">Заполните это поле</p>
      <?php endif; ?>
    </div>

    <div class="form__row">
        <?php $value = isset($task['preview']) ? $task['preview'] : ""; ?>
      <label class="form__label" for="preview">Файл</label>

      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="preview" id="preview" value="<?=$value;?>">

        <label class="button button--transparent" for="preview">
            <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="add_btn" value="Добавить">
    </div>
  </form>
</div>
