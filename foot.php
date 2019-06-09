	<footer style = "margin-top: 20px; position: relative; background-color: #070707;">
		
		<div style = "padding-top: 10px; font-size: 14pt; font-weight: normal;">
			<a href = "../main/" style = "margin-right: 20px">Главная</a> 
			<a href = "../news/" style = "margin-right: 20px">Новости</a>
			<a href = "../invest/" style = "margin-right: 20px">Инвестиции</a>
			<a href = "https://xn--90adear.xn--p1ai/check/fines" target="_blank" style = "margin-right: 20px">Проверить штрафы</a>
			<a href = "http://yasvidetel.ru/" target="_blank" style = "margin-right: 20px">Свидетель ДТП</a>
			<a href = "../about_me/" style = "margin-right: 20px">Об авторе</a>
		
		</div> 


		<div style = "position: absolute; width: 100%; bottom: 0; margin: 5px 20px;">
			На сайте зарегистрировано:&nbsp;&nbsp; 
<?			$helmets = $connect->query("SELECT user_id FROM users"); 
			$hel_num = $helmets->num_rows;
			echo $hel_num;							?>			
			<img style = "height: 25px; position: relative; top: 3px;" src = ../images/helmet.png>
			&nbsp; и &nbsp;
<?			$wd = $connect->query("SELECT moto_id FROM moto"); 
			$wd_num = $wd->num_rows;
			echo $wd_num;							?>				 
			<img style = "height: 25px; position: relative; top: 6px;" src = ../images/whiteducati.jpg> 
		</div>		
      &nbsp;&nbsp; 
      <!-- Yandex.Metrika informer -->
<a href="https://metrika.yandex.ru/stat/?id=51190979&amp;from=informer"
target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/51190979/3_0_202020FF_000000FF_1_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="51190979" data-lang="ru" /></a>
<!-- /Yandex.Metrika informer -->

	</footer>

</div>  <!---main  (start on head.php) -->

</body>
</html>