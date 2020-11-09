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
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($rates as $value): ?>
          <tr class="rates__item <?php [$hours, $minutes, $second] = get_dt_range($value['lot_date_finish']); if($value['winner_id'] == $user_id) {echo 'rates__item--win';} elseif ($hours <= 0 && $minutes <= 0 && $second <= 0) { echo 'rates__item--end';}?>">
            <td class="rates__info">
              <div class="rates__img">
                <img src="<?= htmlspecialchars($value['img_path']); ?>" width="54" height="40" alt="Сноуборд">
              </div>
              <div>
                <h3 class="rates__title"><a href="lot.php?id=<?= $value['lot_id']; ?>"><?= htmlspecialchars($value['lot_name']); ?></a></h3>
                <?php if ($value['winner_id'] == $user_id): ?>
                  <p><?= htmlspecialchars($value['contact']); ?></p>
                <?php endif; ?>
              </div>
            </td>
            <td class="rates__category">
              <?= htmlspecialchars($value['category_name']); ?>
            </td>
            <td class="rates__timer">
                <?php if ($value['winner_id'] == $user_id): ?>
                  <div class="timer timer--win">Ставка выиграла</div>
                <?php elseif ($hours <= 0 && $minutes <= 0 && $second <= 0): ?>
                  <div class="timer timer--end">Торги окончены</div> 
                <?php else: ?>
                  <div class="timer <? if ($hours == 0) : ?> timer--finishing <? endif;?>">
                  <? 
                    echo "{$hours}:{$minutes}:{$second}";
                  ?>
                <?php endif; ?>
              </div>
            </td>
            <td class="rates__price">
              <?= htmlspecialchars(get_sum($value['rate_cost'])); ?>
            </td>
            <td class="rates__time">
              <?= get_time_rate(htmlspecialchars($value['rate_date'])); ?>
            </td>
          </tr>
        <?php endforeach; ?>   
      </table>
    </section>
  </main>