<main>
    <nav class="nav">
        <ul class="nav__list container">
        <?php if ($categories !== null) : ?>
            <?php foreach ($categories as $category) : ?>
            <li class="nav__item <?= (int)$category['id'] === (int)$_GET['category_id'] ?  'nav__item--current' : ''?>">
                <a href="all-lots.php?category_id=<?= (int)$category['id']; ?>">
                    <?= htmlspecialchars($category['name']); ?>
                </a>
            </li>
            <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории 
            <span>
                <?php foreach ($categories as $category) : ?>
                    <?= (int)$category['id'] === (int)$_GET['category_id'] ? '«'.$category['name'].'»' : '' ?>
                <?php endforeach; ?>
            </span>
            </h2>
            <?php if ($lots !== null) : ?>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="../<?= $lot['path']; ?>" width="350" height="260" alt="Сноуборд">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= $lot['category']; ?></span>
                                <h3 class="lot__title">
                                    <a class="text-link" href="lot.php?id=<?= $lot['id']; ?>">
                                        <?= htmlspecialchars($lot['title']); ?>
                                    </a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">
                                            <?php if ((int)$lot['count_rate'] === 0) : ?>
                                                Стартовая цена
                                            <?php else : ?>
                                                <?= (int)$lot['count_rate'] . ' ' . get_noun_plural_form(
                                                    (int)$lot['count_rate'],
                                                    'ставка',
                                                    'ставки',
                                                    'ставок'
                                                ); ?>
                                            <?php endif; ?>
                                        </span>
                                        <span class="lot__cost">
                                            <?= htmlspecialchars(get_sum((int)$lot['current_price'])); ?>
                                        </span>
                                    </div>
                                    <?php [$hours, $minutes] = get_dt_range($lot['date_finish']); ?>
                                    <div class="lot__timer timer <?= (int)$hours === 0 ? 'timer--finishing' : '' ?>">
                                        <?= "{$hours}:{$minutes}"; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>В данной категории лоты отсутствуют</p>
            <?php endif; ?>
        </section>
        <?php if ((int)$pages_count > 1) : ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <a href="/all-lots.php?category_id=<?= (int)$category_id; ?>&page=
                    <?= (int)$cur_page > 1 ? $back_page = (int)$cur_page - 1 : $back_page = 1 ?>">
                        Назад
                    </a>
                </li>
                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item <?= (int)$page === (int)$cur_page ? 'pagination-item-active' : '' ?>">
                        <a href="/all-lots.php?category_id=<?= (int)$category_id; ?>&page=<?= (int)$page; ?>">
                            <?= (int)$page; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <a href="/all-lots.php?category_id=<?= (int)$category_id; ?>&page=
                    <?= (int)$cur_page < (int)$pages_count ? ++$cur_page : $cur_page = $pages_count ?>">
                        Вперед
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</main>
