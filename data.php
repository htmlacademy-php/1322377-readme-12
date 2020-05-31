<?php
    $is_auth = rand(0, 1);
    $user_name = 'Артём';

    $post_types = [
        'quote' => 'post-quote',
        'text' => 'post-text',
        'photo' => 'post-photo',
        'link' => 'post-link',
        'video' => 'post-video'
    ];

    $posts = [
        [
            'header' => 'Цитата',
            'type' => $post_types['quote'],
            'content' => 'Мы в <script> любим только раз, а после ищем лишь похожих',
            'user_name' => 'Лариса',
            'user_pic' => 'userpic-larisa-small.jpg'
        ], [
            'header' => 'Игра престолов',
            'type' => $post_types['text'],
            'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
            'user_name' => 'Владик',
            'user_pic' => 'userpic.jpg'
        ], [
            'header' => 'Наконец, обработал фотки',
            'type' => $post_types['photo'],
            'content' => 'rock-medium.jpg',
            'user_name' => 'Виктор',
            'user_pic' => 'userpic-mark.jpg'
        ], [
            'header' => 'Моя мечта',
            'type' => $post_types['photo'],
            'content' => 'coast-medium.jpg',
            'user_name' => 'Лариса',
            'user_pic' => 'userpic-larisa-small.jpg'
        ], [
            'header' => 'Лучшие курсы',
            'type' => $post_types['link'],
            'content' => 'www.htmlacademy.ru',
            'user_name' => 'Владик',
            'user_pic' => 'userpic.jpg'
        ], [
            'header' => 'Видео',
            'type' => $post_types['video'],
            'content' => '#',
            'user_name' => 'Лариса',
            'user_pic' => 'userpic-larisa-small.jpg'
        ]
    ];

    for ($i = 0; $i < count($posts); $i++) {
        $posts[$i]['date'] = generate_random_date(5);
    }
