<? 
session_start();
$pagetitle = "Поиск";
require "../head.php" ?>

<div class="mainplace">
	<div class ="mainplacetext">

<?php
if(isset($_SESSION['login'])){
	$login = $_SESSION['login'];
	$user_id = $_SESSION['user_id'];
}	

?>
		<h4> Поиск</h4>

<table id = "search">
<? 
if(isset($_GET['vin'])){
	unset($_SESSION['region']);
	unset($_SESSION['city']);
	unset($_SESSION['brand']);
	unset($_SESSION['model']);
	unset($_SESSION['yearfrom']);
	unset($_SESSION['yearto']);
}

if(isset($_GET['region'])){
	$_SESSION['region'] = $_GET['region'];
}
if(isset($_SESSION['region'])){
	$region = $_SESSION['region'];
}

if(isset($_GET['city'])){
	$_SESSION['city'] = $_GET['city'];
}
if(isset($_SESSION['city'])){
	$city = $_SESSION['city'];
} 
if(isset($_GET['regiondel'])){    // СБРОСИТЬ ДАННЫЕ ПОИСКА ВЫБОРА региона
	
		unset($_SESSION['region']);
		unset($_SESSION['city']);
		unset($region);
		unset($city);
}

	if(!isset($region)){   //    004   ВЫБИРАЕМ РЕГИОН   ?>
		<tr>
			<td class = "s1">Выберите регион</td>
			<td class = "s2"> 
				<form method="get">
					<select name = "region" style="width: 180px;">
<?						$query_reg = "SELECT * FROM `region`";	
						$query_rez = $connect->query($query_reg);
						$reg_num = mysqli_num_rows($query_rez);
						
						for($i = 1; $i <= $reg_num; $i++ ){
							$arr_reg = $query_rez->fetch_array(MYSQLI_BOTH);
							
							if(isset($region) AND $arr_reg['region'] == $region AND !isset($_GET['region'])){
								$selected = 'selected';
							}
							elseif(isset($_GET['region']) AND $arr_reg['region'] == $_GET['region']){
								$selected = 'selected';
							}
							else{$selected = "";}


							echo '<option value="'.$arr_reg['region']."\" $selected >".$arr_reg['region_id']."&nbsp&nbsp".$arr_reg['region'].'</option>';
						}

?>
					</select>	&nbsp;
					<input type = "submit" value = "Продолжить">
				</form>
			</td>
		</tr>
<?	}  	//    004   ВЫБИРАЕМ РЕГИОН			



	else{ 	//  005  ЕСЛИ ВЫБРАН РЕГИОН   ?>
		<tr>
			<td class = "s1">Регион</td>
			<td class = "s2"> <? echo "$region"; ?>	&nbsp;
				<form method="get" style="display: inline;">
						<input type="hidden" name="regiondel" value="true">
						<input type="submit" value="Сбросить">
				</form>
			</td>
		</tr>

		<!--  --------------------------    В ЗАВИСИМОСТИ ОТРЕГИОНА ВЫБИРАЕМ ГОРОД   -----------------------  -->
<?					if($region != "Все"){  //  006   РЕГИОН != ВСЕ  
						
						if(isset($city)){    ?>
		<tr>
			<td class = "s1">Город</td>
			<td class = "s2"><? echo $city;  ?></td>
		</tr>   <? }
						else{    //  007   ВЫБИРАЕМ ГОРОД  ?>
		<tr>
			<td class = "s1">Выберите город</td>
			<td class = "s2">
				<form method="get">
					<select name = "city">
<?						if(isset($_GET['region'])){ $region = $_GET['region'];}
							// echo $region;
							$query_r_id = "SELECT `region_id` FROM `region` WHERE region = '$region'";
							$r_id_res = $connect->query($query_r_id);
							$arr_r_id = $r_id_res->fetch_array(MYSQLI_BOTH);
							$reg_id = $arr_r_id['region_id'];
							$query_city = "SELECT `city` FROM `city` WHERE `region_id` = $reg_id ORDER BY `city`";
							$city_res = $connect->query($query_city);
							$city_num = mysqli_num_rows($city_res);

							for($i = 1; $i <= $city_num; $i++){
								$arr_city = $city_res->fetch_array(MYSQLI_BOTH);	?>
								<option value="<?=$arr_city['city']?>"><?=$arr_city['city']?></option>	
<?							}
?>						
					</select>	&nbsp;
					<input type = "submit" value = "Продолжить">
				</form>
			</td>
		</tr>
<?  					}  //  007  ВЫБИРАЕМ ГОРОД   else 
					}   //  006  РЕГИОН != ВСЕ    if($region != "Все") 

				}    //  005  РЕГИОН ВЫБРАН    else
?>
		<?	
	
