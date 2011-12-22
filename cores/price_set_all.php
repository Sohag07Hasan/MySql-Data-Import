<?php
$price = trim($_POST['new_price_percentage']);
$k = preg_replace('/[^\d-]/','', $price);
if($k == '') return;

global $wpdb;
$ids =$wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type='listing' AND post_status='publish' ");


foreach($ids as $id){
	$o_p = get_post_meta($id,'org_price_value',true);
	$mtth = (float) $o_p*$k/100;
	$rev = $o_p + $mtth;				
	update_post_meta($id,'price_value',round($rev));		
}

update_option('percent_price_level_all',$k);
$message = '<div class="updated"><p>Price of all cars has been changed </p></div>';