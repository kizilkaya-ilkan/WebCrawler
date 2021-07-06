$(document).ready(function() {


	$(".linkurl").on("click", function() {
		
		var id = $(this).attr("data-linkId");
		var url = $(this).attr("href");

		if(!id) {
			alert("Link ID bulunamadı :( ");
		}
		
		loginfoGönder(id, url);

		return false;
	});

		var grid = $(".imageResults");

	grid.masonry({
		itemSelector: ".gridItem",
		columnWidth: 200,
		gutter: 5,
		isInitLayout: true
	});
	$("[data-fancybox]").fancybox(); 
});

	

function loginfoGönder(linkId, url) {

	$.post("data/log.php", {linkId: linkId})
	.done(function(linkurl) {
		if(linkurl != "") {
			alert(linkurl);
			return;
		}

		window.location.href = url;
	});
 }