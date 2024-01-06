//~ function pageClick1(pageNumber) {
    //~ document.getElementById("page-number-1").innerText = pageNumber;
//~ }
//~ document.addEventListener("DOMContentLoaded", function () {
    //~ var itemsCount = 300;
    //~ var itemsOnPage = 10;

    //~ var pagination1 = new Pagination({
        //~ container: document.getElementById("pagination-1"),
        //~ pageClickCallback: pageClick1
    //~ });
    //~ pagination1.make(itemsCount, itemsOnPage);
//~ });

 
function handleFileUpload() {
	$(".loaderOuter").show();
	var fileInput = document.getElementById('fileToUpload');
	var imagePreview = document.getElementById('imagePreview');

	if (fileInput.files.length > 0) {
		var selectedFile = fileInput.files[0];
		var formData = new FormData();

		formData.append('fileToUpload', selectedFile);

		// Use AJAX to send the file to the server
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'UploadImage.php', true);
		xhr.onload = function () {
			$(".loaderOuter").hide();
			if (xhr.status === 200) {
				
				// Server response handling (if needed)
				var response = JSON.parse(xhr.responseText);
				$("#uploadImage").val(response.name)
				$("#leftImg").attr("src","uploads/"+response.name)
				
				
			}
		};

		// Send the FormData object to the server
		xhr.send(formData);

		// Display a preview of the selected image (optional)
	   
	}

}
$(document).ready(function(){
  $('#generateStoryCheck').on("click", function(e){
		$(".loaderOuter").show();
		
	});
  $('#sumarisedDataOuterButton').on("click", function(e){
		$(".sumarisedDataOuter").show();
		
	});
});

