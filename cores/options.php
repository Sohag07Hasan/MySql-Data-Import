<?php
$a = array();
foreach($_POST['mysql_information'] as $key=>$value){
	$a[$key] = trim($value);
}

update_option('cron_information',$a);

$b = array();
foreach($_POST['ftp_information'] as $k=>$v){
	$b[$k] = trim($v);
}

update_option('cron_ftp',$b);