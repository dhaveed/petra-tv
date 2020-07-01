<?php
/**
 * @package DawnThemes
 */

if( !class_exists('DawnThemes_Mega_Menu_Walker_Core') ):
	/*
	 * Walker for the FrontEnd Mega menu
	 */
	class DawnThemes_Mega_Menu_Walker_Core extends Walker_Nav_Menu{

		protected $index = 0;
		protected $menuItemOptions;
		protected $noMegaMenu;

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Calls parent function in wp-includes/class-wp-walker.php
		 */
		function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
			if ( !$element )
				return;

			//Add indicators for top level menu items with submenus
			$id_field = $this->db_fields['id'];
			$element->classes[] = 'level' . $depth;
			if ( $depth == 0 && !empty( $children_elements[ $element->$id_field ] ) ) {
				$element->classes[] = 'menu-item-has-sub-content';
			}
			
			$id_field = $this->db_fields['id'];

			//display this element
			if ( is_array( $args[0] ) )
				$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
			if($this->getMegaMenuOption($element->menu_item_parent,'menu_style') == 'preview'){
				if($depth == 1 && is_array($args[0]))
					$args[0]['parentMega'] = 'preview';
			} elseif(is_array($args[0])){
					$args[0]['parentMega'] = $this->getMegaMenuOption($element->menu_item_parent,'menu_style');
			}
			
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			
			call_user_func_array(array($this, 'start_el'), $cb_args);

			$id = $element->$id_field;
					
			// descend only when the depth is right and there are childrens for this element
			if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id])) {
				if(isset( $children_elements[$id])){
					foreach( $children_elements[ $id ] as $child ){

						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							
							$sidebar_name = $this->getMegaMenuOption($element->$id_field,'addSidebar');
							$args = array(array("id"=>$element->$id_field,"title"=>$element->title,'addSidebar'=>$sidebar_name));
							
							
							if($depth == 0)
								$args[0]["parentMega"] = $this->getMegaMenuOption($element->$id_field,'menu_style') ;
							else
								$args[0]["parentMega"] = $this->getMegaMenuOption($element->menu_item_parent,'menu_style') ;
								
							
							$cb_args = array_merge( array(&$output, $depth), $args);
													
							call_user_func_array(array($this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
					unset( $children_elements[ $id ] );
				}
			}

			if ( isset($newlevel) && $newlevel ){
				//end the child delimiter
				$args = array(array("id"=>$element->$id_field,"title"=>$element->title));
				
				$args[0]["parentMega"] = $this->getMegaMenuOption($element->$id_field,'menu_style');
				
				$cb_args = array_merge( array(&$output, $depth), $args);
				call_user_func_array(array($this, 'end_lvl'), $cb_args);
			}

			//end this element
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array($this, 'end_el'), $cb_args);
		}
		
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			if($depth == 0){
				if(isset($args["parentMega"]) && $args["parentMega"] == 'preview'){
					$output .= "\n$indent<div class=\"sub-content sub-preview\"><ul class=\"sub-grid-tabs\">";
				} elseif(isset($args["parentMega"]) && $args["parentMega"] == 'columns') {
					$output .= "\n$indent<div class=\"sub-content sub-menu-box-grid sub-columns\"><ul class=\"columns\">\n";
				} else {
					$output .= "\n$indent<ul class=\"sub-menu sub-menu-list level0\">\n";
				}
			} else {
				
				if(isset($args["parentMega"]) && $args["parentMega"] == 'columns'){
					$output .= "\n<li class=\"column\"><ul class=\"list\"><li class=\"header\">".$args["title"]."</li>\n";				
				} else {
					$output .= "\n$indent<ul class=\"sub-menu level" . $depth . "\">\n";
				}
			}
		}
		
		function end_lvl( &$output, $depth = 0, $args = array() ){
			$indent = str_repeat( "\t", $depth );
			if($depth == 0){
				if(isset($args["parentMega"]) && $args["parentMega"] == 'preview'){
					$output .= "\n$indent</ul></div>"; // end <ul class="sub-grid-tabs">
				} elseif(isset($args["parentMega"]) && $args["parentMega"] == 'columns') {
					$output .= "\n$indent</ul></div>\n"; // end <ul class="columns">
				} else {
					$output .= "</ul>";
				}
			} else {
				if(isset($args["parentMega"]) && $args["parentMega"] == 'columns')
					$output .= "\n$indent</ul></li>\n";
				else
					$output .= "</ul>";
			}
		}

		function getMegaMenuOption( $item_id , $id ){
			$option_id = 'menu-item-'.$id;

			//Initialize array
			if( !is_array( $this->menuItemOptions ) ){
				$this->menuItemOptions = array();
				$this->noMegaMenu = array();
			}

			//We haven't investigated this item yet
			if( !isset( $this->menuItemOptions[ $item_id ] ) ){
				
				$megamenu_options = false;
				if( empty( $this->noMegaMenu[ $item_id ] ) ) {
					$megamenu_options = get_post_meta( $item_id , '_megamenu_options', true );
					if( !$megamenu_options ) $this->noMegaMenu[ $item_id ] = true; //don't check again for this menu item
				}

				//If $megamenu_options are set, use them
				if( $megamenu_options ){
					$this->menuItemOptions[ $item_id ] = $megamenu_options;
				} 
				//Otherwise get the old meta
				else{
					$option_id = '_menu_item_'.$id;
					return get_post_meta( $item_id, $option_id , true );
				}
			}
			return isset( $this->menuItemOptions[ $item_id ][ $option_id ] ) ? stripslashes( $this->menuItemOptions[ $item_id ][ $option_id ] ) : '';
		}
		
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
			$args = (object)$args;
			
			// check display logic
			$display_logic = $this->getMegaMenuOption( $item->ID, 'displayLogic' );
			if(($display_logic == 'guest' && is_user_logged_in()) || ($display_logic == 'member' && !is_user_logged_in())){
				return;
			}
			if(isset($classes)){
				unset($classes['list-style']);
			}
			global $wp_query;
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	 
			//Handle class names depending on menu item settings
			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			
			if($depth == 1 && $args->parentMega == 'preview'){
				$classes[] = 'grid-title';
			}
			if( $depth == 0){
				$opt_menu_style = $this->getMegaMenuOption( $item->ID, 'menu_style' );
				if( $opt_menu_style !== '' ){
					$classes[] = $opt_menu_style.'-style';
				}
			}


			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			$class_names = ' '. esc_attr( $class_names ) . '';

			$options = get_option('megamenu_options');
			
			if($depth == 1 && $args->parentMega == 'preview'){
				$post_type = 'any';
				/* if you want exactly what kind of post types which belong to this category
				 * uncomment & edit code below
				 * ====================
				 * if($item->object = 'custom-taxonomy') $post_type = 'custom-post-type';
				 * ====================
				 */

				if($options['ajax_loading'] != 'on'){
					$output .= '<div class="sub-grid-content" id="grid-'.$item->ID.'"><div class="sub-grid-item-list">';
					
					$helper = new DawnThemes_Mega_Menu_Content_Helper();
					
					switch($item->object){
						case 'category':
							$output .= $helper->get_latest_category_items($item->object_id);
							break;
						case 'post_tag':
							$output .= $helper->get_latest_items_by_tag($item->object_id);
							break;
						case 'page':
							$output .= $helper->get_page_content($item->object_id);
							break;
						case 'post':
							$output .= $helper->get_post_content($item->object_id);
							break;
						case 'product_cat':
							$output .= $helper->get_woo_product_items($item->object_id);
							break;
						default:						
							$output .= $helper->get_latest_custom_category_items($item->object_id, $item->object,$post_type);
							break;
					}
					
					
					$output .= '</div></div>';
				}
				
				$output .= /*$indent . */'<li id="mega-menu-item-'. $item->ID . '"' . $value .' class="'. $class_names .'" data-target="grid-'.$item->ID.'" data-type="'.$item->type.'" data-post="'.$post_type.'" data-object="'.$item->object.'" data-id="'.$item->object_id.'">';
			} else if($depth != 1){
				$output .= /*$indent . */'<li id="mega-menu-item-'. $item->ID . '"' . $value .' class="'.$class_names.'">';
			}else{
				
			}

			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_url( $item->url        ) .'"' : '';
			
			
			$item_output = '';
			
			/* Add title and normal link content - skip altogether if nolink and notext are both checked */
			if( !empty( $item->title ) && trim( $item->title ) != '' ){
				
				//Determine the title
				$title = apply_filters( 'the_title', $item->title, $item->ID );
				
				if(!empty($args->before)){
					$item_output = $args->before;
				}
				if(!in_array("header",$classes)){
					$item_output.= '<a'. $attributes .'>';
				}
				
				
				$opt_icon = $this->getMegaMenuOption( $item->ID, 'icon' );
				$opt_iconPos = $this->getMegaMenuOption( $item->ID, 'iconPos' );
				$opt_caretDownPos = $this->getMegaMenuOption( $item->ID, 'caretDownPos' );
				
				
				
				if($depth == 0 && $opt_caretDownPos == 'left'){
					if($options['icon_mainmenu_parent'] != ''){
						$item_output .= "<i class='fa " . $options['icon_mainmenu_parent'] . "'></i>";
					} else {
						$item_output .= "<i class='fa fa-caret-down'></i>";
					}
				}
					if($depth == 1){
						if($options['icon_subchannel_item_left'] != ''){
							$item_output .= "<i class='fa " . $options['icon_subchannel_item_left'] . "'></i>";
						} else {
							$item_output.= '';
						}
					}
				if(!empty( $args->link_before)){
					$item_output.= $args->link_before;
				}
				// add menu icon
				if($opt_icon != '' && $opt_iconPos == 'left'){
					$title = "<i class='fa " . $opt_icon . "'></i>" . $title;
				} else if($opt_icon != '' && $opt_iconPos == 'right'){
					$title .= "<i class='fa " . $opt_icon . "'></i>";
				}
				
				//Text - Title
				$prepend='';
				$append='';
				$item_output.= $prepend . $title . $append;
				
				//Description
				$description ='';
				$item_output.= $description;
				
				//Link After
				if(!empty($args->link_after)){ 
					$item_output.= $args->link_after;
				}
				//Close Link or emulator
				if($depth == 0){
					if($opt_caretDownPos == 'right'){
						if($options['icon_mainmenu_parent'] != ''){
							$item_output .= "<i class='fa " . $options['icon_mainmenu_parent'] . "'></i>";
						} else {
							$item_output .= "<i class='fa fa-caret-down'></i>";
						}
					}
				} else if($depth == 1 && $args->parentMega == 'preview'){
					if($options['icon_subchannel_item_right'] != ''){
						$item_output .= "<i class='fa " . $options['icon_subchannel_item_right'] . "'></i>";
					} else {
						$item_output.= '<i class="fa fa-chevron-right"><!-- --></i>';
					}
				}
				
				if(!in_array("header",$classes)){
					$item_output.= '</a>';
				}
				
				//Append after Link
				if(!empty($args->after)){
					$item_output .= $args->after;
				}
			}
			$with_child ='';
			if (in_array("parent", $classes)){
				$with_child ='parent';	
			}
			if($depth == 1 && isset($args->parentMega) && $args->parentMega == 'columns'){
				$sidebar = $this->getMegaMenuOption( $item->ID, 'addSidebar' );
				if($sidebar != '0'){
					ob_start();
					dynamic_sidebar($sidebar);
					$html = ob_get_contents();
					ob_end_clean();
					$output .= '<li class="column"><ul class="list"><li class="header menu-sidebar-title">' . $item->title . '</li><li class="megamenu-widgets">'. $html .'</li></ul>';
				} else {				
					$output .= '';
				}
			} else {
				if((!isset($args->parentMega) || $args->parentMega == 'list') && $depth == 1){
					$output .= apply_filters( 'walker_nav_menu_start_el', '<li class="menu-item level'.($depth).' '.$with_child.''.$class_names.'">'.$item_output, $item, $depth, $args );
				}elseif ( (!isset($args->parentMega) && $depth == 1) || ( isset($args->parentMega) && $args->parentMega == '') ){
					$output .= apply_filters( 'walker_nav_menu_start_el', '<li class="menu-item level'.($depth).' '.$with_child.''.$class_names.'">'.$item_output, $item, $depth, $args );
				} else{
					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
				}
			}
		}

		function end_el(&$output, $item, $depth = 0, $args = array()) {
			$output .= "</li>";
		}
	}
