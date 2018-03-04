<div class="modal">
  <a href="index.php"><button class="modal__close" type="button" name="button">Закрыть</button></a>

  <h2 class="modal__heading">Добавление проекта</h2>

  <form class="form"  action="index.php" method="post">
    <div class="form__row">
      <?php $classname = isset($errors['name']) ? "form__input--error" : "";
            $value = isset($project['name']) ? $project['name'] : ""; ?>
      <label class="form__label" for="project_name">Название <sup>*</sup></label>

      <input class="form__input <?=$classname;?>" type="text" name="name" id="project_name" value="<?=$value;?>" placeholder="Введите название проекта">
      <?php if (isset($errors['name'])): ?>
        <p class="form__message"><?=$errors['name']?></p>
      <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="proj_add_btn" value="Добавить">
    </div>
  </form>
</div>
