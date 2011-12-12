<?php
/*
 * It is used for sanitizing the cron values
 * */

if(!class_exists('mysql_utilites')):
	class mysql_utilites{
	//db instance
		function db_object(){
			$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
			return $wpdb;
		}
		
		//for mime type
		function mime($a){
			$mimtypes = array('jpg','jpeg');
			$b = preg_replace('/\w.+\//','',$a);
			$b = preg_replace('/[^0-9a-zA-Z.]/','',$b);			
			$c = explode('.',$b);			
			if(in_array($c[1],$mimtypes)){
				$c[1] = 'jpeg';
			}
			return $c;
		}
		
		//engine size taxononey
		function engine($a){
			$a = strtolower($a);
			preg_match('/\w.+cylinder/',$a,$b);
			return $b[0];
		}
		
		//setting price range
		function pricerange($price){
			$price = preg_replace('/^\d/','',$price);
			$price = (int) $price;
			global $wpdb;
			$price_lists = $wpdb->get_col("SELECT ``");
		}
		
	}
	
	$cron_utility = new mysql_utilites();
endif;