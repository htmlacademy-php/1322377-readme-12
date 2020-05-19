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
            'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!Не могу дождаться начала финального сезона своего любимого сериала!',
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

    function cut_string($string, $max_length = 300) {
        if (strlen($string) <= $max_length)
            return '<p>' . $string . '</p>';
        else {
            $read_more = '<a class="post-text__more-link" href="#">Читать далее</a>';
            $words = explode(" ", $string, $max_length);
            $result_string = null;
            $counter = 0;
            while (strlen($result_string) < $max_length) {
                if ($counter === 0)
                    $result_string .= $words[$counter];
                else
                    $result_string .= " " . $words[$counter];
                $counter++;
            }
            return '<p>' . $result_string . "..." . '</p>' . $read_more;
        }
    }

    function include_template($path, $content = array()) {
        if (file_exists($path)) {
            ob_start(); // включает буферизацию вывода
            // заполняет буфер
            extract($content);
            require $path;
            return ob_get_clean(); // возвращает содержимое буфера и очищает его
        }
    }

    $page_content = include_template('templates/main.php', ['posts' => $posts]);
    $layout_content = include_template('templates/layout.php', ['page_title' => 'readme: популярное',
                                                                'page_content' => $page_content]);

    print $layout_content;
?>

