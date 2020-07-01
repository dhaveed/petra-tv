<?php
if( ! function_exists( 'viem_pricing_features' ) ){
	function viem_pricing_features($features = ''){ 
		if(!empty($features)){
			$features = json_decode(base64_decode($features));
			echo '<ul class="pricing-features">';
			foreach ((array)$features as $feature){
				echo '<li><i class="fa fa-check" aria-hidden="true"></i>'.$feature->content.'</li>';
			}
			echo '</ul>';
		}
	}
}