jQuery(document).ready(function($) {
	$('.status-post > input').uniform();
	$('input[type="file"]').filestyle({classButton: "btn btn-primary"});

	function removeFlash() {
		var $flash = $('div.alert-ajax');
		if ($flash.length > 0) {
			setTimeout(function() {
				$flash.fadeOut(500);
			}, 3000);
		}
	}

	/**
	 * Upload image with JQuery Ajax
	 * @author Rony<rony@likipe.se>
	 * @return {FormData} File upload
	 */
	$('#fileupload').change(function(e){
		$('.images-response').children('.alert-ajax').remove();
		var aFileUpload = new FormData();
		aFileUpload.append("filesUpload", this.files[0]);
			var sHtml = '';
			sHtml += '<li class="image-item current span2">';
			sHtml += '<div class="alert alert-danger"><span class="close remove-image">';
			sHtml += '&times;</span><strong>Remove</strong></div>';
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
				var input = '';
				input += '<input name="urlImage[' + data.filesize + ']" value="' + data.url + '" type="hidden" >';
				input += '<input name="requestUrl" value="' + data.url + '" type="hidden" >';
				$(input).appendTo('.image-item.current');
				$('.image-item').removeClass('current');
				$img.removeClass('img-current');
			}
		});
		e.preventDefault();
		}).submit();
		
	/**
	 * Remove image with ajax
	 * When click button close
	 * @author Rony <rony@likipe.se>
	 */
	$(document).on('click', "li.image-item .remove-image", function(e) {
		var $this = $(this);
		var $parent = $this.parents('.image-item');
		var url = $parent.children("input[name='requestUrl']").val();
		var sContent = '{';
		sContent += '"url":"' + url + '"';
		sContent += '}';
		var infoImage = $.parseJSON(sContent);
		$.ajax({
			type: 'DELETE',
			url: Routing.generate('LikipeProductBundle_Product_deleteAjax'),
			contentType: 'application/json',
			data: JSON.stringify(infoImage),
			success: function(data) {
				$parent.remove();
				var sHtml = '';
				sHtml += '<div class="span3 alert alert-success alert-ajax"><button type="button" class="close" data-dismiss="alert">';
				sHtml += '&times;</button><strong>' + data.success + '</strong></div>';
				$(sHtml).appendTo('.images-response');
				removeFlash();
			}
		});
		e.preventDefault();
	});
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
