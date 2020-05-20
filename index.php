<?php
    require 'helpers.php';
    require 'data.php';

    for ($i = 0; $i < count($posts); $i++) {
        $posts[$i]['date'] = generate_random_date(5);
        $posts[$i]['rel_date'] = get_rel_date($posts[$i]['date']);
    }

    $page_content = include_template('main.php', ['posts' => $posts]);
    $layout_content = include_template('layout.php', ['page_title' => 'readme: популярное',
                                                      'page_content' => $page_content]);

    print $layout_content;
