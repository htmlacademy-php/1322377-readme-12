<?php
	require 'assets/helpers.php';
	require 'assets/connect.php';

	$is_auth = 1;
	$user_name = 'Артём';
	$page_title = 'readme: пост';

	$categories = get_categories($connection);
	$posts = get_posts($connection);

	$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS);

	if (is_value_in_array($posts, 'post_id', $post_id)) {
		$current_post = is_value_in_array($posts, 'post_id', $post_id);
	} else {
		throw_404();
	}

	$user_id = $current_post['user_id'];
	$posts_count = get_posts_count($connection, $user_id);
	$subs_count = get_subs_count($connection, $user_id);

	$content_type = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS);

	$post_content = include_template('posts/post-' . $current_post['type'] . '.php', [
		'post' => $current_post
	]);

	$page_content = include_template('post.php', [
		'post' => $current_post,
		'post_content' => $post_content,
		'categories' => $categories,
		'content_type' => $content_type,
		'posts_count' => $posts_count,
		'subs_count' => $subs_count
	]);

	$layout_content = include_template('layout.php', [
		'page_title' => $page_title,
		'page_content' => $page_content,
		'is_auth' => $is_auth
	]);

	print $layout_content;
