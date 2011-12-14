<?php
/*
 * creates a admin section where you can insert the mysql details to impor the data
 * */

if(!class_exists('mysql_import_admin')) : 
	class mysql_import_admin{
		var $sohag = 14;
		function __construct(){
			add_action('admin_menu',array($this,'optionspage'));
			register_activation_hook(MYSQLIMPORT_FILE,array($this,'table_creation'));
			add_action('deleted_post',array($this,'delete_post'));
			add_action('add_meta_boxes',array($this,'meta_box'));
			add_action('save_post',array($this,'save_post_meta'),50);
			
			add_filter('manage_edit-listing_columns',array($this,'remove_thumb'),100);
		}
		
		function remove_thumb($cols){
			if(isset($cols['thumbnail'])) unset($cols['thumbnail']);
			return $cols;
		}
		
		function save_post_meta($p_id){
			update_post_meta($p_id,'door_value',$_REQUEST['door_value']);
			update_post_meta($p_id,'driven_train',$_REQUEST['driventrain_value']);
			update_post_meta($p_id,'vin_value',$_REQUEST['vin_value']);
		}
		
		//metabox
		function meta_box(){
			add_meta_box('vehicles_extra_data',__('Vehicales\' Extra Information'),array($this,'extra_info'),'listing','normal','high');
			add_meta_box('blackbook_details',__('BlackBook'),array($this,'blackbook'),'listing','normal','high');
			add_meta_box('mmr_details',__('MMR'),array($this,'mmr'),'listing','normal','high');
			add_meta_box('end_date',__('End Date'),array($this,'end_date'),'listing','normal','high');
		}
		
		function end_date(){
			global $post;
			echo '<div class="wrap"><table>';			
			echo get_post_meta($post->ID,'end_date_value',true);
			echo '</table></div>';
		}
		
		function mmr(){
			global $post;
			echo '<div class="wrap">';			
			echo get_post_meta($post->ID,'mmr_value',true);
			echo '</div>';	
		}

		function blackbook(){
			global $post;
			echo '<div class="wrap">';
			echo get_post_meta($post->ID,'blackbook_value',true);
			echo '</div>';		
		}
		
		function extra_info(){
			global $post;

			?>
			<div class='wrap'>
				<table class='form-table'>
					<tr><th>Meta Key</th> <th>Meta Value</th></tr>
					<tr>
						<td>Vin</td>
						<td> <input type='text' name="vin_value" value="<?php echo get_post_meta($post->ID,'vin_value',true); ?>" /></td>
					</tr>				
						<td>Door</td>
						<td> <input type='text' name="door_value" value="<?php echo get_post_meta($post->ID,'door_value',true); ?>" /></td>
					</tr>
					<tr>
						<td>Driven Train</td>
						<td> <input type='text' name="driventrain_value" value="<?php echo get_post_meta($post->ID,'driven_train',true); ?>" /></td>
					</tr>
					
				</table>
			</div>
			
			<?php
		}
		
		//if the post is deleted this function triggers
		function delete_post($post_id){
			global $wpdb;
			$cracking_table = $wpdb->prefix . 'cron';
			$wpdb->query($wpdb->prepare("DELETE FROM $cracking_table WHERE p_id = %d", $post_id));
		}
		
		//options page
		function optionspage(){
			add_options_page('mysql database information','Cron Information','activate_plugins','cron-information',array($this,'optionsPageDetails'));
		}
		
		//details
		function optionsPageDetails(){
			if($_POST['mysql_information_submit'] == 'Y'){
				include MYSQLIMPORT_CORE . '/options.php';
			}
			
			$data = get_option('cron_information');
			$ftp = get_option('cron_ftp');
			
			if($_POST['free_the_cron_table'] == 'Y'){
				global $wpdb;
				$cracking_table = $wpdb->prefix . 'cron';
				$wpdb->query("DELETE FROM $cracking_table");
			}
			
		?>
		
			<div class="wrap">
				<?php screen_icon('options-general'); ?>
				
				<h2>MySql & FTP Details</h2>
				<?php 
					if($_POST['mysql_information_submit'] == 'Y'){
						echo '<div class="updated"><p>Saved</p></div>';
					}
					
					if($_POST['free_the_cron_table'] == 'Y'){
						echo '<div class="updated"><p>Tracking Table is now clean</p></div>';
					}
				?>
				
				<form action="" method="post">
					<input type="hidden" name="mysql_information_submit" value="Y" />								
					<table class="form-table">
							
							<tr>
								<td colspan="3"> <h2>MySql Information</h2> </td>
							</tr>
							
							<tr valign="top"><th scope="row">MYSQL SERVER (localhost:port) </th>
							
								<td colspan="2"><input style="width:400px" name="mysql_information[server]" type="text" value= "<?php echo $data['server']; ?>" /></td>												
							</tr>
							
							<tr valign="top"><th scope="row">MYSQL DATABASE </th>
							
								<td colspan="2"> <input style="width:400px" name="mysql_information[db]" type="text" value= "<?php echo $data['db']; ?>" /></td>												
							</tr>
							
							<tr valign="top"><th scope="row">DATABASE TABLE</th>
							
								<td colspan="2"> <input style="width:400px" name="mysql_information[table]" type="text" value= "<?php echo $data['table']; ?>" /></td>												
							</tr>
							
							<tr valign="top"><th scope="row">DATABASE USER </th>
							
								<td colspan="2"><input style="width:400px" name="mysql_information[user]" type="text" value= "<?php echo $data['user']; ?>" /></td>												
							</tr>
							
							<tr valign="top"><th scope="row">USER PASSWORD </th>
							
								<td colspan="2"><input style="width:400px" name="mysql_information[password]" type="text" value= "<?php echo $data['password']; ?>" /></td>												
							</tr>
							
							<tr>
								<td colspan="3"> <h2>FTP Information (under construction)</h2> </td>
							</tr>
							
							<tr valign="top"><th scope="row">FTP SERVER </th>
							
								<td><input style="width:400px" name="ftp_information[server]" type="text" value= "<?php echo $ftp['server']; ?>" /></td>												
							</tr>
							
							<tr valign="top"><th scope="row">FTP USER </th>
							
								<td><input style="width:400px" name="ftp_information[user]" type="text" value= "<?php echo $ftp['user']; ?>" /></td>												
							</tr>
							<tr valign="top"><th scope="row">FTP password </th>
							
								<td><input style="width:400px" name="ftp_information[password]" type="text" value= "<?php echo $ftp['password']; ?>" /></td>												
							</tr>
							<tr>
								<td colspan="3"> Please Insert wp-content direcotory relative to ftp root directory (".../wp-content") pls see the screenshots attached &nbsp <a href= "<?php echo plugins_url('/imdb-scraping-easy/screenshots/screenshots.png'); ?>" target="_blank">screenshots</a> </td>
								
							</tr>
							<tr valign="top"><th scope="row">FTP Path </th>
							
								<td colspan="3"> <input style="width:400px" name="ftp_information[path]" type="text" value= "<?php echo $ftp['path']; ?>" /></td>												
							</tr>
							<tr>
							
							
							
							<tr>
								<td>
								<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
								</td>
							<tr>
							
						</table>
				</form>
				
				<div style="margin-top:20px;"></div>
				<?php screen_icon('options-general'); ?>
				<h2>Free Cron Tracking Table</h2>
				<small>
					If the mother table is somehow cleared, please clear the cron tracking table. Otherwise every row may not be included into wp database from the mother database
				</small>
				
				<form action="" method="post">
					<input type="hidden" name="free_the_cron_table" value="Y" />
					<input class="button-primary" type="submit" name="free" value="free the table" />
				</form>
				
			</div>
			
		<?php
			
		}
				
		//DATABASE TRACKING
		function table_creation(){
			global $wpdb;
			$table = $wpdb->prefix . 'cron';
			$sql = "CREATE TABLE IF NOT EXISTS $table(
				`id` bigint unsigned NOT NULL AUTO_INCREMENT,
				`p_id` bigint unsigned NOT NULL,
				`c_id` bigint unsigned NOT NULL,		
				PRIMARY KEY(id)				
			)";
			
			if(!function_exists('dbDelta')) :
				include ABSPATH . 'wp-admin/includes/upgrade.php';
			endif;
			
			dbDelta($sql);
		}	
		
	}
	
	$mysql_cron = new mysql_import_admin();
endif;