<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');


  $connect = db_connect();


  $title = 'Мои ставки';


  $user_name = '';


  $is_auth = false;

  if(isset($_SESSION['name']) && isset($_SESSION['auth'])) {
      $user_name = $_SESSION['name'];
      $is_auth = $_SESSION['auth'];
      $user_id = $_SESSION['id'];
  } 


  $categories = get_categories($connect);


  $sql_rate = "SELECT rate.user_id AS user_id, rate.lot_id AS lot_id, rate.cost AS rate_cost, rate.date AS rate_date, lot.path AS img_path, lot.title AS lot_name, lot.date_finish AS lot_date_finish, category.name AS category_name, lot.winner_id AS winner_id, user.contact AS contact
    FROM rate
      JOIN lot ON lot.id = rate.lot_id
      JOIN user ON user.id = rate.user_id
      JOIN category ON category.id = lot.category_id
      WHERE rate.user_id = $user_id 
      ORDER BY rate.date DESC";

  $result_rate = mysqli_query($connect, $sql_rate);

  if(!$result_rate) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: '.$error;
  }

  $rates = mysqli_fetch_all($result_rate, MYSQLI_ASSOC);


  $lots_finish = get_lots_finish($connect, $user_id);

  foreach($lots_finish as $lot_finish) {
    $user = $lot_finish['user_id'];
    $lot = $lot_finish['lot_id'];


    if($lot_finish['winner_id'] == NULL) {

      $sql_winner = "UPDATE lot SET winner_id = $user WHERE id = $lot";

      $result_winner = mysqli_query($connect, $sql_winner);

      if(!$result_winner) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
      }
    }
  }  

  $page_content = include_template('myrates.php', ['categories' => $categories, 'is_auth' => $is_auth, 'rates' => $rates, 'user_id' => $user_id]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

  echo $layout_content;
  
?>