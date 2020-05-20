<?php
    require 'helpers.php';
    require 'data.php';

    $page_content = include_template('main.php', ['posts' => $posts]);
    $layout_content = include_template('layout.php', ['page_title' => 'readme: популярное',
                                                      'page_content' => $page_content]);

    print $layout_content;
?>

