<?   //  01  02	 03
session_start();
$pagetitle = "Мотоцикл";

require '../head.php';  

error_reporting(E_ALL);?>
	<div class="mainplace">
		<div class ="mainplacetext">
<?
if(isset($_GET['moto_id']) && isset($_GET['owner_id'])){ 	//	01
	$moto_id = $_GET['moto_id'];
	$owner_id = $_GET['owner_id'];
	$query = $connect->query("SELECT * FROM `moto` WHERE `moto_id` = '$moto_id' ");
	$arr = $query->fetch_array(MYSQLI_BOTH);
	
	$brand = $arr['brand'];  
	$model = $arr['model'];
	$year = $arr['year'];
	$vin = $arr['vin'];
	$owner_from = $arr['owner_from'];
	$owner_to = $arr['owner_to'];
	$query = $connect->query("SELECT `name`, `lastname` FROM `users` WHERE `user_id` = '$owner_id' ");
	$arr_name = $query->fetch_array(MYSQLI_ASSOC);
	$name = $arr_name['name'];
	$lastname = $arr_name['lastname'];

	if($arr['moto_pic'] == '0'){
		$motopic = "../user_data/nomotopic.jpg";
	}	
	else{
		$motopic = '../user_data/'.$owner_id.'/'.$moto_id.'/'.$arr['moto_pic'];
	}	
	if(isset($_POST['comm_text'])){
		$comm_text = $_POST['comm_text'];
		$writer_id = $_POST['writer_id'];
		$comm_date = $_POST['comm_date'];
		$pic_id = $_POST['pic_id'];
		$connect->query("INSERT INTO comment (comm_id, comm_text, writer_id, comm_date, pic_id) VALUES (NULL, '$comm_text', '$writer_id', '$comm_date', '$pic_id')");
	}
	if(isset($_POST['del_comm'])){
		$del_comm = $_POST['del_comm'];
		$connect->query("DELETE FROM comment WHERE comm_id = '$del_comm' ");
	}

?>		
			<div style = "text-align: left;">
				<img class = "owner_moto" src="<?=$motopic?> " >
				<table class = "owner_moto_data">
					<tr>
						<td>Владелец</td>
						<td>
							<a href = "../owner/index.php?owner_id=<?=$owner_id?>"><?=$lastname."&nbsp&nbsp".$name?></a>
						</td>
					</tr>
					<tr>
						<td>Мотоцикл</td>
						<td><?=$brand.'&nbsp&nbsp'.$model.'&nbsp&nbsp'.$year?></td>
					</tr>
					<tr>
						<td>VIN</td>
						<td><?=$vin?></td>
					</tr>
					<tr>
						<td>Период<br>Владения</td>
						<td> <font style = "font-size: 12pt;"><?=$owner_from?> - <br><?=$owner_to?> </font> </td>
					</tr>
					<tr>
						<td><p></p></td><td></td>
					</tr>
				</table>
			</div>
			<br style="clear: both;">
			<h2>История мотоцикла</h2>

<?	$stories = $connect->query("SELECT * FROM story WHERE moto_id = '$moto_id' ORDER BY `date` DESC ");  //  =======   Запрашиваем ИСТОРИИ   ==================
	$num_story = $stories->num_rows;
	for($i = 1; $i <= $num_story; $i++){ 	// 	02
		$arr = $stories->fetch_array(MYSQLI_ASSOC);
		foreach($arr as $key => $value){
			$$key = $value;
		}
		// $story_id = $arr['story_id'];   !!!
		// $date = $arr['date'];
		// $story_name = $arr['story_name'];
		// $run = $arr['run'];
		// $posted = $arr['posted'];
?>

			<div class = "story_cont">
				<div class = "story_data">
					<table style = "background-color: #0c0c0c; border-radius: 5px 5px 0 0;  box-shadow: 1px -1px 3px #777" >
						<tr style="font-size: 14pt;">
							<td>Дата</td>
							<td>Название</td>
							<td>Пробег</td>
							<td>Размещено</td>
						</tr>
						<tr style="font-size: 12pt; height: 30px;">
							<td><?=$date?></td>
							<td><?=$story_name?> <br><br></td>
							<td style = "font-size: 16pt; color: #fff;">
<?		$dig = array(
				substr($run, 0, 1),
				substr($run, 1, 1),
				substr($run, 2, 1),
				substr($run, 3, 1),	
				substr($run, 4, 1),
				substr($run, 5, 1)
				)

?>	
							<div class="dig_cont">
<?							foreach ($dig as $value) { 		?>

								<div class="dig" style = "padding-top: <? echo strval(rand(2,8))?>px;">
									<?=$value?>
								</div>
								
<? 							} 		?>						
								<div class = "dig_red">
									9<br>0
								</div>				
							</div>	
						</td>
							<td> <i><?=$posted?> </i> </td>
						</tr>
					</table>
				</div>	

<?			$pic = $connect->query("SELECT * FROM picture WHERE story_id = '$story_id' ");	  //  =================  ЗАПРАШИВАЕМ КАРТИНКИ ИСТОРИИ  ==================
			$num_pic = $pic->num_rows; 		
?>
				<div class = "story" id = "story<?=$story_id?>"  <? if($num_pic >= 10){ echo 'style = "height: 200px;" '; } ?> >  <!-- ============   КОНТЕЙНЕР ДЛЯ ИСТОРИЙ   ============  -->

<?			for($j = 0; $j < $num_pic; $j++){ 	//	03   ЗАПУСКАЕМ ЦИКЛ СЧИТЫВАНИЯ ДАННЫХ ПО КАРТИНКАМ
				$arr = $pic->fetch_array(MYSQLI_ASSOC);
				foreach ($arr as $key => $value) {
					$$key = $value;
				}
?>
					<div class = "div_pic <?=$story_id?>" >
						<img id = "<?=$pic_name?>"
							 class = "pic" 
							 title = "<?=$pic_text?>" 
							 name = "<?=$story_id?>" 
							 src = "<?=$pic_name?>"  
							 onclick = "var j = <?=$j?>; var imgNum = <?=$num_pic?>; bigger(this.id, this.name, j, imgNum); comment(<?=$pic_id?>)">	
					</div>
<?				if($num_pic>=10){		?>
					<div class="all_pic" onclick = "show_all_pic('<? echo 'story'.$story_id; ?>');"> 
						Показать<br>все<br>картинки
					</div>
<?				} 						?>
					<div name = "comment<?=$story_id?>" style = "display: none; margin-bottom: 20px;">   <!-- =========  КОММЕНТАРИЙ К ФОТО =========== -->
<?						if(!empty($pic_text)){  ?>	<font style = "font-size: 12pt;"> <b style = "color: #fff;"> Описание: </b> <br> <?=$pic_text?> </font> <br> <br> <?  }  ?>
						<b style = "color: #fff;"> Комментарии: </b> <br><br> 
<?						$query_comm = "SELECT * FROM comment WHERE pic_id = '$pic_id' LIMIT 100";
						$query = $connect->query($query_comm);
						$num_comm = $query->num_rows;

						if($num_comm > 0){
							for($i = 1; $i <= $num_comm; $i++){
								$arr = $query->fetch_array(MYSQLI_ASSOC);
								$comm_id = $arr['comm_id'];
								$comm_text = $arr['comm_text'];
								$writer_id = $arr['writer_id'];
								$comm_date = $arr['comm_date']; 	
								//echo "comm_id = $comm_id,   com_text = $comm_text, writer  = $writer_id";

								$res = $connect->query("SELECT name, avatar FROM users WHERE user_id = '$writer_id' ");
								$arr_wr = $res->fetch_array(MYSQLI_ASSOC);
								$writer = $arr_wr['name'];
								$ava = $arr_wr['avatar'];

								//echo "name = $writer,   ava = $ava";
								?>
								<div style = "border-bottom: 2px solid #333;"> 
<?									if(isset($user_id) AND ($user_id == $writer_id OR $user_id == $owner_id)){				?>
										<form method = "post">
											<input type = "hidden" name="del_comm" value = "<?=$comm_id?>">
											<input type = "submit" class = "del_email" title = "Удалить комментарий" value = "&#10006;">
										</form>
<?									}								 		?>
									<font style = "font-style: italic; font-weight: bold; color: #77f; font-size: 10pt;">	<?=$comm_date;?> </font>&nbsp;&nbsp;
									<font style = "font-style: bold; color: #6ff; font-size: 12pt;"> <?=$writer;?> </font> <br>
									<img style = "max-width: 50px; max-height: 70px; border-radius: 5px; float: left; margin: 5px 10px 5px 0;" src = "../user_data/<?echo $writer_id.'/'.$ava;?>" >
									<font style = "" > <?=$comm_text;?>  </font>
									<br style = "clear: both;">
								</div>		
<?							}
						}
						if(isset($user_id)){  		 ?>
							
							<form method = "post">  <!-- COMMENT -->
								<br>
								<textarea name = "comm_text" cols = "75" required></textarea> <br><br>
								<input type = "hidden" name="writer_id" value = "<?=$user_id?>">
								<input type = "hidden" name = "comm_date" value = "<?=strval(date("Y-m-d"))?>">
								<input type = "hidden" name="pic_id" value = "<?=$pic_id?>">
								<input type = "submit" value = "Написать" class = "button">
							</form>		<br>
<?						}						?>						
 					</div>	
<?
			}	// 	03  	?>
													
				</div>	
			
			</div>	
			<br><br>
<?	} 	//	02	

}	//	01
else{
	echo '<h2>Данные о мотоцикле отсутствуют</h2>';
	echo '<meta http-equiv = "refresh" content = "3, url = ../search/">';
}
?>			
		</div>	
	</div>
