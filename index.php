<?php

session_start();
require_once 'helpers.php';
require_once 'functions.php';
require_once 'getwinner.php';

$connect = db_connect();

$title = 'Главная';

$user_name = '';

$back_page = '';

$is_auth = false;

if (isset($_SESSION['name']) && isset($_SESSION['auth'])) {
    $user_name = htmlspecialchars($_SESSION['name']);
    $is_auth = $_SESSION['auth'];
}

$categories = get_categories($connect);

$sql_lots = 'SELECT COUNT(*) as count FROM lot WHERE date_finish > NOW()';

$result_lots = mysqli_query($connect, $sql_lots);

if (!$result_lots) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: ' . $error;
}

$lots = (int)mysqli_fetch_assoc($result_lots)['count'];

if (isset($_GET['page']) && $_GET['page'] === '') {
    header('Location: 404.php');
} elseif (!isset($_GET['page'])) {
    $cur_page = 1;
} else {
    $cur_page = (int)$_GET['page'];
}

$page_items = 9;

$pages_count = ceil($lots / $page_items);
$offset = ($cur_page - 1) * $page_items;

if (isset($_GET['page']) && ((int)$_GET['page'] > (int)$pages_count || (int)$_GET['page'] <= 0)) {
    header('Location: 404.php');
}

$pages = range(1, $pages_count);

$sql_lots = 'SELECT lot.id, date_finish, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
    FROM lot
        JOIN category ON lot.category_id = category.id
        LEFT JOIN rate ON rate.lot_id = lot.id
            WHERE date_finish > NOW()
            GROUP BY lot.id
            ORDER BY lot.date_start DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

$result_lots = mysqli_query($connect, $sql_lots);

if (!$result_lots) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: ' . $error;
}

$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'cur_page' => $cur_page,
    'back_page' => $back_page
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

echo $layout_content;
