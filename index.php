<?php
	require 'assets/helpers.php';
	require 'assets/connect.php';

	$is_auth = 1;
	$user_name = 'Артём';
	$page_title = 'readme: популярное';

	$categories = get_categories($connection);
	$posts = get_posts($connection);

	$content_type = filter_input(INPUT_GET, 'content-type', FILTER_SANITIZE_SPECIAL_CHARS);

	if (is_value_in_array($categories, 'name', $content_type) || $content_type == 'all' || !isset($content_type)) {
		$filtered_posts = filter_posts($posts, $content_type);
	} else {
		throw_404();
	}

	$page_content = include_template('main.php', [
		'posts' => !$filtered_posts ? $posts : $filtered_posts,
		'categories' => $categories,
		'content_type' => $content_type
	]);
	$layout_content = include_template('layout.php', [
		'page_title' => $page_title,
		'page_content' => $page_content,
		'is_auth' => $is_auth
	]);

	print $layout_content;
