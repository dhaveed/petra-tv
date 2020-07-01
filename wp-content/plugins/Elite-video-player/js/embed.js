(function($) {
	$(document).ready(function(){
	
		var videoplayers = $(".Elite_video_player");

		$.each(videoplayers, function(){
			var id = $(this).attr('id');
			
			var options = $(this).find("#elite_options").html();
			
			var json_str = options.replace(/&quot;/g, '"');
			
			json_str = json_str.replace(/“/g, '"');
			json_str = json_str.replace(/”/g, '"');
			json_str = json_str.replace(/″/g, '"');
			json_str = json_str.replace(/„/g, '"');
			json_str = json_str.replace(/amp;/g, '');
			
			options = jQuery.parseJSON(json_str);
			
			function convertStrings(obj) {

                $.each(obj, function(key, value) {
                    if (typeof(value) == 'object' || typeof(value) == 'array') {
                        convertStrings(value)
                    } else if (!isNaN(value)) {
                        if (obj[key] === "")
                            delete obj[key]
                        else
                            obj[key] = Number(value)
                    } else if (value == "true") {
                        obj[key] = true
                    } else if (value == "false") {
                        obj[key] = false
                    }
                });

            }
            convertStrings(options)

			if(options.playerLayout == "fitToContainer"){
				if(options.lightBox){
					var vp_Container = $(this).css({
						width: options.lightBoxThumbnailWidth,
						height: options.lightBoxThumbnailHeight,
						position: "relative",
						display:"inline-flex",
						margin:10
					});
				}
				else{
					var vp_Container = $(this).css({
						width: "100%",
						height: options.videoPlayerHeight,
						position: "relative"
					});
				}
			}
			else if(options.playerLayout == "fixedSize"){
				if(options.lightBox){
					var vp_Container = $(this).css({
						width: options.lightBoxThumbnailWidth,
						height: options.lightBoxThumbnailHeight,
						position: "relative",
						display:"inline-flex",
						margin:10
					});
				}
				else{
					var vp_Container = $(this).css({
						width: options.videoPlayerWidth,
						height: options.videoPlayerHeight
					});
				}
			}
			else if(options.playerLayout == "fitToBrowser"){
				var fixedCont = $("<div />")
				.addClass("fixedCont")
				.css({
						position: 'fixed',
						width: '100%',
						height: '100%',
						top: 0,
						left: 0,
						background: '#000000',
						zIndex: 2147483647
					});
				videoplayers.parent().append(fixedCont);
				videoplayers.appendTo(fixedCont);
			}
			vp_Container = $(this).css({direction:"ltr"});
			
			if(typeof Video != 'function'){
				var i = setInterval(function(){
					if(typeof Video == 'function'){
						vp_Container.Video(options);
						clearInterval(i);
					}
				}, 200);
			}
			else{
				vp_Container.Video(options);
			}
		})
	});
}(jQuery));