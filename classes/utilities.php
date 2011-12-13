<?php
/*
 * It is used for sanitizing the cron values
 * */

if(!class_exists('mysql_utilites')):
	class mysql_utilites{
	//db instance
		function db_object(){
			require_wp_db();
			wp_set_wpdb_vars();
			global $wpdb;			
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
			$price = preg_replace('/[^0-9]/','',$price);
			$price = (int) $price;						

			$temrs = get_terms(array('pricerange'));
			$names = array();
			$slugs = array();
			foreach($temrs as $term){
				$a = utf8_decode($term->name);				
				$a = preg_replace('/[?]/','-',$a);
				$a = preg_replace('/[ ]/','',$a);
				$names[] = $a;
				$slugs[] = preg_replace('/[^0-9-+]/','',$a);
				
			}
						
			$plus = array();
			foreach($slugs as $key=>$slug){
				$prices = explode('-',$slug);
				
				if(count($prices)<2){
					$plus[$key] = $slug;
					continue;
				}
				
				if($price >= (int)$prices[0] && $price <= (int)$prices[1]){
					$found = $key;
					break;
				}				
			}
			
			if(isset($found)){
				return $names[$found];
			}
			else{
				foreach ($plus as $k=>$v){
					$v = preg_replace('/^\d/','',$v);
					if($price >= $v){
						$found = $k;
						break;
					}
				}				
				return (isset($found)) ? $names[$found] : null;
			}		
		}

		//pasring model year from name
		function model_year($title){
			preg_match('/\b\d{4,4}\b/',$title,$b);
			return $b[0];							
		}
		
		//mileage
		function mileage($mile){
			//$temrs = get_terms(array('mileage'));
			$mile = preg_replace('/[^0-9]/','',$mile);
			$mile .= ' mile';
			return $mile;		
		}
		
		//audio and video in feature
		function audio_video($audio){
			$tax = array();
			$a = strtolower($audio);
			if(strstr($a,'am')) $tax[] = 'AM/FM Radio';
			if(strstr($a,'fm')) $tax[] = 'AM/FM Radio';
			if(strstr($a,'cd')) $tax[] = 'Compact Disc Player';
			return $tax;
		}
		
		//meta data
		function fuel($fuel){
			if(!$fuel) return;
						
			$fuels = array('gasoline', 'diesel', 'flexfuel');
			if(in_array(strtolower($fuel),$fuels)){
				return 'Gas';
			}
			else{
				return $fuel;
			}
		}
		
		
	}
	
	$cron_utility = new mysql_utilites();
endif;