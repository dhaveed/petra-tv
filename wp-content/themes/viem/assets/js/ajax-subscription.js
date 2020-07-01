jQuery(document).ready(function ($) {
	"use strict";
	jQuery('a.viem-subscribe-button').on('click', function(e){
		if( jQuery(this).find('.data-subcription').length > 0 ){
			e.preventDefault();
			var $_this = jQuery(this);
			
			var channel_id = jQuery($_this).find('.data-subcription').attr('data-id-sub'),
			user_id = jQuery($_this).find('.data-subcription').attr('data-user-id');
			
			var nonce = jQuery($_this).find('.data-subcription').attr("data-nonce");
			
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: viem_ajax_channel_subscription_object.ajaxurl,
				data:{
					'action' : 'jcajaxsubscription',
					'channel_id' : channel_id,
					'user_id' : user_id,
					'nonce' : nonce,
				},
				success: function(data){
					
					var $show_pp_ms = jQuery('#viem-popup-container').find('.notification-action-renderer');
					jQuery($show_pp_ms).html( data.message ).addClass('active');
					
					if( data.subscribed == 1 ){
						jQuery($_this).removeClass('subscribe');
						jQuery($_this).addClass('subscribed');
					}else{
						jQuery($_this).removeClass('subscribed');
						jQuery($_this).addClass('subscribe');
					}
					
					jQuery($_this).closest('.viem-subscribe-renderer').find('.subscribers-count').html(data.subscriber_count);
					
					setTimeout(function(){
						jQuery($show_pp_ms).removeClass('active');
					}, 3000);
					
				},
				error: function(e) {
					console.log(e);
				}
			});
		}
		
	});
});