<?php
  require_once('helpers.php');
  require_once('functions.php');

  $is_auth = rand(0, 1);
  
  $user_name = 'Павел';


  $connect = db_connect();



  if (!isset($_GET['id']) || '' == $id = (int)$_GET['id'] ) {
    header("Location: pages/404.html");
  };



  $sql_lots = 'SELECT lot.rate_step, date_finish, description, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
    FROM lot 
        JOIN category ON lot.category_id = category.id
        LEFT JOIN rate ON rate.lot_id = lot.id
            WHERE lot.id = '.$id.' 
            GROUP BY lot.id';

  $result_lots = mysqli_query($connect, $sql_lots);

  if(!$result_lots) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
  }

  if(!mysqli_num_rows($result_lots)) {
    header("Location: pages/404.html");
  };

  $lot = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
  
  $categories = get_categories($connect);


  
  $page_content = include_template('lots.php', ['categories' => $categories, 'lot' => $lot]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $user_name]);

  echo $layout_content;
  
?>
