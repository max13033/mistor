<? 
session_start();

$pagetitle = "Техподдержка";

require '../head.php';  

error_reporting(E_ALL);

if(isset($_POST['name'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$text = $_POST['message'];

	mail("admin@mistor.su", "Письмо в техподдержку", "$text", "From: $email");
	echo '<div class="mainplace">
			<div class ="mainplacetext">
				<h3> Сообщение успешно отправлено</h3>
			</div>
		</div>';
	require	'../foot.php';		
	echo REFRESH_3;
	exit;
}



?>
	<div class="mainplace">
		<div class ="mainplacetext">
			<h3> Техподдержка</h3>
			<p style = "text-align: justify;">
				Если вы заметили шибку в работе сайта - пишите в форму ниже, буду устранять. 
				Также, если у Вас есть пожелания к сайту - тоже пишите, рассмотрю все предложения и пожелания.
				Если в процессе заполнения данных о мотоцикле Вы не нашли в списке марку или модель Вашего мотоцикла - 
				пишите сюда, после проверки недостающие марки и модели будут добавлены на сайт.
			</p>
			<form method = "post" >
				<table class = "teh">
					<tr>
						<td>Ваше имя или логин</td>
						<td>	<input type = "text" name = "name" required>	</td>
					</tr>
					<tr>
						<td>Ваша почта</td>
						<td>	<input type = "text" name = "email" required>  </td>
					</tr>
					<tr>
						<td>Текст сообщения</td>
						<td>	<textarea name = "message" cols = "60" required>	</textarea>		</td>
					</tr>
					<tr>	<td>	</td>	<td>	<input type = "submit" value = "Отправить" class = "button">	</td>
					</tr>
				</table>
			</form>	
			<h4>Часто задаваемые вопросы</h4>



		</div>			
	</div>


<? require '../foot.php';  ?>