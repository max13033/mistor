<? 
function photo_type($name){   //  ФУНКЦИЯ ПРОВЕРКИ РАСШИРЕНИЯ ФОТО

	$name = basename($name);

	$pattern1 = "/\.jpeg$/";
	$pattern2 = "/\.png$/";
	$pattern3 = "/\.gif$/";

	if(preg_match($pattern1, $name)){
		$rass = ".jpeg";
	}
	elseif(preg_match($pattern2, $name)){
		$rass = ".png";
	}
	elseif(preg_match($pattern3, $name)){
		$rass = ".gif";
	}
	else{
		$rass = ".jpg";
	}
	return $rass;
}
function del_pic($pic_id){  // 		УДАЛЯЕМ КАРТИНКУ
	global $connect;

	$pic_name = $connect->query("SELECT `pic_name` FROM `picture` WHERE `pic_id` = '$pic_id' ");
	$arr = $pic_name->fetch_array(MYSQLI_ASSOC);
	if(file_exists($arr['pic_name'])){
		unlink($arr['pic_name']);
	}
	$comm = $connect->query("SELECT comm_id FROM comment WHERE pic_id = '$pic_id' ");
	$comm_num = $comm->num_rows;
	for($i = 1; $i <= $comm_num; $i++){
		$connect->query("DELETE FROM comment WHERE pic_id = '$pic_id' ");
	}
	$connect->query("DELETE FROM `picture` WHERE `pic_id` = '$pic_id' ");
}
function del_story($del_story, $moto_id){  //   Функция удаления истории

	global $connect;

	$del_pic_id = $connect->query("SELECT `pic_id` FROM `picture` WHERE `story_id` = '$del_story' ");
	$num_pic = $del_pic_id->num_rows;

	for($i = 1; $i <= $num_pic; $i++){
		$arr = $del_pic_id->fetch_array(MYSQLI_BOTH);
		$arr_pic_id[] = $arr['pic_id'];
	}
	if(!is_null($arr_pic_id)){
		foreach($arr_pic_id as $value){
				del_pic($value);
		}		
	}
	
	
	$connect->query("DELETE FROM `story` WHERE `story_id` = '$del_story' AND `moto_id` = '$moto_id' ");
}
function del_moto($moto_id){			// 			УДАЛЯЕТ МОТОЦИКЛ
	
	global $user_id, $connect;

	$stories = $connect->query("SELECT story_id FROM story WHERE moto_id = '$moto_id' ");
	$num_stor = $stories->num_rows;
	for($i = 1; $i <= $num_stor; $i++){
		$arr = $stories->fetch_array(MYSQLI_BOTH);
		$arr_st[] = $arr[0];
	}
	if(isset($arr_st) AND !is_null($arr_st)){
		foreach($arr_st as $value){
			del_story($value, $moto_id);
		}	
	}
	$moto_pic = $connect->query("SELECT moto_pic FROM moto WHERE moto_id = '$moto_id' ");
	$m_pic = $moto_pic->fetch_array(MYSQLI_ASSOC);
	if($m_pic['moto_pic'] != '0'){
		$pic = '../user_data/'.$user_id.'/'.$moto_id.'/'.$m_pic['moto_pic'];
		if(file_exists($pic)){
			unlink($pic);
		}	
	}

	$connect->query("DELETE FROM moto WHERE `moto_id` = '$moto_id' ");
	rmdir('../user_data/'.$user_id.'/'.$moto_id);  
}
function del_user($user_id){     			//		УДАЛЯЕТ ПОЛЬЗОВАТЕЛЯ 
	global $connect;

	$motos = $connect->query("SELECT moto_id FROM moto WHERE user_id = '$user_id' ");
	$num_motos = $motos->num_rows;
	for($i = 1; $i <= $num_motos; $i++){
		$arr_ids = $motos->fetch_array(MYSQLI_NUM);
		$arr_m_ids[] = $arr_ids[0];
	}
	if(isset($arr_m_ids) AND !is_null($arr_m_ids)){
		foreach($arr_m_ids as $value){
			del_moto($value);
		}
	}	
	$ava = $connect->query("SELECT avatar FROM users WHERE user_id = '$user_id' ");
	$arr = $ava->fetch_array(MYSQLI_ASSOC);
	$avatar = '../user_data/'.$user_id.'/'.$arr['avatar'];
	if(file_exists($avatar)){
		unlink($avatar);
	}
	//	ПОМЕЧАЕМ ОТПРАВЛЕННЫЕ ПИСЬМА "НА УДАЛЕНИЕ"

	$sent_mes = $connect->query("SELECT message_id FROM message WHERE sender = '$user_id' ");
	$num_sent = $sent_mes->num_rows;
	if($num_sent){
		for($i = 1; $i <= $num_sent; $i++){
			$arr = $sent_mes->fetch_array(MYSQLI_NUM);
			$sent[] = $arr[0];
		}
		foreach($sent as $value) {
			$connect->query("UPDATE message SET del_sender = '1' WHERE message_id = '$value' ");
		}
	} 
	// 	ПОМЕЧАЕМ ПРИНЯТЫЕ ПИСЬМА "НА УДАЛЕНИЕ"

	$rec_mes = $connect->query("SELECT message_id FROM message WHERE receiver = '$user_id' ");
	$num_rec = $rec_mes->num_rows;
	if($num_rec){
		for($i = 1; $i <= $num_rec; $i++){
			$arr = $rec_mes->fetch_array(MYSQLI_NUM);
			$rec[] = $arr[0];
		}
		foreach($rec as $value) {
			$connect->query("UPDATE message SET del_receiver = '1' WHERE message_id = '$value' ");
		}
	} 

	$connect->query("DELETE FROM users WHERE `user_id` = '$user_id' ");
	rmdir('../user_data/'.$user_id);
	unset($_SESSION['login']);
	unset($_SESSION['user_id']);
	echo '<meta http-equiv = "refresh" content = "0, url = ../main/">';
}



