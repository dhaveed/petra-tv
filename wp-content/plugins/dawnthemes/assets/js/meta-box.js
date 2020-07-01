function dt_trim(str, charlist) {
  //  discuss at: http://phpjs.org/functions/trim/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: mdsjack (http://www.mdsjack.bo.it)
  // improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Steven Levithan (http://blog.stevenlevithan.com)
  // improved by: Jack
  //    input by: Erkekjetter
  //    input by: DxGx
  // bugfixed by: Onno Marsman
  //   example 1: trim('    Kevin van Zonneveld    ');
  //   returns 1: 'Kevin van Zonneveld'
  //   example 2: trim('Hello World', 'Hdle');
  //   returns 2: 'o Wor'
  //   example 3: trim(16, 1);
  //   returns 3: 6

  var whitespace, l = 0,
    i = 0;
  str += '';

  if (!charlist) {
    // default list
    whitespace =
      ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
  } else {
    // preg_quote custom list
    charlist += '';
    whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
  }

  l = str.length;
  for (i = 0; i < l; i++) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(i);
      break;
    }
  }

  l = str.length;
  for (i = l - 1; i >= 0; i--) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(0, i + 1);
      break;
    }
  }

  return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

;(function($){
	$(document).ready(function(){
		if ($('#post-formats-select').length > 0) {
			var format = $('#post-formats-select input:checked').attr('value');
			$('#post-body div[id^=dt-metabox-post-]').hide();
			$('#post-body #dt-metabox-post-'+format+'').show();
			$('#post-formats-select').find('input').on('click',function(){
				var format = $(this).attr('value');
				$('#post-body div[id^=dt-metabox-post-]').hide();
				$('#post-body #dt-metabox-post-'+format+'').stop(true,true).fadeIn(500);
			});
		}
		
		$('.dt-metaboxes .dt-image-select li img').click(function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).closest('.dt-image-select').find('li.selected').removeClass("selected");
			$(this).closest('li').addClass('selected');
			$(this).closest('label').find('input[type="radio"]').prop('checked',false);
			$(this).closest('label').find("input[type='radio']").prop("checked", true);
		});
		
		//page heading
		var dt_page_heading_callback = function(el){
			var $this = $(el);
			var arr = ['_dt_heading_menu_anchor_field','_dt_page_heading_background_image_field','_dt_page_heading_sub_title_field','_dt_page_heading_title_field'];
			var harr = ['_dt_heading_menu_anchor_field','_dt_highligh_cat_field','_dt_highligh_intro_cat_field'];
			var revarr = ['_dt_heading_menu_anchor_field','_dt_rev_alias_field'];
			var hero = ['_dt_heading_menu_anchor_field'];
			$.each(arr,function(i,ar){
				$('.'+ar).hide();
			});
			$.each(harr,function(i,har){
				$('.'+har).hide();
			});
			$.each(revarr,function(i,revar){
				$('.'+revar).hide();
			});
			if($this.val() == 'heading'){
				$.each(arr,function(i,ar){
					$('.'+ar).show();
				});
			}else if($this.val() == 'highlighted_post'){
				$.each(harr,function(i,har){
					$('.'+har).show();
				});
			}else if($this.val() == 'rev'){
				$.each(revarr,function(i,revar){
					$('.'+revar).show();
				});
			}else if($this.val() == 'landingHero'){
				$.each(hero,function(i,her){
					$('.'+her).show();
				});
			}
		}
		// if($('#_dt_page_heading').length){
		// 	dt_page_heading_callback($('#_dt_page_heading'));
		// 	$('#_dt_page_heading').on('change',function(){
		// 		var $this = $(this);
		// 		dt_page_heading_callback($this);
		// 	});
		// }

		var dawnthemes_meta_dependency = function(section,field,dependency, dependency_value){
			var $this_section = $(section);
			var $dependency_value_arr = dependency_value.split(',');

			var $dependency_val_def = $($this_section).find('#_dt_'+dependency).val();
			//console.log($dependency_val_def); 
			//console.log($dependency_value_arr);
			if(typeof $dependency_val_def !== 'undefined' ){
				if( $.inArray( $dependency_val_def,  $dependency_value_arr) == -1 ){
					// the element is not in the array
					dawnthemes_meta_dependency_callback(field,"hide");
				}else{
					dawnthemes_meta_dependency_callback(field,"show");
				}
			}
			
			$($this_section).find('#_dt_'+dependency).on('change', function(){
				var $this = $(this);
				if( $.inArray( $this.val(),  $dependency_value_arr) == -1 ){
					// the element is not in the array
					dawnthemes_meta_dependency_callback(field,"hide");
				}else{
					dawnthemes_meta_dependency_callback(field,"show");
				}
			});
		}

		var dawnthemes_meta_dependency_callback = function(field, display){
			if( display == "show" ){
				$(field).show();
			}else{
				$(field).hide();
			}
		}

		if( $('.dawnthemes-meta-options').length ){
			$('.dawnthemes-meta-options').each(function(){
				var $this_section = $(this);
				$('.dt-meta-box-field').each(function(){
					var $_dependency = $(this).find('.dt_meta_field_dependency');
					if( $_dependency.length ){
						var $this_field = $($_dependency).closest('.dt-meta-box-field');
						var $dependency = $($_dependency).data("dependency");
						var $dependency_value = $($_dependency).data("dependency-value");
						dawnthemes_meta_dependency($this_section,$this_field,$dependency,$dependency_value);
					}else{}
				})
			});
		}

		$('.dawnthemes-meta-options').removeClass('hidden');

	});
})(jQuery);