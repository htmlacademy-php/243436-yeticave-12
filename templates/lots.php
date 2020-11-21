<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
          <li class="nav__item <?php if ((int) $category['id'] === (int) $_GET['category_id']) {
          echo 'nav__item--current';
      } ?>">
            <a href="all-lots.php?category_id=<?= (int) $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
          </li>
        <?php endforeach; ?> 
      </ul>
    </nav>
    <section class="lot-item container">      
        <h2><?= htmlspecialchars($lot['title']); ?></h2>
        <div class="lot-item__content">
          <div class="lot-item__left">
            <div class="lot-item__image">
              <img src="<?= htmlspecialchars($lot['path']); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category']); ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
          </div>
          <div class="lot-item__right">
            <?php [$hours, $minutes] = get_dt_range($lot['date_finish']);
            if ($is_auth && ((int) $hours > 0 || (int) $minutes > 0) && empty($errors_user) && (int) $user_id_last_rate !== (int) $_SESSION['id'])  : ?>
              <div class="lot-item__state">
                <div class="lot-item__timer timer <?php if ((int) $hours === 0) : ?> timer--finishing <?php endif; ?>">
                  <?php
                    if ((int) $hours <= 0 && (int) $minutes <= 0) {
                        $hours = 0;
                        $minutes = 0;

                        [$hours, $minutes] = [str_pad($hours, 2, '0', STR_PAD_LEFT), str_pad($minutes, 2, '0', STR_PAD_LEFT)];
                        echo "{$hours}:{$minutes}";
                    } else {
                        echo "{$hours}:{$minutes}";
                    }

                  ?>
                </div>
                <div class="lot-item__cost-state">
                  <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?=  htmlspecialchars(get_sum($lot['current_price'])); ?></span>
                  </div>
                  <div class="lot-item__min-cost">
                    Мин. ставка 
                    <span><?= htmlspecialchars(get_sum($lot['current_price'] + $lot['rate_step'])); ?></span>
                  </div>
                </div>
                  <form class="lot-item__form" action="lot.php?id=<?= $lot_id; ?>" method="post" autocomplete="off">
                  <p class="lot-item__form-item form__item <?= $errors['cost'] ? 'form__item--invalid' : ''; ?>">
                    <label for="cost">Ваша ставка</label>
                    <input id="cost" type="text" name="cost" placeholder="<?= htmlspecialchars(get_sum($lot['current_price'] + $lot['rate_step'])); ?>" value="<?= htmlspecialchars($cost); ?>">
                    <span class="form__error"><?= $errors['cost'] ?? ''; ?></span>
                  </p>
                  <button type="submit" class="button">Сделать ставку</button>
                </form>
              </div>
            <?php endif; ?> 
              <div class="history">
                <h3>История ставок (<span><?= $rate_count; ?></span>)</h3>
                <?php if($rates !== null) : ?>
                  <table class="history__list">
                    <?php foreach ($rates as $rate) : ?>
                      <tr class="history__item">
                        <td class="history__name"><?= htmlspecialchars($rate['name']); ?></td>
                        <td class="history__price"><?= htmlspecialchars(get_rate($rate['cost'])); ?></td>
                        <td class="history__time"><?= get_time_rate(htmlspecialchars($rate['date'])); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </table>
                <? endif; ?>      
              </div>      
          </div>
        </div>       
    </section>
  </main>