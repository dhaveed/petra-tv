<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Support yoast-seo-breadcrumbs
if ( function_exists('yoast_breadcrumb') ):
	echo '<div class="row">';
		//echo '<div class="col-md-8 col-sm-8"><h2 class="breadcrumbs-title">'.viem_page_title(false).'</h2></div>';
		echo '<div class="breadcrumbs col-md-4 col-sm-4 hidden-xs">'.yoast_breadcrumb('','', false).'</div>';
	echo '</div>';
else:

	/* === THE OPTIONS === */
	$text = array(
	    'home'      => esc_html__('Home','viem'), // text for the 'Home' link
	    'category'  => '%s', // text for a category page
	    'tag'       => esc_html__('Tag','viem').' "%s"', // text for a tag page
	    'search'    => esc_html__('Search Results for','viem').' "%s"', // text for a search results page
	    'author'    => esc_html__('Author','viem').' %s', // text for an author page
	    '404'       => esc_html__('404 Page','viem'), // text for the 404 page
	);
	$show_current   = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
	$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
	$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
	$show_title     = 1; // 1 - show the title for the links, 0 - don't show
	$show_term		= 'post_type'; // 'term' - show category of custom post type, 'post_type' - show post-type
	$delimiter      = '<i class="delimiter fa fa-angle-right" aria-hidden="true"></i>'; // delimiter between crumbs
	if( viem_get_theme_option('rtl', '0') == '1' ) $delimiter = '<i class="delimiter fa fa-angle-left" aria-hidden="true"></i>';
	$before         = '<span class="current">'; // tag before the current crumb
	$after          = '</span>'; // tag after the current crumb
	/* === END OF OPTIONS === */
	
	global $post;
	$home_link    = esc_url( home_url( '/' ) );
	$link_before  = '<span typeof="v:Breadcrumb">';
	$link_after   = '</span>';
	$link_attr    = ' rel="v:url" property="v:title"';
	$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
	$parent_id    = $parent_id_2 = ($post) ? $post->post_parent : 0;
	$frontpage_id = get_option('page_on_front');
	
	
	if (is_front_page()) {
	    if ($show_on_home == 1) echo '<div class="breadcrumbs"><h2 class="breadcrumbs-title"><a href="' . $home_link . '">' . $text['home'] . '</a></h2></div>';
	}elseif(is_home()){
	    //echo '<div class="breadcrumbs"><h2 class="breadcrumbs-title">'.viem_page_title(false).'</h2><a href="' . $home_link . '">' . $text['home'] . '</a>'.$delimiter.''.viem_page_title(false).'</div>';
	    echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a>'.$delimiter.''.viem_page_title(false).'</div>';
	} else {
	
	    $html = '';
	
	    // breadcrumbs links
	
	    if ($show_home_link == 1) {
	        if(function_exists ( "is_shop" ) && is_shop()){
	            
	        }else{
	            $html .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
	            if ($frontpage_id == 0 || $parent_id != $frontpage_id) $html .= $delimiter;
	        }
	    }
	
	    if ( is_category() ) {
	        $this_cat = get_category(get_query_var('cat'), false);
	        if ($this_cat->parent != 0) {
	            $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
	            if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
	            $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
	            $cats = str_replace('</a>', '</a>' . $link_after, $cats);
	            if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
	            $html .= $cats;
	        }
	        if ($show_current == 1) $html .= $before . sprintf($text['category'], single_cat_title('', false)) . $after;
	
	    } elseif ( is_search() ) {
	        $html .= $before . sprintf($text['search'], get_search_query()) . $after;
	
	    } elseif ( is_day() ) {
	        $html .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
	        $html .= sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
	        $html .= $before . get_the_time('d') . $after;
	
	    } elseif ( is_month() ) {
	        $html .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
	        $html .= $before . get_the_time('F') . $after;
	
	    } elseif ( is_year() ) {
	        $html .= $before . get_the_time('Y') . $after;
	
	    } elseif ( is_single() && !is_attachment() ) {
	        if ( get_post_type() != 'post' ) {
	            if( $show_term == 'term' ){
	            	
	            }elseif ($show_term == 'post_type'){
	            	$post_type = get_post_type_object(get_post_type());
	            	$slug = $post_type->rewrite;
	            	$has_archive = $post_type->has_archive;
	            	$html .= sprintf($link, $home_link . '' . $has_archive . '/', $post_type->labels->singular_name) . $delimiter;
	            }
	        	
	            if ($show_current == 1) $html .=  $before . get_the_title() . $after;
	        } else {
	            $cat = get_the_category(); $cat = $cat[0];
	            $cats = get_category_parents($cat, TRUE, $delimiter);
	            if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
	            $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
	            $cats = str_replace('</a>', '</a>' . $link_after, $cats);
	            if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
	            $html .= $cats;
	            if ($show_current == 1) $html .= $before . get_the_title() . $after;
	        }
	
	    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
	        if(function_exists ( "is_shop" ) && is_shop()){
	            //do_action( 'woocommerce_before_main_content' );
	            //do_action( 'woocommerce_after_main_content' );
	        	$html .= sprintf($link, $home_link . '', $text['home']);
	        	if ($show_current == 1) $html .= $delimiter . $before . get_the_title(get_option('woocommerce_shop_page_id')) . $after;
	        }else{
	            $post_type_object = get_post_type_object( get_query_var( 'post_type' ) );
	            if(isset($post_type_object->labels->name)){
	            	$html .= $before . $post_type_object->labels->name . $after;
	            }else{
	            	$html .= $before . viem_page_title(false) . $after;
	            }
	        }
	
	    } elseif ( is_attachment() ) {
	        $parent = get_post($parent_id);
	        $cat = get_the_category($parent->ID); $cat = isset($cat[0])?$cat[0]:'';
	        if($cat){
	            $cats = get_category_parents($cat, TRUE, $delimiter);
	            $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
	            $cats = str_replace('</a>', '</a>' . $link_after, $cats);
	            if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
	            $html .= $cats;
	        }
	        $html .= sprintf($link, get_permalink($parent), $parent->post_title);
	        if ($show_current == 1) $html .= $delimiter . $before . get_the_title() . $after;
	
	    } elseif ( is_page() && !$parent_id ) {
	        if ($show_current == 1) $html .= $before . get_the_title() . $after;
	
	    } elseif ( is_page() && $parent_id ) {
	        if ($parent_id != $frontpage_id) {
	            $breadcrumbs = array();
	            while ($parent_id) {
	                $page = get_page($parent_id);
	                if ($parent_id != $frontpage_id) {
	                    $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
	                }
	                $parent_id = $page->post_parent;
	            }
	            $breadcrumbs = array_reverse($breadcrumbs);
	            for ($i = 0; $i < count($breadcrumbs); $i++) {
	                $html .= $breadcrumbs[$i];
	                if ($i != count($breadcrumbs)-1) $html .= $delimiter;
	            }
	        }
	        if ($show_current == 1) {
	            if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) $html .= $delimiter;
	            $html .= $before . get_the_title() . $after;
	        }
	
	    } elseif ( is_tag() ) {
	        $html .= $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
	
	    } elseif ( is_author() ) {
	        global $author;
	        $userdata = get_userdata($author);
	        $html .= $before . sprintf($text['author'], $userdata->display_name) . $after;
	
	    } elseif ( is_404() ) {
	        $html .= $before . $text['404'] . $after;
	    }elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
			$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );
			$html .= $before . $post_type_object->labels->name . $after;
		}
	
	    if ( get_query_var('paged') ) {
	        if(function_exists ( "is_shop" ) && is_shop()){
	        }else{
	            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() ) $html .= ' (';
	            $html .= '<span class="navigation-pipe">' .esc_html__('Page','viem') . ' ' . get_query_var('paged') . '</span>';
	            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() ) $html .= ')';
	        }
	    }
	
	        echo '<div class="breadcrumbs">'.$html.'</div>';
	
	
	}
	
endif;
