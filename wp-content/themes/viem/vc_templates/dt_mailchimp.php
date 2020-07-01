<?php
$default_atts =  array(
	'title'				=>'',
);
extract(shortcode_atts($default_atts, $atts));

$type = 'viem_DT_Mailchimp_Widget';
$args = array('widget_id'=>'dt_widget_mailchimp');
ob_start();
the_widget( $type, wp_parse_args($atts,$default_atts), $args );
echo ob_get_clean();