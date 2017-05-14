jQuery(document).ready(function($) {

	$('.the-image').css('background-color','green');

	$(".file-upload").ajaxfileupload({
		'action': test_url_submit,
		'onComplete': function(response) {
			var json_obj = $.parseJSON(response);
			console.log(json_obj);

			var url = json_obj['url'];

			$(this).next('.box-with-content').html('<img src="'+url+'" />');
		},

	});

});
