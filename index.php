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

$title = 'Главная';

$connect = db_connect();

$sql_lots = 'SELECT lot.id, date_finish, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
    FROM lot 
        JOIN category ON lot.category_id = category.id
        LEFT JOIN rate ON rate.lot_id = lot.id
            WHERE date_finish > NOW()
            GROUP BY lot.id
            ORDER BY lot.date_start DESC';

$result_lots = mysqli_query($connect, $sql_lots);

if(!$result_lots) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: '.$error;
}

$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);



$categories = get_categories($connect);



$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

$layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

echo $layout_content;

?>
