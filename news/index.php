<? 			//  01	02	03
session_start();
$pagetitle = "Новости и факты";

require '../head.php';  

error_reporting(E_ALL);?>
	<div class="mainplace">
		<div class ="mainplacetext">
			<h2>Новости и факты</h2>

<?			if(!isset($_GET['news_id'])){ 		// 		02  	?>			

				<table class = "news">
<?					$q_text = "SELECT * FROM news ORDER BY news_id DESC";  //	ВЫБИРАЕМ ВСЕ НОВОСТИ СОРТИРУЕМ ОТ НОВЫХ К СТАРЫМ
					$query_all = $connect->query($q_text);
					$all_news = $query_all->num_rows; 		// 		ПОДСЧИТЫВАЕМ ОБЩЕЕ КОЛИЧЕСТВО НОВОСТЕЙ (СТРОК)
					if(isset($_GET['page']) && $_GET['page'] != 1){ 			// 	ЕСЛИ ВЫБРАНА СТРАНИЦА  И ЭТО НЕ ПЕРВАЯ СТРАНИЦА
						$message = " LIMIT ".(($_GET['page'] - 1)*10).", 10"; 	// 	ДОПИСЫВАЕМ ОГРАНИЧЕНИЕ ПО ВЫВОДУ. (УСЛИ СТРАНИЦА 2, ДОЛЖНО ВЫВОДИТЬСЯ LIMIT 10, 10) СООТВЕТСТВЕННО
					}															// (2-1)*10 = 10 (ВЫВОДИТ С 10 НОВОСТИ).   ЕСЛИ СТ 3, (3-1)*10 = 20 (ВЫВОДИТ С 20-Й НОВОСТИ) И Т.Д.
					else $message = " LIMIT 10"; 		// 	ЕСЛИ СТРАНИЦА НЕ ЗАДАНА ИЛИ СТРАНИЦА = 1 - ПРОСТО ОГРАНИЧИВАЕМ ПЕРВАЕ 10 СТРОК.
					$q_text.= $message;

					$query = $connect->query($q_text);
					$rows = $query->num_rows; 			//	КОЛИЧЕСТВО ОГРАНИЧЕННЫХ СТРОК

					for($i = 1; $i <= $rows; $i++){			// 01     ЗАПУСКАЕМ ЦИКЛ НА ОГРАНИЧЕННОЕ КОЛИЧЕСТВО СТРОК
						$arr = $query->fetch_array(MYSQLI_ASSOC);
						foreach($arr as $key => $value){
							$$key = $value;
						}						?>
						<tr>
							<td>
								<a href = "index.php?news_id=<?=$news_id?>">
									<img style = "max-width: 120px; max-height: 100px; border-radius: 3px;" src = "<?echo PATH; echo '/images/news/'.$news_pic?>">
								</a>
							</td>
						
							<td>
								<a href = "index.php?news_id=<?=$news_id?>" style = "color: #ccc">
									<span style = "font-style: italic; color: #ddd"><?=$news_date?></span>	<br><?=$news_name?>
								</a>	
							</td>	
						</tr>	
						
<?					}  	// 	01 			?>
					<tr>
						<td colspan = "2">
			<?				if($all_news > 10){ 	//	
								$pages = intdiv($all_news - 1, 10) + 1; 	// intdiv - целый остато от деления (например intdiv(35, 10) = 3) СТРАНИЦ ДОЛЖНО БЫТЬ НА ОДНУ БОЛЬШЕ ЧЕМ
								$current = 1;								// ОСТАТОК ОТ ДЕЛЕНИЯ (НАПРИМЕР СТРАНИЦ 15, ОСТАТОК ОТ ДЕЛ 1, А СТРАНИЦ ДОЛЖНО БЫТЬ 2 (1+1)).  
								if(isset($_GET['page'])){					// ПРИ ЭТОМ, ЕСЛИ КОЛИЧЕСТВО СТРАНИЦ КРАТНО 10 ( НАПРИМЕР 20, 20/10 = 2  + 1 = 3 СТРАНИЦЫ) ПОЭТОМУ
									$current = $_GET['page'];				// ОТ КОЛИЧЕСТВА НОВОСТЕЙ НУЖНО ВЫЧЕСТЬ 1.
								}
								echo func_pages($pages, $current);

							}				?>							
						</td>	
					</tr>
				</table>	<br><br>
<?			} 	// 	02 			
			else{ 	//	03                   ВЫВОДИМ КОНКРЕТНУЮ НОВОСТЬ
				$news_id = $_GET['news_id'];
				$query = $connect->query("SELECT * FROM news WHERE news_id = '$news_id' ");
				$arr = $query->fetch_array(MYSQLI_ASSOC);
				foreach ($arr as $key => $value) {
					$$key = $value;
				}
				?>
				
				<h4>	<?=$news_name?>	</h4>
				<img style = "max-width: 500px; max-height: 400px; border-radius: 7px;" src = "<?echo PATH; echo '/images/news/'.$news_pic?>">
				<p style = "text-align: justify; font-size: 12pt; line-height: 130%; margin: 20px;"> <?=$news_text?> 	</p>
				
				<div style = "text-align: right;"> <?=$news_source?> </div>
				<br>
				<hr>
				<a href = "index.php">Вернуться к списку новостей</a><br><br>
<?			} 	//	03				?>
		</div>
	</div>

<? require '../foot.php';  ?>