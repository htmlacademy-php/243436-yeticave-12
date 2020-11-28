<?php

session_start();
require_once 'helpers.php';
require_once 'functions.php';

$connect = db_connect();

$title = 'Мои ставки';

$user_name = '';

$is_auth = false;

if(isset($_SESSION['id']) && get_all_data_user($connect, (int) $_SESSION['id']) === null) {
    session_unset();
    header('Location: /index.php');
} elseif (isset($_SESSION['name']) && isset($_SESSION['auth'])) {
    $user_name = htmlspecialchars($_SESSION['name']);
    $is_auth = $_SESSION['auth'];
    $user_id = (int) $_SESSION['id'];
} elseif(!isset($_SESSION['auth'])) {
    http_response_code(403);
    header('Location: user-login.php');
    die();
}

$categories = get_categories($connect);

$rates = get_rates_lots_user($connect, $user_id);

$page_content = include_template('myrates.php',
    ['categories' => $categories, 'is_auth' => $is_auth, 'rates' => $rates, 'user_id' => $user_id]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

echo $layout_content;
