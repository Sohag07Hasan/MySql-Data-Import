<?php 
/*
 * plugin name: MySql Data import with cron
 * author: Mahibul Hasan
 * plugin uri: http://voltierdigital.com/ove/
 * author uri: http://sohag.me
 * */

// defining the constants

define('MYSQLIMPORT_FILE', __FILE__);
define('MYSQLIMPORT', dirname(__FILE__));
define('MYSQLIMPORT_CLASS', MYSQLIMPORT . '/classes');
define('MYSQLIMPORT_CORE', MYSQLIMPORT . '/cores');

include MYSQLIMPORT_CLASS . '/utilities.php';
include MYSQLIMPORT_CLASS . '/admin-panel.php';

?>