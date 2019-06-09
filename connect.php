<?php
error_reporting(E_ALL);

$connect = new mysqli("localhost", "max1303_mistor", "362718aB", "max1303_mistor");

if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}

mysqli_query($connect, "SET NAMES utf8");

// -============================================	Константы	=====================================================-

//define("PATH", "http://localhost/mistor.su");   // 	просто как вариант

const PATH = "https://mistor.su";


const PATH_DIR = "/home/m/max1303/public_html";	//	для рабочего ноутбука

//const PATH_DIR = "C:/Ampps/www/mistor.su";   // 	для домашнего ноутбука

const REFRESH = '<meta http-equiv = "refresh" content = "0">';   //	обновляем страницу

const REFRESH_3 = '<meta http-equiv = "refresh" content = "3">'; 
?>