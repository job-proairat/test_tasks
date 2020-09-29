<?php

	date_default_timezone_set('UTC');

	$mysqli = new mysqli("localhost", "proairat", "NM38_B!4GNT0x!0m", "auslogics_db");

	if ($mysqli->connect_errno)
	{
		exit("Не удалось подключиться к базе данных: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	}

	$mysqli->set_charset("utf8");
	$mysqli->query('SET time_zone = "+00:00"');

