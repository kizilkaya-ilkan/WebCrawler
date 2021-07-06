<?php
class ImageResultsProvider {

	private $baglan;

	public function __construct($baglan) {
		$this->baglan = $baglan;
	}

	public function getNumResults($kelime) {  // adet bulma

		$query = $this->baglan->prepare("SELECT COUNT(*) as total 
										 FROM images WHERE title LIKE :kelime 
										 OR alt LIKE :kelime
										 AND deadLink=0");

		$searchTerm = "%". $kelime . "%";
		$query->bindParam(":kelime", $searchTerm);
		$query->execute();

		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row["total"];

	}

	public function getResultsHtml($sayfa, $sayfamiktar, $kelime) {  //  ' ayarları ve index'de arama

		$sayfa_Limit = ($sayfa - 1) * $sayfamiktar;  

		// formül;

		//sayfamiktari = 20 olarak düşünürsek;

		// sayfa 1 için (1-1) * 20 = 0;
		// sayfa 2 için (2-1) * 20 = 20;
		// sayfa 3 için (3-1) * 20 = 40;


		$query = $this->baglan->prepare("SELECT *
										 FROM images WHERE (title LIKE :kelime 
										 OR alt LIKE :kelime)
										 AND deadLink=0
										 ORDER BY clicks DESC
										 LIMIT :sayfa_Limit,:sayfamiktar");

		$searchTerm = "%". $kelime . "%";
		$query->bindParam(":kelime", $searchTerm);
		$query->bindParam(":sayfa_Limit", $sayfa_Limit,PDO::PARAM_INT);
		$query->bindParam(":sayfamiktar", $sayfamiktar,PDO::PARAM_INT);
		$query->execute();


		$siteYaz = "<div class='imageResults'>";


		while($row = $query->fetch(PDO::FETCH_ASSOC)) {  

			$id = $row["id"];
			$ImageUrl = $row["ImageUrl"];
			$siteUrl = $row["siteUrl"];
			$title = $row["title"];
			$alt = $row["alt"];

			if($title){

				$displayText = $title;
			}
			else if($alt){
				$displayText = $alt;
			}
			else{
				$displayText = $ImageUrl;
			}

			$siteYaz .=  "<div class='gridItem'>  
								<a href='$ImageUrl' data-fancybox>
									<img src='$ImageUrl'>
									<span class='details'>$displayText</span>
								</a>

							</div>";


		}
		

		$siteYaz .= "</div>";

		return $siteYaz;
	}

	 

}
?>