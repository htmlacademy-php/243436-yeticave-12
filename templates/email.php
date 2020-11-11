<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $_SESSION['name'] ?></p>
<?php foreach($winners as $winner) : ?>
  <p>Ваша ставка для лота <a href="http://243436-yeticave-12/lot.php?id=<?= $winner['lot_id']; ?>"><?= htmlspecialchars($winner['lot_name']); ?></a> победила.</p>
<?php endforeach; ?>
  <p>Перейдите по ссылке <a href="http://243436-yeticave-12/my-bets.php">мои ставки</a>,
  чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
