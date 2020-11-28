<main>
    <nav class="nav">
        <ul class="nav__list container">
        <?php if($categories === null) : ?>
            <?= ''; ?>
        <?php else :?>
            <?php foreach ($categories as $category): ?>
                <li class="nav__item <?php if (isset($_GET['category_id']) && ((int)$category['id'] === (int)$_GET['category_id'])) {
                    echo 'nav__item--current';
                } ?>">
                    <a href="all-lots.php?category_id=<?= (int)$category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </nav>
    <form class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="user-login.php" method="post">
        <h2>Вход</h2>
        <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : ''; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail"
                   value="<?= htmlspecialchars($email); ?>">
            <span class="form__error"><?= $errors['email'] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--last <?= isset($errors['password']) ? 'form__item--invalid' : ''; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль"
                   value="<?= htmlspecialchars($password); ?>">
            <span class="form__error"><?= $errors['password'] ?? ''; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
