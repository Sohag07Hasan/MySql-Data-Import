<?php
$ban = $_REQUEST['common_banner'];
global $wpdb;
$ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type='listing' AND post_status='publish'");
$options = get_option('auction_expire');
$day = $options['day'] * 24 * 60 * 60 ;


foreach($ids as $id){
	$date = get_post_meta($id,'end_date_value',true);
	preg_match('/\d{1,2}\/\d{1,2}\/\d{2,4}/',$date,$b);
	$timestamp = strtotime($b[0]);
	$new_timestamp = $timestamp + $day;
	
	if($new_timestamp > time()) continue;
	
	update_post_meta($id,'banner_value',$ban);
}

$message = '<div class="updated"><p>Banner has been set for all the expired cars</p></div>';