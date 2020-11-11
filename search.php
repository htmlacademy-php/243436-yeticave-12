<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');


  $connect = db_connect();


  $title = 'Поиск';


  $user_name = '';

  $lots = '';
  $search = '';
  $back_page = '';


  $is_auth = false;

  if(isset($_SESSION['name']) && isset($_SESSION['auth'])) {
    $user_name = $_SESSION['name'];
    $is_auth = $_SESSION['auth'];
  }


  $categories = get_categories($connect);


  if (
    isset($_GET['search'])
  ) {
    $search = trim($_GET['search']);

    $sql_lots = "SELECT COUNT(*) as count FROM lot WHERE date_finish > NOW() AND MATCH(title,description) AGAINST(?)";

    $stmt = mysqli_prepare($connect, $sql_lots);

    mysqli_stmt_bind_param($stmt, 's', $_GET['search']);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if(!$res) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
      die();
    }

    $lots = mysqli_fetch_assoc($res)['count'];

    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;

    $pages_count = ceil($lots/$page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $sql_lots = "SELECT lot.id, date_finish, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
    FROM lot 
        JOIN category ON lot.category_id = category.id
        LEFT JOIN rate ON rate.lot_id = lot.id
            WHERE date_finish > NOW() AND MATCH(title,description) AGAINST(?)
            GROUP BY lot.id
            ORDER BY lot.date_start DESC LIMIT ".$page_items." OFFSET ".$offset;
    
    $stmt = mysqli_prepare($connect, $sql_lots);

    mysqli_stmt_bind_param($stmt, 's', $_GET['search']);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if(!$res) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
        die();
    }

    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
  }

  $page_content = include_template('search-result.php', ['categories' => $categories, 'lots' => $lots, 'search' => $search, 'pages_count' => $pages_count, 'pages' => $pages, 'cur_page' => $cur_page, 'back_page' => $back_page]);
  
  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth, 'search' => $search]);

  echo $layout_content;
?>