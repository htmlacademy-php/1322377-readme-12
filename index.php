<?php
    require 'assets/helpers.php';
    require 'assets/data.php';

    $page_title = 'readme: популярное';
    $page_content = include_template('main.php', ['posts' => $posts]);
    $layout_content = include_template('layout.php', ['page_title' => $page_title,'page_content' => $page_content, 'is_auth' => $is_auth]);

    print $layout_content;
