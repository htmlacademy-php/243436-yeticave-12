<main>
    <nav class="nav">
        <ul class="nav__list container">
        <?php if ($categories !== null) : ?>
                <?php foreach ($categories as $category) : ?>
                    <li class="nav__item">
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
            <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search); ?></span>»</h2>
            <?php if ($lots === null) : ?>
            <p>Ничего не найдено по вашему запросу</p>
            <?php else : ?>
                <ul class="lots__list">
                    <?php foreach ($lots as $key => $value) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= htmlspecialchars($value['path']); ?>" width="350" height="260"
                                     alt="Сноуборд">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($value['category']); ?></span>
                                <h3 class="lot__title">
                                    <a class="text-link" href="lot.php?id=<?= $value['id']; ?>">
                                        <?= htmlspecialchars($value['title']); ?>
                                    </a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost">
                                            <?= htmlspecialchars(get_sum($value['current_price'])); ?>
                                        </span>
                                    </div>
                                    <?php [$hours, $minutes] = get_dt_range($value['date_finish']); ?>
                                    <div class="lot__timer timer <?= (int)$hours === 0 ? 'timer--finishing' : '' ?>">
                                        <?= "{$hours}:{$minutes}"; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
        <?php if ((int)$pages_count > 1) : ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <a href="/search.php?search=<?= $search; ?>&find=Найти&page=
                    <?= (int)$cur_page > 1 ? ($back_page = (int)$cur_page - 1) : $back_page = 1 ?>">
                        Назад
                    </a>
                </li>
                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item <?= (int)$page === (int)$cur_page ? 'pagination-item-active' : '' ?>">
                        <a href="/search.php?search=<?= $search; ?>&find=Найти&page=<?= (int)$page; ?>">
                            <?= (int)$page; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <a href="/search.php?search=<?= $search; ?>&find=Найти&page=
                        <?= (int)$cur_page < (int)$pages_count ? (int)++$cur_page : (int)$cur_page = (int)$pages_count
                        ?>">
                        Вперед
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</main>
