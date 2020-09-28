<?php

	function file_download($condition) : void {
	
		if ($condition === 'referrer'){		// условие для использования функции file_download() с возможностью расширения для других файлов, например, через switch вместо if 
			
			$file_name = 'file.exe';
			$file_name_for_user = 'my'.$file_name;
			$file = $_SERVER['DOCUMENT_ROOT'].'/'.$file_name;
		}
		
		if (file_exists($file)){
			
			// для работы заголовков ниже и избежания переполнения памяти, сбрасываем буфер вывода PHP
			
			if (ob_get_level()){
				ob_end_clean();
			}
			
			// браузер показывает окно сохранения файла
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$file_name_for_user);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Content-Length: ' . filesize($file));
			
			// читаем файл и отправляем пользователю
			
			readfile($file);
		
		} else {
			
			echo 'Файл не найден';
		}
	}
	
	if (isset($_SERVER['HTTP_REFERER'])){
		
		if (isset($_COOKIE['referrer'])){
			
			$cookie_name = 'referrer';
		
		} else {
			
			$name = 'referrer';
			$value = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
			$expires = time()+60*60*24;		// cookie устанавливается на 1 сутки
			$path = '/';
			$domain = 'auslogics.com';
			$secure = TRUE;
			$httponly = TRUE;
			
			setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
			
			$cookie_name = $name;
		}
		
		file_download($cookie_name);
	}

	