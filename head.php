<!DOCTYPE HTML>
<html>
<head>
	<title><?echo "$pagetitle"; ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="shortcut icon" href="../images/favicon.png" type="image/png">
	<!-- <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"> -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(51190979, "init", {
        id:51190979,
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/51190979" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
	
</head>

<body>
<?
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);

require 'connect.php';
require 'functions.php';
require 'js.php';

 ?>
<!-- =============== блок показывающий, что идёт загрузка	==================	-->

	<div id = "load">  </div>

	<div id = "load_img">
		<img style = "height: inherit;" src = "../images/load.jpg">
	</div>
	<div id = "load_cont">	
		<div class = "load"></div>
		<div class = "load" style = "animation-delay: 0.2s"></div>
		<div class = "load" style = "animation-delay: 0.4s"></div>
		<div class = "load" style = "animation-delay: 0.6s"></div>
		<div class = "load" style = "animation-delay: 0.8s">З</div>
		<div class = "load" style = "animation-delay: 1s">А</div>
		<div class = "load" style = "animation-delay: 1.2s">Г</div>
		<div class = "load" style = "animation-delay: 1.4s">Р</div>
		<div class = "load" style = "animation-delay: 1.6s">У</div>
		<div class = "load" style = "animation-delay: 1.8s">З</div>
		<div class = "load" style = "animation-delay: 2s">К</div>
		<div class = "load" style = "animation-delay: 2.2s">А</div>
		<div class = "load" style = "animation-delay: 2.4s"></div>
		<div class = "load" style = "animation-delay: 2.6s"></div>
		<div class = "load" style = "animation-delay: 2.8s"></div>
		<div class = "load" style = "animation-delay: 3s"></div> 	
	</div>

<!--		КОНЕЦ БЛОКАБ ПОКАЗЫВАЮЩЕГО ЧТО ИДЁТ загрузка 		-->


<div class="main" >   <!--  ends in foot.php  -->
 <?
if($pagetitle=="Главная"){
?>
	<div class="mainpic">
		<div width="100%" style="margin: 7px; position: relative;">
			<img class="silencer" src="../images/silencer.png">
			<img class="flame" src="../images/flame.png">
		</div>
	</div>
<?	
}
else{	?>
	<div width="100%" style="margin: 7px; position: relative;">
		<img class="silencer" src="../images/silencer.png">
		<img class="flame" src="../images/flame.png">
	</div>
	<!-- <img src = "../images/biker_blue.jpg" style = "position: fixed; width: 1000px; top: 0; opacity: 0.3">  --> <!-- Фон на страницах (кроме главной) -->
<?	
}	?>
	<div class="menu"> 

		<a href="../main/">
			<div class="menu_button">		
				<div class="ducati"></div>
				<img class = "main_road" src="../images/main_road.png">
				<img class = "main_road_back" src="../images/main_road_back.png">
				<img class = "stick" src="../images/stick.png">
				<div class="menu_text">Главная</div>
			</div>
		</a>

		<a href="../search/">
			<div class="menu_button">	
				<img class="notebook" src="../images/notebook.png">	
				<div class="note_screen">
					<img class="note_pic" src="../images/note_screen.jpg">
				</div>
				<div class="menu_text">Поиск</div>
			</div>
		</a>


		<a href="../mypage/">
			<div class="menu_button">
				<img class="garage1" src = "../images/garage1.png">
				<img class="garage2" src = "../images/garage2.png">	
				<div class = "gates_div">
					<img class = "gates_img" src = "../images/garage_gates.jpg">
				</div>
				<div class="menu_text">Гараж</div>
			</div>
		</a>
		<a href="../teh/">
			<div class="menu_button">
				<img class = "chopper" src = "../images/chopper.png">
				<img class = "chopper_fw" src = "../images/chopper_fw.png">
				<img class = "chopper_rw" src = "../images/chopper_rw.png">
				<img class = "chopper_lt" src = "../images/chopper_light.png">
				<div class="menu_text">Техподдержка</div>
			</div>	
		</a>
	</div>
	
	<div class="left_block">
		<div class="regplace">
			
			<div class="regplacetext">
