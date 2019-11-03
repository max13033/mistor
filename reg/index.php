<?
session_start();
$pagetitle = "Регистрация";

require '../head.php';  

?>
	<div class="mainplace">
		<div class ="mainplacetext">
			<h3>Регистрация</h3>
<?
$code = rand(1, 999);

		if(isset($_POST['reg_login']) && isset($_POST['password']) && isset($_POST['email'])){		//	001
			$login = $_POST['reg_login'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			
			if(strlen($login)<2 || strlen($login)>20){
				echo "Логин должен содержать от 2 до 20 символов";
				echo REFRESH_3;
				exit;
			}
			if (strlen($password)<6 || strlen($password)>15){
				unset($login);
				unset($password);
				echo "Пароль должен быть не менее 6 и не более 15 символов <br>";
				echo REFRESH_3;
				exit;
			}
			if(preg_match("/[^a-zA-Z0-9_-]/", $password)){
				echo "Пароль содержит запрещённые символы"; 
				echo REFRESH_3;
				exit;
			}

			$query = "SELECT `login` FROM `users` WHERE `login`='$login'";
			$check = $connect->query($query);
			$checkrez = $check->fetch_array(MYSQLI_BOTH);

// ========  ПРОВЕРЯЕМ ПОЧТУ ИЗ ЧЁРНОГО СПИСКА	 =====
			$query = $connect->query("SELECT `email` FROM `black_mail` WHERE `email` = '$email' ");
			$arr = $query->fetch_array(MYSQLI_ASSOC);
			if(!empty($arr['email'])){
				echo "Ваша почта в чёрном списке";
				echo REFRESH_3;
				echo "</div></div>";
				require '../foot.php';
				exit;
			}



				/* Если логин свободен */
			if(empty($checkrez['login'])){
				echo "Логин $login свободен.";

				mail("$email", "Регистрация на сайте mistor.su", "Добрый день, мотобрат!\nДля завершения регистрации на сайте, подтвердите Ваши данные:\nЛогин - $login\nПароль - $password\nКод для подтвеждения $code", "From: admin@mistor.su");
?>		<h4>Вам на электронную почту было отправлено письмо</h4>
		<form method = "post">
			<label>Введите код подтверждения </label>
			<input type="text" name="code_proof" required>
			<input type="hidden" name="login_prooved" value="<?=$login?>">
			<input type="hidden" name="password" value="<?=$password?>">
			<input type="hidden" name="email_prooved" value="<?=$email?>">   
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="submit" name="" value="Подтвердить" class="button">
		</form>	
<?			}
			else{
				echo "Логин $login занят, пожалуйста придумайте другой"; 
				echo REFRESH_3;
				echo "<br><br>";
			}	

?>				

<?		}	//	001	
		else{	//	002 		
			if(!isset($_POST['login_prooved'])){	//	006			?>


<form method="post" name = "form_reg">

	<table id="tab_reg" name = "tab_reg">
		<tr>
			<td width="300px">Придумайте логин</td>
			<td>	<input id = "reg_login" type="text" name="reg_login" size="20" required placeholder="2 - 20 символов" onchange = "regLogin()">	</td> <!-- reg-login - потому, что был конфликт в файле head.php  -->
		</tr>

		<tr>
			<td>Придумайте пароль</td>
			<td>	<input id = "reg_pas" type="password" name="password" size="20" required placeholder = "6 - 15 символов" onchange = "regPas()">	</td>
		</tr>
		
		<tr>
			<td>Введите адрес эл. почты<br>	</td>
			<td><input type="text" size = "35" name="email" id = "email" required placeholder="на него будет отправлено письмо с кодом" onchange="checkMail()"> </td>
		</tr>
		<tr>
			<td></td><td><p></p></td>
		</tr>

		<tr>
			<td></td>
			<td>	<input id = "reg_but" type="submit" value="Регистрация" class="button">	</td>
		</tr>
	</table> 
</form>

<?			}	//	006
		}	//	002		
if(isset($_POST['code']) AND isset($_POST['code_proof'])){  //  003
	if($_POST['code'] == $_POST['code_proof']){  //  004

		if (isset($_POST['login_prooved']) AND isset($_POST['password']) AND isset($_POST['email_prooved'])){   //  005
			$login = $_POST['login_prooved'];
			$password = $_POST['password'];
			$email_p = $_POST['email_prooved'];

			$login = trim($login);
			$password = trim($password);

			$md5password = md5($password);


				$query = "SELECT `login` FROM `users` WHERE `login`='$login'";
				$check = $connect->query($query);
				$checkrez = $check->fetch_array(MYSQLI_BOTH);

				/* Если логин свободен */
				if(empty($checkrez['login'])){

					$query_ins = "INSERT INTO `users` (`user_id`, `login`, `password`, `email`) VALUES (NULL, '$login', '$md5password', '$email_p')";
					if($connect->query($query_ins)){
						echo "Вы успешно зарегистрированы<br><br>";
						$_SESSION['login'] = $login;
						$query_id = $connect->query("SELECT `user_id` FROM `users` WHERE `login` = '$login' ");
						$arr = $query_id->fetch_array(MYSQLI_ASSOC);
						$user_id = $arr['user_id'];
						$_SESSION['user_id'] = $user_id;

						if(!is_dir("../user_data/$user_id")){
							mkdir("../user_data/$user_id", 0777);	/* Создаем папку с именем пользователя*/
						}	
						echo "<meta http-equiv=\"refresh\" content=\"3;".PATH."/mypage/\">";
					}	
					else{
						echo "Регистрация не удалась";
						echo $connect->error;
						header('Refresh:7; URL=index.php');
						exit();
					}
				}
					/* Если логин занят */
				else{
					echo "Логин $login занят, придумайте другой";
					echo REFRESH_3;
				}
		}  //  005
	}   //  004
	else{
		echo "Введёный Вами код не верный";
		echo REFRESH_3;
	}
}   //  003

?>		

			<br>
			<a href="../main/"><div class="button noreg">Вернуться без регистрации</div></a>
		</div> <!-- mainplacetext -->
	</div> <!-- mainplace -->

<? require '../foot.php';  ?>