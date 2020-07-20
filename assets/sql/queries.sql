USE readme;

INSERT INTO readme.users (email, name, password, avatar_path)
VALUES ('larisa@gmail.com', 'Лариса', 'secret', 'userpic-larisa-small.jpg'),
       ('vladik@gmail.com', 'Владик', 'secret', 'userpic.jpg'),
       ('viktor@gmail.com', 'Виктор', 'secret', 'userpic-mark.jpg');

INSERT INTO readme.categories (title, name)
VALUES ('Фото', 'photo'),
       ('Видео', 'video'),
       ('Текст', 'text'),
       ('Цитата', 'quote'),
       ('Ссылка', 'link');

INSERT INTO readme.hashtag (title)
VALUES ('hello');

INSERT INTO readme.posts (title, content, user_id, category_id, hashtag_id, quote_author, link_title)
VALUES
  ('Цитата', 'Мы в script любим только раз, а после ищем лишь похожих', '1', '4', '1', 'Неизвестный Автор', NULL),
  ('Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', '2', '3', '1', NULL, NULL),
  ('Наконец, обработал фотки', 'rock-medium.jpg', '3', '1', '1', NULL, NULL),
  ('Моя мечта', 'coast-medium.jpg', '1', '1', '1', NULL, NULL),
  ('Лучшие курсы', 'www.htmlacademy.ru', '2', '5', '1', NULL, NULL),
  ('Видео', '#', '1', '2', '1', NULL, NULL),
  ('Цитата', 'Тысячи людей живут без любви, но никто — без воды.', '2', '4', '1', 'Хью Оден', NULL),
  ('Полезный пост про Байкал', 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.', '3', '3', '1', NULL, NULL),
  ('Делюсь с вами ссылочкой', 'http://www.vitadental.ru', '1', '5', '1', NULL, 'Стоматология "Вита"');

INSERT INTO readme.comments (user_id, post_id, content)
VALUES
  ('1', '2', 'Вау, тоже жду!'),
  ('3', '4', 'Красотища!');

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
USE readme;
SELECT u.name, c.name, p.title
FROM posts AS p, users AS u, categories AS c
WHERE p.user_id = u.id AND c.id = p.category_id
ORDER BY p.views_count;

-- получить список постов для конкретного пользователя;
USE readme;
SELECT u.name, p.title, p.content
FROM posts AS p, users AS u
WHERE u.name = 'Лариса' AND p.user_id = u.id;

-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
USE readme;
SELECT u.name, p.title, c.content
FROM posts AS p, users AS u, comments AS c
WHERE p.id = '4' AND u.id = c.user_id AND p.id = c.post_id;

-- добавить лайк к посту;
USE readme;
INSERT INTO readme.likes (user_id, post_id)
VALUES ('1', '2');
SELECT * FROM readme.likes;

-- подписаться на пользователя.
USE readme;
INSERT INTO readme.subs (user_who_id, user_on_whom_id)
VALUES ('1', '2');
SELECT * FROM readme.subs;


