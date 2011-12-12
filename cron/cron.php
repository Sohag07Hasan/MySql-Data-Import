<?php
/*
 * This script automaticlly run by cron of unix operating system
 * */

include '../../../../wp-load.php';

global $wpdb, $cron_utility;
$cracking_table = $wpdb->prefix . 'cron';


// importing the data
$info = get_option('cron_information');
$link = mysql_connect($info['server'], $info['user'], $info['password']);
mysql_select_db($info['db'], $link);
$table = $info['table'];
$query = "SELECT * FROM $table ORDER BY `id` LIMIT 2";
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
$wpdb = $cron_utility->db_object();

foreach($cars as $car){
	$equipments = @ unserialize($car['equipment']);
	$engine = array($cron_utility->engine($car['engine']));
	$price_range = $cron_utility->pricerange($car['pricerange']);
	var_dump($price_range);
	exit;
	
	/*
	$taxanomies = array(
		'features' => $equipments,
		'enginesize' => $engine,
		'transmission' => array($car['transmission']),
		'pricerange' => '',
		'bodytype' => '',
		'modelyear' => '',
		'mileage' =>	''	
	);
	
	$data = array(
				'post_type' =>'listing',
				'post_title' => $car['vehicle_name'],
				'post_content' => isset($car['description']) ? $car['description'] : $car['vehicle_name'],
				'post_status' => 'draft',
					
				
	);
			//inserting data with some defined data
	$p_id = wp_insert_post( $data );
	if(!$p_id) continue;
	
	$attachments = unserialize($car['images']);
	foreach ($attachments as $attachment){
		$mime = $cron_utility->mime($attachment);
		$a_data = array(
				'post_type' =>'attachment',
				'post_title' => $mime[0],
				'post_content' => isset($car['description']) ? $car['description'] : $car['vehicle_name'],				
				'post_status' => 'inherit',
				'post_mime_type' => 'image/' . $mime[1],
				'guid' => $attachment
		);
	}
	*/
}