function get_pic_type($id_moto){  // ФУНКЦИЯ ВОЗВРАЩАЕТ РАСШИРЕНИЕ ФОТО МОТОЦИКЛА

	global $connect;

	$pic_name = $connect->query("SELECT `moto_pic` FROM `moto` WHERE `moto_id` = '$id_moto' ");
	$arr = $pic_name->fetch_array(MYSQLI_BOTH);
	$name = $arr['moto_pic'];
	$ras = photo_type($name);
	return $ras;
}
function func_pages($pages, $current){  //  ФУНКЦИЯ СОЗДАЁТ ЦИФРЫ ПЕРЕХОДА ПО СТРАНИЦАМ И ТОЧКИ, ЕСЛИ СТРАНИЦ МНОГО

	if($current > 3){
		echo '<a href = "index.php?page=1" style = "cursor: pointer;">1 &nbsp;</a>'; // 	ЕСЛИ СТРАНИЦ БОЛЬШЕ 3-Х, ПЕРВАЯ СТРАНИЦА ОТОБРАЖАЕТСЯ ВНЕ ЦИКЛА
	}
	if($current >= 5 AND $pages >= 7) echo '.... '; // 	УСЛИ СТР БОЛЬШЕ ИЛИ = 7 И ТЕКУЩАЯ БОЛЬШЕ ИЛИ = 5, ПОСЛЕ ЕДИНИЦЫ НУЖНО ОТОБРАЗИТЬ МНОГОТОЧИЕ

	for($p = ($current - 2); $p <= ($current + 2); $p++){  //  отображаем текущую страницу и  соседние (две до и 2 после)
		if($p > 0 AND $p <= $pages){                       // 	что бы страница не была отрицательной или больше максимальной
			echo '<a href = "index.php?page='.$p.'" style = "';
				if($p == $current) echo 'font-size: 1.2em;';
				echo ' cursor: pointer;">'.$p.' &nbsp;</a>';
		}	
	}

	if($current <= ($pages - 4) AND $pages >=7) echo '.... ';  // 	УСЛИ СТР БОЛЬШЕ ИЛИ = 7 И ТЕКУЩАЯ меньше четвёртой с конца отображаем многоточие

	if($current <= ($pages - 3)){
		echo '<a href = "index.php?page='.$pages.'" style = "cursor: pointer;">'.$pages.' &nbsp;</a>';
	}							
}

















 ?>