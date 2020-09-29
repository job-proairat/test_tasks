<?php

	try {
	
		if (isset($_GET['hash'])){
		
			require_once('database_connection.php');
			
			$hash = $_GET['hash'];
			
			$stmt = $mysqli->stmt_init();
			$stmt->prepare("SELECT * FROM `contacts` WHERE `e_mail` = ?");
			$stmt->bind_param('s', $hash);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
			$stmt->free_result();
			
			if (!empty($result)){
				
				if ($result['e_mail_status'] === 'pending'){
					
					$e_mail_status = 'approved';
					
					$stmt = $mysqli->prepare("UPDATE `contacts` SET `e_mail_status` = ? WHERE `e_mail` = ?");
					$stmt->bind_param('ss', $e_mail_status, $hash);
					$stmt->execute();
					$stmt->close();
					
					$status = 'good';
					$output = 'Ваш e-mail подтверждён!';
				
				} else {
					
					$status = 'good';
					$output = 'Ваш e-mail уже был подтвержден раннее.';
				}
			
			} else {
				
				$status = 'bad';
				$output = 'Вы прошли по ссылке, но в базе данных не сохранился e-mail, который вы должны были подтвердить. Свяжитесь с нами по адресу support@localhost';
			}
		
		} else {
		
			$status = 'good';
			$output = 'Ссылка содержит некорректные данные. Свяжитесь с нами по адресу support@localhost';
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
					  <a href="http://localhost/Auslogics/test_tasks/html/fourth_task.html">Перейти на сайт</a>';
			}
			
			if ($status === 'bad'){
				
				echo '<h4 class="bad">'.$output.'</h4> 
					  <a href="http://localhost/Auslogics/test_tasks/html/fourth_task.html">Вернуться на предыдущую страницу</a>';
			}
		
		?>
	</body>
</html>