<?php

	$pwd = 'secretpwd';
	$connection = mysqli_connect('localhost', 'root', $pwd, 'readme', '3306');
	if (!$connection) {
		print ('Ошибка подключения: ' . mysqli_connect_error());
		die();
	} else {
		print('Соединение установлено' . "\n");
		mysqli_set_charset($connection, 'utf-8');
	}