//  =========================== Выбираем МОТО  =============================================================================================================
?>		<tr><td><p></p></td><td></td></tr>

<?
if(isset($_GET['brand'])){
	$_SESSION['brand'] = $_GET['brand'];
}	
if(isset($_SESSION['brand'])){
	$brand = $_SESSION['brand'];
}
if(isset($_GET['model'])){
	$_SESSION['model'] = $_GET['model'];
}	
if(isset($_SESSION['model'])){
	$model = $_SESSION['model'];
}
if(isset($_GET['yearfrom'])){
	$_SESSION['yearfrom'] = $_GET['yearfrom'];
}	
if(isset($_GET['yearto'])){
	$_SESSION['yearto'] = $_GET['yearto'];
}

if(isset($_GET['motodel'])){    // СБРОСИТЬ ДАННЫЕ ПОИСКА ВЫБОРА МОТОЦИКЛА
	
		unset($_SESSION['brand']);
		unset($_SESSION['model']);
		unset($_SESSION['yearfrom']);
		unset($_SESSION['yearto']);
		unset($brand);
		unset($model);
		unset($yearfrom);
		unset($yearto);
}

		if(!isset($brand)){  //  008  ВЫБИРАЕМ БРЕНД    ?>
		<tr>
			<td class = "s1">Выберите бренд</td>
			<td class = "s2">
				<form method="get" >
					<select name="brand" style="width: 180px;">

<?					$query_brand = "SELECT * FROM `brand` ORDER BY `brand`";
					$query_brand_res = $connect->query($query_brand);
					$num_brand = mysqli_num_rows($query_brand_res);

					for($i = 1; $i <= $num_brand; $i++){
						$arr_brand = $query_brand_res->fetch_array(MYSQLI_BOTH);
						$sel_brand = $arr_brand['brand'];	
?>
						<option value="<?=$sel_brand?>" ><?=$sel_brand?></option>
<?					}
?>	
					</select>  &nbsp;
					<input type = "submit" value = "Продолжить">
				</form>
			</td>
		</tr>
<?		}	//  008  ВЫБИРАЕМ БРЕНД

		else{ //  009  БРЕНД ВЫБРАН   
?>		<tr>
			<td class = "s1">Бренд</td>
			<td class = "s2">
				<?=$brand?> &nbsp
				<form method="get" style="display: inline;">
						<input type="hidden" name="motodel" value="true">
						<input type="submit" value="Сбросить">
				</form>
			</td>
		</tr>

<?			if(!isset($model)){  //    010  ВЫБИРАЕМ МОДЕЛЬ
				if($brand!= "Все"){   //  012   БРЕНД !=  Все 
?>		<tr>
			<td class = "s1">Выберите модель</td>				
			<td class = "s2">
				<form method="get"> 
							<select name="model">
<?								$query_brand_id = "SELECT `brand_id` FROM `brand` WHERE `brand` = '$brand'";
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
							</select>	&nbsp;
					<input type="submit" value="Продолжить">					
				</form>
			</td>
		</tr>
<?				}    //  012   БРЕНД !=  Все 
			}   //    010  ВЫБИРАЕМ МОДЕЛЬ

			else{   //    011    МОДЕЛЬ ВЫБРАНА
 ?>		<tr>
			<td class = "s1">Модель</td>
			<td class = "s2"><?=$model?></td>
		</tr>
<?			}  //    011    МОДЕЛЬ ВЫБРАНА


		}  //  009  БРЕНД ВЫБРАН 
		

	if(isset($brand)){  //   015  БРЕНД ВЫБРАН   
?>
		<tr>
			<td class="s1">Год выпуска</td>
			<td class="s2">
				От
				<form style="display: inline;" method="get">
					<input type="number" name = "yearfrom" min="1970" max="2018" value="1970">
					&nbsp - &nbsp До
					<input type="number" name = "yearto" min="1970" max="2018" value="2018">
					<input type="submit" value="Применить">
				</form> <br>
<?	if(isset($_SESSION['yearfrom'])){
		if($_SESSION['yearfrom'] > $_SESSION['yearto']){
			$yearfrom = $_SESSION['yearto'];
			$yearto = $_SESSION['yearfrom'];
		}
		else{
			$yearfrom = $_SESSION['yearfrom'];
			$yearto = $_SESSION['yearto'];
		}
		echo "$yearfrom - $yearto";
	}
	
?>			</td>
		</tr>	
<?	}  //   015  БРЕНД ВЫБРАН	?>
		<tr>
			<td class = "s1">Поиск по VIN</td>
			<td class = "s2">
				<form method = "get">
					<input type = "text" name="vin" size = "17" onblur="var vin2 = this.value; checkVin2(vin2);">&nbsp;
					<input type = "submit" value = "Найти">
				</form>	
			</td>
		</tr>	
	</table>

