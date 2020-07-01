jQuery(document).ready(function ($) {
	"use strict"; 
	jQuery('a.viem-watch-later').on('click', function(e){
		e.preventDefault();
		
		var $_this = jQuery(this),
		action_type = $_this.attr('data-action'),
		video_id = $_this.attr('data-video'),
		video_id_remove = $_this.attr('data-remove');
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: viem_ajax_video_watch_later_object.ajaxurl,
			data:{
				'action' : 'jcajaxwatchlater',
				'video_id' : video_id,
				'action_type' : action_type,
			},
			success: function(data){
				jQuery($_this).attr('title', data.message );
				var $show_pp_ms = jQuery('#viem-popup-container').find('.notification-action-renderer');
				jQuery($show_pp_ms).html( data.message ).addClass('active');
				
				if( data.action_type_change == 'add' ){
					jQuery($_this).attr('data-action', 'add');
					jQuery($_this).removeClass('addedToWatchLater');
				}else{
					jQuery($_this).attr('data-action', 'remove');
					jQuery($_this).addClass('addedToWatchLater');
				}
				
				if( typeof(video_id_remove) != 'undefined' ){
					//console.log(' video_id_remove = ' + video_id_remove);
					jQuery($_this).closest('#post-'+video_id_remove).remove();
				}
				
				setTimeout(function(){
					jQuery($show_pp_ms).removeClass('active');
				}, 3000);
				
			},
			error: function(e) {
				console.log(e);
			}
		});
		
	});
});