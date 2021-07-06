<?php

include("config.php");
include("data/indexpast.php");
include("data/imagepast.php");
include_once("./fonksiyon.php");



if (isset(($_GET["kelime"]))) {
	$kelime=$_GET["kelime"];
	}
else
	{
	exit("Aranacak Kelime Boş Bırakılamaz !!");
	}

	$type = isset($_GET["type"]) ? $_GET["type"] : "sites"; // arama tipi sites olarak ayarlandı
	$sayfa = isset($_GET["sayfa"]) ? $_GET["sayfa"] : "1"; // başlangıc sayfa sayisi default:1 olarak entegre edildi.
?>

<!DOCTYPE html>
<html>
<head>
	 <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title> YBO Arama Motoru</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
	<link rel="stylesheet" type="text/css" href="data/css/style.css">
	<style type="text/css">
    body {color:aqua;}
    p.stilim{color:FF0000;}
    h4{color:orange;}
    p{color:#0033FF;}
   </style>  
</head>
<body>
	<div class="wrapper">
		<div class="header">
			<div class="logoContainer">
				<a href="index.php">
			<img src="https://www.resimupload.org/images/2021/04/10/logo25d4e1c9cb08487ea.png" alt="logo25d4e1c9cb08487ea.png" border="0" width="180" height="100" />
				</a>
			</div>
			<div class="searchContainer">
				<form action="ara.php" method="GET">

					<div class="searchBarContainer">

						<input type="hidden" name="type" value="<?php echo $type; ?>">
						<input class="searchBox" type="text" name="kelime" value="<?php echo $kelime ?>">
						
						<button class="button123"><img src="data:image/png;base64,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" width="30" height="30" ></button>
					</div>
					<div class="mainResultsSection">

			<?php

			if ($type == "sites") {
				$sonuc = new SiteResultsProvider($baglan);

			 $sonuc_adet= $sonuc->getNumResults($kelime);

			 echo "<p class='sonuc_miktar'>$sonuc_adet sonuç bulundu</p>";
			}
			else{
				$sonuc = new imageResultsProvider($baglan);

			 $sonuc_adet= $sonuc->getNumResults($kelime);

			 echo "<p class='sonuc_miktar'>$sonuc_adet sonuç bulundu</p>";
			}
			
			?>


		</div>

			 </div>

			 <div class="tabsContainer">

				<ul class="tabList">
	 				<li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
	 					<a href='<?php echo "ara.php?kelime=$kelime&type=sites"; ?>'>
	 						Site  
	 					</a>
	 				</li>
	 				<li class="<?php echo $type == 'images' ? 'active' : '' ?>">
	 					<a href='<?php echo "ara.php?kelime=$kelime&type=images"; ?>'>
	 						Resimler  
	 					</a>
	 				</li>
	 				<li class="<?php echo $type == 'video' ? 'active' : '' ?>">
	 					<a href='<?php echo "ara.php?kelime=$kelime&type=video"; ?>'>
	 						Videolar  
	 				</a>
	 				</li>
	 				<li class="<?php echo $type == 'haberler' ? 'active' : '' ?>">
	 					<a href='<?php echo "ara.php?kelime=$kelime&type=haberler"; ?>'>
	 						Haberler  
	 				</a>
	 				</li>
	 			</ul>

				</form>

			</div>
		</div>

		<div class="site_ara"> 
			<?php
			if($type == "sites")
			{
				$sonuc = new SiteResultsProvider($baglan); //site-->>verileri gösterme
				$sayfa_Limit =20;
			}
			else{
				$sonuc = new imageResultsProvider($baglan); //resim-->>verileri gösterme
				$sayfa_Limit = 35;
			}



			
			echo $sonuc->getResultsHtml($sayfa, $sayfa_Limit, $kelime); // sayfa 1 den baslayıp limiti kadar gösterim yapma
			
			?>

			<div class="paginationContainer"> 

			<div class="pageButtons">



				<div class="pageNumberContainer"> 
					<img src="data/images/1.png"> 
				</div>

				<?php // sayfalar arası geçiş.. page=1,page=2 vb YBOOOOO gibi.

				$sayfagöster = 10;
				$toplam_Sayfa_Sayisi = ceil($sonuc_adet / $sayfa_Limit); // (veritabanındaki bulunan kelime kaynak / sayfa limiti belirlediğimiz.)
				$sayfa_sol_Taraf_hesap = min($sayfagöster, $toplam_Sayfa_Sayisi); // (sayfa hakkında =  min(sayfa göster - bulunan adet)) sol tarafı hesaplamak için

				$Gecerli_Sayfa = $sayfa - floor($sayfagöster / 2); // geçerli sayfa sayısı bulma 

				if($Gecerli_Sayfa < 1) { // gösterim sayfası 1'den küçükse 1 e eşitle 
					$Gecerli_Sayfa = 1;
				}

				if($Gecerli_Sayfa + $sayfa_sol_Taraf_hesap > $toplam_Sayfa_Sayisi + 1) { // simge ileri yönelik hesaplama mesela 82 adet öge 4 sayfa etmesini sağlıyor
					$Gecerli_Sayfa = $toplam_Sayfa_Sayisi + 1 - $sayfa_sol_Taraf_hesap;
				}
			
				

				while($sayfa_sol_Taraf_hesap != 0 && $Gecerli_Sayfa <= $toplam_Sayfa_Sayisi) {  // 

					if($Gecerli_Sayfa == $sayfa) {
						echo "<div class='pageNumberContainer'>
								<img src='data/images/tik.png'>
								<span class='pageNumber'>$Gecerli_Sayfa</span>
							</div>";
					}
					else { // ziyarete hazır sayfalar
						echo "<div class='pageNumberContainer'>
								<a href='ara.php?kelime=$kelime&type=$type&sayfa=$Gecerli_Sayfa'> 
									<img src='data/images/2.png'>
									<span class='pageNumber'>$Gecerli_Sayfa</span>
								</a>
						</div>";
					}


					$Gecerli_Sayfa++;
					$sayfa_sol_Taraf_hesap--;

				}



  				//en son kısım simgesi YBOOO "O";

				?> 

				<div class="pageNumberContainer">
					<img src="data/images/son.png">
				</div>
			</div>
	</div>
	
	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="data/javaScprt.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	
</body>



</html>
