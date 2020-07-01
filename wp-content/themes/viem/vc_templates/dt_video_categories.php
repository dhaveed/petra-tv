<?php
$output = array();
extract(shortcode_atts(array(
	'style'				=>'style_1',
	'template'			=> 'grid',
	'title'				=>'',
	'icon_player'		=>'',
	'columns'			=> 4,
	'posts_per_page'	=>'5',
	'orderby'			=>'view_by',
	'view_by'			=>'views',
	'order'				=>'DESC',
	'categories'		=>'',
	'exclude_categories'=>'',
	'img_size'			=> 'viem_750_490_crop',
	'el_class'			=>'',
	'css'				=>'',
), $atts));

$sc_id = uniqid('viem_sc_');
$class 	= !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

switch ($orderby) {
	case 'view_by':
		$orderby = 'view_by';
		break;
		
	case 'latest':
		$orderby = 'date';
		break;

	case 'oldest':
		$orderby = 'date';
		$order = 'ASC';
		break;

	case 'alphabet':
		$orderby = 'title';
		break;

	case 'ralphabet':
		$orderby = 'title';
		break;
		
	case 'rand':
		$orderby = 'rand';
		break;

	default:
		$orderby = 'date';
		break;
}

$posts_per_page = ( $style == 'style_1' ) ? 5 : $posts_per_page;
$posts_per_page = ( $style == 'style_3' ) ? 3 : $posts_per_page;
$display_type = $style;

// Order By
$meta_key = '';
if( $orderby == 'view_by' ){
	if( $view_by == 'comment' ){
		$orderby = 'comment_count';
	} elseif ( $view_by == 'views' ) {
		$orderby = 'meta_value_num';
		$meta_key = 'post_views_count';
	}
}

// Array tabs title
$tab_titles = viem_get_list_tab_title('category', $categories, '', '');
if(empty($tab_titles)){ return;}

$query_types = 'category';

if( !empty($categories) ){
	$tabs = explode(',', $categories);
}else{
	$tabs = viem_get_cats();
}

$data_tab = array();
$tab_all = '';
if($query_types !== 'orderby'){
	$i=0;
	foreach ($tab_titles as $tab) {
		$i++;
		if( $tab_all !== 'yes' && $i == 1 ){
			array_push($data_tab, $tab['name']);
			break;
		}else{
			array_push($data_tab, $tab['name']);
		}
	}
}
if(!empty($data_tab)){
	$data_tab = implode(',', $data_tab);
}else{
	$data_tab = '';
}

