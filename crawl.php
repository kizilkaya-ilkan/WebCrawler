<!DOCTYPE html>
<html>
<head>
	<title>YBO Bot 1.0</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
<?php
header('Content-Type: text/html; charset=UTF-8');

include("crawler.php");
include("config.php");
include("data/DomDocumentParser.php");

$alreadyCrawled = array();
$crawling = array();
$alreadyFoundImages = array();

$LearningCrawl=$_GET['LearningCrawl'];

function linkExists($url){

	global $baglan;

	$query= $baglan ->prepare("SELECT * FROM sites WHERE url=:url");

	$query->bindParam(":url",$url);
	$query->execute();

	return $query->rowCount() != 0;
}


function insertLink($url,$title,$description,$keywords){

	global $baglan;

	$query= $baglan ->prepare("INSERT INTO sites(url,title,description,keywords) 
								VALUES(:url,:title,:description,:keywords)");

	$query->bindParam(":url",utf8_decode($url));
	$query->bindParam(":title",utf8_decode($title));
	$query->bindParam(":description",utf8_decode($description));
	$query->bindParam(":keywords",utf8_decode($keywords));

	return $query->execute();
}

function insertImage($url, $src, $alt, $title) {

	global $baglan;

	$query = $baglan->prepare("INSERT INTO images(siteUrl, ImageUrl, alt, title)
							VALUES(:siteUrl, :ImageUrl, :alt, :title)");

	$query->bindParam(":siteUrl", $url);
	$query->bindParam(":ImageUrl", $src);
	$query->bindParam(":alt", $alt);
	$query->bindParam(":title", $title);

	return $query->execute();
}


function createLink($src, $url) {

	$scheme = parse_url($url)["scheme"]; 
	$host = parse_url($url)["host"]; 
	
	if(substr($src, 0, 2) == "//") {
		$src =  $scheme . ":" . $src;
	}
	else if(substr($src, 0, 1) == "/") {
		$src = $scheme . "://" . $host . $src;
	}
	else if(substr($src, 0, 2) == "./") {
		$src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
	}
	else if(substr($src, 0, 3) == "../") {
		$src = $scheme . "://" . $host . "/" . $src;
	}
	else if(substr($src, 0, 5) != "https" && substr($src, 0, 4) != "http") {
		$src = $scheme . "://" . $host . "/" . $src;
	}

	return $src;
}

function getDetails($url) {

	global $alreadyImages;

	$parser = new DomDocumentParser($url);

	$titleArray = $parser->getTitleTags();

	if(sizeof($titleArray) == 0 || $titleArray->item(0) == NULL) {
		return;
	}

	$title = $titleArray->item(0)->nodeValue;
	$title = str_replace("\n", "", $title);

	if($title == "") {
		return;
	}

	$description = "";
	$keywords = "";

	$metasArray = $parser->getMetatags();

	foreach($metasArray as $meta) {

		if($meta->getAttribute("name") == "description") {
			$description = $meta->getAttribute("content");
		}

		if($meta->getAttribute("name") == "keywords") {
			$keywords = $meta->getAttribute("content");
		}
	}

	$description = str_replace("\n", "", $description);
	$keywords = str_replace("\n", "", $keywords);

	if(linkExists($url)){
		
		// echo " Adet Veri Veritabanında mevcut!!<b><br>";
	}
	else if (insertLink($url,$title,$description,$keywords)) {

		echo "YBO Veritabanına Site eklendi -> $url<br>";
	}
	else{

		// echo " Hata:Veritabanı Eklenemedi!!! $url<b><br>";
	}

	$imageArray = $parser->getImages();
	foreach($imageArray as $image) {
		$src = $image->getAttribute("src");
		$alt = $image->getAttribute("alt");
		$title = $image->getAttribute("title");

		if(!$title && !$alt) {
			continue;
		}

		$src = createLink($src, $url);

		if(!in_array($src, $alreadyFoundImages)) {
			$alreadyFoundImages[] = $src;

			echo "YBO Veritabanına Resim eklendi -> $src <br>";
		}

	}


}

function followLinks($url) {

	global $alreadyCrawled;
	global $crawling;

	$parser = new DomDocumentParser($url);

	$linkList = $parser->getLinks();  // tüm linkleri linklist'e atama

	foreach($linkList as $link) {
		
		$href = $link->getAttribute("href");

		if(strpos($href, "#") !== false) { // çekilen veride # varsa atla
			continue;
		}
		if(strpos($href, "") !== false) {  // boş veri varsa atla
			continue;
		}
		else if(substr($href, 0, 11) == "javascript") { // js linki varsa atla
			continue;
		}
		

		$href = createLink($href, $url);


		if(!in_array($href, $alreadyCrawled)) {
			$alreadyCrawled[] = $href;
			$crawling[] = $href;

			getDetails($href);
		}

	}

	array_shift($crawling);

	foreach($crawling as $site) {
		followLinks($site);
	}

}

  // crawler yapıcagımız url
followLinks($LearningCrawl);
?>
</body>
</html>


