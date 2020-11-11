<?php
  $connect = db_connect();
  require_once 'vendor/autoload.php';

  $transport = (new Swift_SmtpTransport('phpdemo.ru', 25));
  $transport->setUsername("keks@phpdemo.ru");
  $transport->setPassword("htmlacademy");


  if(isset($_SESSION['auth'])) {
    $sql_winner = "SELECT rate.user_id AS rate_user_id, rate.lot_id AS lot_id, MAX(rate.cost) AS rate_cost, lot.date_finish AS lot_date_finish, lot.winner_id AS winner_id, user.email AS user_email, user.name AS user_name, lot.title AS lot_name 
    FROM rate
      JOIN lot ON lot.id = rate.lot_id
      JOIN user ON user.id = rate.user_id  
      WHERE rate.user_id = ".$_SESSION['id']." AND lot.winner_id IS NULL AND lot.date_finish < NOW()
      GROUP BY rate.lot_id";

    $result_winner = mysqli_query($connect, $sql_winner);

    if(!$result_winner) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
    }

    $winners = mysqli_fetch_all($result_winner, MYSQLI_ASSOC);

    $recipients = [];

    foreach($winners as $winner) {
      $recipients[$winner['user_email']] = $winner['user_name'];
    }

    $message = (new Swift_Message('Ваша ставка победила'));
    $message->setFrom(['keks@phpdemo.ru' => 'keks@phpdemo.ru']);
    $message->setTo($recipients);

    $lots_finish = get_lots_finish($connect, $_SESSION['id']);

    $message_content = include_template('email.php', ['winners' => $winners, 'lots_finish' => $lots_finish]);
    $message->setBody($message_content, 'text/html');

    $mailer = new Swift_Mailer($transport);

    $lots_finish = get_lots_finish($connect, $_SESSION['id']);

    foreach($lots_finish as $lot_finish) {
      $user = $lot_finish['user_id'];
      $lot = $lot_finish['lot_id'];


      if($lot_finish['winner_id'] == NULL) {
        $mailer->send($message);
      }

    }
  }
?>