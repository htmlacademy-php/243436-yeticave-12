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
            <?php [$hours, $minutes] = get_dt_range($lot[0]['date_finish']); 
            if ($is_auth && ($hours > 0 || $minutes > 0) && empty($errors_user) && $rate_user_id != $_SESSION['id'])  : ?>
              <div class="lot-item__state">
                <div class="lot-item__timer timer <? if ($hours == 0) : ?> timer--finishing <? endif;?>">
                  <?
                    if($hours <= 0 && $minutes <= 0) {
                      $hours = 0;
                      $minutes = 0;
                      
                      [$hours, $minutes] = [str_pad($hours, 2, "0", STR_PAD_LEFT), str_pad($minutes, 2, "0", STR_PAD_LEFT)];
                      echo "{$hours}:{$minutes}";
                    } else {                   
                      echo "{$hours}:{$minutes}";
                    }
                    
                  ?>
                </div>
                <div class="lot-item__cost-state">
                  <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?= htmlspecialchars(get_sum($lot[0]['current_price'])); ?></span>
                  </div>
                  <div class="lot-item__min-cost">
                    Мин. ставка 
                    <span><?= htmlspecialchars(get_sum($lot[0]['current_price'] + $lot[0]['rate_step'])); ?></span>
                  </div>
                </div>
                  <form class="lot-item__form" action="lot.php?id=<?= $id; ?>" method="post" autocomplete="off">
                  <p class="lot-item__form-item form__item <?= $errors['cost'] ? $errors['cost'] : ''; ?>">
                    <label for="cost">Ваша ставка</label>
                    <input id="cost" type="text" name="cost" placeholder="12 000" value="<?= htmlspecialchars($cost); ?>">
                    <span class="form__error">Введите ставку лота</span>
                  </p>
                  <button type="submit" class="button">Сделать ставку</button>
                </form>
              </div>
            <?php endif; ?> 
              <div class="history">
                <h3>История ставок (<span><?= $rate_count; ?></span>)</h3>
                <table class="history__list">
                  <?php foreach($rates as $rate) : ?>
                    <tr class="history__item">
                      <td class="history__name"><?= htmlspecialchars($rate['name']); ?></td>
                      <td class="history__price"><?= htmlspecialchars(get_rate($rate['cost'])); ?></td>
                      <td class="history__time"><?= get_time_rate(htmlspecialchars($rate['date'])); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>      
          </div>
        </div>       
    </section>
  </main>