<?php
/*
 * It is used for sanitizing the cron values
 * */

if(!class_exists('mysql_utilites')):
	class mysql_utilites{
	//db instance
	
		var $price = array('$100-$500','$500-$1,000','$1,000-$3,000','$3,000-$5,000','$5,000-$7,000','$7,000-$10,000','$10,000-$15,000','$15,000-$20,000','$20,000-$25,000','$25,000-$30,000','$30,000-$50,000','$50,000-$100,000','$100,000-$200,000','$200,000-$300,000');
		var $year = array('1950-1960','1960-1970','1970-1980','1980-1990','1990-1995','1995-1998','1998-2001','2001-2004','2004-2007','2007-2010','2010-2013','2013-2016');
		var $milage = array('0-10,000','10,000-20,000','20,000-30,000','30,000-40,000','40,000-50,000','50,000-60,000','60,000-70,000','70,000-80,000','80,000-90,000','90,000-100,000','100,000-110,00','110,000-120,000','120,000-130,000','130,000-140,000','140,000-150,000','150,000-160,000','160,000-170,000','170,000-180,000','180,000-190,000','190,000-200,000','210,000-220,000','220,000-230,000','230,000-240,000','240,000-250,000','250,000-260,000','260,000-270,000','270,000-280,000','280,000-290,000','290,000-300,000');
		
		//milage set
		function mile($p){
			$p = preg_replace('/[^0-9]/','',$p);
			$p = (int) $p;
			
			foreach($this->milage as $key=>$ps){
				$price_range = preg_replace('/[^0-9-]/','',$ps);
				$ranges = explode('-',$price_range);
				if($p>=$ranges[0] && $p<$ranges[1]) return $this->milage[$key]; 
			}	
		}
		
		//manufacturere
		function manufacturerer($m){
			$man = get_option('wp_manufacturer_level1');
			$ms = explode("\n", $man);
			if(!in_array($m,$ms)){
				$ms[] = $m;
				$jy = implode("\n",$ms);
				update_option('wp_manufacturer_level1',$jy);
			}
			return $m;			
		}
		
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
		function pricerange($p){
			$p = preg_replace('/[^0-9]/','',$p);
			$p = (int) $p;
			
			foreach($this->price as $key=>$ps){
				$price_range = preg_replace('/[^0-9-]/','',$ps);
				$ranges = explode('-',$price_range);
				if($p>=$ranges[0] && $p<$ranges[1]) return $this->price[$key]; 
			}
									
		}

		//pasring model year from name
		function model_year($p){
			$y = $this->model_year_extract($p);
			$p = preg_replace('/[^0-9]/','',$y);
			$p = (int) $p;
			
			foreach($this->year as $key=>$ps){
				$price_range = preg_replace('/[^0-9-]/','',$ps);
				$ranges = explode('-',$price_range);
				if($p>=$ranges[0] && $p<$ranges[1]) return $this->year[$key]; 
			}							
		}
		
		//model year snaitizing
		function model_year_extract($year){
			preg_match('/\b\d{4,4}\b/',$year,$b);
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