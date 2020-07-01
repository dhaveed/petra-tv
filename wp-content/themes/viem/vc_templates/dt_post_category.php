<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$output = array();
extract(shortcode_atts(array(
	'template'			=>'grid',
	'title'				=>'',
	'posts_per_page'	=>'4',
	'orderby'			=>'latest',
	'category'			=>'',
	'visibility'		=>'',
	'el_class'			=>'',
), $atts));

if(empty($category)){
	return;
}
$template = ($template !== '') ? $template : 'grid';
$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= viem_dt_visibility_class($visibility);

$order = 'DESC';
switch ($orderby) {
	case 'latest':
		$orderby = 'date';
		break;

	case 'oldest':
		$orderby = 'date';
		$order = 'ASC';
		break;

	case 'alphabet':
		$orderby = 'title';
		$orderby = 'ASC';
		break;

	case 'ralphabet':
		$orderby = 'title';
		break;

	default:
		$orderby = 'date';
		break;
}

$posts_per_page = ($template == 'list') ? '4' : $posts_per_page;

$args = array(
	'category_name'   => "{$category}",
	'orderby'         => "{$orderby}",
	'order'           => "{$order}",
	'post_type'       => "post",
	'posts_per_page'  => $posts_per_page,
);

$p = new WP_Query($args);

if($p->have_posts()):
?>
<div id="<?php echo esc_attr($sc_id);?>" class="dt-post-category wpb_content_element viem-preload <?php echo esc_attr( $class ) . ' tem-'.$template;?>">
	<div class="dt-post-category__wrap">
		<div class="dt-post-category__heading">
			<?php if(!empty($title)):?>
			<h3 class="viem-sc-title"><span><?php echo esc_html($title);?></span></h3>
			<?php endif;?>
		</div>
		<div class="dt-post-category__grid dt-content__wrap">
			<div class="dt-content <?php echo ' tem-'.$template;?>">
			<?php
				$i = 0;
				global $post;
				switch ($template){
					case 'list'; case 'list':
						while ($p->have_posts()): $p->the_post();?>
							<?php 
							$i++;
							viem_dt_get_template('tpl-post-category-list.php',
								array(
									'template' => $template,
									'post' => $post,
									'i' => $i,
								),
								'vc_templates/tpl', 'vc_templates/tpl'
							);
							?>
						<?php endwhile;
						break;
					default: // grid
						while ($p->have_posts()): $p->the_post();?>
							<?php 
							$i++;
							viem_dt_get_template('tpl-post-category.php',
								array(
									'post' => $post,
									'i' => $i,
								),
								'vc_templates/tpl', 'vc_templates/tpl'
							);
							?>
						<?php endwhile;
						break;
				}
			?>
			</div>
		</div>
	</div>
</div>
<?php
endif;
wp_reset_postdata();
?>