<?php
if(isset($_POST['login'])){
	$login = $_POST['login'];
	$password = $_POST['password'];
	$md5password = md5($password);

	$user_query = "SELECT `user_id` from `users` WHERE login='$login' AND password='$md5password'";
	$user = $connect->query($user_query);
	$id_user = $user->fetch_array(MYSQLI_ASSOC);

	if(empty($id_user['user_id'])){
		echo "Введённый логин или пароль не верный";
		}
	else {
		$_SESSION['login'] = $login;
		$_SESSION['user_id'] = $id_user['user_id'];
		echo '<meta http-equiv = "refresh" content = "0, url = ../mypage/">';
	}
}
if(isset($_POST['reset'])){
	unset($_SESSION['login']);
	unset($_SESSION['user_id']);
	echo '<meta http-equiv = "refresh" content = "0, url = ../main/">';
}	
	if((isset($_SESSION['login']))){
		echo 'Привет &nbsp&nbsp&nbsp'.$_SESSION['login'];	?>
		<form method="post">
			<input type="hidden" name="reset" value="1">
			<input type="submit" class="button" value="Выйти">			
		</form><br>
<?		
		$login = $_SESSION['login'];
		$user_id = $_SESSION['user_id']; 

		//	-=================================		удаляем аватар	=================================-
		if(isset($_GET['delava'])){
			$query = $connect->query("SELECT `avatar` FROM `users` WHERE `user_id` = '$user_id' ");
			$arr = $query->fetch_array(MYSQLI_BOTH);	
			unlink("../user_data/".$user_id.'/'.$arr['avatar']);
			clearstatcache();		
			$connect->query("UPDATE `users` SET `avatar` = '0' WHERE `users`.`user_id` = '$user_id';") or die(mysql_error());

			echo '<meta http-equiv = "refresh" content = "0; url = ../main/">';
			exit();
		}
		$checkava = $connect->query("SELECT * from `users` WHERE user_id = '$user_id' ");
		$avanum = $checkava->fetch_array(MYSQLI_ASSOC);

		$path_file = PATH_DIR."/user_data/".$user_id;
		
		if(isset($_FILES['avatar'])){
			//   делаем проверку расширения и размера файла

			if($_FILES['avatar']['size'] > 3*1024*1024){  //   РАЗМЕР ФОТО ЮОЛЬШЕ 3Мб
?>
			<script type="text/javascript">
				alert("Размер картинки превышает 3 Мб.");
			</script>				
<?				echo REFRESH;
				exit; 
			}
			$f_name = $_FILES['avatar']['name'];
			$photo_name = "avatar".photo_type($f_name);
			move_uploaded_file($_FILES['avatar']['tmp_name'], "../user_data/$user_id/$photo_name"); // or die(mysql_error());
			$connect->query("UPDATE `users` SET `avatar` = '$photo_name' WHERE `users`.`user_id` = '$user_id'"); // or die(mysql_error());
			echo '<meta http-equiv = "refresh" content = "0; url = ../main/">';
			exit();
		}
		if(isset($avanum['avatar']) && $avanum['avatar'] == '0'){	
			$avatar = "noava.jpg";  ?>
				<img class="avaimg" src="../user_data/<?=$avatar;?>" > 
				<form method="post"  enctype = "multipart/form-data">
					<input type = "file" accept = "image/jpeg, image/png, image/gif" name="avatar" class="button" style = "width: 230px; font-size: 13px;" required><br>
					<input type="submit" value="Загрузить" class="button" style="margin-top: 15px; margin-bottom: 15px;">
				</form>
<?		}
		else{
			$query = $connect->query("SELECT `avatar` FROM `users` WHERE `user_id` = '$user_id' ");
			$arr = $query->fetch_array(MYSQLI_ASSOC);

			$avatar = $user_id.'/'.$arr['avatar'];  ?>
				<img class="avaimg" src="../user_data/<?=$avatar;?>"><br>
				<a href="../main/index.php?delava=1" class="button" style = "padding: 3px;" >&nbsp Удалить фото &nbsp</span></a>
<?		}
	}
	else{   ?>
		<p class="enter">Вход на сайт</p>
		<form name="login" method="post">

			<table border=0 align="left">
				<tr>
					<td class="td_1">Логин</td>
					<td class="td_2">	<input class="input_login"type="text" name="login" size="15" >	</td>
				</tr>
				<tr>
					<td class="td_1">Пароль</td>
					<td class="td_2">
						<input type="password" name="password" size="15" >
					</td>
				</tr>
				<tr>
					<td class="td_1" colspan="2">
						<input type="submit" value="Войти" class="button">
					</td>
				</tr>
				<tr>	<td><p></p>	</td>	<td>	</td>	</tr>
				<tr>
					<td colspan="2">
						<a href="../reg/">
							<div class="reg">
								Регистрация  
								<div class="rus">
									rus
									<div class="flag"></div>
								</div>

							</div>
						</a>
					</td>
				</tr>
			</table> 
		</form>
<?	}   ?>
			</div>   <!--   regplecetext   -->
		</div>     <!--   regplace   -->
<?		if(isset($user_id)){
			$query_in = $connect->query("SELECT * FROM message WHERE receiver = '$user_id' AND del_receiver = '0'");
			$num_in = $query_in->num_rows;
			$query_out = $connect->query("SELECT * FROM message WHERE sender = '$user_id' AND del_sender = '0' ");
			$num_out = $query_out->num_rows;	
			$query_new = $connect->query("SELECT * FROM message WHERE receiver = '$user_id' AND `new` = '1' ");
			$num_new = $query_new->num_rows; 			?>
		<div class = "email_block">
			<a href = "../email/index.php?email=in" style = "cursor: pointer;"> Письма</a> <br>
			<font style = "font-size: 12pt;">  
				<a href = "../email/index.php?email=in" style = "cursor: pointer;">Входящие - <?if($num_new > 0){echo "<font style = \"color: #f33; font-weight: bolder; font-size: 1.2em;\">$num_new</font>/";} echo $num_in?> </a> <br>
				<a href = "../email/index.php?email=out" style = "cursor: pointer;">Исходящие - <?=$num_out?> </a> 	
			</font>
		</div>	
<?		}					?>
		<div class="news">
			<a href = "../news/" style = "cursor: pointer;"> Новости и факты</a>
<?				$query = $connect->query("SELECT * FROM news ORDER BY news_id DESC LIMIT 5");

				for($i = 1; $i <= 5; $i++){			// 
					$arr = $query->fetch_array(MYSQLI_ASSOC);
					foreach($arr as $key => $value) {
						$$key = $value;
					}      ?>
					<a href = "../news/index.php?news_id=<?=$news_id?>">
						<p style = "font-size: 10pt;" ><?=$news_date?><br><?=$news_name?></p>
					</a>	
<?				} 				?>		
		</div>
		<div class = "advertising">
			<br>
			<img src = "../images/bilbord.png" style = "width: 350px; opacity: 1; position: relative; left: -50px;"> 
		</div>
		<br><br><br><br>
	</div>   <!-- left_block-->