<!--  ======================================   ТАБЛИЦА РЕЗУЛЬТАТА ПОИСКА     =============================     -->     
<br><br>

	<table id = "rezult">  
<?	
$squery = "SELECT user_id, login, name, lastname, region, city, moto_id, brand, model, year, moto_pic FROM `users` INNER JOIN `moto` using(user_id)";	

if(isset($region) && $region != "Все"){  
	$squery.= " WHERE region = '$region'";	

	if(isset($city)){
			$squery.= " AND city = '$city'";
	}	
}

if(isset($brand)){
	if($brand!="Все"){

		$squery.= (isset($region) && $region!= "Все")?" AND brand = '$brand'":" WHERE brand = '$brand'";
		
		if(isset($model) && $model!="Все" ){
			$squery.=" AND model = '$model'";
		}
		if(isset($yearfrom)){
		$squery.=" AND year BETWEEN '$yearfrom' AND '$yearto'";
		}
	}
	else{  //	$brand = "Все"
		if(isset($yearfrom)){
			$squery.= (isset($region) && $region!= "Все")?" AND year BETWEEN '$yearfrom' AND '$yearto'":" WHERE year BETWEEN '$yearfrom' AND '$yearto'";
		}
	}

}
else{	// $brand не выбран
	if(isset($yearfrom)){
		$squery.= isset($region)?" AND year BETWEEN '$yearfrom' AND '$yearto'":" WHERE year BETWEEN '$yearfrom' AND '$yearto'";
	}
}

$select_all_query = $connect->query($squery);
$all_rows = $select_all_query->num_rows;

$squery.= ' ORDER BY moto_id DESC';

if(isset($_GET['page']) && $_GET['page'] != 1){
	$message = " LIMIT ".(($_GET['page'] - 1)*10).", 10";
}
else $message = " LIMIT 10";
$squery.= $message;

if(isset($_GET['vin'])){
	$vin = $_GET['vin'];
	$squery = "SELECT user_id, login, name, lastname, region, city, moto_id, brand, model, year, moto_pic FROM `users` INNER JOIN `moto` using(user_id) WHERE vin = '$vin' ";
	$all_rows = 1; // ЧТОБЫ НЕ ДОБАВЛЯЛОСЬ ПЕРЕКЛЮЧЕНИЕ ПО СТРАНИЦАМ
}
//echo $squery;
$select_query = $connect->query($squery);
$rows = $select_query->num_rows;


if($all_rows == NULL){
	echo "По Вашему запросу ничего не найдено.";
}

for($i = 1; $i <= $rows; $i++){
	$arr = $select_query->fetch_array(MYSQLI_BOTH);		
	$moto_id = $arr['moto_id'];
	$owner_id = $arr['user_id'];						?>	
		<tr>
			<td class="rez1">
				<a href = "../moto/index.php?moto_id=<?=$moto_id?>&owner_id=<?=$owner_id?>" >		
					<img class="rezimg" src="../user_data/<? 
					$bool = boolval($arr['moto_pic']);   //  motopic.jpg = true,  0 = false
					if($bool){
						$ras = get_pic_type($moto_id);
						echo $owner_id.'/'.$moto_id.'/motopic'.$ras;
					}	
					else{
						echo "nomotopic.jpg";
					} 	?> ">
				</a>	
			</td>  
			<td class="rez2">
				<a href = "../moto/index.php?moto_id=<?=$moto_id?>&owner_id=<?=$owner_id?>" >
<?					echo $arr['brand'].'&nbsp;&nbsp;&nbsp;<wbr>'.$arr['model'].'&nbsp;&nbsp;&nbsp;<wbr>'.$arr['year']."<br>";
					echo $arr['region'].'&nbsp;&nbsp;&nbsp;<wbr>'.$arr['city'];	?>
				</a>	
			</td>

			<td class="rez3">
				<a href="../owner/index.php?owner_id=<?=$owner_id?>" >
					<div style="text-align: center;"> Владелец</div>
<?					echo $arr['name']."<br>".$arr['lastname'];		?>
				</a>
			</td>
			
		</tr>	<?
}	?>
		<tr>					<!--  -========================           добавляем переключение по страницам     ==========================--        -->
			<td colspan=3 style="border: 0px solid #fff;">	<?

				if($all_rows > 10){
					$pages = intdiv($all_rows - 1, 10) + 1;
					$current = 1;
					if(isset($_GET['page'])){
						$current = $_GET['page'];
					}
					echo func_pages($pages, $current);					
				}		
?>			</td>
		</tr>

	</table>  <br> 

		</div><!--mainplacetext-->
	</div> <!--   mainplace   -->  

<?php require '../foot.php' ?>