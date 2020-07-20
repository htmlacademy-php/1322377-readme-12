<?php
/**
 * Преобразует интервал времени от даты публикации до текущего момента в относительный формат.
 * @param string $date1 дата публикации
 * @return string дата в относительном формате
 */
function get_rel_date($date1) {
    $date2 = date('c');
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
    $month = $week * 4;

    $interval = strtotime($date2) - strtotime($date1);

    $isMinutes = $interval / $hour < 1;
    $isHours = $interval / $hour >= 1 && $interval / $hour < 24;
    $isDays = $interval / $hour >= 24 && $interval / $day < 7;
    $isWeeks = $interval / $day >= 7 && $interval / $month < 1;
    $isMonths = $interval / $month >= 1;

    if ($isMinutes) {
        return floor($interval / $minute) . ' ' . get_noun_plural_form($interval / $minute, 'минута', 'минуты', 'минут') . ' назад';
    }
    else if ($isHours) {
        return floor($interval / $hour) . ' ' . get_noun_plural_form($interval / $hour, 'час', 'часа', 'часов') . ' назад';
    }
    else if ($isDays) {
        return floor($interval / $day) . ' ' . get_noun_plural_form($interval / $day, 'день', 'дня', 'дней') . ' назад';
    }
    else if ($isWeeks) {
        return floor($interval / $week) . ' ' . get_noun_plural_form($interval / $week, 'неделя', 'недели', 'недель') . ' назад';
    }
    else if ($isMonths) {
        return floor($interval / $month) . ' ' . get_noun_plural_form($interval / $month, 'месяц', 'месяца', 'месяцев') . ' назад';
    }
}

/**
  * Ограничение длины текста и добавление кнопки 'Читать далее'
  * @param {string} $string - обрабатываемый текст
  * @param {integer} $max_length - максимальная длина текста
  * @return {string} - текст в нужных тегах
  */
  function cut_string($string, $max_length = 300) {
    if (strlen($string) <= $max_length)
        return '<p>' . $string . '</p>';
    $read_more = '<a class="post-text__more-link" href="#">Читать далее</a>';
    $words = explode(" ", $string, $max_length);
    $result_string = null;
    $counter = 0;
    while (strlen($result_string) < $max_length) {
        if (!$counter)
            $result_string .= $words[$counter];
        else
            $result_string .= " " . $words[$counter];
        $counter++;
    }
    return '<p>' . $result_string . '...' . '</p>' . $read_more;
}


/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'private/views/' . $name;
    if (!is_readable($name)) {
        return '';
    }
    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Проверяет, что переданная ссылка ведет на публично доступное видео с youtube
 * @param string $youtube_url Ссылка на youtube видео
 * @return bool
 */
function check_youtube_url($youtube_url)
{
    $res = false;
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $api_data = ['id' => $id, 'part' => 'id,status', 'key' => 'AIzaSyBN-AXBnCPxO3HJfZZdZEHMybVfIgt16PQ'];
        $url = "https://www.googleapis.com/youtube/v3/videos?" . http_build_query($api_data);

        $resp = file_get_contents($url);

        if ($resp && $json = json_decode($resp, true)) {
            $res = $json['pageInfo']['totalResults'] > 0 && $json['items'][0]['status']['privacyStatus'] == 'public';
        }
    }

    return $res;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Возвращает список категорий
 * @param mysqli $link
 * @return array двумерный массив, полученный в ответ на запрос
 */
function get_categories ($link)
{
    $sql = 'SELECT id, title, name FROM categories';
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param mysqli $link
 * @return array двумерный массив, полученный в ответ на запрос
 */
function get_posts ($link) {
    $sql = 'SELECT p.title as header, 
       					   p.content, 
       					   p.dt_add as date, 
       					 	 p.id as post_id, 
       						 p.quote_author as quote_author,
       						 p.link_title as link_title,
       					   c.name as type, 
       						 u.name as user_name, 
       						 u.avatar_path as user_pic,
       						 u.id as user_id,
       					   s.user_on_whom_id as subs
            FROM posts AS p, users AS u, categories as c, subs as s
            WHERE p.user_id = u.id AND c.id = p.category_id
            ORDER BY p.views_count';
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_posts_count ($link, $userid) {
	$sql = 'SELECT p.title as header, 
       					 p.id as post_id, 
       					 u.name as user_name
            FROM posts AS p, users AS u
            WHERE p.user_id = u.id and u.id = ' . $userid;
	$result = mysqli_query($link, $sql);
	$user_posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return count($user_posts);
}

function get_subs_count ($link, $userid) {
	$sql = 'SELECT s.user_on_whom_id
            FROM subs as s
            WHERE s.user_on_whom_id = ' . $userid;
	$result = mysqli_query($link, $sql);
	$user_posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return count($user_posts);
}

	/**
	 * Фильтрует заданный массив постов по заданному типу контента
	 * @param array $posts Список постов
	 * @param string|null $post_type Тип поста, по которому нужно фильтровать
	 * @return array Отфильтрованный массив
	 */
	function filter_posts (array $posts, $post_type) {
		$filtered_array = [];
		if ($post_type === 'all' || !isset($post_type)) {
			return $posts;
		}
		foreach ($posts as $post) {
			if ($post['type'] === $post_type) {
				array_push($filtered_array, $post);
			}
		}
		return $filtered_array;
	};

	/**
	 * Формирует URL исходя из переданного пути и параметров запроса
	 * @param array $params Массив с параметрами запроса
	 * @param string $path Адрес страницы
	 * @return string Сформированный URL
	 */
	function get_query_href(array $params, string $path): string {
		$current_params = $_GET;
		$merged_params = array_merge($current_params, $params);
		$query = http_build_query($merged_params);

		return $path . '?' . ($query ? $query : '');
	}

	/**
	 * Проверяет, совпадает ли значение $value со значением какого-либо элемента массива $array с ключом $key. Если совпадает, возвращает соответствующий элемент массива.
	 * @param array $array
	 * @param $key
	 * @param $value
	 * @return object|int
	 */
	function is_value_in_array($array, $key, $value) {
		foreach ($array as $item) {
			if ($item[$key] === $value) {
				return $item;
			}
		}
		return 0;
	}

	function throw_404 () {
		$page_content = include_template('404.php');
		$layout_content = include_template('layout.php', [
			'page_title' => 'Страница не найдена',
			'page_content' => $page_content,
			'is_auth' => 1
		]);

		print $layout_content;
		die();
	}
