<?php
/*
 * Add menu to handle the auction key data
 * */

if(!class_exists('car_menu_page')) : 

	class car_menu_page{
		
		// constructor
		function __construct(){
			add_action('admin_menu', array($this,'create_a_menu'));
			//add_action('admin_enqueue_scripts',array($this,'css_js'),100);
		}
		
		//adding javascript and css for media uploader
		function css_js(){
			if($_REQUEST['page'] == 'auction_day_set') :
				wp_enqueue_style('thickbox');
				wp_enqueue_script('jquery');
				wp_register_script('media_auction_script', MYSQLIMPORT_JS . '/media-uploader.js', array('jquery','media-upload','thickbox'));
				wp_enqueue_script('media_auction_script');
			endif;
		}
		
		//menu page
		function create_a_menu(){
					
			add_menu_page(__('Ove Data Import'),__('Auction Expires'),'activate_plugins','wp_ove_auction',array($this,'acution_lists'));
			add_submenu_page('wp_ove_auction',__('Auction Expire Set'),__('Auction Time Set'),'activate_plugins','auction_day_set',array($this,'auction_page'));
			add_submenu_page('wp_ove_auction',__('Price variations'),__('Price settings'),'activate_plugins','ove_price_lists',array($this,'price_page'));
			add_submenu_page('wp_ove_auction',__('Banner Page'),__('Banner Selection'),'activate_plugins','ove_banner_selects',array($this,'banner_page'));

		}
		
		//banner set options will be shown
		function banner_page(){
			if($_REQUEST['common_banner_submit'] == 'Y'){
				update_option('ove_common_banner',$_REQUEST['common_banner']);
				include MYSQLIMPORT_CORE . '/expired_banner_set.php';
			}
			
			
			$banner = array("None", "Reduced", "Sold", "Automatic", "Manual", "Diesel", "Low Mileage", "1 Owner", "Reserved", "Diesel Automatic");
			$saved_banner = get_option('ove_common_banner');
			?>
			<div class='wrap'>
				<?php screen_icon('options-general'); ?>
				<h2>Set A Banner for Expired Cars</h2>
				
				<?php 
					if($_REQUEST['common_banner_submit'] == 'Y'){
						echo '<div class="updated"><p>Saved</p></div>';
					}				
				?>
				
				<form method='post' action=''>
					<input type="hidden" name="common_banner_submit" value='Y' />
					<table class="form-table">
					<tr valign="top"><th scope="row"> Banner </th>
						<td>
							<select name='common_banner'>
								<?php 
									foreach($banner as $b){
										echo "<option value='$b' " . selected($saved_banner,$b) ." > $b </option>";
									}
								?>
							</select>
						</td>
						</tr>
					</table>
					
					<input type='submit' name='submit' value='Save Changes' class='button-primary'  />					
				</form>
			</div>
			
			<?php 
		}
		
		//set the expirationg date
		function auction_page(){
					
			
			if($_REQUEST['auction_expire_Set'] == 'Y'){
				$day = preg_replace('/[^0-9]/','',$_REQUEST['auction_expire']);
				$logo = trim($_REQUEST['auction_expire_logo']);				
				update_option('auction_expire',array('day'=>$day));
			}
			$options = get_option('auction_expire');
			
			
			?>
			
			<div class='wrap'>
			<?php screen_icon('options-general'); ?>
			<h2>Auction Expires Settings</h2>
			<?php 
				if($_REQUEST['auction_expire_Set'] == 'Y'){
					echo "<div class='updated'><p>Saved</p></div>";
				}
			?>
				<form action="" method="post">
					<input type="hidden" name='auction_expire_Set' value='Y' />
					<table class='form-table'>
											
						<tr valign="top"><th scope="row"> Day <small style='color:red;font-style:italic;'> (how many days past due, if the auction has been expired)</small></th>							
							<td colspan="3">									
								<input style="width:300px;" type='text' name="auction_expire" value="<?php echo $options['day']; ?>" /> 
							</td>												
						</tr>										
						
						<tr valign="top"><th scope="row"> <input type="submit" name='auction_submit' value="save" class="button-primary" /> </th>
						</tr>
						
					</table>		
					
				</form>	
					
			</div>
			
			<?php 
		}
		
		//price_set page
		function price_page(){
			$message = '';
			if($_REQUEST['ove_price_submit'] == 'Y'){
				//include MYSQLIMPORT_CORE . '/price_set.php';
				include MYSQLIMPORT_CORE . '/price_set_all.php';
			}
			
			$man = get_option('wp_manufacturer_level1');
			$ms = explode("\n", $man);
			?>
				<div class="wrap">
				<?php screen_icon('options-general'); ?>				
				<h2>Ove price set</h2>
				<p> Set the price percentage (+ve/-ve). only percentage (e.g 10 for 10%)  </p>
				<?php 
					if($_REQUEST['ove_price_submit'] == 'Y'){
						echo $message;
					}
					$opt = get_option('percent_price_level');
					
				?>
				<form action="" method="post">
					<input type="hidden" name="ove_price_submit" value="Y" />								
					<table class="form-table">
						<?php //foreach($ms as $m) : ?>	
						<!-- 					
							<tr valign="top"><th scope="row"> <?php// echo $m ?> </th>							
								<td colspan="2">
									<input type='text' name="<?php// echo "price[$m]"; ?>" value="<?php //echo $opt[$m] ?>" /> 
								</td>												
							</tr>
							 -->
						<?php //endforeach; ?>
						
						<tr valign="top"><th scope="row"> Set Price </th>							
								<td colspan="2">
									<input type='text' name="new_price_percentage" value="<?php echo get_option('percent_price_level_all'); ?>" /> 
								</td>												
						</tr>
																			
					</table>
					<input type="submit" name="su" value="Change Price" class="button-primary"  />
				</form>
			</div>
			
			<?php 
		}
		
		function acution_lists(){
			$message = '';
			if($_GET['action'] == 'del' && isset($_GET['id'])){
				include MYSQLIMPORT_CORE . '/single_del.php';
			}
			if($_GET['action'] == 'block' && isset($_GET['id'])){
				include MYSQLIMPORT_CORE . '/single_block.php';
			}
			if($_POST['ove_bulk_action'] == 'Y'){
				include MYSQLIMPORT_CORE . '/bulk_action.php';
			}
			
			?>
		<div class="wrap">
			<form action="" method="post">
				
				<input type="hidden" name="ove_bulk_action" value="Y" />
				
				<?php echo $message; ?>
				
				<div class="tablenav top">
				
					<div class="alignleft actions">
					
						<select name="action">
							<option selected="selected" value="-1">Bulk Actions</option>
							<option value="delete">Delete</option>
							<option value="block">Draft</option>
						</select>
						<input id="doaction" class="button-secondary action" type="submit" value="Apply" name="ove_bulk_action_submit">
					</div>
					<div class="alignleft actions">
						
					</div>
					<br class="clear">
				</div>
				<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
								<input type="checkbox">
							</th>
							<th id="name" class="manage-column column-name sortable desc" style="" scope="col">
								<a href="#">
									<span>Vehicle Listings</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							 
							<th id="url" class="manage-column column-url sortable desc" style="" scope="col">
								<a href="#">
									<span>Banner</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							
							<th id="url_cloaked" class="manage-column column-url sortable desc" style="" scope="col">
								<a href="#">
									<span>Status</span>
									<span class="sorting-indicator"></span>
								</a>
							</th>											
							
						</tr>
					</thead>
					
					<tbody>
			
			<?php 
			global $wpdb;
			$ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type='listing' AND post_status='publish'");
			//var_dump($ids);
			$home = get_option('siteurl');
			$options = get_option('auction_expire');
			$day = $options['day'] * 24 * 60 * 60 ;
			
			foreach($ids as $id){
				$date = get_post_meta($id,'end_date_value',true);
				$banner = get_post_meta($id,'banner_value',true);
				
				preg_match('/\d{1,2}\/\d{1,2}\/\d{2,4}/',$date,$b);
				$timestamp = strtotime($b[0]);
				$new_timestamp = $timestamp + $day;
				
				if($new_timestamp > time()) continue;
				
				$name = $wpdb->get_var("SELECT post_title FROM $wpdb->posts WHERE ID='$id' ");
				$edit_link = $home . "/wp-admin/post.php?post=$id&action=edit";
				$del_link = $home . "/wp-admin/admin.php?page=wp_ove_auction&action=del&id=$id";
				$block_link = $home . "/wp-admin/admin.php?page=wp_ove_auction&action=block&id=$id";
				$status = (get_post_meta($id,'auction_status',true)) ? get_post_meta($id,'auction_status',true) : 'active';
							
				
				echo "<tr>
					<th class='check-column' scope='row'>
						<input type='checkbox' value='$id' name='linkcheck[]'>
					</th>
					<td> 
						$name
						<div class='row-actions'>
							<a href='$edit_link'>Edit</a>&nbsp| 
							<a style='color:red' href='$del_link'>Delete</a> &nbsp |
							<a style='color:red' href='$block_link'>Draft </a>
						</div>
					</td>
					
					<td>
						$banner
					</td>
					
					<td>
						$status
					</td>
				</tr>";
				
			}
		?>
			</tbody>
			<tfoot>
				<tr>
					<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
						<input type="checkbox">
					</th>
					<th id="name" class="manage-column column-name sortable desc" style="" scope="col">
						<a href="#">
							<span>Vehicle Listings</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					
					<th id="url" class="manage-column column-url sortable desc" style="" scope="col">
						<a href="#">
							<span>Banner</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					 
					<th id="url_cloaked" class="manage-column column-url sortable desc" style="" scope="col">
						<a href="#">
							<span>Status</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>											
					
				</tr>
			</tfoot>
			</table>
		</form>
	</div> <!-- wrap -->
		<?php 
		}
	}
	
	$car_menu = new car_menu_page();
endif;