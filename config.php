<?php
ob_start();

try{
	$baglan = new PDO("mysql:dbname=ybo;host=localhost","root" , "123456789");
	$baglan ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOExeption $e){
	echo "Bağlantı Hatasi: " . $e->getMessage();
}
?>