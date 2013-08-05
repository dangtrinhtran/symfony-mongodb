jQuery(document).ready(function($) {
	$('.status-post > input').uniform();
	$('input[type="file"]').filestyle({classButton: "btn btn-primary"});

	/**
	 * Upload image with JQuery Ajax
	 * @author Rony<rony@likipe.se>
	 * @return {FormData} File upload
	 */
	$('#fileupload').change(function(e){
		/*var aFileUpload = new Array();
		var file = $("#fileupload").val();
		
		var sContent = '{';
		sContent += '"filename":"' + file + '"';
		sContent += '}';
		aFileUpload[0] = $.parseJSON(sContent);
		*/
		var aFileUpload = new FormData();
		aFileUpload.append("filesUpload", this.files[0]);
		
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
				alert('success');
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
