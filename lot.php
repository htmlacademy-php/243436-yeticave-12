<?php
session_start();

require_once 'helpers.php';
require_once 'functions.php';

$connect = db_connect();

$user_name = '';

$min_rate = '';

$cost = '';

$user_id_last_rate = '';

$errors = [];

$errors_user = [];

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
    $user_session_id = (int) $_SESSION['id'];
}

$categories = get_categories($connect);

if (!isset($_GET['id'])
    || isset($_GET['id']) && $_GET['id'] === ''
    || get_lot($connect, $_GET['id']) === null
) {
    header('Location: 404.php');
    die();
}

$lot_id = (int)$_GET['id'];

$lot = get_lot($connect, $lot_id);

$title = htmlspecialchars($lot['title']);

if (isset($_POST['cost'])
) {
    $min_rate = htmlspecialchars($lot['current_price'] + $lot['rate_step']);

    $cost = (int)$_POST['cost'];

    if (show_errors('cost', 10, 'Введите ставку лота', 'Слишком большое число')) {
        $errors['cost'] = show_errors('cost', 10, 'Введите ставку лота', 'Слишком большое число');
    } elseif (validate_price('cost') === 'form__item--invalid' || $cost < $min_rate) {
        $errors['cost'] = 'Введите корректное значение';
    }

    $errors = array_filter($errors);

    $date_start = date('Y-m-d H:i:s', time());

    $user_id = $user_session_id;

    $data = [$date_start, $cost, $user_id, $lot_id];

    if (empty($errors)) {
        insert_rate($connect, $data);

        header("Location: lot.php?id=$lot_id");
    }
}

$rates = get_lot_rates($connect, $lot_id);

$rate_count = $rates === null ? 0 : count($rates);

if (isset($user_session_id)) {
    $user_id_create_lot = get_user_id_create_lot($connect, $lot_id);

    if ($user_session_id === $user_id_create_lot['user_id']) {
        $errors_user[$user_id_create_lot['user_id']] = 'Лот создан текущим пользователем';
    }
}


if (get_user_id_last_rate($connect, $lot_id) !== null) {
    $user_id_last_rate = get_user_id_last_rate($connect, $lot_id)['user_id'];
}

$page_content = include_template(
    'lots.php',
    [
    'categories' => $categories,
    'lot' => $lot,
    'is_auth' => $is_auth,
    'cost' => $cost,
    'min_rate' => $min_rate,
    'errors' => $errors,
    'lot_id' => $lot_id,
    'rates' => $rates,
    'rate_count' => $rate_count,
    'errors_user' => $errors_user,
    'user_id_last_rate' => $user_id_last_rate
    ]
);

$layout_content = include_template(
    'layout.php',
    [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
    ]
);

echo $layout_content;
