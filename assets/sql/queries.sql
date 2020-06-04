USE readme;

INSERT INTO readme.users (email, name, password, avatar_path)
VALUES ('larisa@gmail.com', 'Лариса', 'secret', 'avatar'),
       ('vladik@gmail.com', 'Владик', 'secret', 'avatar'),
       ('viktor@gmail.com', 'Виктор', 'secret', 'avatar');

INSERT INTO readme.categories (name)
VALUES ('post-quote'),
	   ('post-text'),
	   ('post-photo'),
       ('post-link'),
       ('post-video');

INSERT INTO readme.hashtag (title)
VALUES ('hello');

INSERT INTO readme.posts (title, content, user_id, category_id, hashtag_id)
VALUES
  ('Цитата', 'Мы в script любим только раз, а после ищем лишь похожих', '1', '1', '1'),
  ('Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', '2', '2', '1'),
  ('Наконец, обработал фотки', 'rock-medium.jpg', '3', '3', '1'),
  ('Моя мечта', 'coast-medium.jpg', '1', '3', '1'),
  ('Лучшие курсы', 'www.htmlacademy.ru', '2', '2', '1'),
  ('Видео', '#', '1', '5', '1');

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


