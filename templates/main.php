<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?= $category['code']; ?>">
                    <a class="promo__link" href="all-lots.php?category_id=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $key => $value): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($value['path']); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($value['category']); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $value['id']; ?>"><?= htmlspecialchars($value['title']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= htmlspecialchars(get_sum($value['current_price'])); ?></span>
                            </div>
                            <div class="lot__timer timer <? [$hours, $minutes] = get_dt_range($value['date_finish']); if ($hours == 0) : ?> timer--finishing <? endif;?>">
                                <? 
                                  echo "{$hours}:{$minutes}";
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>    
        </ul>
    </section>
    <?php if($pages_count > 1) : ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="/index.php?page=<?php if($cur_page > 1) {echo $back_page = $cur_page - 1;} else {echo $back_page = 1;} ?>">Назад</a></li>
        <?php foreach ($pages as $page): ?>
          <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
            <a href="/index.php?page=<?=$page;?>"><?=$page;?></a>
          </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a href="/index.php?page=<?php if($cur_page < $pages_count) {echo $cur_page += 1;} else {echo $cur_page = $pages_count;} ?>">Вперед</a></li>
      </ul>
    <?php endif; ?>
</main>