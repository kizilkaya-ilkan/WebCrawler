<?php
class DomDocumentParser {

	private $doc;

	public function __construct($url) {

		$options = array(
			'http'=>array('method'=>"GET", 'header'=>"User-Agent: YBO-BOT/0.1\n")
			);
		$context = stream_context_create($options);

		$this->doc = new DomDocument(); // Doc isminde domdocument yapıda bir değişken oluşturuyoruz.

		@$this->doc->loadHTML(file_get_contents($url, false, $context));
		 // hedef web sitesinin içerigi  doc adı verilen değişkene tanımlanması.
	}


	public function getlinks() {
		return $this->doc->getElementsByTagName("a"); // Link elementleri arasında etkileşimi sağlama fonksiyonu.
	}

	public function getTitleTags() {
		return $this->doc->getElementsByTagName("title"); // başlık tagları ile etkileşimi sağlama fonksiyonu.
	}

	public function getMetaTags() {
		return $this->doc->getElementsByTagName("meta"); // meta tagları ile etkileşimi sağlama fonksiyonu.
	}
	public function getImages() {
		return $this->doc->getElementsByTagName("img"); // resim elementleri ile etkileşim sağlama fonksiyonu.
	}

}
?>