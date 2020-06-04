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
    $name = 'templates/' . $name;
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
