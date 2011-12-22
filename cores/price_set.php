<?php
$prices = $_POST['price'];

$prices_opt = array();
global $wpdb;
foreach($prices as $m=>$p){
	
	$k = preg_replace('/[^\d-]/','', $p);	
	
	$prices_opt[$m] = $k;
	if($k == '') continue;
	
	$ids =$wpdb->get_col("SELECT post_id FROM $wpdb->postmeta WHERE meta_value='$m' ");
	$ids = array_unique($ids);
	
	foreach($ids as $id){
		$o_p = get_post_meta($id,'org_price_value',true);
		$mtth = (float) $o_p*$k/100;
		$rev = $o_p + $mtth;				
		update_post_meta($id,'price_value',$rev);		
	}
}

update_option('percent_price_level',$prices_opt);
$message = '<div class="updated"><p>Price of associated manufacturer has been changed </p></div>';