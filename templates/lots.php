<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
          <li class="nav__item">
            <a href="all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
          </li>
        <?php endforeach; ?>    
      </ul>
    </nav>
    <section class="lot-item container">      
        <h2><?= htmlspecialchars($lot[0]['title']); ?></h2>
        <div class="lot-item__content">
          <div class="lot-item__left">
            <div class="lot-item__image">
              <img src="<?= htmlspecialchars($lot[0]['path']); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot[0]['category']); ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot[0]['description']); ?></p>
          </div>
          <div class="lot-item__right">
            <div class="lot-item__state">
              <div class="lot-item__timer timer <? [$hours, $minutes] = get_dt_range($lot[0]['date_finish']); if ($hours == 0) : ?> timer--finishing <? endif;?>">
                <? 
                  echo "{$hours}:{$minutes}";
                ?>
              </div>
              <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                  <span class="lot-item__amount">Текущая цена</span>
                  <span class="lot-item__cost"><?= htmlspecialchars(get_sum($lot[0]['current_price'])); ?></span>
                </div>
                <div class="lot-item__min-cost">
                  Мин. ставка 
                  <span><?= (htmlspecialchars(get_sum($lot[0]['current_price'] + $lot[0]['rate_step'])));?></span>
                </div>
              </div>
            </div>
          </div>
        </div>       
    </section>
  </main>