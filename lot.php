<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');

  $is_auth = false;

  $user_name = '';


  if(isset($_SESSION['name']) && isset($_SESSION['auth'])) {
      $user_name = $_SESSION['name'];
      $is_auth = $_SESSION['auth'];
  } 

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

  $title = htmlspecialchars($lot[0]['title']); 
  
  $categories = get_categories($connect);


  
  $page_content = include_template('lots.php', ['categories' => $categories, 'lot' => $lot, 'is_auth' => $is_auth]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

  echo $layout_content;
  
?>
