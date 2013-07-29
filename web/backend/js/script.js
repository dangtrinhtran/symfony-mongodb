jQuery(document).ready(function($) {
	$('#post_featuredimage, .status-post > input').uniform();
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