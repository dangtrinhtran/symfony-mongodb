jQuery(document).ready(function($) {
	$('.status-post > input').uniform();
	$('input[type="file"]').filestyle({classButton: "btn btn-primary"});

	/**
	 * Upload image with JQuery Ajax
	 * @author Rony<rony@likipe.se>
	 * @return {FormData} File upload
	 */
	$('#fileupload').change(function(e){
		
		var aFileUpload = new FormData();
		aFileUpload.append("filesUpload", this.files[0]);
			var sHtml = '';
			sHtml += '<li class="image-item">';
			sHtml += '<img class="img-thumbnail img-current" src="https://localhost/symfony-mongodb/web/backend/images/ajax-loader.gif" >';
			sHtml += '</li>';
			$(sHtml).appendTo('.images-response');
		$.ajax({
			type: 'POST',
			url: Routing.generate('LikipeProductBundle_Product_uploadAjax'),
			//contentType: 'application/json',
			name: 'filesUpload',
			//data: JSON.stringify(aFileUpload),
			processData: false,
			contentType: false,
			data: aFileUpload,
			success: function(data) {
				var $img = $('.images-response li img.img-current');
				var url = 'https://localhost/symfony-mongodb/web/' + data.url;
				$img.attr('src', url);
				$img.removeClass('img-current');
			}
		});
		e.preventDefault();
		}).submit();
});

(function() {
	var input = document.getElementById("post_featuredimage");

	function showUploadedItem(source) {
		var $img = $('#img-preview');
		$img.attr('src', source);
	}
	if (input !== null) {
		input.addEventListener("change", function(evt) {

			var i = 0, len = this.files.length, img, reader, file;

			for (; i < len; i++) {
				file = this.files[i];

				if (!!file.type.match(/image.*/)) {
					if (window.FileReader) {
						reader = new FileReader();
						reader.onloadend = function(e) {
							showUploadedItem(e.target.result, file.fileName);
						};
						reader.readAsDataURL(file);
					}
				}
			}

		}, false);
	}
}());
