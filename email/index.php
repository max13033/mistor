<?  	// 01	02	03	04	05
session_start();
$pagetitle = "Сообщения";

require '../head.php';  

error_reporting(E_ALL);?>
	<div class="mainplace">
		<div class ="mainplacetext">
<?			
			//	УДАЛЯЕМ СООБЩЕНИЕ
			if(isset($_POST['del_mes'])){
				$del_mes = $_POST['del_mes'];
				$res = $connect->query("SELECT * FROM message WHERE message_id = '$del_mes' ");
				$arr = $res->fetch_array(MYSQLI_ASSOC);

				if($arr['sender'] == $user_id){
					$connect->query("UPDATE message SET del_sender = '1' WHERE message_id = '$del_mes' ");
				}
				elseif($arr['receiver'] == $user_id){
					$connect->query("UPDATE message SET del_receiver = '1' WHERE message_id = '$del_mes' ");
				}
				$res = $connect->query("SELECT message_id FROM message WHERE del_sender = '1' AND del_receiver = '1' ");
				$num_del_mes = $res->num_rows;
				for($i = 1; $i <= $num_del_mes; $i++){
					$arr = $res->fetch_array(MYSQLI_ASSOC);
					$del_id = $arr['message_id'];
					$connect->query("DELETE FROM message WHERE message_id = '$del_id' ");
				}
				echo REFRESH;
			}
			//	НЕПРОЧИТАННЫЕ\
			if(isset($_POST['new_mes'])){
				$oponent = $_POST['oponent'];
				$new_text = $_POST['new_mes'];
				$new_date =	strval(date("Y-m-d"));
				$connect->query("INSERT INTO message (`message_id`, `sender`, `receiver`, `mes_text`, `date`) VALUES (NULL, '$user_id', '$oponent', '$new_text', '$new_date')");
				echo REFRESH;
			}
			//	ИСХОДЯЩИЕ	
			$text_out = "SELECT * FROM message WHERE sender = '$user_id' AND del_sender = '0' ORDER BY `message_id` DESC";	
			$query_out_all = $connect->query($text_out);
			$num_out_all = $query_out_all->num_rows;
			//	ВХОДЯЩИЕ
			$text_in = "SELECT * FROM message WHERE receiver = '$user_id' AND del_receiver = '0' ORDER BY `message_id` DESC";
			$query_in_all = $connect->query($text_in);
			$num_in_all = $query_in_all->num_rows;


?>
			<div style = "float: left; text-align: left; padding-left: 10px ;" >
				<a href = "index.php?email=in" style = "cursor: pointer;">
					<div class = "email_punct" style = "margin-bottom: 3px; <? if(isset($_GET['email']) AND ($_GET['email'] == "in")){ echo 'background-color: #222;';} ?>" >Входящие &nbsp; <?if($num_new > 0){echo "<font style = \"color: #f33; font-weight: bolder; font-size: 1.2em;\">$num_new</font>/";} echo $num_in?></div>
				</a>
				<a href = "index.php?email=out" style = "cursor: pointer;">	
					<div class = "email_punct" style = "<? if((isset($_GET['email'])) AND ($_GET['email'] == "out")){ echo 'background-color: #222;';}?>" >Исходящие &nbsp; <?=$num_out?> </div>
				</a>	
			</div>
			<div style = "float: left;">
