(function($){
	$('document').ready(function(){
		$('.edd_download_file_link').each(function(){
			if ($(this).attr('href').indexOf('c4dendtime=1') >= 0) {
				$(this).addClass('c4d-edd-time-end');
			}
		});
	});
})(jQuery);