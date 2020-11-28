<?php

session_start();
require_once 'helpers.php';
require_once 'functions.php';

$connect = db_connect();

$title = 'Все лоты';

$user_name = '';

$back_page = '';

$is_auth = false;

if(isset($_SESSION['id']) && get_all_data_user($connect, (int) $_SESSION['id']) === null) {
    session_unset();
    header('Location: /index.php');
} elseif (isset($_SESSION['name']) && isset($_SESSION['auth'])) {
    $user_name = htmlspecialchars($_SESSION['name']);
    $is_auth = $_SESSION['auth'];
}

$categories = get_categories($connect);

if (!isset($_GET['category_id']) || (isset($_GET['category_id']) && $_GET['category_id'] === '')) {
    header('Location: 404.php');
} else {
    $category_id = (int)$_GET['category_id'];

    if (get_list_id_category($connect, (int)$category_id) === null) {
        header('Location: 404.php');
    }

    $lots = get_category_count($connect, $category_id)['count'];

    $page_items = 9;
    $pages_count = ceil($lots / $page_items);

    if (isset($_GET['page']) && ($_GET['page'] === '' || (int)$_GET['page'] > (int)$pages_count || (int)$_GET['page'] <= 0)) {
        header('Location: 404.php');
        die();
    } elseif (!isset($_GET['page'])) {
        $cur_page = 1;
    } else {
        $cur_page = (int)$_GET['page'];
    }

    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $lots = get_lot_category_count($connect, $category_id, $page_items, $offset);
}

$page_content = include_template('all-lots-result.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'cur_page' => $cur_page,
    'back_page' => $back_page,
    'category_id' => $category_id
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

echo $layout_content;
