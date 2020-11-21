<main>
  <nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
          <li class="nav__item">
            <a href="all-lots.php?category_id=<?= (int) $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
          </li>
        <?php endforeach; ?> 
    </ul>
  </nav>
  <form class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
      <div class="form__item <?= $errors['lot-name'] ? 'form__item--invalid' : ''; ?>">
        <label for="lot-name">Наименование <sup>*</sup></label>
        <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= htmlspecialchars($lot_name); ?>">
        <span class="form__error"><?= $errors['lot-name'] ?? ''; ?></span>
      </div>
      <div class="form__item <?= $errors['category'] ? 'form__item--invalid' : ''; ?>">
        <label for="category">Категория <sup>*</sup></label>
        <select id="category" name="category">
          <option>Выберите категорию</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?= (int) $category['id']; ?>" <?= (int) $category['id'] === (int) $select_category ? 'selected' : ''; ?> ><?= htmlspecialchars($category['name']); ?></option>
          <?php endforeach; ?>  
        </select>
        <span class="form__error">Выберите категорию</span>
      </div>
    </div>
    <div class="form__item form__item--wide <?= $errors['message'] ? 'form__item--invalid' : ''; ?>">
      <label for="message">Описание <sup>*</sup></label>
      <textarea id="message" name="message" placeholder="Напишите описание лота"><?= htmlspecialchars($message); ?></textarea>
      <span class="form__error">Напишите описание лота</span>
    </div>
    <div class="form__item form__item--file <?= $errors['lot-img'] ? 'form__item--invalid' : ''; ?>">
      <label for="lot-img">Изображение<sup>*</sup></label>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
        <label for="lot-img"">
          Добавить
        </label>
      </div>
      <span class="form__error">Загрузите допустимый формат файла: jpg, jpeg, png</span>
    </div>
    <div class="form__container-three">
      <div class="form__item form__item--small  <?= $errors['lot-rate'] || validate_price('lot-rate') === 'form__item--invalid' ? 'form__item--invalid' : ''; ?>">
        <label for="lot-rate">Начальная цена <sup>*</sup></label>
        <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= htmlspecialchars($lot_rate); ?>">
        <span class="form__error"><?= $errors['lot-rate'] ?? ''; ?></span>
      </div>
      <div class="form__item form__item--small <?= $errors['lot-step'] || validate_price('lot-step') === 'form__item--invalid' ? 'form__item--invalid' : ''; ?>">
        <label for="lot-step">Шаг ставки <sup>*</sup></label>
        <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= htmlspecialchars($lot_step); ?>">
        <span class="form__error"><?= $errors['lot-step'] ?? ''; ?></span>
      </div>
      <div class="form__item <?= $errors['lot-date'] ? 'form__item--invalid' : ''; ?>">
        <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
        <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= htmlspecialchars($lot_date); ?>">
        <span class="form__error">Введите дату завершения торгов</span>
      </div>
    </div>
    <span class="form__error form__error--bottom">
        Пожалуйста, исправьте ошибки в форме.
    </span>
    <button type="submit" class="button">Добавить лот</button>
  </form>
</main>
