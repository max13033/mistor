<script type="text/javascript">

window.onload = loaded;

function loaded(){
	load.style.display = 'none';
	document.getElementById("load_img").style.display = 'none';
	load_cont.style.display = 'none';
}
//	проверяем логин при регистрации

function regLogin(){				
	var get_login = document.getElementById("reg_login").value;	  //	
	if(get_login.length < 2 || get_login.length > 20){
		alert("Логин должен содержать от 2 до 20 символов");
		return;
	}

	if(get_login.search(/[^a-zA-Z0-9-_]/) != '-1'){ 
		alert('Логин должен состоять из латинских букв, цифр или занков _ и -');
		return;
	}

}

//	проверяем пароль при регистрации

function regPas(){				
	var get_pas = document.getElementById("reg_pas").value;
	if(get_pas.length < 6 || get_pas.length > 15){
		alert("Пароль должен содержать от 6 до 15 символов");
		return;
	}

	if(get_pas.search(/[^a-zA-Z0-9-_]/) != '-1'){ 
		alert("Пароль должен состоять из латинских букв, цифр или занков _ и -");
		return;
	}
} 

function checkMail(){
	var email = document.getElementById("email").value;

	if(email.search(/[^a-zA-Z0-9-_\.\@]/) != '-1'){
		alert("Введите корректный адрес почты");
		return;
	}
}

function checkTel() {
	var tel = document.getElementById("telephone").value;
	if(tel.match(/^[8][9]\d{9}$/) == null){
		if(tel != ""){ 
			alert("Введите корректный номер телефона или оставьте поле пустым.");
		}	
	}
}

function checkVin(xvin){  // НА СТРАНИЦЕ "ГАРАЖ"
	if(xvin.match(/^[a-zA-Z0-9]{17}$/) == null){
		if(xvin != ""){
			alert("Введите корректный vin или оставьте поле пустым.")
		}
	}

}
function checkVin2(xvin2){  // НА СТРАНИЦЕ ПОИСКА
	if(xvin2.match(/^[a-zA-Z0-9]{17}$/) == null){
		alert("Введите корректный vin.");
	}

}

function owner(){
	var select = document.getElementsByName('owner_to_m')[0].value;
	if(select == "Настоящее время"){
		document.getElementsByName('owner_to_y')[0].style.display = 'none';
		document.getElementsByName('owner_to_y')[0].value = '';
	}
	else{
		document.getElementsByName('owner_to_y')[0].style.display = 'inline';

	}
}

// function checkModel(){
// 	var model = document.getElementsByName('model')[0].value;
// 	if(model == null){
// 		alert("Вы");
// 	}
// }
function bigger(id, story, j, nImg){   // УВЕЛИЧЕНИЕ КАРТИНКИ ПРИ НАЖАТИИ
	var imgSrc = id;
	bigger.story = story;
	bigger.j = j;
	bigger.imgnum = nImg;
	document.getElementById('big_pic').src = imgSrc;  				// ПРИСВАИВАЕМ БОЛЬШОЙ КАРТИНКЕ ПУТЬ 
	document.getElementById('big_bg').style.display = 'block';   	// ПОКАЗАВАЕМ ФОН
	document.getElementById('big_cont').style.display = 'block';	// ПОКАЗЫВАЕМ КОНТЕЙНЕР С КАРТИНКОЙ
	var text = document.getElementsByName('comment' + story)[j].innerHTML;
	document.getElementsByClassName('comment')[0].innerHTML = text;
	num_amount();
}
function prev(){
	var pj;
	var story = bigger.story
	if(bigger.j == 0){
		pj = 0;
	}
	else{
		pj = parseInt(bigger.j) - 1;
		bigger.j = pj;
	}
	var pname = document.getElementsByName(bigger.story)[pj].id; 			// считываем id (путь к картинке) У ПРЕДЫДУЩЕГО ЭЛЕМЕНТА С ИМЕНЕМ ИСТОРИИ
	document.getElementById('big_pic').src = pname;							// ПРИСВАИВАЕМ ПУТЬ КАРТИНКИ К БОЛЬШОЙ КАРТИНКЕ 
	var text = document.getElementsByName('comment' + story)[pj].innerHTML; // СЧИТЫВАЕМ У ЭЛЕМЕНТА С ИМЕНЕМ commenrStory_id и 	порядковым номером предыдущей j его содержимое.
	document.getElementsByClassName('comment')[0].innerHTML = text; 		// ПРИСВАИВАЕМ ЭТО СОДЕРЖИМОЕ ЭЛЕМЕНТУ С КЛАССОМ COMMENT
	num_amount();
}
function next(){
	var nj;
	var story = bigger.story
	if(bigger.j == bigger.imgnum - 1 ){
		nj = bigger.imgnum - 1;
	}
	else{
		nj = parseInt(bigger.j) + 1;
		bigger.j = nj;
	}
	var nname = document.getElementsByName(bigger.story)[nj].id;
	document.getElementById('big_pic').src = nname;
	var text = document.getElementsByName('comment' + story)[nj].innerHTML;
	document.getElementsByClassName('comment')[0].innerHTML = text;
	num_amount();
}
function num_amount(){
	var text = bigger.j + 1 + '/' + bigger.imgnum;
	document.getElementById('num_amount').innerHTML = text;
}
function comment(pic_id){
	var text = document.getElementById(pic_id).innerHTML;
	document.getElementsByClassName('comment')[0].innerHTML = text;
}
function show_all_pic(story){
	if(show_all_pic.condition == null){
		show_all_pic.condition = "close";
	}

	if(show_all_pic.condition == 'open'){
		document.getElementById(story).style.height = '200px';
		show_all_pic.condition = 'close';
		return;
	}

	if(show_all_pic.condition = 'close'){
		document.getElementById(story).style.height = '';
		show_all_pic.condition = 'open';
		return;
	}	

}
function checkCity(x){
	if(x == ""){
		var newcity = prompt('Введите название населённого пункта.');
		if(newcity != null && newcity != ''){
			if(newcity.search(/[^а-яА-Я0-9-\. ]/) != '-1'){
				alert('Используйте только русские буквы.');
			}
			else{newCity(newcity);}
		}
	}
}
function newCity(city){
	document.getElementById('newcity').style.display = 'inline';
	document.getElementById('newcity').value = city;
}






















</script>