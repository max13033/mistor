<? 
session_start();
$pagetitle = "Гараж";
require "../head.php" ?>

<div class="mainplace">
	<div class ="mainplacetext">
<?
if(isset($_POST['delete_user'])){
	del_user($user_id);
}

if(isset($_SESSION['login'])){  //  001
	$login = $_SESSION['login'];
	$user_id = $_SESSION['user_id'];

	if(isset($_POST['name'])){
		$name = $_POST['name'];
		$query_update = "UPDATE `users` SET `name` = '$name'";

		if(isset($_POST['lastname'])){
			$lastname = $_POST['lastname'];
			$query_update .= ", `lastname` = '$lastname'";
		}
		if(isset($_POST['region'])){
			$region = $_POST['region'];
			$query_update .= ", `region` = '$region'";	
		}
		if(isset($_POST['newcity']) AND !empty($_POST['newcity'])){   // ЕСЛИ ЗАДАН НОВЫЙ ГОРОД - ЗАПИСЫВАЕМ ЕГО В СПЕЦИАЛЬНУЮ ТАБЛИЦУ
			$newcity = $_POST['newcity'];
			$query_update .= ", `city` = '$newcity'";
			$connect->query("INSERT INTO `newcity` (newcity_id, newcity, region, user_id) VALUES (NULL, '$newcity', '$region', '$user_id') " );
		}
		else{
			$city = $_POST['city'];
			$query_update .= ", `city` = '$city'";
		}
		if(isset($_POST['gender'])){
			$gender = $_POST['gender'];
			$query_update .= ", `gender` = '$gender'";
		}
		if(isset($_POST['age'])){
			$age = $_POST['age'];
			$query_update .= ", `age` = '$age'";
		}
		if(isset($_POST['telephone'])){
			$telephone = $_POST['telephone'];
			$tel = preg_match('/^[0-9]{0,11}$/', $telephone);	//	проверяем номер телефона, должны быть только цифры
			if($tel){
				$query_update .= ", `telephone` = '$telephone'";
			}	
		}
		if(isset($_POST['show_tel'])){
			$show_tel = $_POST['show_tel'];
			$query_update .= ", `show_tel` = '$show_tel'";
		}
		$query_update .= " WHERE `login` = '$login'";
		// echo $query_update;
		// if($connect->query($query_update)){
		// 	echo "<h3>Ваши данные</h3>"; 
		// }
		// else{echo "not OK";}
		$connect->query($query_update);
	}




	$query_select = "SELECT * from `users` WHERE user_id ='$user_id'";
	$get = $connect->query($query_select);
	$getrez = $get->fetch_array(MYSQLI_ASSOC);
	$name = $getrez['name'];
	$lastname = $getrez['lastname'];
	$region = $getrez['region'];
	$city = $getrez['city'];
	$gender = $getrez['gender'];
	$age = $getrez['age'];
	$email = $getrez['email'];
	$telephone = $getrez['telephone'];
	$show_tel = $getrez['show_tel'];


	if(empty($name) OR (isset($_POST['update_user'])) OR isset($_POST['region1'])){	//	002   нет имени или (обновление и задан регион) ?>	

		<h3 align = "center">Укажите ваши данные </h3>

		<table class = "userdata">  <!-- выбираем регион -->
			<tr>
				<td>Выберите регион</td>
				<td>
					<form method="post">	
						<select name = "region1" size = "1" required >
<?						$query_reg = "SELECT * FROM `region`";	
						$query_rez = $connect->query($query_reg);
						$reg_num = mysqli_num_rows($query_rez);
						
						for($i = 1; $i <= $reg_num; $i++ ){
							$arr_reg = $query_rez->fetch_array(MYSQLI_BOTH);
							
							if(isset($region) AND $arr_reg['region'] == $region AND !isset($_POST['region1'])){
								$selected = 'selected';
							}
							elseif(isset($_POST['region1']) AND $arr_reg['region'] == $_POST['region1']){
								$selected = 'selected';
							}
							else{$selected = "";}


							echo '<option value="'.$arr_reg['region']."\" $selected >".$arr_reg['region_id']."&nbsp&nbsp".$arr_reg['region'].'</option>';
						}

?>		
	 					</select>
			 			<input type="submit" style = "cursor: pointer;" value="Далее">
		 			</form>
		 		</td>
		 	</tr>
			
			<form method = "post">
				<input type="hidden" name="region" value="<? echo (isset($_POST['region1']))?$_POST['region1']:$region?>">
				<tr>
					<td>Выберите город</td>
	 				<td>
<?						if(isset($_POST['region1'])){ 
?>	
		 					<select name = "city" size = "1" onchange = "var city = this.value; checkCity(city);" required>
<?
							$region = $_POST['region1'];
							$query_r_id = "SELECT `region_id` FROM `region` WHERE region = '$region'";
							$r_id_res = $connect->query($query_r_id);
							$arr_r_id = $r_id_res->fetch_array(MYSQLI_BOTH);
							$reg_id = $arr_r_id['region_id'];
							$query_city = "SELECT `city` FROM `city` WHERE `region_id` = $reg_id ORDER BY `city`";
							$city_res = $connect->query($query_city);
							$city_num = mysqli_num_rows($city_res);

							for($i = 1; $i <= $city_num; $i++){
								$arr_city = $city_res->fetch_array(MYSQLI_BOTH);	?>
								<option value="<?=$arr_city['city'];?>" <? if(isset($city) AND ($city == $arr_city['city'])){ echo "selected"; }?> > <?=$arr_city['city'];?> </option>	
<?								}

?>									<option value = "">Другой..</option>
								</select>
								<input type="text" id = "newcity" name = "newcity" value = "" style="display: none;">
						
<?						}
						else echo "<font style = \"color: red; font-size: 14pt;\">Регион не выбран</font> ";
?>

						
					</td>
				</tr>

				<tr>
					<td width="300px;">Введите имя</td>
					<td><input type = "text" name = "name" required <? if(isset($name)){ echo 'value ="'.$name.'"';} ?> ></td>
				</tr>

				<tr>
					<td>Введите фамилию </td>
					<td><input type="text" name="lastname" required <? if(isset($lastname)){ echo 'value ="'.$lastname.'"';} ?> > <font size="2pt">(необязательное поле)</font></td>
				</tr>

			 	<tr>
			 		<td>Пол</td>
			 		<td>Муж<input type="radio" required name = "gender" value="М" <? if(isset($gender) AND $gender == "М")echo "checked"?> >&nbsp
			 			Жен<input type="radio" name = "gender" value="Ж" <? if(isset($gender) AND $gender == "Ж")echo "checked"?>></td>
			 	</tr>
			 	<tr>
			 		<td>Возраст</td>
			 		<td>
			 			<select name = "age" style="width: 80px;">
<?							$i = 18;
							while ($i <= 100) {		?>
							<option value = "<?=$i?>" <? if(isset($age) AND $age == $i){ echo "selected";} ?> >&nbsp;&nbsp; <?=$i?> </option>								
<?							$i++;
							}						?>			 				
			 			</select>
			 		</td>
			 	</tr>
			 	<tr>
			 		<td>Номер телефона</td>
			 		<td><input type = "text" name = "telephone"  id = "telephone"<? if(isset($telephone)){ echo 'value ="'.$telephone.'"';} ?>  onblur = "checkTel()">
			 			&nbsp;<input type="checkbox" name="show_tel" value="0">	<font size="2pt">не показывать на сайте</font></td>
			 	</tr>
			 	
			 	<tr>	<td><p></p></td><td></td></tr>
			 	<tr>
			 		<td></td>	<td><input type="submit" class= "button" value ="Сохранить"></td>
			 	</tr>
			 </form>
		</table>
		<br><br>
	 	
<?
	}	//	002
	else {		?>
	<h3 align = "center">Ваши данные </h3>
		<table id = "userdatafilled" cellpadding = "0 10 0 20">
			<tr>
				<td>Ваш регион</td>
				<td><?=$region?></td>
			</tr>
			<tr>
				<td>Ваш город</td>
				<td><?=$city?></td>
			</tr>
			<tr>
				<td>Ваше имя</td>
				<td><?=$name?></td>
			</tr>	
			<tr>
				<td>Ваша фамилия</td>
				<td><?=$lastname?></td>
			</tr>
			<tr>
				<td>Ваш пол</td>
				<td><?=$gender?></td>
			</tr>
			<tr>
				<td>Ваш возраст</td>
				<td><?=$age?></td>
			</tr>
			<tr>
				<td>Ваш номер телефона</td>
				<td><?=$telephone?></td>
			</tr>
			<tr><td></td><td><p></p></td></tr>

			<tr>
				<td>
					<input type="button" class= "button" value="Удалить профиль" onclick = "document.getElementById('del_user').style.display = 'block' " >
					<div id = "del_user" style = "display: none; position: relative; top: -30px; right: -5px; background-color: #000">
						<font style = "color: red;"> Подтвердите удаление<br> своего профиля</font>	<br>
						
						<form method="POST">
							<input type="button" class= "button" value = "Отмена" onclick = "document.getElementById('del_user').style.display = 'none' ">&nbsp;&nbsp;
							<input type="hidden" name="delete_user" value="1">
							<input type="submit" class= "button" value="Удалить">
						</form>					
					</div>	
				</td>
				<td>
					<form method="POST">
						<input type="hidden" name="update_user" value="1">
						<input type="submit" class= "button" value="Редактировать">
					</form>
				</td>
			</tr>
		</table>
		<br><br>
<?
	}
}	//001
else{	//  login не введён
	echo "Чтобы иметь доступ к личному кабинету - авторизируйтесь или зарегистрируйтесь на сайте";
}
//  ------------------------------------------------- Раздел мото ------------------------------------


