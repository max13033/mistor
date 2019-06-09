<?
session_start(); 
$pagetitle = "История мото";

require '../head.php';  

error_reporting(E_ALL);?>
	<div class="mainplace">
		<div class ="mainplacetext">
			
<?
if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
}
if(isset($_POST['moto_id'])){ 	//  GET['moto_id']
	$moto_id = $_POST['moto_id'];
	$_SESSION['moto_id'] = $moto_id;
}

if(!isset($moto_id) AND isset($_SESSION['moto_id'])){
	$moto_id = $_SESSION['moto_id'];
}

if(isset($moto_id)){ 	//	05
	echo "<h3>Добавьте событие</h3>";

	if(isset($_GET['del_story'])){   //   УДАЛЯЕМ ИСТОРИЮ
		$del_story_id = $_GET['del_story'];
		del_story($del_story_id, $moto_id);
		echo '<meta http-equiv = "refresh" content = "0; url = index.php">';
	}	

	$q_st_id = "SELECT `story_id` FROM `story` WHERE `moto_id` = '$moto_id'";
	$num_story_q = $connect->query($q_st_id);
	$num_story = $num_story_q->num_rows;

	if (isset($_POST['story_id'])) {
		$story_id = $_POST['story_id'];
	}

	if(isset($_POST['moto_id']) && isset($_POST['date_1']) && isset($_POST['story_name_1']) && isset($_POST['run_1'])){	//	06    ========  ПЕРВИЧНАЯ ЗАПИСЬ   ===========
		$moto_id = $_POST['moto_id'];
		$date = $_POST['date_1'];
		$story_name = trim($_POST['story_name_1']);
		$story_name = preg_replace("/[\\\'\"\/]/", "", $story_name);
		$run = $_POST['run_1'];
		$posted = strval(date("Y-m-d"));

		$q_ins = "INSERT INTO `story` (`story_id`, `date`, `story_name`, `run`, `posted`, `moto_id`) VALUES (NULL, '$date', '$story_name', '$run', '$posted', '$moto_id')";
		$connect->query($q_ins);

	// 						===================  max  story_id  ===================
		$q_max_t = "SELECT MAX(`story_id`) FROM `story` WHERE `moto_id` = '$moto_id' ";
		$max_st_id = $connect->query($q_max_t);
		$arr = $max_st_id->fetch_array(MYSQLI_NUM);
		$story_id = $arr[0];

	// 						=================== /  max  story_id  ===================

		if(isset($_FILES['story'])){

			foreach ($_FILES['story']['tmp_name'] as $num => $story) {
				$f_name = $_FILES['story']['name'][$num];
				$f_type = photo_type($f_name);
				move_uploaded_file($_FILES['story']['tmp_name'][$num], "../user_data/$user_id/$moto_id/$story_id-$num$f_type");

				$insert = "INSERT INTO `picture` (`pic_id`, `pic_name`, `story_id`) 
							VALUES (NULL, '../user_data/$user_id/$moto_id/$story_id-$num$f_type', '$story_id')";      
				$connect->query($insert);  
			}
		}

	}	//	06   ========  / ПЕРВИЧНАЯ ЗАПИСЬ   ===========

	if(isset($_POST['moto_id']) && isset($_POST['date']) && isset($_POST['story_name']) && isset($_POST['run'])){	//	01 	=======   ВТОРИЧНАЯ ЗАПИСЬ	============
		$moto_id = $_POST['moto_id'];
		$date = $_POST['date'];
		$story_name = trim($_POST['story_name']);
		$story_name = preg_replace("/[\\\'\"\/]/", "", $story_name);
		$run = $_POST['run'];

	//						=====================  ДЕЛАЕМ ВЫБОРКУ КАРТИНОК МОТО  ==========================		
		$select = "SELECT `pic_id` FROM `picture` WHERE `story_id` = '$story_id' ";
		$sel_rez = $connect->query($select);
		$num_pic = $sel_rez->num_rows;

		for($i = 1; $i <= $num_pic; $i++){
			$arr_pic_id = $sel_rez->fetch_array(MYSQLI_NUM);    //   

			foreach($arr_pic_id as $value) {
				$text = trim($_POST[$value]);
				$text = preg_replace("/[\\\'\"\/]/", "", $text);
				$in_query = "UPDATE `picture` SET `pic_text` = '$text' WHERE `pic_id` = '$value' ";
				if(!$connect->query($in_query)){
					echo "Ошибка $connect->error";
				}
			}
		}	
		$q_upd = "UPDATE `story` SET `date` = '$date', `story_name` = '$story_name', `run` = '$run' WHERE `story_id` = '$story_id' ";
		$connect->query($q_upd);

		//	УДАЛЁННЫЕ ФОТО
		if(isset($_POST['del_photo'])){
			foreach ($_POST['del_photo'] as $value) {
				del_pic($value); 
			}
		}
		if(isset($_FILES['story_add']) AND is_uploaded_file($_FILES['story_add']['tmp_name'][0]) ){ 	//	ДОБАВЛЯЕМ ФОТО ПРИ КОРРЕКТИРОВКЕ

			$max_sel = $connect->query("SELECT MAX(pic_id) FROM `picture` WHERE `story_id` = '$story_id' ");  //  НАХОДИМ КАРТИНКУ С МАКСИТМАЛЬНЫМ ID ЗАТЕМ ИЗ ЕЁ НАЗВАНИЯ 
			$arr_max = $max_sel->fetch_array(MYSQLI_NUM);													//  ИЗВЛЕКАЕМ ПОСЛЕДНИЙ ПОРЯДКОВЫЙ НОМЕР КАРТИНКИ
			$max_pic = $arr_max[0];
			$q_text = $connect->query("SELECT `pic_name` FROM `picture` WHERE `pic_id` = '$max_pic' ");
			$arr_t = $q_text->fetch_array(MYSQLI_BOTH);
			$pic_t = $arr_t['pic_name'];
			$cut = strstr($pic_t,"-");
			$ar_put = array("-" => "", ".jpg" => "", ".jpeg" => "", ".png" => "", ".gif" => "");     
			$max_pic_num = strtr($cut, $ar_put);

			foreach ($_FILES['story_add']['tmp_name'] as $num => $story) {
				$f_name2 = $_FILES['story_add']['name'][$num];
				$f_type2 = photo_type($f_name2);
				$num2 = $max_pic_num + $num + 1; 	//		нумерация добавляемых фото должна начинаться после уже сохранённых фото + 1 (так как нумерация с нуля)
				move_uploaded_file($_FILES['story_add']['tmp_name'][$num], "../user_data/$user_id/$moto_id/$story_id-$num2$f_type2");

				$insert = "INSERT INTO `picture` (`pic_id`, `pic_name`, `story_id`) 
							VALUES (NULL, '../user_data/$user_id/$moto_id/$story_id-$num2$f_type2', '$story_id')";      
				$connect->query($insert);  
			}
		}

	}	//	01

		

	if(!isset($_POST['date_1'])){	//	03 	ПУСТАЯ ФОРМА
?>
				<form method="post" enctype="multipart/form-data">
					<table class="tab_mystory_new">
						<tr>
							<td style="width: 200px;">Укажите дату</td>
							<td>	
								<input type = "hidden" name = "moto_id" value = "<?=$moto_id?>">
								<input type="date" name="date_1" required>	
							</td>
						</tr>

						<tr>
							<td>Название события</td>
							<td> 
								<textarea name = "story_name_1" cols = "50" rows = "1">		</textarea>
							</td>
						</tr>

						<tr>
							<td>Пробег мото</td>
							<td>	<input type="number" name="run_1" required>	</td>
						</tr>

						<tr>
							<td>Добавьте фотографии</td>
							<td>	<input type="file" name="story[]" multiple accept = "image/jpeg, image/png, image/gif" required>   
							</td>
						</tr>
					
						<tr>	<td></td>	<td>	<p></p>	</td>	</tr>

						<tr>
							<td></td>	<td> <input type = "submit" value="Продолжить" class="button"> </td>
						</tr>
					</table>
				</form>
<?	}	//	03	
	else{		//	02	 ЗАПОЛНЕННАЯ ФОРМА    			

		if(isset($_POST['correct_story'])){				// 			получаем данные для корректировки
			$story_id = $_POST['story_id'];
			$date = $_POST['date_1'];
			$story_name = trim($_POST['story_name']);
			$run = $_POST['run'];
		}	
								?>

				<form method="post" enctype="multipart/form-data">
					<table class="tab_mystory_new">
						<tr>
							<td style="width: 200px;">Дата</td>
							<td>	
								<input type = "hidden" name = "moto_id" value = "<?=$moto_id?>">
								<input type = "hidden" name = "story_id" value = "<?=$story_id?>">
								<input type="date" name="date" <?if(isset($date)) echo 'value = '.$date;?> required>	
							</td>
						</tr>

						<tr>
							<td>Название события</td>
							<td> 
								<textarea name = "story_name" cols = "50" rows = "1">
<?								if(isset($story_name)) echo $story_name;					?>
								</textarea>
							</td>
						</tr>

						<tr>
							<td>Пробег мото</td>
							<td>	<input type="number" name="run" <?if(isset($run)) echo 'value = '.$run;?> >	</td>
						</tr>

<?			$select = "SELECT * FROM `picture` WHERE `story_id` = '$story_id' ";
			$sel_rez = $connect->query($select);

			$num_pic = $sel_rez->num_rows;

			for($i = 1; $i <= $num_pic; $i++){
			
				$arr_s = $sel_rez->fetch_array(MYSQLI_BOTH);
				$pic_id = $arr_s['pic_id'];
				$pic_name = $arr_s['pic_name'];
				$pic_text = $arr_s['pic_text']
?>
						<tr>
							<td>  <img class = "story_pic_ins" src = " <?=$pic_name; ?> "> </td>
							<td>	
								<textarea cols = "60" rows = "3" name = "<?=$pic_id;?>"><? if(isset($pic_text)) echo "$pic_text"; ?></textarea>	
								
								<label for = "checkbox<?=$pic_id?>" style = "cursor: pointer;"> &nbsp; Удалить фото &nbsp; </label>
								<input type = "checkbox" name = "del_photo[]" id = "checkbox<?=$pic_id?>" value = "<?=$pic_id?>"> 
							</td>
						</tr>
<?			}			?>
						<tr>
							<td>Добавить ещё фотографии</td>
							<td>	<input type="file" name="story_add[]" multiple accept = "image/jpeg, image/png, image/gif">   </td>
						</tr>

						<tr><td></td><td><p></p></td></tr>

						<tr>
							<td></td>	<td> <input type = "submit" value="Сохранить" class="button"> </td>
						</tr>
					</table>
				</form>
<?	} 	//	02		?>
				


				

<?
	$query_stories = "SELECT * FROM `story` WHERE `moto_id` = '$moto_id' ORDER BY `date` DESC";
	$q_rez = $connect->query($query_stories);


	if(isset($num_story) && $num_story != 0){	//	04		
		if(!isset($_POST['date_1'])){					?>

			<h3>Ваши истории</h3>

			<table class="mystory">
				<tr>
					<td style="width: 30px;" >№</td>
					<td style="width: 140px;" >Дата</td>
					<td style="width: 400px;">Название</td>
					<td style="width: 100px;">Пробег</td>
					<td style="width: 100px;"> </td>
				</tr>	
<?
			for($i = $num_story; $i >= 1; $i--){
				$arr = $q_rez->fetch_array(MYSQLI_BOTH);	

				$story_id = $arr['story_id'];
				$date = $arr['date'];	
				$story_name = $arr['story_name'];
				$run = $arr['run'];								?>

				<tr>
					<td><?=$i?></td>
					<td><?=$date?></td>
					<td><?=$story_name?></td>
					<td><?=$run?></td>
					<td>	
						<form method="post">                					<!--    	ОТПРАВЛЯЕМ ДАННЫЕ ДЛЯ КОРРЕКТИРОВКИ ИСТОРИИ     -->
							<input type="hidden" name="correct_story" value="1">
							<input type="hidden" name="story_id" value = "<?=$story_id?>">
							<input type="hidden" name="date_1" value = "<?=$date?>">
							<input type="hidden" name="story_name" value = "<?=$story_name?>">
							<input type="hidden" name="run" value = "<?=$run?>">
							<input class="button" style="margin: 5px;" type = "submit" value = "Редак-ть">	
						</form>

						<div class="button" style="margin: 5px; padding-top: 5px;" onclick = "document.getElementById('<?=$i?>').style.display = 'table-cell'; ">Удалить</div> 
					</td>
				</tr>
				<tr>
					<td colspan="5" id = "<?=$i?>" style = "display: none;">
						Вы действительно хотите удалить историю № <?=$i?>? <a style = "background-color: red; border-radius: 2px; color: #000;" href="index.php?del_story=<?=$story_id;?>" >&nbsp; Да &nbsp;</a>
						<font style = "background-color: #39e600; color: #000; border-radius: 2px; cursor: pointer;" onclick = "document.getElementById('<?=$i?>').style.display = 'none'"> &nbsp; Отмена &nbsp;</font>
					</td>
				</tr>	
<?		
			}	
			echo '</table>';
		}
	}		//	04
	else 	echo "<h3>У Вас пока нет историй</h3>";
		
}	//	05  if(isset($moto_id))
else echo "<h3>Не выбран мотоцикл!</h3> Зайдите в раздел \"Гараж\" и выберите мотоцикл.";
?>
		</div>
	</div>

<? require '../foot.php';  ?>