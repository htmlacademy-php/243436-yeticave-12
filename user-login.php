<?php

session_start();

require_once 'helpers.php';
require_once 'functions.php';

$connect = db_connect();

$title = 'Вход';

$user_name = '';

$email = '';

$password = '';

$errors = [];

$is_auth = false;

if (isset($_SESSION['auth'])) {
    http_response_code(403);
    die();
}

$categories = get_categories($connect);

if (isset($_POST['email'])
    && isset($_POST['password'])
) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($_POST['email'])) {
        $errors['email'] = 'Введите email';
    } elseif (validateEmail('email')) {
        $errors['email'] = 'Введите корректный email';
    } elseif (check_email($connect, $email) === null) {
        $errors['email'] = 'Данный email не зарегистрирован';
    } else {
        $data_user = get_data_user($connect, $_POST['email']);

        if (validateFilled('password')) {
            $errors['password'] = 'Введите пароль';
        } elseif (!password_verify($_POST['password'], $data_user[0]['password'])) {
            $errors['password'] = 'Вы ввели неверный пароль';
        }

        if (empty($errors)) {
            $user_name = $data_user[0]['name'];
            $user_id = $data_user[0]['id'];
            $_SESSION['auth'] = true;
            $_SESSION['name'] = $user_name;
            $_SESSION['id'] = $user_id;
            header('Location: index.php');
            die();
        }
    }
}

$page_content = include_template(
    'login.php',
    [
    'categories' => $categories,
    'errors' => $errors,
    'email' => $email,
    'password' => $password
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
