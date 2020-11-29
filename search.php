<?php

session_start();

require_once 'helpers.php';
require_once 'functions.php';

$connect = db_connect();

$title = 'Поиск';

$user_name = '';

$lots = '';

$search = '';

$back_page = '';

$pages_count = '';

$pages = '';

$cur_page = '';

$is_auth = false;

if (isset($_SESSION['id'])
    && get_all_data_user($connect, (int) $_SESSION['id']) === null
) {
    session_unset();
    header('Location: /index.php');
    die();
} elseif (isset($_SESSION['name'])
    && isset($_SESSION['auth'])
) {
    $user_name = htmlspecialchars($_SESSION['name']);
    $is_auth = $_SESSION['auth'];
}

$categories = get_categories($connect);

if (!isset($_GET['search'])
    || (isset($_GET['search']) && $_GET['search'] === '')
) {
    header('Location: 404.php');
    die();
} else {
    $search = trim($_GET['search']);

    $sql_lots = 'SELECT COUNT(*) as count 
        FROM lot 
            WHERE date_finish > NOW() AND MATCH(title,description) AGAINST(?)';

    $stmt = mysqli_prepare($connect, $sql_lots);

    mysqli_stmt_bind_param($stmt, 's', $_GET['search']);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if (!$res) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $lots = mysqli_fetch_assoc($res)['count'];

    $page_items = 9;

    $pages_count = ceil($lots / $page_items);

    if (isset($_GET['page'])
        && ($_GET['page'] === ''
        || (int)$_GET['page'] > (int)$pages_count
        || (int)$_GET['page'] <= 0)
    ) {
        header('Location: 404.php');
        die();
    } elseif (!isset($_GET['page'])) {
        $cur_page = 1;
    } else {
        $cur_page = (int)$_GET['page'];
    }

    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $lots = get_lots_active_search($connect, $search, $page_items, $offset);
}

$page_content = include_template(
    'search-result.php',
    [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'cur_page' => $cur_page,
    'back_page' => $back_page,
    ]
);

$layout_content = include_template(
    'layout.php',
    [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'search' => $search,
    ]
);

echo $layout_content;
