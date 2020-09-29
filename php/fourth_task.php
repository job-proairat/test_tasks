<?php

	try {

		require_once('database_connection.php');
		
		$left_form_e_mail = $_POST['left_form_e_mail'];
		$left_form_phone_number = $_POST['left_form_phone_number'];

		if (empty($left_form_e_mail) === FALSE && empty($left_form_phone_number) === TRUE){		// указана почта, но не указан телефонный номер
			
			$output = 'указана почта, но не указан телефонный номер';
			$status = 'email_not_phone_number';
		}
		
		if (empty($left_form_e_mail) === TRUE && empty($left_form_phone_number) === FALSE){		// не указана почта, но указан телефонный номер
			
			$output = 'не указана почта, но указан телефонный номер';
			$status = 'not_email_phone_number';
		}
		
		if (empty($left_form_e_mail) === TRUE && empty($left_form_phone_number) === TRUE){		// не указана ни почта, ни телефонный номер
			
			$output = 'не указана ни почта, ни телефонный номер';
			$status = 'not_email_not_phone_number';
		}
		
		if (empty($left_form_e_mail) === FALSE && empty($left_form_phone_number) === FALSE){	// указана и почта и телефонный номер
			
			$output = 'указана и почта и телефонный номер';
			$status = 'email_phone_number';
		}
			
		//if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		//exit('Email is not valid!');
		//}
		
		if ($status === 'email_phone_number'){
		
			// ВНИМАНИЕ!!!
			// Если во всей базе данных всё захешировать, то использование функции password_verify не представляется возможным.
			// Закомментированный код ниже отработал бы, если бы, например, была ещё одна таблица, допустим, `authentication` и поле `login` в этой таблице было бы не захешировано.
			// При соблюдении хотя бы этих услових, можно было бы написать следующий код:
			
			//$stmt = $mysqli->stmt_init();
			//$stmt->prepare("SELECT * FROM `authentication` WHERE `login` = ?");
			//$stmt->bind_param('s', $_POST['login']); // где $_POST['login'] - логин, введённый пользователем
			//$stmt->execute();
			//$result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
			//$stmt->free_result();
			
			//if (password_verify($_POST['password'], $result['password'])){ // где $_POST['password'] - пароль, введённый пользователем, а $result['password'] - захешированный пароль, храняшийся в базе данных
			
				//$stmt = $mysqli->stmt_init();
				//$stmt->prepare("SELECT * FROM `contacts` WHERE `id_user` = ?");
				//$stmt->bind_param('i', $result['id_user']); // $result['id_user'] берётся из таблицы `authentication`, а столбец `id_user` был бы внешним ключом к таблице `authentication`
				//$stmt->execute();
				//$result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
				//$stmt->free_result();
				
				//if (!empty($result)){
					
				//	$output = 'Здравствуйте! Рады видеть вас на нашем сайте!';
				//}
				
			//} else {
				
				$left_form_e_mail_hash = password_hash($left_form_e_mail, PASSWORD_DEFAULT);
				$left_form_phone_number_hash = password_hash($left_form_phone_number, PASSWORD_DEFAULT);
				
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				$headers .= "To: <".$left_form_e_mail.">\r\n";
				$headers .= "From: <admin@localhost>\r\n";
				
				$message = '<html>
								<head>
									<title>E-mail Verification</title>
								</head>
								<body>
									<h1>Hello!</h1>
									<p style="font-size: 16px;">In order to activate your e-mail on the site, please follow the link <a href="http://localhost/Auslogics/test_tasks/php/activate_email.php?hash='.$left_form_e_mail_hash .'">CLICK TO ACTIVATE YOUR E-MAIL</a></p>
								</body>
							</html>';
				
				$stmt = $mysqli->stmt_init();
				$stmt->prepare("INSERT INTO `contacts` (`e_mail`, `phone_number`) VALUES (?, ?)");
				$stmt->bind_param('ss', $left_form_e_mail_hash, $left_form_phone_number_hash);
				$stmt->execute();
				$stmt->close();
				
				if (mail($left_form_e_mail, "E-mail verification", $message, $headers)){
					
					$output = 'На вашу электронную почту отправлена ссылка, по которой необходимо перейти, для того чтобы подтвердить право владения указанной электронной почтой';
					$status = 'good';
				}
			//}
		}
		
	} catch (Exception $error) {
		
		echo $error->getMessage();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Fourth task</title>
		<link rel="icon" href="../favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="../styles/main.css" />
		<link rel="stylesheet" href="../styles/fourth_task_operations.css" />
	</head>
	<body>
		<?php 
			
			if ($status === 'good'){
				
				echo '<h4 class="good">'.$output.'</h4> 
					  <a href="http://localhost/Auslogics/test_tasks/html/fourth_task.html">Вернуться на предыдущую страницу</a>';
			}
			
			if ($status === 'email_not_phone_number' || $status ===  'not_email_phone_number' || $status === 'not_email_not_phone_number'){
				
				echo '<h4 class="bad">'.$output.'</h4> 
					  <a href="http://localhost/Auslogics/test_tasks/html/fourth_task.html">Вернуться на предыдущую страницу</a>';
			}
			
		?>
	</body>
</html>
