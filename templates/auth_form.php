<div class="modal">
  <a href="index.php"><button class="modal__close" type="button" name="button">Закрыть</button></a>

  <h2 class="modal__heading">Вход на сайт</h2>

  <form class="form" action="index.php" method="post">
    <div class="form__row">
      <?php $classname = isset($errors['email']) ? "form__input--error" : "";
            $value = isset($user['email']) ? $user['email'] : ""; ?>
      <label class="form__label" for="email">E-mail <sup>*</sup></label>

      <input class="form__input <?=$classname;?>" type="text" name="email" id="email" value="<?=$value;?>" placeholder="Введите e-mail">
      <?php if (isset($errors['email'])): ?>
      <p class="form__message">Такой пользователь не найден</p>
      <?php endif; ?>
    </div>

    <div class="form__row">
      <?php $classname = isset($errors['password']) ? "form__input--error" : "";
            $value = isset($user['password']) ? $user['password'] : ""; ?>
      <label class="form__label" for="password">Пароль <sup>*</sup></label>

      <input class="form__input <?=$classname;?>" type="password" name="password" id="password" value="<?=$value;?>" placeholder="Введите пароль">
      <?php if (isset($errors['password'])): ?>
      <p class="form__message">Вы ввели неверный пароль</p>
      <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="login_btn" value="Войти">
    </div>
  </form>
</div>
