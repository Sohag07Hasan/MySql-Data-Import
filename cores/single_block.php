<?php
$id = (int)$_GET['id'];

//update_post_meta($id,'auction_status','blocked');
global $wpdb;
$wpdb->update($wpdb->posts,array('post_status'=>'draft'),array('ID'=>$id),array('%s'),array('%d'));

$message = '<div class="updated"><p>Drafted</p></div>';