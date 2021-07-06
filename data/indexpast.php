<?php
class SiteResultsProvider {

	private $baglan;

	public function __construct($baglan) { // ana sınıf kurucusu oluşturuyoruz.
		$this->baglan = $baglan;
	}

	public function getNumResults($kelime) {  // Sorgu sonrası gelen bilgilerin kac adet olduğunu buluyoruz.

		$query = $this->baglan->prepare("SELECT COUNT(*) as total 
										 FROM sites WHERE title LIKE :kelime 
										 OR url LIKE :kelime
										 OR keywords LIKE :kelime 
										 OR description LIKE :kelime");

		$searchTerm = "%". $kelime . "%";
		$query->bindParam(":kelime", $searchTerm);
		$query->execute();

		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row["total"];

	}

	public function getResultsHtml($sayfa, $sayfamiktar, $kelime) {  // sites ' ayarları ve index'de arama

		$sayfa_Limit = ($sayfa - 1) * $sayfamiktar;  

		// formül;

		//sayfamiktari = 20 olarak düşünürsek;  // göremediğimiz link sayıları

		// sayfa 1 için (1-1) * 20 = 0;
		// sayfa 2 için (2-1) * 20 = 20;
		// sayfa 3 için (3-1) * 20 = 40;


		$query = $this->baglan->prepare("SELECT * 
										 FROM sites WHERE title LIKE :kelime 
										 OR url LIKE :kelime 
										 OR keywords LIKE :kelime 
										 OR description LIKE :kelime
										 ORDER BY clicks DESC
										 LIMIT :sayfa_Limit,:sayfamiktar");

		$searchTerm = "%". $kelime . "%";
		$query->bindParam(":kelime", $searchTerm);
		$query->bindParam(":sayfa_Limit", $sayfa_Limit,PDO::PARAM_INT);
		$query->bindParam(":sayfamiktar", $sayfamiktar,PDO::PARAM_INT);
		$query->execute();


		$siteYaz = "<div class='siteResults'>";


		while($row = $query->fetch(PDO::FETCH_ASSOC)) 

		{  // Site Sınırlandırmalar

			$id = $row["id"];
			$url = $row["url"];
			$title = $row["title"];
			$description = $row["description"];

			$title = $this ->Sinirlama($title, 100); // başlık karekter sınırlandırılması
			$description = $this ->Sinirlama($description, 230);  // karekter sınırlaması

			$siteYaz .= "<div class='siteCıktısı'>

						<h3 class='baslık'>
							<a class='linkurl' href='$url' data-linkId='$id'>
							 $title
							</a>
						</h3>
						<span class='link_Nedir'>$url</span>
						<span class='Acıklama'>$description</span>
						</div>";			
		}
		
		$title = mb_convert_encoding($title, "UTF-8", "auto");
		$description = mb_convert_encoding($description, "UTF-8", "auto");
		$siteYaz = mb_convert_encoding($siteYaz, "UTF-8", "auto");

		$siteYaz .= "</div>";

		return $siteYaz;
	}

	private function Sinirlama($string , $charecterLimit){
		$dots=strlen($string) > $charecterLimit ? "..." : "";
		return substr($string, 0, $charecterLimit) . $dots;
	}

}
?>