endif;

if( !class_exists('DawnThemes_Mega_Menu_Walker_Edit') ):
	/*
	 * Mega menu walker edit
	 */
	class DawnThemes_Mega_Menu_Walker_Edit extends Walker_Nav_Menu  {
		
		/**
		 * @see Walker_Nav_Menu::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 */
		function start_lvl(&$output, $depth = 0, $args = array()) {}

		/**
		 * @see Walker_Nav_Menu::end_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {
		}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param object $args
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			ob_start();
			$item_id = esc_attr( $item->ID );
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);

			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
				if ( is_wp_error( $original_title ) )
					$original_title = false;
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title = $original_object->post_title;
			}

			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
			);

			$title = $item->title;

			if ( ! empty( $item->_invalid ) ) {
				$classes[] = 'menu-item-invalid';
				/* translators: %s: title of menu item which is invalid */
				$title = sprintf( esc_html__( '%s (Invalid)', 'viem' ), $item->title );
			} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf( esc_html__('%s (Pending)', 'viem'), $item->title );
			}

			$title = empty( $item->label ) ? $title : $item->label;

			?>
			<li id="menu-item-<?php echo esc_attr($item_id);?>" class="<?php echo implode(' ', $classes);?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><?php echo esc_html($title);?></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html($item -> type_label);?></span>
							<span class="item-order hide-if-js">
								<a href="<?php
								echo wp_nonce_url(add_query_arg(array('action' => 'move-up-menu-item', 'menu-item' => $item_id, ), remove_query_arg($removed_args, esc_url( admin_url('nav-menus.php') ) ) ), 'move-menu_item');
								?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'viem');?>">&#8593;</abbr></a>
								|
								<a href="<?php
								echo wp_nonce_url(add_query_arg(array('action' => 'move-down-menu-item', 'menu-item' => $item_id, ), remove_query_arg($removed_args, esc_url( admin_url('nav-menus.php')) )), 'move-menu_item');
								?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'viem');?>">&#8595;</abbr></a>
							</span>
							<a class="item-edit" id="edit-<?php echo esc_attr($item_id);?>" title="<?php esc_attr_e('Edit Menu Item', 'viem');?>" href="<?php
								echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? esc_url( admin_url( 'nav-menus.php' ) ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, esc_url( admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) );
							?>"><?php esc_html_e('Edit Menu Item', 'viem');?></a>
						</span>
					</dt>
				</dl>

				<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr($item_id);?>">
					<?php if( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo esc_attr($item_id);?>">
								<?php esc_html_e('URL', 'viem');?><br />
								<input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id);?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> url);?>" />
							</label>
						</p>
					<?php endif;?>
					<p class="description description-thin">
						<label for="edit-menu-item-title-<?php echo esc_attr($item_id);?>">
							<?php esc_html_e('Navigation Label', 'viem');?><br />
							<input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id);?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> title);?>" />
						</label>
					</p>
					<p class="description description-thin">
						<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id);?>">
							<?php esc_html_e('Title Attribute', 'viem');?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id);?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> post_excerpt);?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo esc_attr($item_id);?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id);?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id);?>]"<?php checked($item -> target, '_blank');?> />
							<?php esc_html_e('Open link in a new window/tab', 'viem');?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo esc_attr($item_id);?>">
							<?php esc_html_e('CSS Classes (optional)', 'viem');?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id);?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr(implode(' ', $item -> classes));?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo esc_attr($item_id);?>">
							<?php esc_html_e('Link Relationship (XFN)', 'viem');?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id);?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> xfn);?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo esc_attr($item_id);?>">
							<?php esc_html_e('Description', 'viem');?><br />
							<textarea id="edit-menu-item-description-<?php echo esc_attr($item_id);?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id);?>]"><?php echo esc_html($item -> description);
								// textarea_escaped
	 ?></textarea>
							<span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'viem');?></span>
						</label>
					</p>
					
					<?php do_action( 'megamenu_menu_item_options', $item_id );?>

					<div class="menu-item-actions description-wide submitbox">
						<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
							<p class="link-to-original">
								<?php printf(esc_html__('Original: %s', 'viem'), '<a href="' . esc_attr($item -> url) . '">' . esc_html($original_title) . '</a>');?>
							</p>
						<?php endif;?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id);?>" href="<?php
						echo wp_nonce_url(add_query_arg(array('action' => 'delete-menu-item', 'menu-item' => $item_id, ), remove_query_arg($removed_args, esc_url( admin_url('nav-menus.php') ))) , 'delete-menu_item_' . $item_id);
	 ?>"><?php esc_html_e('Remove', 'viem');?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id);?>" href="<?php	echo esc_url(add_query_arg(array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg($removed_args, admin_url('nav-menus.php'))));?>#menu-item-settings-<?php echo esc_attr($item_id);?>"><?php esc_html_e('Cancel', 'viem');?></a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item_id);?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> object_id);?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> object);?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> menu_item_parent);?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> menu_order);?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id);?>]" value="<?php echo esc_attr($item -> type);?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
			<?php
			$output .= ob_get_clean();
		}
	}
