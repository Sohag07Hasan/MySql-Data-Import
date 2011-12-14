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