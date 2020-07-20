
<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="#"
                title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($post['content']); ?>"
                            alt="Иконка" width="120" height="120">
                </div>
                <div class="post-link__info">
                    <h3><?= htmlspecialchars($post['header']); ?></h3>
                    <p><?= htmlspecialchars($post['link_title']); ?></p>
                    <span><?= htmlspecialchars($post['content']); ?></span>
                </div>
            </div>
        </a>
    </div>
</div>
