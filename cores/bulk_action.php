<?php
$action = $_POST['action'];
if($action == -1){
	$message = "<div class='error'><p>Select any one options from bluck dropdown </p></div>";
	return;
}

$ids = $_POST['linkcheck'];

if(count($ids)<1){
	$message = "<div class='error'><p>Select at least one listing !</p></div>";
	return;
}

if($action == 'delete'){
	foreach($ids as $id){
		$id = (int) $id;
		wp_delete_post( $id, true );
	}
	$message = "<div class='updated'><p>Selected ared Deleted</p></div>";
}

if($action == 'block'){
	global $wpdb;
	foreach($ids as $id){
		$id = (int) $id;		
		$wpdb->update($wpdb->posts,array('post_status'=>'draft'),array('ID'=>$id),array('%s'),array('%d'));
	}
	$message = '<div class="updated"><p>Selected are kept in draft</p></div>';
}