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
    <section class="rates container">
        <h2>Мои ставки</h2>
        <?php if ($rates === null) : ?>
        <p>Ставок нет</p>
        <?php else : ?>
        <table class="rates__list">
            <?php foreach ($rates as $value) : ?>
                <?php [$hours, $minutes, $second] = get_dt_range($value['lot_date_finish']); ?>
                <tr class="rates__item 
                <?php if ((int)$value['winner_id'] === (int)$user_id) : ?>
                    rates__item--win
                <?php elseif ((int)$hours <= 0 && (int)$minutes <= 0 && (int)$second <= 0) : ?> 
                    rates__item--end
                <?php endif; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= htmlspecialchars($value['img_path']); ?>" width="54" height="40"
                                 alt="Сноуборд">
                        </div>
                        <div>
                            <h3 class="rates__title">
                                <a href="lot.php?id=<?= (int)$value['lot_id']; ?>">
                                    <?= htmlspecialchars($value['lot_name']); ?>
                                </a>
                            </h3>
                            <?php if ((int)$value['winner_id'] === (int)$user_id) : ?>
                                <p><?= htmlspecialchars($value['contact']); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= htmlspecialchars($value['category_name']); ?>
                    </td>
                    <td class="rates__timer">
                        <?php if ((int)$value['winner_id'] === (int)$user_id) : ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php elseif ((int)$hours <= 0 && (int)$minutes <= 0 && (int)$second <= 0) : ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <?php else : ?>
                        <div class="timer <?= (int)$hours === 0 ? 'timer--finishing' : '' ?>">
                            <?= "{$hours}:{$minutes}:{$second}"; ?>
                        </div>
                        <?php endif; ?>
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
        <?php endif; ?>
    </section>
</main>