endif;

if( !class_exists('DawnThemes_Mega_Menu_Content_Helper') ):
	class DawnThemes_Mega_Menu_Content_Helper{
		/*
		 * Get 3 Latest posts in custom category (taxonomy)
		 *
		 * $post_type: post type to return
		 * $tax: type of custom taxonomy
		 * $cat_id: custom taxonomy ID
		 * Return HTML
		 */
		function get_latest_custom_category_items($cat_id, $tax, $post_type = 'any'){

			$term = get_term_by('id',$cat_id,$tax);
			if($term === false){
				return;
			}

			$posts_per_page = apply_filters('viem_megamenu_preview_posts_per_page', 5);

			$args = array('posts_per_page'=> $posts_per_page,'post_type'=>$post_type,$tax=>$term->slug);

			$query = new WP_Query($args);

			$html = '';

			ob_start();
			
			global $post;
			$temp_post = $post;
			
			while($query->have_posts()) : $query->the_post();

			?>
			<div class="grid-item">
				<?php 
				if( has_post_thumbnail() ){?>
					<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php the_title_attribute(); ?>">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'viem-megamenu-524x342' ); ?>
					</a>
				<?php
				} ?>
				<h3 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h3>
			</div>
			<?php
			endwhile;

			$html = ob_get_contents();
			ob_end_clean();

			wp_reset_postdata();
			
			$post = $temp_post;

			return $html;
		}

		/*
		 * Get 3 Latest posts in category
		 *
		 * Return HTML
		 */
		function get_latest_category_items($cat_id, $post_type = 'post'){
			$args = array('posts_per_page'=>3,'category'=>$cat_id,'post_type'=>$post_type);

			$posts = get_posts($args);
			$html = '';

			ob_start();

			global $post;
			$tmp_post = $post;
			$options = get_option('megamenu_options');
			$sizes = $options['thumbnail_size'];
			$width = 520; $height = 354;

			if($sizes != '') {
				$sizes = explode('x',$sizes);
				if(count($sizes) == 2){
					$width = intval($sizes[0]);
					$height = intval($sizes[1]);
					if($width == 0) $width = 200;
					if($height == 0) $height = 200;
				}
			}

			foreach($posts as $post) : setup_postdata($post);
			?>
			<div class="grid-item col-md-4">
				<div class="grid-item-post">
					<div class="entry-item-wrap">
						<div class="img-wrap">
							<a href="<?php the_permalink(); ?>" class="image" title="<?php the_title();?>">
								
								<?php the_post_thumbnail( apply_filters( 'viem-megamenu-thumbnail', 'viem-megamenu-preview-thumbnail' ) );?>
							</a>
						</div>
					</div>
						<h3 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h3>
				</div>
			</div>
			<?php
			endforeach;
			$html = ob_get_contents();
			ob_end_clean();
			
			$temp_post='';
			$post = $temp_post;
			
			wp_reset_postdata();
			
			return $html;
		}

		/*
		 * Get 3 Latest WooCommerce/JigoShop Products in category
		 *
		 * Return HTML
		 */
		function get_woo_product_items($cat_id){
			$html = '';

			// get slug by ID
			$term = get_term_by('id',$cat_id,'product_cat');
			if($term){
				$args = array('posts_per_page'=>3,'product_cat'=>$term->slug,'post_type'=>'product');
				$posts = get_posts($args);
				ob_start();
				global $post;
				$tmp_post = $post;
				$options = get_option('megamenu_options');

				$sizes = $options['thumbnail_size'];
				$width = 200;$height = 200;
				if($sizes != '') {
					$sizes = explode('x',$sizes);
					if(count($sizes) == 2){
						$width = intval($sizes[0]);
						$height = intval($sizes[1]);
						if($width == 0) $width = 200;
						if($height == 0) $height = 200;
					}
				}

				foreach($posts as $post) : setup_postdata($post);
					
					if (class_exists('WC_Product')) {
						// WooCommerce Installed
						global $product;
					} else if(class_exists('jigoshop_product')){
						$product = new jigoshop_product( $post->ID ); // JigoShop
					}
				?>
				<div class="grid-item">
					<?php $options['image_link'] = 'on'; if($options['image_link'] == 'on'){?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title();?>">
						<?php the_post_thumbnail(array($width,$height));?>
					</a>
					<?php } else {?>
					<?php the_post_thumbnail(array($width,$height));?>
					<?php }?>
					<h3 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php if ( ($options['show_price'] == 'left') && $price_html = $product->get_price_html() ) { echo wp_kses( $price_html, array('del'=>array(), 'ins'=>array(), 'span'=>array('class'=>array())) ); } ?> <?php the_title();?> <?php if ( (!isset($options['show_price']) || $options['show_price'] == '') && $price_html = $product->get_price_html() ) { echo wp_kses( $price_html, array('del'=>array(), 'ins'=>array(), 'span'=>array('class'=>array())) ); } ?></a></h3>
				</div>
				<?php
				endforeach;
				$html = ob_get_contents();
				ob_end_clean();

				$post = $temp_post;
				
				wp_reset_postdata();
			}
			return $html;
		}

		/*
		 * Get page content
		 *
		 * Return HTML
		 */
		function get_page_content($page_id){
			$page = get_page($page_id);

			$html = '';
			if($page){
				ob_start();
				?>
				<div class="page-item">
					<h3 class="title"><a href="<?php echo get_permalink($page->ID); ?>" title="<?php echo esc_attr($page->post_title);?>"><?php echo apply_filters('the_title', $page->post_title);?></a></h3>
					<?php
						$morepos = strpos($page->post_content,'<!--more-->');
						if($morepos === false){
							echo apply_filters('the_content',$page->post_content);
						} else {
							echo apply_filters('the_content',substr($page->post_content,0,$morepos));
						}
					?>
				</div>
				<?php
			}

			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		/*
		 * Get post content
		 *
		 * Return HTML
		 */
		function get_post_content($post_id){
			$page = get_post($post_id);

			$html = '';

			$options = get_option('megamenu_options');
			$sizes = $options['thumbnail_size'];

			$width = 200;$height = 200;
			if($sizes != '') {
				$sizes = explode('x',$sizes);
				if(count($sizes) == 2){
					$width = intval($sizes[0]);
					$height = intval($sizes[1]);
					if($width == 0) $width = 200;
					if($height == 0) $height = 200;
				}
			}

			if($page){
				ob_start();
				?>
				<div class="page-item">
					<h3 class="title"><a href="<?php echo get_permalink($page->ID); ?>" title="<?php echo esc_attr($page->post_title);?>"><?php echo apply_filters('the_title', $page->post_title);?></a></h3>
					<div>
						<div class="thumb">
						<?php echo get_the_post_thumbnail( $page->ID, array($width,$height));?>
						</div>
					<?php
						$morepos = strpos($page->post_content,'<!--more-->');
						if($morepos === false){
							echo apply_filters('the_content',$page->post_content);
						} else {
							echo apply_filters('the_content',substr($page->post_content,0,$morepos));
						}
					?>
					</div>
				</div>
				<?php
			}

			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		/*
		 * Get 3 Latest posts that has tag id
		 *
		 * Return HTML
		 */
		function get_latest_items_by_tag($tag_id, $post_type = 'post'){
			$tag = get_term($tag_id,'post_tag');
			$args = array('showposts'=>3,'tag'=>$tag->slug,'caller_get_posts'=>1,'post_status'=>'publish','post_type'=>$post_type);
			$query = new WP_Query($args);

			$html = '';

			ob_start();
			$options = get_option('megamenu_options');

			$sizes = $options['thumbnail_size'];
			$width = 200;$height = 200;
			if($sizes != '') {
				$sizes = explode('x',$sizes);
				if(count($sizes) == 2){
					$width = intval($sizes[0]);
					$height = intval($sizes[1]);
					if($width == 0) $width = 200;
					if($height == 0) $height = 200;
				}
			}

			while($query->have_posts()) : $query->the_post();
			?>
			<div class="grid-item">
				<?php if($options['image_link'] == 'on'){?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title();?>">
					<?php the_post_thumbnail(array($width,$height));?>
				</a>
				<?php } else {?>
				<?php the_post_thumbnail(array($width,$height));?>
				<?php }?>
				<h3 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h3>
			</div>
			<?php
			endwhile;
			$html = ob_get_contents();
			ob_end_clean();

			$post = $temp_post;
			wp_reset_postdata();
			return $html;
		}
	}
endif;

define('MEGA_MENU_NAV_LOCS', 'wp-mega-menu-nav-locations');

if( !class_exists('DawnThemes_Mega_Menu') ):
	class DawnThemes_Mega_Menu{
		protected $baseURL;

		protected $menu_item_options;
		protected $optionDefaults;

		protected $count = 0;

		function __construct(){
			
			$this->baseURL =  get_template_directory_uri().'/includes/megamenu/';
			$this->menu_item_options = array();

			//ADMIN
			if( is_admin() ){
				add_action( 'admin_menu' , array( &$this , 'admin_init' ) );

				add_filter( 'wp_edit_nav_menu_walker', array( &$this , 'edit_walker' ) , 2000);
				add_action( 'wp_ajax_dt_megamenu_update_nav_locs', array( $this , 'update_nav_locs' ) ); //For logged in users
				add_action( 'wp_ajax_dt_megamenu_add_menu_item', array( $this , 'megamenu_add_menu_item' ) );
				
				//Appearance > Menus : save custom menu options
				add_action( 'wp_update_nav_menu_item', array( &$this , 'update_nav_menu_item' ), 10, 3); //, $menu_id, $menu_item_db_id, $args;
				add_action( 'megamenu_menu_item_options', array( &$this , 'menu_item_custom_options' ), 10, 1);		//Must go here for AJAX purposes

				// front-end Ajax
				add_action( 'wp_ajax_dt_megaMenu_getGridContent', array( &$this , 'get_grid_content' ) );
				add_action( 'wp_ajax_nopriv_dt_megaMenu_getGridContent', array( &$this , 'get_grid_content' ));

				$this->optionDefaults = array(
					'menu-item-isMega'				=> 'off'
				);
			} else {
				$this->init();
			}

			add_action( 'wp_enqueue_scripts', array ($this, 'add_scripts'));
		}

		function init(){
			//Filters
			add_filter( 'wp_nav_menu_args' , array( $this , 'mega_menu_filter' ), 2000 );  	//filters arguments passed to wp_nav_menu
		}

		function admin_init(){
			
			//Appearance > Menus : load additional styles and scripts
			add_action( 'admin_print_styles-nav-menus.php', array( $this , 'load_admin_nav_menu_js' ) );
			add_action( 'admin_print_styles-nav-menus.php', array( $this , 'load_admin_nav_menu_css' ));
		}

		/*
		 * Save the Menu Item Options
		 */
		function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ){
			$megamenu_options_string = isset( $_POST[sanitize_key('megamenu_options')][$menu_item_db_id] ) ? $_POST[sanitize_key('megamenu_options')][$menu_item_db_id] : '';
			$megamenu_options = array();
			parse_str( $megamenu_options_string, $megamenu_options );

			$megamenu_options = wp_parse_args( $megamenu_options, $this->optionDefaults );

			update_post_meta( $menu_item_db_id, '_megamenu_options', $megamenu_options );
		}
		
		function get_grid_content(){
			$data = $_POST[sanitize_key('data')];	 // Array(dataType, dataId, postType)
			$helper = new DawnThemes_Mega_Menu_Content_Helper();
			switch($data[0]){
				case 'category':
					echo viem_print_string( $helper->get_latest_category_items($data[1]) );
					break;
				case 'post_tag':
					echo viem_print_string( $helper->get_latest_items_by_tag($data[1]) );
					break;
				case 'page':
					echo viem_print_string( $helper->get_page_content($data[1]) );
					break;
				case 'post':
					echo viem_print_string( $helper->get_post_content($data[1]) );
					break;
				/* WooCommerce/JigoShop Product Category */
				case 'product_cat':
					echo viem_print_string( $helper->get_woo_product_items($data[1]) );
					break;
				/* Custom Taxonomy */
				default:
					echo viem_print_string( $helper->get_latest_custom_category_items($data[1],$data[0],$data[2]) );
					break;
			}

			die();
		}
		
		/*
		 * Update the Locations when the Activate Mega Menu Locations Meta Box is Submitted
		 */
		function update_nav_locs(){
		
			$data = $_POST[sanitize_key('data')];
			$data = explode(',', $data);
		
			update_option( MEGA_MENU_NAV_LOCS, $data);
		
			echo viem_print_string( $data );
			die();
		}
		
		function megamenu_add_menu_item(){
		
			if ( ! current_user_can( 'edit_theme_options' ) )
				die('-1');
		
			check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );
		
			require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
		
			// For performance reasons, we omit some object properties from the checklist.
			// The following is a hacky way to restore them when adding non-custom items.
		
			$menu_items_data = array();
			foreach ( (array) $_POST[sanitize_key('menu-item')] as $menu_item_data ) {
				if (
					! empty( $menu_item_data['menu-item-type'] ) &&
					'custom' != $menu_item_data['menu-item-type'] &&
					! empty( $menu_item_data['menu-item-object-id'] )
				) {
					switch( $menu_item_data['menu-item-type'] ) {
						case 'post_type' :
							$_object = get_post( $menu_item_data['menu-item-object-id'] );
							break;
		
						case 'taxonomy' :
							$_object = get_term( $menu_item_data['menu-item-object-id'], $menu_item_data['menu-item-object'] );
							break;
					}
		
					$_menu_items = array_map( 'wp_setup_nav_menu_item', array( $_object ) );
					$_menu_item = array_shift( $_menu_items );
		
					// Restore the missing menu item properties
					$menu_item_data['menu-item-description'] = $_menu_item->description;
				}
		
				$menu_items_data[] = $menu_item_data;
			}
		
			$item_ids = wp_save_nav_menu_items( 0, $menu_items_data );
			if ( is_wp_error( $item_ids ) )
				die('-1');
		
			foreach ( (array) $item_ids as $menu_item_id ) {
				$menu_obj = get_post( $menu_item_id );
				if ( ! empty( $menu_obj->ID ) ) {
					$menu_obj = wp_setup_nav_menu_item( $menu_obj );
					$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
					$menu_items[] = $menu_obj;
				}
			}
		
			if ( ! empty( $menu_items ) ) {
				$args = array(
					'after' => '',
					'before' => '',
					'link_after' => '',
					'link_before' => '',
					'walker' =>	new DawnThemes_Mega_Menu_Walker_Edit,
				);
				echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
			}
		}

		function menu_item_custom_options( $item_id ){
			?>

				<!--  START MASHMENU ATTS -->
				<div>
					<div class="wpmega-atts wpmega-unprocessed" style="display:block">
						<input id="megamenu_options-<?php echo esc_attr($item_id);?>" class="megamenu_options_input" name="megamenu_options[<?php echo esc_attr($item_id);?>]" type="hidden" value="" />

						<?php $this->showMenuOptions( $item_id ); ?>

					</div>
					<!--  END MASHMENU ATTS -->
				</div>
		<?php
		}

		function showMenuOptions( $item_id ){
			if(viem_get_theme_option('megamenu', 'on')=='on'){
				$this->showCustomMenuOption(
					'menu_style',
					$item_id,
					array(
						'level'    => '0',
						'title' => esc_html__( 'Select style for Menu' , 'viem' ),
						'label' => esc_html__( 'Menu Style' , 'viem' ),
						'type'     => 'select',
						'default' => '',
						'ops'    => array(
							'list'=>esc_html__('List','viem'),
							'columns'=>esc_html__('Columns','viem'),
							'preview'=>esc_html__('Preview','viem')
						)
					)
				);
			}
			
			/** Get Sidebar **/
			global  $wp_registered_sidebars;
				$arr = array("0"=>"No Sidebar");
				foreach ( $wp_registered_sidebars as $sidebar ) :
			         $arr = array_merge($arr, array($sidebar['id']=>$sidebar['name']));
			    endforeach;
			if(viem_get_theme_option('megamenu', 'on')=='on'){
				$this->showCustomMenuOption(
					'addSidebar',
					$item_id,
					array(
						'level'	=> '1',
						'title' => esc_html__( 'Select the widget area to display' , 'viem' ),
						'label' => esc_html__( 'Display widgets area ' , 'viem' ),
						'type' 	=> 'select',
						'default' => '0',
						'ops'	=> $arr
					)
				);
			}
			/** Get Sidebar **/

			if(viem_get_theme_option('megamenu', 'on')=='on'){
				$this->showCustomMenuOption(
					'displayLogic',
					$item_id,
					array(
						'level'	=> '0',
						'title' => esc_html__( 'Logic to display this menu item' , 'viem' ),
						'label' => esc_html__( 'Display Logic' , 'viem' ),
						'type' 	=> 'select',
						'default' => '',
						'ops'	=> array('both'=>esc_html__('Always visible','viem'),'guest'=>esc_html__('Only Visible to Guests','viem'),'member'=>esc_html__('Only Visible to Members','viem'))
					)
				);
			}
		}

		function showCustomMenuOption( $id, $item_id, $args ){
			extract( wp_parse_args(
				$args, array(
					'level'	=> '0-plus',
					'title' => '',
					'label' => '',
					'type'	=> 'text',
					'ops'	=>	array(),
					'default'=> '',
				) )
			);

			$_val = $this->getMenuItemOption( $item_id , $id );

			$desc = '<span class="ss-desc">'.$label.'<span class="ss-info-container">?<span class="ss-info">'.$title.'</span></span></span>';
			?>
					<p class="field-description description description-wide wpmega-custom wpmega-l<?php echo esc_attr($level);?> wpmega-<?php echo esc_attr($id);?>">
						<label for="edit-menu-item-<?php echo esc_attr($id);?>-<?php echo esc_attr($item_id);?>">

							<?php

							switch($type) {
								case 'text':
									echo viem_print_string( $desc );
									?>
									<input type="text" id="edit-menu-item-<?php echo esc_attr($id);?>-<?php echo esc_attr($item_id);?>"
										class="edit-menu-item-<?php echo esc_attr($id);?>"
										name="menu-item-<?php echo esc_attr($id);?>[<?php echo esc_attr($item_id);?>]"
										size="30"
										value="<?php echo htmlspecialchars( $_val );?>" />
									<?php

									break;
								case 'checkbox':
									?>
									<input type="checkbox"
										id="edit-menu-item-<?php echo esc_attr($id);?>-<?php echo esc_attr($item_id);?>"
										class="edit-menu-item-<?php echo esc_attr($id);?>"
										name="menu-item-<?php echo esc_attr($id);?>[<?php echo esc_attr($item_id);?>]"
										<?php
											if ( ( $_val == '' && $default == 'on' ) ||
													$_val == 'on')
												echo 'checked="checked"';
										?> />
									<?php
									echo viem_print_string( $desc );
									break;
								case 'select':
									echo viem_print_string( $desc );
									if( empty($_val) ) $_val = $default;
									?>
									<select
										id="edit-menu-item-<?php echo esc_attr($id); ?>-<?php echo esc_attr($item_id); ?>"
										class="edit-menu-item-<?php echo esc_attr($id); ?>"
										name="menu-item-<?php echo esc_attr($id);?>[<?php echo esc_attr($item_id);?>]">
										<?php foreach( $ops as $opval => $optitle ): ?>
											<option value="<?php echo esc_attr($opval); ?>" <?php if( $_val == $opval ) echo 'selected="selected"'; ?> ><?php echo esc_html($optitle); ?></option>
										<?php endforeach; ?>
									</select>
									<?php
									break;
							}
	 						?>

						</label>
					</p>
		<?php
		}

		function getMenuItemOption( $item_id , $id ){

			$option_id = 'menu-item-'.$id;

			//We haven't investigated this item yet
			if( !isset( $this->menu_item_options[ $item_id ] ) ){

				$megamenu_options = get_post_meta( $item_id , '_megamenu_options', true );
				//If $megamenu_options are set, use them
				if( $megamenu_options ){
					//echo '<pre>'; print_r( $megamenu_options ); echo '</pre>';
					$this->menu_item_options[ $item_id ] = $megamenu_options;
				}
				//Otherwise get the old meta
				else{
					return get_post_meta( $item_id, '_menu_item_'.$id , true );
				}
			}
			return isset( $this->menu_item_options[ $item_id ][ $option_id ] ) ? $this->menu_item_options[ $item_id ][ $option_id ] : '';

		}

		/*
		 * Custom Walker Name - to be overridden by Standard
		 */
		function edit_walker( $className ){
			return 'DawnThemes_Mega_Menu_Walker_Edit';
		}

		/*
		 * Default walker, but this can be overridden
		 */
		function get_walker(){
			return new DawnThemes_Mega_Menu_Walker_Core();
		}

		function get_menu_args( $args ){
			$new_articles = '';
			ob_start();
			global $wpdb;
			$number_day         = viem_get_theme_option('lns_number_days');
			if($number_day != ''):
			$limit_latest_news  = viem_get_theme_option('lns_maxinum_articles');
			$latest_news_str    = '';
			$limit_date = is_numeric($number_day) ? date('Y-m-d', strtotime('-' . $number_day . ' day')) : date('Y-m-d');

			$options = array(
				'post_type'         => 'post',
				'posts_per_page'    => $limit_latest_news,
				'orderby'           => 'post_date',
				'post_status'       => 'publish',
				'date_query'        => array(
						'after'         => $limit_date
								),
				'ignore_sticky_posts'   => true
			);
			$the_query = new WP_Query( $options );
			$query_count = $wpdb->get_results( 'select a.ID, a.post_title from ' . $wpdb->prefix .'posts as a where a.post_date >="' . $limit_date . '" and a.post_status = "publish" and a.post_type = "post"');
			?>
	            <li class="post-toggle">
	                <a class="link toggle" href="javascript:void(0)">
	                    <span class="post-count"><?php echo count($query_count);?></span>
	                    <span class="post-heading"><?php echo esc_html__('NEWS','viem');?> <i style="display:inline-block" class="fa fa-angle-down"></i></span>
	                </a>
	                <div class="sub-menu-box sub-menu-box-post article-dropdown item-post-menu item-list-post">
	                    <?php
	                        if($the_query->have_posts()): 
							$i = 0;
							$count = $the_query->post_count;
							$item_per_column = ceil($count / 3);
							$col = 1;
							while($the_query->have_posts()): $the_query->the_post();
	                            if($i % $item_per_column == 0){
								?>
										<div class="col-md-4">
									<?php }?>
								<article class="item item-post item-post-menu-post article-content clearfix">
									<a class="thumb" href="<?php echo get_permalink();?>" title="<?php echo get_the_title();?>">
										<?php the_post_thumbnail('xsmall');?>
									</a>
									<h3><a href="<?php echo get_permalink();?>" title="<?php echo get_the_title();?>"><?php echo get_the_title();?></a></h3>
								</article>
							<?php 
								if($i % $item_per_column == ($item_per_column - 1) || $i == $count - 1){
								?>
										</div>
									<?php }
								$i++;
							endwhile; 
							wp_reset_postdata();
						endif;?>
	                </div>
	            </li>
	        <?php endif;?>
	        <?php
			$new_articles .= ob_get_contents();
			ob_end_clean();
			
			$args['walker'] 			= $this->get_walker();
			$args['container_id'] 		= 'dt-megamenu';
			$args['container_class'] 	= 'megamenu viem-megamenu hidden-mobile';
			$args['menu_class']			= 'menu';
			$args['depth']				= 0;
			$args['items_wrap']			= '<ul id="%1$s" class="%2$s main-menu" data-theme-location="">%3$s'. str_replace('%','%%',$new_articles).'</ul>'/*.$css*/;
			$args['link_before']		= '';
			$args['link_after']			= '';
			
			return $args;
		}
		/*
		 * Apply options to the Menu via the filter
		 */
		function mega_menu_filter( $args ){

			//Only print the menu once
			if( $this->count > 0 ) return $args;

			if( isset( $args['responsiveSelectMenu'] ) ) return $args;
			if( isset( $args['filter'] ) && $args['filter'] === false ) return $args;

			//Check to See if this Menu Should be Megafied
			if(!isset($args['is_megamenu']) || !$args['is_megamenu']){
				return $args;
			}
			
			$this->count++;

			$items_wrap 	= '<ul id="%1$s" class="%2$s" data-theme-location="primary-menu">%3$s</ul>'; //This is the default, to override any stupidity

			$args['items_wrap'] = $items_wrap;

			$args = $this->get_menu_args( $args );

			return $args;
		}

		function add_scripts(){
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '.min';
			wp_enqueue_script('megamenu-js', $this->baseURL.'js/megamenu'.$suffix.'.js', array('jquery'), '', true);
			
			wp_localize_script( 'megamenu-js', 'dt_megamenu', array( 'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),'ajax_loader'=>'','ajax_enabled'=>0) );
		}

		function load_admin_nav_menu_js(){
			wp_enqueue_script('megamenu-admin-js', $this->baseURL.'js/megamenu.admin.js', array('jquery'), '', true);
		}

		function load_admin_nav_menu_css(){
			wp_enqueue_style('megamenu-admin-css',$this->baseURL.'css/megamenu.admin.css');
		}
	}

	new DawnThemes_Mega_Menu();
endif;
