<main>
    <nav class="nav">
      <ul class="nav__list container top">
        <?php foreach ($categories as $category): ?>
          <li class="nav__item">
            <a href="all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
          </li>
        <?php endforeach; ?>    
      </ul>
    </nav>
    <section class="lot-item container">
      <?php foreach ($lots as $key => $value): ?>
        <h2><?= htmlspecialchars($value['title']); ?></h2>
        <div class="lot-item__content">
          <div class="lot-item__left">
            <div class="lot-item__image">
              <img src="<?= htmlspecialchars($value['path']); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($value['category']); ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($value['description']); ?></p>
          </div>
          <div class="lot-item__right">
            <div class="lot-item__state">
              <div class="lot-item__timer timer <? [$hours, $minutes] = get_dt_range($value['date_finish']); if ($hours == 0) : ?> timer--finishing <? endif;?>">
                <? 
                  echo "{$hours}:{$minutes}";
                ?>
              </div>
              <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                  <span class="lot-item__amount">Текущая цена</span>
                  <span class="lot-item__cost">                     
                    <?php
                      if ($value['lot_id'] == NULL) {
                        echo htmlspecialchars(get_sum($value['cost']));
                      } else {
                        echo htmlspecialchars(get_sum($value['current_price'])); 
                      }                      
                    ?>
                  </span>
                </div>
                <div class="lot-item__min-cost">
                  Мин. ставка 
                  <span>                    
                    <?php
                      if ($value['lot_id'] == NULL) {
                        echo (htmlspecialchars(get_sum($value['cost'] + $value['rate_step'])));
                      } else {
                        echo (htmlspecialchars(get_sum($value['current_price'] + $value['rate_step']))); 
                      }                      
                    ?> 
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>   
    </section>
  </main>