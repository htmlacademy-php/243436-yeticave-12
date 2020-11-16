<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');


  $connect = db_connect();


  $title = 'Все лоты';

  $user_name = '';

  $back_page = '';


  $is_auth = false;

  if(isset($_SESSION['name']) && isset($_SESSION['auth'])) {
    $user_name = $_SESSION['name'];
    $is_auth = $_SESSION['auth'];
  }


  $categories = get_categories($connect);

  
  if (!isset($_GET['category_id']) || '' == $category_id = (int)$_GET['category_id'] ) {
    header("Location: 404.php");
  } else {
    $category_id = $_GET['category_id'];

    $sql_categories = "SELECT category.id AS category_id
      FROM category
        WHERE category.id = $category_id";
    
    $result_categories = mysqli_query($connect, $sql_categories);

    if(!$result_categories) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
    }

    $category_count  = mysqli_num_rows($result_categories);

    if(!$category_count) {
      header("Location: 404.php");
    };

    $sql_lots = "SELECT COUNT(*) as count FROM lot WHERE date_finish > NOW() AND category_id = $category_id";

    $result_lots = mysqli_query($connect, $sql_lots);

    if(!$result_lots) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
    }    

    $lots = mysqli_fetch_assoc($result_lots)['count'];

    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;

    $pages_count = ceil($lots/$page_items);


    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);
      

    $sql_lots = "SELECT lot.id, date_finish, category.name AS category, lot.category_id AS category_id, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price, (SELECT COUNT(*) FROM rate WHERE rate.lot_id = lot.id) AS count_rate
    FROM lot 
      JOIN category ON lot.category_id = category.id
      LEFT JOIN rate ON rate.lot_id = lot.id
        WHERE date_finish > NOW() AND category.id = $category_id 
        GROUP BY lot.id
        ORDER BY lot.date_start DESC LIMIT ".$page_items." OFFSET ".$offset;

    $result_lots = mysqli_query($connect, $sql_lots);

    if(!$result_lots) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
    }

    $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
  }

  $page_content = include_template('all-lots-result.php', ['categories' => $categories, 'lots' => $lots, 'pages_count' => $pages_count, 'pages' => $pages, 'cur_page' => $cur_page, 'back_page' => $back_page, 'category_id' => $category_id]);
  
  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

  echo $layout_content;
?>