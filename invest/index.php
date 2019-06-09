<? 
$pagetitle = "Инвестиции";

require '../head.php';  

error_reporting(E_ALL);?>
	<div class="mainplace">
		<div class ="mainplacetext" style = "padding: 10px; box-sizing: border-box;">
			<h4 style = "color: #fff;">Инвестиции</h4>
			<p style = "text-align: justify;" >&nbsp;&nbsp; 
			В последнее время всё больше и больше людей начинают задумываться об инвестициях. 
			Ведь это возможность вложить деньги и получить обратно свою сумму с процентами или 
			получать дивиденды от выручки организации на протяжении многих лет. <br>
			Я собираюсь открыть прокат мотоциклов в Краснодаре и предлагаю Вам стать инвестором в
			этот проект. Минимальная сумма инвестиций - 1000 р. <br>
			</p>
			<n3 style = "font-weight: bold; color:#fff;">Три способа оплаты:</n3>

			<ul style = "text-align: justify;">
				<li>Наличными при встрече (для жителей Краснодара). Заключаем договор, в котором будет прописана размер и сроки возврата.</li> <br>
				<li>На карту Сбербанк (номер карты вышлю по запросу, обращайтесь на почту admin@mistor.su или через форму ниже)  </li> <br>
				<li>На расчётный счёт (реквизиты вышлю по запросу, обращайтесь на почту admin@mistor.su или через форму ниже)"</li>
			</ul>	

			<p style = "text-align: justify;" >&nbsp;&nbsp; В инвестициях, как и в любом деле, есть риски, но начать можно с малой суммы, вряд ли кто-то будет плохо спать по ночам инвестировав 1 - 2 т.р. 
			У Вас есть возможность стать участником в развитии бизнеса, кто знает, во что это вырастит. Те, кто 30 лет назад купил акции Apple наверняка считают тот день одним из лучших в своей жизни :) <br>
			Удачи всем!!!
			</p>
			<form method = "post" >
				<table class = "teh">
					<tr>
						<td>Ваше имя или логин</td>
						<td>	<input type = "text" name = "name" required>	</td>
					</tr>
					<tr>
						<td>Ваша почта</td>
						<td>	<input type = "text" name = "email" required>  </td>
					</tr>
					<tr>
						<td>Текст сообщения</td>
						<td>	<textarea name = "message" cols = "60" required>	</textarea>		</td>
					</tr>
					<tr>	<td>	</td>	<td>	<input type = "submit" value = "Отправить" class = "button">	</td>
					</tr>
				</table>
			</form>	
          <br>
		  <script type="text/javascript" src="https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?MerchantLogin=mistor&InvoiceID=0&Culture=ru&Encoding=utf-8&DefaultSum=1000&SignatureValue=5ae12c5d6686225fc1eed0e97b58b242">
          </script>
          	
<?			
if(isset($_POST['name'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$text = $_POST['message'];

	mail("admin@mistor.su", "Письмо в техподдержку", "$text", "From: $email");
	echo '<div class="mainplace">
			<div class ="mainplacetext">
				<h3> Сообщение успешно отправлено</h3>
			</div>
		</div>';
	require	'../foot.php';		
	echo REFRESH_3;
	exit;
}
?>

		</div>
	</div>


<? require '../foot.php';  ?>