<?php

session_start();
require_once 'helpers.php';
require_once 'functions.php';

$connect = db_connect();

$title = 'Регистрация';

$email = '';
$password = '';
$first_name = '';
$message = '';
$len = '';

$errors = [];

$is_auth = false;

if (isset($_SESSION['auth'])) {
    http_response_code(403);
    die();
}

$categories = get_categories($connect);

if (
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['name']) &&
    isset($_POST['message'])
) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['name'];
    $message = $_POST['message'];

    $rules = [
        'email' => function () {
            return validateEmail('email');
        },
        'password' => function () {
            return validateFilled('password');
        },
        'name' => function () {
            return validateFilled('name');
        },
        'message' => function () {
            return validateFilled('message');
        },
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    if (check_email($connect, $email) !== null) {
        $errors['email'] = check_email($connect, $email);
    } elseif (show_errors('email', 72, 'Введите email', 'Слишком длинный email')) {
        $errors['email'] = show_errors('email', 72, 'Введите email', 'Слишком длинный email');
    }

    $errors['password'] = show_errors('password', 64, 'Введите пароль', 'Слишком длинный пароль');
    $errors['name'] = show_errors('name', 100, 'Введите имя', 'Слишком длинное имя');
    $errors['message'] = show_errors('message', 255, 'Напишите как с вами связаться',
        'Слишком много контактных данных');

    $errors = array_filter($errors);

    $created_at = date('Y-m-d H:i:s', time());

    if (empty($errors)) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $data = [$created_at, $email, $first_name, $password, $message];

        insert_user($connect, $data);

        header('Location: user-login.php');
    }
}

$page_content = include_template('sign-up.php', [
    'categories' => $categories,
    'errors' => $errors,
    'email' => $email,
    'password' => $password,
    'first_name' => $first_name,
    'message' => $message
]);

$layout_content = include_template('layout.php',
    ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'is_auth' => $is_auth]);

echo $layout_content;
