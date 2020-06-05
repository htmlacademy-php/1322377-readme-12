<?php
    require 'assets/helpers.php';
//    require 'assets/data.php';

    $is_auth = 1;
    $user_name = 'Артём';


    $pwd = 'secretpwd';
    $connection = mysqli_connect('localhost', 'root', $pwd, 'readme', '3306');
    if (!$connection) {
        print ('Ошибка подключения: ' . mysqli_connect_error());
    } else {
        print('Соединение установлено' . "\n");
        mysqli_set_charset($connection, 'utf-8');

        $categories = get_categories($connection);
        $posts = get_posts($connection);
    }

    $page_title = 'readme: популярное';
    $page_content = include_template('main.php', ['posts' => $posts]);
    $layout_content = include_template('layout.php', ['page_title' => $page_title, 'page_content' => $page_content, 'is_auth' => $is_auth]);

    print $layout_content;


