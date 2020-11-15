<?php
  $connect = db_connect();
  require_once 'vendor/autoload.php';



  $transport = (new Swift_SmtpTransport('phpdemo.ru', 25));
  $transport->setUsername("keks@phpdemo.ru");
  $transport->setPassword("htmlacademy");


  $sql_rate_winner = "SELECT  rate.lot_id AS lot_id, rate.cost AS rate_cost, rate.user_id AS user_id, lot.winner_id AS winner_id, lot.title AS lot_name, user.name AS user_name, user.email AS user_email
    FROM rate
      JOIN lot ON lot.id = rate.lot_id
      JOIN user ON user.id = rate.user_id    
      JOIN 
        (
        SELECT  lot_id AS lot_id_rate, MAX(cost) AS max_cost_rate 
        FROM   rate
        GROUP BY lot_id 
        ) rate_new ON rate.lot_id = rate_new.lot_id_rate AND rate.cost = rate_new.max_cost_rate
        WHERE lot.winner_id IS NULL AND lot.date_finish < NOW()";

  $result_rate_winner = mysqli_query($connect, $sql_rate_winner);

  if(!$result_rate_winner) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: '.$error;
  }

  $winners = mysqli_fetch_all($result_rate_winner, MYSQLI_ASSOC);

  $recipients = [];

  foreach($winners as $winner) {
    $recipients[$winner['user_email']] = $winner['user_name'];
  }

  $message = (new Swift_Message('Ваша ставка победила'));
  $message->setFrom(['keks@phpdemo.ru' => 'keks@phpdemo.ru']);
  $message->setTo($recipients);

  $message_content = include_template('email.php', ['winners' => $winners]);
  $message->setBody($message_content, 'text/html');

  $mailer = new Swift_Mailer($transport);

  foreach($winners as $winner) {
    if($winner['winner_id'] == NULL) {

      $mailer->send($message);

      $user = $winner['user_id'];
      $lot = $winner['lot_id'];

      $sql_winner = "UPDATE lot SET winner_id = $user WHERE id = $lot";

      $result_winner = mysqli_query($connect, $sql_winner);

      if(!$result_winner) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
      }
    }
  }
?>