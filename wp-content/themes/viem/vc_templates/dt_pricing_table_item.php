<?php
extract(shortcode_atts(array(
	'recommend'				=>'',
	'title'					=>'',
	'price'					=>'35.00',
	'currency'				=>'$',
	'units'					=>'',
	'features'				=>'W3siY29udGVudCI6IjxpIGNsYXNzPVwiZmEgZmEtY2hlY2tcIj48L2k+IEkgYW0gYSBmZWF0dXJlIn0seyJjb250ZW50IjoiPGkgY2xhc3M9XCJmYSBmYS1jaGVja1wiPjwvaT4gSSBhbSBhIGZlYXR1cmUifV0=',
	'href'					=>'',
	'target'				=>'',
	'btn_title'				=>'Buy Now',
), $atts));
echo '<div class="pricing-column '.(!empty($recommend) ? ' pricing-recommend':'').'">';
echo '<div class="pricing-column-wrap">';
echo '<div class="pricing-column-content">';

echo '<div class="pricing-header '.(!empty($recommend) ? ' viem-main-color-bg':'').'">';
if(!empty($title)){
	echo '<div class="pricing-title">';
	echo '<h3>'.$title.'</h3>';
	echo '</div>';
}
if(!empty($price)){
	echo '<div class="pricing-price">';
	echo '<div class="price-value">'.$price.'<sup>'.$currency.'</sup></div>';
	if(!empty($units)){
		echo '<span class="price-unit">'.$units.'</span>';
	}
	echo '</div>';
}
echo '</div>'; 
echo '<div class="pricing-body">';
if( !empty($features) && function_exists( 'viem_pricing_features' ) ){
	viem_pricing_features( $features );
}
echo '</div>';

echo '</div>';

echo '<div class="pricing-footer viem-main-color-bg">';
echo '<div class="pricing-footer-wrap">';

echo  '<a href="'.(!empty($href) ? esc_url_raw($href) : '#').'" class="btn-pricing-table" '.(!empty($target) ? 'target="'.$target.'"':'').'>'.esc_html($btn_title).'</a>';
echo '</div>';
echo '</div>';
echo '<div class="pricing-bg-over"></div>';
echo '</div>';
echo '</div>';

