<main>
    <nav class="nav">
        <ul class="nav__list container">
        <?php if($categories === null) : ?>
            <?= ''; ?>
        <?php else :?>
            <?php foreach ($categories as $category): ?>
                <li class="nav__item
          <?php if ((int)$category['id'] == (int)$_GET['category_id']) {
                    echo 'nav__item--current';
                } ?>">
                    <a href="all-lots.php?category_id=<?= (int)$category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?php foreach ($categories as $category) {
                        if ((int)$category['id'] === (int)$_GET['category_id']) {
                            echo $category['name'];
                        }
                    } ?>»</span></h2>
            <?php if ($lots !== null) : ?>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="../<?= $lot['path']; ?>" width="350" height="260" alt="Сноуборд">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= $lot['category']; ?></span>
                                <h3 class="lot__title"><a class="text-link"
                                                          href="lot.php?id=<?= $lot['id']; ?>"><?= htmlspecialchars($lot['title']); ?></a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                      <span class="lot__amount"><?php if ((int)$lot['count_rate'] === 0) {
                              echo 'Стартовая цена';
                          } else {
                              echo (int)$lot['count_rate'] . ' ' . get_noun_plural_form((int)$lot['count_rate'],
                                      'ставка', 'ставки', 'ставок');
                          } ?></span>
                                        <span
                                            class="lot__cost"><?= htmlspecialchars(get_sum((int)$lot['current_price'])); ?></span>
                                    </div>
                                    <div class="lot__timer timer <?php [
                                        $hours,
                                        $minutes
                                    ] = get_dt_range($lot['date_finish']);
                                    if ((int)$hours === 0) {
                                        echo 'timer--finishing';
                                    } ?>">
                                        <?php
                                        if ((int)$hours <= 0 && (int)$minutes <= 0) {
                                            $hours = 0;
                                            $minutes = 0;

                                            [$hours, $minutes] = [
                                                str_pad($hours, 2, '0', STR_PAD_LEFT),
                                                str_pad($minutes, 2, '0', STR_PAD_LEFT)
                                            ];
                                            echo "{$hours}:{$minutes}";
                                        } else {
                                            echo "{$hours}:{$minutes}";
                                        }

                                        ?>
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
                <li class="pagination-item pagination-item-prev"><a
                        href="/all-lots.php?category_id=<?= (int)$category_id; ?>&page=<?php if ((int)$cur_page > 1) {
                            echo $back_page = (int)$cur_page - 1;
                        } else {
                            echo $back_page = 1;
                        } ?>">Назад</a></li>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?php if ((int)$page === (int)$cur_page): ?>pagination-item-active<?php endif; ?>">
                        <a href="/all-lots.php?category_id=<?= (int)$category_id; ?>&page=<?= (int)$page; ?>"><?= (int)$page; ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next"><a
                        href="/all-lots.php?category_id=<?= (int)$category_id; ?>&page=<?php if ((int)$cur_page < (int)$pages_count) {
                            echo ++$cur_page;
                        } else {
                            echo $cur_page = $pages_count;
                        } ?>">Вперед</a></li>
            </ul>
        <?php endif; ?>
    </div>
</main>
