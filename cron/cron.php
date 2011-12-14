<?php
/*
 * This script automaticlly run by cron of unix operating system
 * */

set_time_limit(1000);

include '../../../../wp-load.php';

global $wpdb, $cron_utility;
$cracking_table = $wpdb->prefix . 'cron';
$max = $wpdb->get_var("SELECT `c_id` FROM $cracking_table ORDER BY `id` DESC LIMIT 1 ");
if(!max) $max = 0;
$max = (int) $max;


// importing the data
$info = get_option('cron_information');
$link = mysql_connect($info['server'], $info['user'], $info['password']);
mysql_select_db($info['db'], $link);
$table = $info['table'];

$query = "SELECT * FROM $table WHERE id > $max ORDER BY `id` LIMIT 2";
$result = mysql_query($query);
$cars = array();
while ($row = mysql_fetch_assoc($result)) {
  $cars[] = $row;
}


mysql_free_result($result);
mysql_close($link);
//closing the database


//instantiating the wpdb database again
unset($wpdb);
$cron_utility->db_object();
global $wpdb;

foreach($cars as $car){
	
	$attachments = unserialize($car['images']);
	if(!is_array($attachment)) continue;
	
	// all are the taxonomies
	$equipments = @ unserialize($car['equipment']);
	if(!$equipments) $equipments = array();
 	$engine = array($cron_utility->engine($car['engine']));
	$price_range = $cron_utility->pricerange($car['price']);
	$model_year = $cron_utility->model_year($car['vehicle_name']);	
	$mileage = $cron_utility->mile($car['odometer']);
	$audio = $cron_utility->audio_video($car['audio']);
	$extras = array($car['interior_type']);
	$manufacturer = array($car['manufacturer']);
	$features = array_merge($equipments,$audio,$extras);
		
	$taxanomies = array(
		'features' => $features,
		'enginesize' => $engine,
		'transmission' => array($car['transmission']),
		'pricerange' => array($price_range),
		'bodytype' => array($car['body_type']),
		'modelyear' => array($model_year),
		'mileage' => array($mileage),
		'manufacturer' => $manufacturer	
	);
	
	$data = array(
				'post_type' =>'listing',
				'post_title' => $car['vehicle_name'],
				'post_content' => isset($car['description']) ? $car['description'] : $car['vehicle_name'],
				'post_status' => 'publish'
									
				
	);
			//inserting data with some defined data
	$p_id = wp_insert_post( $data );
	if(!$p_id) continue;
	
	foreach($taxanomies as $taxonmy=>$terms){
		if(empty($terms)) continue;
		wp_set_object_terms($p_id,$terms,$taxonmy);
	}
	
	
	$attachments = unserialize($car['images']);
	if(is_array($attachments)) :
		foreach ($attachments as $attachment){
			$mime = $cron_utility->mime($attachment);
			$a_data = array(
					'post_type' =>'attachment',
					'post_title' => $mime[0],
					'post_content' => isset($car['description']) ? $car['description'] : $car['vehicle_name'],				
					'post_mime_type' => 'image/' . $mime[1],
					'guid' => $attachment,
					'post_parent' => $p_id
			);
			wp_insert_attachment($a_data);		
		}
	
	endif;
	//updating the meta data for the post
	
	$fuel = $cron_utility->fuel($car['fuel']);
	update_post_meta($p_id,'fueltype_value',$fuel);
	update_post_meta($p_id,'intcolor_value',$car['interior_color']);
	update_post_meta($p_id,'extcolor_value',$car['exterior_color']);
	update_post_meta($p_id,'body_type_value',$car['body_type']);
	update_post_meta($p_id,'door_value',$car['door']);
	update_post_meta($p_id,'driven_train',$car['drive_train']);
	update_post_meta($p_id,'trans_value',$car['transmission']);
	update_post_meta($p_id,'price_value',preg_replace('/[^0-9]/','',$car['price']));
	update_post_meta($p_id,'mileage_value',preg_replace('/[^0-9]/','',$car['odometer']));
	update_post_meta($p_id,'top_value',$car['top']);
	update_post_meta($p_id,'vin_value',$car['vin']);
	update_post_meta($p_id,'manufacturer_level1_value',$cron_utility->manufacturerer($car['manufacturer']));
	update_post_meta($p_id,'year_value',$cron_utility->model_year_extract($car['vehicle_name']));
	update_post_meta($p_id,'mmr_value',$car['mmr_details']);
	update_post_meta($p_id,'blackbook_value',$car['blackbook_details']);
	update_post_meta($p_id,'end_date_value',$car['end_date']);
	
	$wpdb->insert($cracking_table,array('p_id'=>(int)$p_id,'c_id'=>(int)$car['id']),array('%d','%d'));
	
	
}