if(!empty($name)){	//	003 	 если задано имя
	//		запрашиваем из базы 

	if(isset($_GET['brand'], $_GET['model'], $_GET['year'])){
		$brand = $_GET['brand'];
		$model = $_GET['model'];
		$year = $_GET['year'];
		$owner_from = $_GET['owner_from_m'].$_GET['owner_from_y'];
		if($_GET['owner_to_m'] == "Настоящее время"){
			$owner_to = $_GET['owner_to_m'];
		}
		else $owner_to = $_GET['owner_to_m'].$_GET['owner_to_y'];
		if(isset($_GET['vin'])){
			$pattern = "/^[\w]{17}$/";
			if(preg_match($pattern, $_GET['vin'])){
				$vin = $_GET['vin'];
			}
			else{
				$vin = "";
			}
		}
		else $vin = "";

		//echo "vin = ".$vin;

		if(!isset($_GET['moto_id'])){
			$query_insert = "INSERT INTO `moto` (`moto_id`, `brand`, `model`, `year`, `vin`, `owner_from`, `owner_to`, `user_id`) VALUES (NULL, '$brand', '$model', '$year', '$vin', '$owner_from', '$owner_to', '$user_id')";
			$connect->query($query_insert);
			// echo "insert OK";
		}
		else{
			$moto_id = $_GET['moto_id'];
			$query_update = "UPDATE `moto` SET `brand` = '$brand', `model` = '$model', `year` = '$year', `vin` = '$vin', `owner_from` = '$owner_from', `owner_to` = '$owner_to' WHERE `moto_id` = '$moto_id'";
			$connect->query($query_update);
			// echo "update";
		}
		echo '<meta http-equiv = "refresh" content = "0; URL = index.php">';
	}
	if(isset($_POST['delmoto2'])){ 		//  код удаления мотоцикла из базы. Стоит перед циклом

		$moto_id = $_POST['moto_id'];

		del_moto($moto_id);
	}

	$query_moto = $connect->query("SELECT * from `moto` WHERE user_id ='$user_id'");
	$moto_amount = $query_moto->num_rows;

	if($moto_amount == 0 OR isset($_GET['moto_amount'])) $moto_amount++; //	увеличивает количество при записи первого мота

	for($moto_num = 1; $moto_num <= $moto_amount; $moto_num++){		//	007		запускаем цикл для каждого мото

		$motorez = $query_moto->fetch_array(MYSQLI_BOTH);

		$moto_id = $motorez['moto_id'];
		$brand = $motorez['brand'];
		$model = $motorez['model'];
		$year = $motorez['year'];
		$vin = $motorez['vin'];
		$owner_from = $motorez['owner_from'];
		$owner_to = $motorez['owner_to'];
		$moto_pic = $motorez['moto_pic'];

		if(!is_dir("../user_data/$user_id/$moto_id")){
			mkdir("../user_data/$user_id/$moto_id", 0777);		
		}
?>
	<div class="moto_cont">    
		<h3 align="center"> <?=(isset($brand)) ? 'Ваш мотоцикл № '.$moto_num : 'Укажите мотоцикл'; ?></h3>	<br>
<?
		if(!empty($brand)){	//		005		Отображаем поле для фото только когда задан брэнд	?>

		<div class ="motopic">
<?	  	
		if($moto_pic == '0'){	//	004   не загружено фото мото
			$motopic = "nomotopic.jpg";	
	?>
			<img class = "motopicimg" src = "../user_data/<?=$motopic?>">
			<form method = "post" action = "index.php" enctype = "multipart/form-data">
				<input type = "file" name="motopicfile" style = "width: 280px;" class="button" required><br>
				<input type="submit" value="Загрузить" class="button" style="margin-top: 15px;">
			</form>
<?
//	загружаем фото мото
				$path = "../user_data/".$user_id."/".$moto_id;
				if($_FILES && $_FILES['motopicfile'] && $_FILES['motopicfile']['tmp_name']){
					if($_FILES['motopicfile']['size'] > 5*1024*1024){  //   ЕСЛИ РАЗМЕР ФАЙЛА ПРЕВЫШАЕТ 5мБ
?>
						<script type="text/javascript">
							alert("Размер картинки превышает 5 Мб.");
						</script>				
<?						echo REFRESH;
						exit; 
					}	
					$f_name = $_FILES['motopicfile']['name'];
					$pic_ras = photo_type($f_name);
					$name = "motopic".$pic_ras;
					move_uploaded_file($_FILES['motopicfile']['tmp_name'], "$path/$name") or die(mysql_error());
					$query_setmoto = "UPDATE `moto` SET `moto_pic` = '$name' WHERE `moto_id` = '$moto_id'";
					$connect->query($query_setmoto);

					echo REFRESH;
					exit();
				} 

		}	//	004		motopic == 0
		else{		//	фото мото загружено

			$pic_name = $connect->query("SELECT `moto_pic` FROM `moto` WHERE `moto_id` = '$moto_id' ");
			$arr = $pic_name->fetch_array(MYSQLI_BOTH);
			$name = $arr['moto_pic'];
			$ras = photo_type($name);
			$motopic = "$user_id/$moto_id/motopic$ras";?>
			<img class = "motopicimg" src="../user_data/<?=$motopic?>">
			<form method="GET">
				<input type="hidden" name="del_moto_photo" value="1">
				<input type="hidden" name="moto_id" value="<?=$moto_id?>"><br>
				<input type="submit" value="Удалить фото" class = "button">
			</form>
<?		
			if(isset($_GET['del_moto_photo']) AND $_GET['moto_id'] == $moto_id){   // 	====================  удаляем фото мото    ========================
				$pic_name = $connect->query("SELECT `moto_pic` FROM `moto` WHERE `moto_id` = '$moto_id' ");
				$arr = $pic_name->fetch_array(MYSQLI_BOTH);
				$name = $arr['moto_pic'];
				$ras = photo_type($name);
				$motopic = '../user_data/'.$user_id.'/'.$moto_id.'/motopic'.$ras;
				if(file_exists($motopic)){
					unlink($motopic);
				}
				$update_motopic = "UPDATE `moto` SET `moto_pic` = '0' WHERE `moto_id` = '$moto_id'";
				$connect->query($update_motopic);

				//  вставить код удаления фото
				echo  REFRESH;
				exit();
			}

		}	?>

		</div>  <!--    Закрыли div motopic   -->
<?		}		//		005		?>		
	<div class="motodata">				<!--	========================	данные о мотоцикле	 	==================================		-->
<?
if( empty($brand) OR (isset($_GET['updatemoto']) AND $_GET['moto_id'] == $moto_id)){	//  брэнд не задан 	или редактирование	?>

			<table id = "motoform" width="350px">
				<tr>
					<td>Бренд</td>
					<td style = "width: 270px;">
						<form method="post">
							<select name="sel_brand" style = "width: 120px">
<?					$query_brand = "SELECT * FROM `brand` ORDER BY `brand`";
					$query_brand_res = $connect->query($query_brand);
					$num_brand = mysqli_num_rows($query_brand_res);

					for($i = 1; $i <= $num_brand; $i++){
						$arr_brand = $query_brand_res->fetch_array(MYSQLI_BOTH);
						$sel_brand = $arr_brand['brand'];	
							?>

						<option value="<?=$sel_brand?>" <?if(isset($_POST['sel_brand']) AND $_POST['sel_brand'] == $sel_brand){echo "selected";}?> ><?=$sel_brand?></option>
<?					}

?>								
								
							</select>
							<input type="hidden" name="moto_num_post" value="<?=$moto_num?>">
							<input type="submit" style = "cursor: pointer;" value="Далее">
						</form>
					</td>
				</tr>

<? 				if(isset($_POST['sel_brand']) AND ($_POST['moto_num_post'] == $moto_num)){		//		008		?>
				
				<tr>
					<td>Модель</td>
					<td>	
						<form method="get">
							<select name="model" style = "width: 120px">
<?								$sel_brand = $_POST['sel_brand'];
								$query_brand_id = "SELECT `brand_id` FROM `brand` WHERE `brand` = '$sel_brand'";
								$query_brand_id_res = $connect->query($query_brand_id);
								$sel_brand_id = $query_brand_id_res->fetch_array(MYSQLI_BOTH);
								$arr_br_id = $sel_brand_id['brand_id'];
								$query_model = "SELECT `model` FROM `model` WHERE `brand_id` = '$arr_br_id' ORDER BY `model`";
								$query_model_res = $connect->query($query_model);
								$model_num = mysqli_num_rows($query_model_res);
								for($i = 1; $i <= $model_num; $i++){	
									$arr_model = $query_model_res->fetch_array(MYSQLI_BOTH);				?>
									<option value="<?=$arr_model['model']?>"><?=$arr_model['model']?></option>
<?								}
								
?>								
							</select>
							<input type="hidden" name="brand" value="<?=$_POST['sel_brand']?>">	
					</td>
				</tr>
<?				}	//	008	
				else{		?>
					<tr>
						<td>Модель</td><td><font style="color: red; font-size: 12pt;"> Бренд не выбран</font></td>
					</tr>
<?				}

?>	
				<tr>
					<td>Год</td>
					<td>
						<select name = "year" style = "width: 120px">
<?							$year = 2018;
							while ($year >= 1970) {	
?>
								<option value="<?=$year?>" <?if(isset($_GET['year']) AND $_GET['year'] == $year){echo "selected";} ?> ><?=$year?></option> 	
<?							$year--;
							}
?>						</select>
					</td>
				</tr>
				<tr>
					<td>VIN</td>
					<td>
						<input type="text" name="vin" <?if(isset($_GET['vin'])){echo 'value = "'.$_GET['vin'].'" ';} ?> placeholder="Необязательное поле" size="20px" onblur="var vin = this.value; checkVin(vin);">
					</td>
				</tr>

				<tr>
					<td>Период владения</td>
					<td>c&nbsp&nbsp; 
						<select name = "owner_from_m" style="width: 100px;">
							<option value = "Январь ">Январь</option>
							<option value = "Февраль ">Февраль</option>
							<option value = "Март ">Март</option>
							<option value = "Апрель ">Апрель</option>
							<option value = "Май ">Май</option>
							<option value = "Июнь ">Июнь</option>
							<option value = "Июль ">Июль</option>
							<option value = "Август ">Август</option>
							<option value = "Сентябрь ">Сентябрь</option>
							<option value = "Октябрь ">Октябрь</option>
							<option value = "Ноябрь ">Ноябрь</option>
							<option value = "Декабрь ">Декабрь</option>
						</select>
						<select name = "owner_from_y" style="width: 60px;">
<?							$y = 2018;					
							while ($y >= 1960) {		?>
							<option value = "<?=$y?>"> <?=$y?> </option>								
<?							$y--;
							}
?>							
						</select><br>
						по
						<select name = "owner_to_m" onchange="owner()">
							<option value = "Настоящее время" selected = "selected">Наст. время</option>
							<option value = "Январь ">Январь</option>
							<option value = "Февраль ">Февраль</option>
							<option value = "Март ">Март</option>
							<option value = "Апрель ">Апрель</option>
							<option value = "Май ">Май</option>
							<option value = "Июнь ">Июнь</option>
							<option value = "Июль ">Июль</option>
							<option value = "Август ">Август</option>
							<option value = "Сентябрь ">Сентябрь</option>
							<option value = "Октябрь ">Октябрь</option>
							<option value = "Ноябрь ">Ноябрь</option>
							<option value = "Декабрь ">Декабрь</option>
						</select>
						<select name = "owner_to_y" style="width: 60px; display: none;">
<?							$y = 2018;					
							while ($y >= 1960) {		?>
							<option value = "<?=$y?>"> <?=$y?> </option>								
<?							$y--;
							}
?>
						</select>
					</td>
				</tr>

				<tr> <td> <p></p> </td> <td> </td> </tr>

				<tr>
					<td>	<a class="button" style="padding: 4px; border: 0px solid #333;" href="index.php">Отмена</a>
							<? if(isset($moto_id)){		?> <input type="hidden" name="moto_id" value="<?=$moto_id?>">	<?	}	?> </td>
					<td>	<input type="submit" value="Сохранить" class = "button" onfocus = "checkModel()">&nbsp
						</form>
					
					</td>
				</tr>
			</table>
		
<?
}						
else{		//		006		брэнд задан 			?>
	
	<table width = "300px">
		<tr>
			<td>Бренд</td>	
			<td><?=$brand;?></td>
		</tr>
		<tr>
			<td>Модель</td>
			<td><?=$model;?></td>
		</tr>
		<tr>
			<td>Год</td>
			<td><?=$year;?></td>
		</tr>
		<tr>
			<td>VIN</td>
			<td><?=$vin?></td>
		</tr>
		<tr>
			<td>Период владения</td>
			<td><font style="font-size: 12pt;"><? echo $owner_from." - <br>".$owner_to; ?> </font></td>
		</tr>
		<tr>
			<td>
				<div class= "button" style="text-align: center; padding-top: 3px;" onclick = "document.getElementById('del_moto_<?=$moto_id?>').style.display = 'block' ;">Удалить</div>
			</td>
			
			<form method="get">
				<td>
					<input type="hidden" name="updatemoto" value="1">
					<input type="hidden" name="moto_id" value="<?=$moto_id?>">
					<input type="hidden" name="year" value="<?=$year?>">
					<input type="hidden" name="vin" value="<?=($vin)?"$vin":'';?>">
					<input type = "submit" value = "Редактировать" class= "button">
				</td>
			</form>
		</tr>
	</table>

	<div id = "del_moto_<?=$moto_id?>" style = "display: none; position: absolute; bottom: -5px; right: 0; background-color: #000; " >
		<font style="color: red;"> Удаление мотоцикла приведёт к удалению истории мотоцикла.<br>
		Всё равно удалить?<br><br>
		</font>

		<div style=" width: 120px; float: left; margin-right: 30px; margin-left: 40px;">
			<form method="post" >
				<input type="hidden" name="delmoto2" value="1">
				<input type="hidden" name="moto_id" value="<?=$moto_id?>">
				<input type="submit" value="Удалить" class="motodatadel"> 
			</form>
		</div>
		
		<div class="motodatacansel" onclick = "document.getElementById('del_moto_<?=$moto_id?>').style.display = 'none' ;">Отмена</div>
	</div>	<br>

	<form method="post" action="../mystory/">
		<input type="hidden" name = "moto_id" value="<?=$moto_id?>">
		<input type="submit" value="История мотоцикла" class="button">
	</form><br>

<?	
}	//		006		?>
		</div>   <!-- motodata-->
	</div>	<!-- moto_cont -->
<?
	}	//	007		?>

		<br>
		<form method="get">
			<input type="hidden" name="moto_amount" value="<?if(isset($moto_amount)) echo $moto_amount;?>">	
			<input type="submit" class="button" value="Добавить мотоцикл">		
		</form>
<?
} //	003		?>		
<br><br>
	</div>	<!--mainplacetext-->
</div>

<?	require '../foot.php';  ?>