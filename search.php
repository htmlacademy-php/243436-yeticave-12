<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');

  $is_auth = false;

  $user_name = '';

  $title = 'Вход';

  $connect = db_connect();

  $categories = get_categories($connect);

  $search = '';

  $back_page = '';


  if (
    isset($_GET['search'])
  ) {
    $search = trim($_GET['search']);

    $sql_lots = "SELECT id FROM lot WHERE date_finish > NOW() AND MATCH(title,description) AGAINST('".$search."')";

    $result_lots = mysqli_query($connect, $sql_lots);

    if(!$result_lots) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
    }

    $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;

    $pages_count = ceil(count($lots)/$page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $sql_lots = "SELECT lot.id, date_finish, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
    FROM lot 
        JOIN category ON lot.category_id = category.id
        LEFT JOIN rate ON rate.lot_id = lot.id
            WHERE date_finish > NOW() AND MATCH(title,description) AGAINST('".$search."')
            GROUP BY lot.id
            ORDER BY lot.date_start DESC LIMIT ".$page_items." OFFSET ".$offset;

    $result_lots = mysqli_query($connect, $sql_lots);

    if(!$result_lots) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
    }

    $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);


  }

  $page_content = include_template('search-result.php', ['categories' => $categories, 'lots' => $lots, 'search' => $search, 'pages_count' => $pages_count, 'pages' => $pages, 'cur_page' => $cur_page, 'back_page' => $back_page]);
  
  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth, 'search' => $search]);

  echo $layout_content;
?>