<?		
				if(isset($_GET['email']) AND $_GET['email'] == "out" ){ 	// 	01  =========   ИСХОДЯЩИЕ   ===========

					if(isset($_GET['page']) && $_GET['page'] != 1){ 			// 	ЕСЛИ ВЫБРАНА СТРАНИЦА  И ЭТО НЕ ПЕРВАЯ СТРАНИЦА
						$message = " LIMIT ".(($_GET['page'] - 1)*10).", 10"; 	// 	ДОПИСЫВАЕМ ОГРАНИЧЕНИЕ ПО ВЫВОДУ. (УСЛИ СТРАНИЦА 2, ДОЛЖНО ВЫВОДИТЬСЯ LIMIT 10, 10) СООТВЕТСТВЕННО
					}															// (2-1)*10 = 10 (ВЫВОДИТ С 10 НОВОСТИ).   ЕСЛИ СТ 3, (3-1)*10 = 20 (ВЫВОДИТ С 20-Й НОВОСТИ) И Т.Д.
					else $message = " LIMIT 10"; 								// 	ЕСЛИ СТРАНИЦА НЕ ЗАДАНА ИЛИ СТРАНИЦА = 1 - ПРОСТО ОГРАНИЧИВАЕМ ПЕРВАЕ 10 СТРОК.
					$text_out.= $message;
					$query_out = $connect->query($text_out);
					$num_out = $query_out->num_rows;

					for($i = 1; $i <= $num_out; $i++){ 	//	02	
						$arr_out = $query_out->fetch_array(MYSQLI_ASSOC);
						$mes_out_id = $arr_out['message_id'];
						$receiver_id = $arr_out['receiver'];
						$mes_text = $arr_out['mes_text'];
						$date_out = $arr_out['date'];
						$new_mes = $arr_out['new'];	
						$receiver_data = $connect->query("SELECT name, avatar FROM users WHERE user_id = '$receiver_id' ");
						$arr_receiver = $receiver_data->fetch_array(MYSQLI_ASSOC);
						if(is_null($arr_receiver['name'])){
							$rec_name = 'Пользователь удален';
							$rec_ava = '../user_data/noava.jpg';	
						}
						else{
							$rec_name = $arr_receiver['name'];
							$rec_ava = '../user_data/'.$user_id.'/'.$arr_receiver['avatar'];
						}	
?>
						<div class = "email">
							<form method = "post">
								<input type = "hidden" name="del_mes" value = "<?=$mes_out_id?>">
								<input type = "submit" class = "del_email" title = "Удалить сообщение" value = "&#10006;">
							</form>
							<div style = "float: right; color: #5f5;" title = "Прочитано">
<?								if($new_mes == 0){echo "&#10004; &nbsp";}				?>
							</div>
							<font style = "font-style: italic; font-weight: bold; color: #77f; font-size: 10pt;"><?=$date_out?></font>&nbsp;&nbsp;&nbsp;
							<font style = "font-size: 12pt; color: #fff;" >Получатель &nbsp; </font>
							<a href = "../owner/index.php?owner_id=<?=$receiver_id?>">
								<font style = "font-style: bold; color: #6ff; font-size: 12pt;" > <?=$rec_name?></font>
							</a>	&nbsp;&nbsp;
							<a href = "index.php?oponent=<?=$receiver_id?>" style = "cursor: pointer;">
								<font style = "font-weight: bold; color: #88f; font-size: 10pt;" >(Читать всю переписку)</font>
							</a>
							<br>
							<img class = "email" src = "<? echo $rec_ava;?>">
							<font style = "font-size: 12pt; color: #eee;"><?=$mes_text?></font>
						</div>
						
<?					}	//	02 		
					if($num_out_all > 10){ 	//	
						echo '<br> <div style = "text-align: center;">';

						$pages = intdiv($num_out_all - 1, 10); 	// ОСТАТОК ОТ ДЕЛЕНИЯ (ЕСЛИ СТРАНИЦ 11)
						$current = 1;
						if(isset($_GET['page'])){
							$current = $_GET['page'];
						}

						for($p = 1; $p <= $pages + 1; $p++){
							if($p == $current){
								echo '<a href = "index.php?email=out&page='.$p.'" style = "font-size: 1.2em; cursor: pointer;">'.$p.'&nbsp;</a> ';
							}
							else echo '<a href = "index.php?email=out&page='.$p.'" style = "cursor: pointer;">'.$p.'&nbsp;</a> ';
						}
						echo '<br><br> </div>';	
					}
				} 	// 	01
				if(isset($_GET['email']) AND $_GET['email'] == "in" ){ 	// 	03		=============	Входящие 	===========

					if(isset($_GET['page']) && $_GET['page'] != 1){ 			// 	ЕСЛИ ВЫБРАНА СТРАНИЦА  И ЭТО НЕ ПЕРВАЯ СТРАНИЦА
						$message = " LIMIT ".(($_GET['page'] - 1)*10).", 10"; 	// 	ДОПИСЫВАЕМ ОГРАНИЧЕНИЕ ПО ВЫВОДУ. (УСЛИ СТРАНИЦА 2, ДОЛЖНО ВЫВОДИТЬСЯ LIMIT 10, 10) СООТВЕТСТВЕННО
					}															// (2-1)*10 = 10 (ВЫВОДИТ С 10 НОВОСТИ).   ЕСЛИ СТ 3, (3-1)*10 = 20 (ВЫВОДИТ С 20-Й НОВОСТИ) И Т.Д.
					else $message = " LIMIT 10"; 								// 	ЕСЛИ СТРАНИЦА НЕ ЗАДАНА ИЛИ СТРАНИЦА = 1 - ПРОСТО ОГРАНИЧИВАЕМ ПЕРВАЕ 10 СТРОК.
					$text_in.= $message;
					$query_in = $connect->query($text_in);
					$num_in = $query_in->num_rows;

					for($i = 1; $i <= $num_in; $i++){ 	//	04	
						$arr_in = $query_in->fetch_array(MYSQLI_ASSOC);
						$mes_in_id = $arr_in['message_id'];
						$sender_id = $arr_in['sender'];
						$mes_text = $arr_in['mes_text'];
						$date_in = $arr_in['date'];
						$new = $arr_in['new'];	
						$sender_data = $connect->query("SELECT name, avatar FROM users WHERE user_id = '$sender_id' ");
						$arr_sender = $sender_data->fetch_array(MYSQLI_ASSOC);
						if(is_null($arr_sender['name'])){ // 	ЕСЛИ ПОЛЬЗОВАТЕЛЬ УДАЛЁН
							$sender_name = 'Пользователь удален';
							$sender_ava = '../user_data/noava.jpg';		
						}
						else{
							$sender_name = $arr_sender['name'];
							$sender_ava = '../user_data/'.$sender_id.'/'.$arr_sender['avatar'];
						}	
?>
						<div class = "email" <?if($new == 1){echo 'style = "background-color: #222" ';} $connect->query("UPDATE `message` SET `new` = '0' WHERE `message_id` = '$mes_in_id' ");?>>
							<form method = "post">
								<input type = "hidden" name="del_mes" value = "<?=$mes_in_id?>">
								<input type = "submit" class = "del_email"  title = "Удалить сообщение" value = "&#10006;">
							</form>	
							<font style = "font-style: italic; font-weight: bold; color: #77f; font-size: 10pt;"><?=$date_in?></font>&nbsp;&nbsp;&nbsp;
							<font style = "font-size: 12pt; color: #fff;" >От &nbsp; </font>
							<a href = "../owner/index.php?owner_id=<?=$sender_id?>">
								<font style = "font-style: bold; color: #6ff; font-size: 12pt;" > <?=$sender_name?></font>
							</a>	&nbsp;&nbsp;
							<a href = "index.php?oponent=<?=$sender_id?>" style = "cursor: pointer;">
								<font style = "font-weight: bold; color: #88f; font-size: 10pt;" >(Читать всю переписку)</font>
							</a>
							<br>
							<img class = "email" src = "<? echo $sender_ava;?>">
							<font style = "font-size: 12pt; color: #eee;"><?=$mes_text?></font>
						</div>
<?					}	//	04
					if($num_in_all > 10){ 	//	
						echo '<br> <div style = "text-align: center;">';

						$pages = intdiv($num_in_all - 1, 10); 	// ОСТАТОК ОТ ДЕЛЕНИЯ (ЕСЛИ СТРАНИЦ 11)
						$current = 1;
						if(isset($_GET['page'])){
							$current = $_GET['page'];
						}

						for($p = 1; $p <= $pages + 1; $p++){
							if($p == $current){
								echo '<a href = "index.php?email=in&page='.$p.'" style = "font-size: 1.2em; cursor: pointer;">'.$p.'&nbsp;</a> ';
							}
							else echo '<a href = "index.php?email=in&page='.$p.'" style = "cursor: pointer;">'.$p.'&nbsp;</a> ';
						}
						echo ' <br><br> </div>';	
					}
				} 		//	03		

				if(isset($_GET['oponent'])){ 	//	05		=========  ПЕРЕПИСКА	============
					$oponent = $_GET['oponent'];
					$text_opo = "SELECT * FROM message 
						WHERE (sender = '$user_id' AND receiver = '$oponent' AND del_sender = '0') 
						OR (sender = '$oponent' AND receiver = '$user_id' AND del_receiver = '0') 
						ORDER BY `message_id` DESC";

					$res = $connect->query($text_opo);
					$num_opo_all = $res->num_rows;

					if(isset($_GET['page']) && $_GET['page'] != 1){ 			// 	ЕСЛИ ВЫБРАНА СТРАНИЦА  И ЭТО НЕ ПЕРВАЯ СТРАНИЦА
						$message = " LIMIT ".(($_GET['page'] - 1)*10).", 10"; 	// 	ДОПИСЫВАЕМ ОГРАНИЧЕНИЕ ПО ВЫВОДУ. (УСЛИ СТРАНИЦА 2, ДОЛЖНО ВЫВОДИТЬСЯ LIMIT 10, 10) СООТВЕТСТВЕННО
					}															// (2-1)*10 = 10 (ВЫВОДИТ С 10 НОВОСТИ).   ЕСЛИ СТ 3, (3-1)*10 = 20 (ВЫВОДИТ С 20-Й НОВОСТИ) И Т.Д.
					else $message = " LIMIT 10"; 								// 	ЕСЛИ СТРАНИЦА НЕ ЗАДАНА ИЛИ СТРАНИЦА = 1 - ПРОСТО ОГРАНИЧИВАЕМ ПЕРВАЕ 10 СТРОК.
					
					$text_opo.= $message;
					$query_opo = $connect->query($text_opo);
					$num_opo = $query_opo->num_rows;

					for($i = 1; $i <= $num_opo; $i++){ 	
						$arr_opo = $query_opo->fetch_array(MYSQLI_ASSOC);
						$mes_id = $arr_opo['message_id'];
						$sender_id = $arr_opo['sender'];
						$mes_text = $arr_opo['mes_text'];
						$date = $arr_opo['date'];	
						$new_mes = $arr_opo['new'];
						$sender_data = $connect->query("SELECT name, avatar FROM users WHERE user_id = '$sender_id' ");
						$arr_sender = $sender_data->fetch_array(MYSQLI_ASSOC);
						if(is_null($arr_sender['name'])){ // 	ЕСЛИ ПОЛЬЗОВАТЕЛЬ УДАЛЁН
							$sender_name = 'Пользователь удален';
							$sender_ava = '../user_data/noava.jpg';		
						}
						else{
							$sender_name = $arr_sender['name'];
							$sender_ava = '../user_data/'.$sender_id.'/'.$arr_sender['avatar'];
						}	

?>
						<div class = "email">
							<form method = "post">
								<input type = "hidden" name="del_mes" value = "<?=$mes_id?>">
								<input type = "submit" class = "del_email"  title = "Удалить сообщение" value = "&#10006;">
							</form>
							<div style = "float: right; color: #5f5;" title = "Прочитано">
<?								if( ($new_mes == 0) AND ($sender_id == $user_id)){echo "&#10004; &nbsp";}				?>
							</div>								
							<font style = "font-style: italic; font-weight: bold; color: #77f; font-size: 10pt;"><?=$date?></font>&nbsp;&nbsp;&nbsp;
							<font style = "font-size: 12pt; color: #fff;" >Отправитель &nbsp; </font>
							<a href = "../owner/index.php?owner_id=<?=$sender_id?>">
								<font style = "font-style: bold; color: #6ff; font-size: 12pt;" > <?=$sender_name?></font>&nbsp;&nbsp;
							</a>	
							<br>
							<img class = "email" src = "<?=$sender_ava;?>">
							<font style = "font-size: 12pt; color: #eee; margin-top: 10px;"><?=$mes_text?></font>
						</div>
<?					}									?>
					<div class = "email">
						<form method = "post">
							<textarea name = "new_mes" cols = "60" rows = "4"></textarea> <br>
							<input type = "hidden" name = "oponent" value = "<?=$oponent?>">
							<input type = "submit" value = "Отправить" class = "button">
						</form>	
					</div>
<?					if($num_opo_all > 10){ 	//	
						echo '<br> <div style = "text-align: center;">';

						$pages = intdiv($num_opo_all - 1, 10); 	// ОСТАТОК ОТ ДЕЛЕНИЯ (ЕСЛИ СТРАНИЦ 11)
						$current = 1;
						if(isset($_GET['page'])){
							$current = $_GET['page'];
						}

						for($p = 1; $p <= $pages + 1; $p++){
							if($p == $current){
								echo '<a href = "index.php?oponent='.$oponent.'&page='.$p.'" style = "font-size: 1.2em; cursor: pointer;">'.$p.'&nbsp;</a> ';
							}
							else echo '<a href = "index.php?oponent='.$oponent.'&page='.$p.'" style = "cursor: pointer;">'.$p.'&nbsp;</a> ';
						}
						echo '<br><br> </div>';	
					 }	
				} 		//	05				?>	
			</div>
			<br><br>
		</div>	
	</div>


<? require '../foot.php';  ?>