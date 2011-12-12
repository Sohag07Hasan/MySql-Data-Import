<?php
/*
 * This script automaticlly run by cron of unix operating system
 * */

include '../../../../wp-load.php';

global $wpdb, $mysql_cron;
$cracking_table = $wpdb->prefix . 'cron';


// importing the data
$info = get_option('cron_information');
$link = mysql_connect($info['server'], $info['user'], $info['password']);
mysql_select_db($info['db'], $link);
$table = $info['table'];
$query = "SELECT * FROM $table ORDER BY `id` LIMIT 1";
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
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

foreach($cars as $car){
	$post = array(
				'post_type' =>'listing',
				'post_title' => $car['vehicle_name'],
				'post_content' => isset($car['description']) ? $car['description'] : $car['vehicle_name'],
				'post_status' => 'draft',			
				'post_date' => date("Y-m-d H:i:s",time()),
				'post_date_gmt' =>date("Y-m-d H:i:s",time()),		
				'ping_status' =>'open',				
				
	);
			//inserting data with some defined data
			$p_id = wp_insert_post( $data );
	$attachments = unserialize($car['images']);
}
