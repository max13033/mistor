<? 
session_start();
$pagetitle = "Владелец";

require '../head.php';  

error_reporting(E_ALL);

if(isset($_GET['owner_id'])){  // 01
	$owner_id = $_GET['owner_id'];
	$query = $connect->query("SELECT * FROM `users` WHERE `user_id` = '$owner_id' ");
	$arr = $query->fetch_array(MYSQLI_BOTH);
	$name = $arr['name'];
	$lastname = $arr['lastname'];
	$avatar = $arr['avatar'];
	$age = $arr['age'];
	$gender = $arr['gender'];
	$region = $arr['region'];
	$city = $arr['city'];
	$tel = "-";
	if($arr['show_tel'] != 0){
		$tel = $arr['telephone'];
	}
if(isset($_POST['message'])){
	$message = $_POST['message'];
	$sender = $_POST['sender'];
	$receiver = $_POST['receiver'];
	$date = strval(date("Y-m-d"));
	$connect->query("INSERT INTO message (`message_id`, `sender`, `receiver`, `mes_text`, `date`) VALUES (NULL, '$sender', '$receiver', '$message', '$date') ");
	echo REFRESH;
}
?>
<div class="mainplace">
	<div class ="mainplacetext">

		<div class = "owner_info">      <!--        ===================      ИНФОРМАЦИЯ О ВЛАДЕЛЬЦЕ    ============================    -->
			<img class = "owner" src = "../user_data/<?=$owner_id.'/'.$avatar?>">

			<table class = "owner" >
				<tr>
					<td>Имя</td><td><?=$name?></td>
				</tr>
				<tr>
					<td>Фамилия</td><td><?=$lastname?></td>
				</tr>
				<tr>
					<td>Регион</td><td><?=$region?></td>
				</tr> 
				<tr>
					<td>Город</td><td><?=$city?></td>
				</tr>
				<tr>
					<td>Пол</td><td><?=$gender?></td>
				</tr>
				<tr>
					<td>Возраст</td><td><?=$age?></td>
				</tr>
				<tr>
					<td>Телефон</td><td><?=$tel?></td>
				</tr>

<? 				if(isset($_SESSION['user_id']) AND $_SESSION['user_id'] != $owner_id){  			?>		
				<tr>
					<td>Написать<br>сообщение</td>
					<td>
						<form method="post">
							<textarea name = "message" cols="40" style="max-width: 300px; max-height: 200px;"></textarea>	<br>
							<input type = "hidden" name = "sender" value = "<?=$_SESSION['user_id']?>">
							<input type = "hidden" name = "receiver" value = "<?=$owner_id?>">
							<input type = "submit" value = "Отправить" class = "button">
						</form>	
					</td>
				</tr>
<?				} 					?>
			</table>
		</div>




		
<?			$query = $connect->query("SELECT * FROM `moto` WHERE `user_id` = '$owner_id' ");          
			$num_moto = $query->num_rows; 			?>
		<div class = "owner_moto">    <!--        ===================      ИНФОРМАЦИЯ О МОТОЦИКЛЕ   ============================    --> 
			<h3><? echo ($num_moto>1)?'Мотоциклы':'Мотоцикл';?></h3>
			<table class = "owner_moto">		
<?
			for($i = 1; $i <= $num_moto; $i++){ 	// 	02
				$arr = $query->fetch_array(MYSQLI_BOTH);
				$moto_id = $arr['moto_id'];
				$brand = $arr['brand'];
				$model = $arr['model'];
				$year = $arr['year'];
				$from = $arr['owner_from'];
				$to = $arr['owner_to'];
				if($arr['moto_pic'] == '0'){
					$motopic = "../user_data/nomotopic.jpg";
				}	
				else{
					$motopic = '../user_data/'.$owner_id.'/'.$moto_id.'/'.$arr['moto_pic'];
				}	

?>					
				<tr>
					<td>
						<a href="../moto/index.php?moto_id=<?=$moto_id?>&owner_id=<?=$owner_id?>" >
							<img class = "rezimg" src = "<?=$motopic?>" >  
						</a>	
					</td>
					<td>
						<a href = "../moto/index.php?moto_id=<?=$moto_id?>&owner_id=<?=$owner_id?>" >
	<?						echo $brand.'&nbsp&nbsp&nbsp'.$model.'&nbsp&nbsp&nbsp'.$year.'<br>';
							echo 'Период владения:&nbsp&nbsp&nbsp<font style = "font-size: 12pt;">'.$from.'</font><br>';
							echo '<font style = "color: black;">Период владения:&nbsp&nbsp&nbsp<font style = "font-size: 12pt;">'.$to.'</font>';
?>						</a>
					</td>
				</tr>	
<?			}	// 	02			?>				
			</table>
		</div>	
	</div>
</div>

<? 
} 	// 	01
else{
	echo "<h2>Данные о пользователе отсутствуют</h2>";
	echo '<meta http-equiv = "refresh" content = "3, url = ../search/">';
}
require '../foot.php';  ?>