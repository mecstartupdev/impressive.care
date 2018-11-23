jQuery(function($) {
	// mobile search
	$('#search_icon').on('click', function() {
		$('#page-container').toggleClass('search_mobile');
	});
	// subscribe
	$('.subscribe-popup-content #es_txt_email').attr('placeholder', 'Enter your email address');
	setTimeout(function(){
		$('#mobile_menu').prepend( '<li>' + $('.head_subscribe')[0].outerHTML + '</li>' );
		subscribe_actions()
	}, 100);
	function subscribe_actions() {
		$('.head_subscribe').on('click', function() {
			$('.subscribe-popup').css('display', 'flex');
		});
		$('.subscribe-close').on('click', function() {
			$('.subscribe-popup').hide();
		});
	}
});