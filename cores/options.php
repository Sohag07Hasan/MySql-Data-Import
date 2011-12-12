<?php
$a = array();
foreach($_POST['mysql_information'] as $key=>$value){
	$a[$key] = trim($value);
}

update_option('cron_information',$a);