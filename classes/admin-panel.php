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
			
		?>
		
			<div class="wrap">
				<?php screen_icon('options-general'); ?>
				
				<h2>MySql Details</h2>
				<?php 
					if($_POST['mysql_information_submit'] == 'Y'){
						echo '<div class="updated"><p>Saved</p></div>';
					}
				?>
				
				<form action="" method="post">
					<input type="hidden" name="mysql_information_submit" value="Y" />								
					<table class="form-table">
					
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
								<td>
								<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
								</td>
							<tr>
							
						</table>
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
		
		
		//cron functions
		function db_object(){
			$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
			return $wpdb;
		}
		
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
		
	}
	
	$mysql_cron = new mysql_import_admin();
endif;