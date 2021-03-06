<div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
</div>
<div class="popular container">
    <div class="popular__filters-wrapper">
        <div class="popular__sorting sorting">
            <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
            <ul class="popular__sorting-list sorting__list">
                <li class="sorting__item sorting__item--popular">
                    <a class="sorting__link sorting__link--active" href="#">
                        <span>Популярность</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link" href="#">
                        <span>Лайки</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link" href="#">
                        <span>Дата</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <div class="popular__filters filters">
            <b class="popular__filters-caption filters__caption">Тип контента:</b>
            <ul class="popular__filters-list filters__list">
                <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                    <a class="filters__button filters__button--ellipse filters__button--all <?= (!$content_type || $content_type === 'all') ? 'filters__button--active' : '' ?>" href="<?= get_query_href(['content-type' => 'all'], '/index.php') ?>">
                        <span>Все</span>
                    </a>
                </li>
              <?php foreach ($categories as $category): ?>
                  <li class="popular__filters-item filters__item">
                      <a class="filters__button filters__button--<?= $category['name'] ?> <?= $content_type === $category['name'] ? 'filters__button--active' : '' ?> button" href="<?= get_query_href(['content-type' => $category['name']], '/index.php') ?>">
                          <span class="visually-hidden"><?= $category['title'] ?></span>
                          <svg class="filters__icon" width="22" height="18">
                              <use xlink:href="#icon-filter-<?= $category['name'] ?>"></use>
                          </svg>
                      </a>
                  </li>
              <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div class="popular__posts">
      <?php foreach ($posts as $post): ?>
          <article class="popular__post post post-<?= htmlspecialchars($post['type']); ?>">
              <header class="post__header">
                  <a href="/post.php?post_id=<?= $post['post_id'] ?>"><h2><?= htmlspecialchars($post['header']); ?></h2></a>
              </header>
              <div class="post__main">
                <?php if (htmlspecialchars($post['type']) ==='quote'): ?>
                    <blockquote>
                        <p><?= htmlspecialchars($post['content']); ?></p>
                        <cite>Неизвестный Автор</cite>
                    </blockquote>
                <?php elseif (htmlspecialchars($post['type'])==='text'): ?>
                  <?= cut_string(htmlspecialchars($post['content'])); ?>
                <?php elseif (htmlspecialchars($post['type'])==='photo'): ?>
                    <div class="post-photo__image-wrapper">
                        <img src="assets/img/<?= htmlspecialchars($post['content']); ?>" alt="Фото от пользователя" width="360" height="240">
                    </div>
                <?php elseif (htmlspecialchars($post['type'])==='link'): ?>
                    <div class="post-link__wrapper">
                        <a class="post-link__external" href="http://<?= htmlspecialchars($post['content']); ?>" title="Перейти по ссылке">
                            <div class="post-link__info-wrapper">
                                <div class="post-link__icon-wrapper">
                                    <img src="https://www.google.com/s2/favicons?domain=htmlacademy.ru" alt="Иконка">
                                </div>
                                <div class="post-link__info">
                                    <h3>
                                      <?= htmlspecialchars($post['header']); ?>
                                    </h3>
                                </div>
                            </div>
                            <span><?= htmlspecialchars($post['content']); ?></span>
                        </a>
                    </div>
                    <!-- По идее должен быть и пост-видео -->
                <?php elseif (htmlspecialchars($post['type'])==='video'): ?>
                    <div class="post-video__block">
                        <div class="post-video__preview">
                          <?php //=embed_youtube_cover();?>
                            <img src="assets/img/coast-medium.jpg" alt="Превью к видео" width="360" height="188">
                        </div>
                        <a href="views/post-details.html" class="post-video__play-big button">
                            <svg class="post-video__play-big-icon" width="14" height="14">
                                <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                            <span class="visually-hidden">Запустить проигрыватель</span>
                        </a>
                    </div>
                <?php endif; ?>
              </div>
              <footer class="post__footer">
                  <div class="post__author">
                      <a class="post__author-link" href="#" title="Автор">
                          <div class="post__avatar-wrapper">
                              <img class="post__author-avatar" src="assets/img/<?= htmlspecialchars($post['user_pic']); ?>" alt="Аватар пользователя">
                          </div>
                          <div class="post__info">
                              <b class="post__author-name"><?= htmlspecialchars($post['user_name']); ?></b>
                              <time class="post__time" title="<?= $post['date']; ?>" datetime="<?= date('c', strtotime($post['date'])); ?>"><?= get_rel_date($post['date']); ?></time>
                          </div>
                      </a>
                  </div>
                  <div class="post__indicators">
                      <div class="post__buttons">
                          <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                              <svg class="post__indicator-icon" width="20" height="17">
                                  <use xlink:href="#icon-heart"></use>
                              </svg>
                              <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                  <use xlink:href="#icon-heart-active"></use>
                              </svg>
                              <span>0</span>
                              <span class="visually-hidden">количество лайков</span>
                          </a>
                          <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                              <svg class="post__indicator-icon" width="19" height="17">
                                  <use xlink:href="#icon-comment"></use>
                              </svg>
                              <span>0</span>
                              <span class="visually-hidden">количество комментариев</span>
                          </a>
                      </div>
                  </div>
              </footer>
          </article>
      <?php endforeach; ?>
    </div>
</div>