?>
<div id="<?php echo esc_attr($sc_id) ?>" class="viem-video-categories viem_tab_loadmore wpb_content_element <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr($style); ?>">
	<div class="viem_sc_heading">
		<?php if($title !=''):?>
		<h5 class="viem-sc-title"><?php echo esc_html($title);?></h5>
		<?php endif; ?>
		<ul class="viem-sc-nav-tabs nav-tabs" data-option-key="filter">
			<?php 
			$i = 0;
			$tab_loaded = '';
			foreach ($tab_titles as $tab) {
				$i++;
				$tab_loaded = ($i == 1) ? 'tab-loaded' : '';
				?>
				<li class="viem-nav-item <?php echo ($i == 1) ? 'active' : ''?>">
					<a href="#<?php echo esc_attr($sc_id.'-'.$tab['name']); ?>" class="tab-intent <?php echo esc_attr($tab_loaded);?>" data-toggle="tab" title="<?php echo esc_attr($tab['title']); ?>"
						data-wrap-id		= "<?php echo esc_attr($sc_id) ?>"
						data-display_type	= "<?php echo esc_attr($display_type) ?>"
						data-query_types	= "category"
						data-tab			= "<?php echo esc_attr($tab['name']) ?>"
						data-orderby		= "<?php echo esc_attr( $orderby ) ?>"
						data-meta_key		= "<?php echo esc_attr( $meta_key ) ?>"
						data-order			= "<?php echo esc_attr( $order ) ?>"
						data-number_query	= "<?php echo esc_attr($posts_per_page) ?>"
						data-columns		= "<?php echo esc_attr($columns) ?>"
						data-number_load	= "<?php echo esc_attr($posts_per_page) ?>"
						data-number_display = "<?php echo esc_attr($posts_per_page) ?>"
						data-template		= "<?php echo esc_attr($template) ?>"
						data-img_size		= "<?php echo esc_attr($img_size) ?>"
						><span><?php echo esc_html($tab['short_title']); ?></span>
					</a>
				</li>
				<?php
			}
			?>
		</ul>
		<ul class="nav-tabs-mb">
		    <li class="dropdown <?php echo ($style == 'style_1') ? 'pull-left' : 'pull-right'; ?> tabdrop hidden-lg hidden-md">
		        <a href="#" data-toggle="dropdown" class="dropdown-toggle">
		            <span class="display-tab"><i class="fa fa-align-justify"></i></span>
		        </a>
		        <ul class="dropdown-menu gfont">
		            <?php
		            $i = 0;
		            $tab_loaded = '';
		            foreach ($tab_titles as $tab) {
		            	$i++;
		            	$tab_loaded = ($i == 1) ? 'tab-loaded' : '';
		                ?>
		                <li class="viem-nav-item <?php echo ($i == 1) ? 'active' : ''?>">
							<a href="#<?php echo esc_attr($sc_id.'-'.$tab['name']); ?>" class="tab-intent <?php echo esc_attr($tab_loaded);?>" data-toggle="tab" title="<?php echo esc_attr($tab['title']); ?>"
								data-wrap-id		= "<?php echo esc_attr($sc_id) ?>"
								data-display_type	= "<?php echo esc_attr($display_type) ?>"
								data-query_types	= "category"
								data-tab			= "<?php echo esc_attr($tab['name']) ?>"
								data-orderby		= "<?php echo esc_attr( $orderby ) ?>"
								data-meta_key		= "<?php echo esc_attr( $meta_key ) ?>"
								data-order			= "<?php echo esc_attr( $order ) ?>"
								data-number_query	= "<?php echo esc_attr($posts_per_page) ?>"
								data-columns		= "<?php echo esc_attr($columns) ?>"
								data-number_load	= "<?php echo esc_attr($posts_per_page) ?>"
								data-number_display = "<?php echo esc_attr($posts_per_page) ?>"
								data-template		= "<?php echo esc_attr($template) ?>"
								data-img_size		= "<?php echo esc_attr($img_size) ?>"
								><span><?php echo esc_html($tab['short_title']); ?></span>
							</a>
						</li>
		            <?php } ?>
		        </ul>
		    </li>
		</ul>
	</div>
	<div class="viem_sc_content viem-preload">
		<div class="viem-ajax-loading">
			<div class="viem-fade-loading"><i></i><i></i><i></i><i></i></div>
		</div>
		<div class="tab-content viem-loadmore-tab-content">
			<?php
				$tab_args = array(
					'tab_id'		=> esc_attr($sc_id.'-'.$data_tab),
					'tab_active'	=> 'active',
					'display_type'	=> $display_type,
					'wrap_id'		=> $sc_id,
					'query_types'	=> $query_types,
					'tab'			=> $data_tab,
					'orderby'		=> $orderby,
					'meta_key'		=> $meta_key,
					'order'			=> $order,
					'number_query'	=> $posts_per_page,
					'columns'		=> $columns,
					'number_load'	=> 5,
					'number_display'=> 5,
					'template'		=> $template,
					'img_size'		=> $img_size,
				);
				
				viem_dt_get_template( 'tpl-tab.php', array('tab_args' => $tab_args), '/vc_templates/tpl/', 'vc_templates/tpl/' );
				
				?>
		</div>
	</div>
</div>