<div>
	<div id = "big_bg" style = "display: none"> 	</div>	
	<div id = "big_cont" style = "display: none" >   <!-- =======   Контейнер для большой картинки   ============= -->
		<div style = "position: relative; display: inline-block;">
			<div>
				<font id = "num_amount" style = "position: absolute; color: #fff; font-size: 12pt; top: 20px; right: 20px; z-index: 13; text-shadow: 1px 1px 1px #000;"></font>
				<img src = "../images/left.jpg" class = "arrow left" onclick = "prev()">
				<img src = "../images/stick.png" style = "position: absolute; top: 40px; left: -71px; z-index: 12; height: 100px; box-shadow: 3px 2px 5px #000;" >
				<img src = "../images/bush.png" style = "position: absolute; top: 130px; left: -120px; z-index: 13; filter: drop-shadow(3px 2px 5px #000);">
				<img id = "big_pic" src = "" onclick = "document.getElementById('big_cont').style.display = 'none'; document.getElementById('big_bg').style.display = 'none';">	
				<img src = "../images/right.jpg" class = "arrow right" onclick = "next()">
				<img src = "../images/stick.png" style = "position: absolute; top: 40px; right: -71px; z-index: 12; height: 100px; box-shadow: 3px 2px 5px #000;" >
				<img src = "../images/bush.png" style = "position: absolute; top: 130px; right: -120px; z-index: 13; filter: drop-shadow(3px 2px 5px #000);">
			</div>
			
			<!-- <div class = "comment"> 	</div> -->

			
		</div>	
		<br>
		<div class = "comment"> 	</div>
	</div>
</div>	
	


<? require '../foot.php';  ?>