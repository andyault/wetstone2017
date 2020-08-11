<?php
/*
Plugin Name: WetStone Core Functionality
Description: Adds much of the functionality required for the WetStone website to work. Removing or disabling this will break a lot of things.
Author: Andrew Ault
Version: 1.0
*/

include('wetstone-core-util.php');

//abstract getting/setting options
$options = [];

add_filter( 'wp_mail_from', 'my_mail_from' );
function my_mail_from( $email ) {
    return "postmaster@wetstonetech.com";
}

add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name ) {
    return "Wetstone Technologies";
}

//supress warning about using same email.
add_filter('pre_user_email', 'skip_email_exist');
function skip_email_exist($user_email){
    define( 'WP_IMPORTING', 'SKIP_EMAIL_EXIST' );
    return $user_email;
}

add_filter( 'password_reset_expiration', function( $expiration ) {
    return MONTH_IN_SECONDS;
});

function wetstone_add_option($group, $name, $default) {
	if(is_array($default))
		throw new Exception('Default value must not be array');

	//register setting
	register_setting('wetstone_settings', sprintf('wetstone_%s_%s', $group, $name));

	//add to options array - for displaying options page
	global $options;

	if(!array_key_exists($group, $options))
		$options[$group] = [];

	$options[$group][$name] = $default;
}

function wetstone_get_option($group, $name) {
	global $options;

	return get_option(sprintf('wetstone_%s_%s', $group, $name), $options[$group][$name]);
}

//options page for everything
function wetstone_add_options_content() {
	global $options;
	?>

	<div class="wrap">
		<h1>Wetstone Settings</h1>

		<form method="POST" action="options.php">
			<?php
				settings_fields('wetstone_settings');

				foreach($options as $group => $settings) { ?>
					<h2><?php echo wetstone_name_to_label($group); ?></h2>

					<table class="form-table">
						<tbody>
							<?php
								foreach($settings as $name => $default) {
									$fullName = sprintf('wetstone_%s_%s', $group, $name);
									?>

									<tr>
										<th scope="row">
											<label for="<?php echo $fullName; ?>">
												<?php echo wetstone_name_to_label($name); ?>
											</label>
										</th>

										<td>
											<input type="text"
												id="<?php echo $name; ?>"
												name="<?php echo $fullName; ?>"
												value="<?php echo wetstone_get_option($group, $name); ?>"
												class="regular-text" />
										</td>
									</tr>
								<?php }
							?>
						</tbody>
					</table>
				<?php }

				submit_button();
			?>
		</form>
	</div>

	<?php
}

/**
 * Filters the TinyMCE config before init.
 *
 * @param array  $mceInit   An array with TinyMCE config.
 * @param string $editor_id Unique editor identifier, e.g. 'content'.
 */
add_filter( 'tiny_mce_before_init', 'wpse_tiny_mce_before_init', 10, 2 );
function wpse_tiny_mce_before_init( $mceInit, $editor_id ) {
    // Allow javascript: in href attributes.
    $mceInit['allow_script_urls'] = true;

    // Allow onclick attribute in anchor tags.
  if ( ! isset( $mceInit['extended_valid_elements'] ) ) {
        $mceInit['extended_valid_elements'] = '';
    } else {
        $mceInit['extended_valid_elements'] .= ',';
    }
    $mceInit['extended_valid_elements'] .= 'a[href|rel|class|id|style|onclick]';

    return $mceInit;
}

function wetstone_add_options_page() {
	add_options_page('Wetstone Site Settings', 'Wetstone Site Settings', 'manage_options', 'wetstone', 'wetstone_add_options_content');
}

add_action('admin_menu', 'wetstone_add_options_page');

 
function dataset_report_menu(){    
	$page_title = 'Dataset Report';   
	$menu_title = 'Dataset Report';   
	$capability = 'manage_options';   
	$menu_slug  = 'dataset_report_menu';   
	$function   = 'dataset_report_menu_page';   
	$icon_url   = 'dashicons-media-code';   
	$position   = 4;    
add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

} 
add_action( 'admin_menu', 'dataset_report_menu' ); 

function dataset_notification_menu(){    
	$page_title = 'Dataset Notification';   
	$menu_title = 'Dataset Notification';   
	$capability = 'manage_options';   
	$menu_slug  = 'dataset_notification_menu';   
	$function   = 'dataset_notification_menu_page';   
	$icon_url   = 'dashicons-media-code';   
	$position   = 4;    
add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

} 
add_action( 'admin_menu', 'dataset_notification_menu' ); 

function ws_inactive_users_menu(){    
	$page_title = 'Users Report';   
	$menu_title = 'Users Report';   
	$capability = 'manage_options';   
	$menu_slug  = 'ws_inactive_users_menu';   
	$function   = 'ws_inactive_users_page';   
	$icon_url   = 'dashicons-media-code';   
	$position   = 4;    
add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

} 
add_action( 'admin_menu', 'ws_inactive_users_menu' ); 

function dataset_notification_menu_page() {	
	global $wpdb;
	$wpdb->show_errors();
	echo "<h1>Dataset Notification</h1>";	
		if($_GET['emailsent'] == true)
			echo '<div class="notice notice-success is-dismissible"><p>'.$_GET["counter"].' Customers notified.</p></div>';
		if($_GET['problem'] == true)
			echo '<div class="notice notice-success is-dismissible"><p>There was a problem sending the emails.</p></div>';
	?>
		<form method="POST" id="mail-dataset" name="mail-dataset" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" autocomplete="off">
		<input type="hidden" name="action" value="wetstone-mail-dataset">
				  <?php wp_nonce_field("wetstone-mail-dataset") ?>
		
			<label for="group">Select group of customers to email:</label><br />
			<select id="group" name="group">
			  <option value="gargoylemp">Gargoyle</option>
			  <option value="stegohunt">StegoHunt</option>
			  <option value="gd">General Dynamics</option>
			  <option value="all">All</option>
			</select><br /><br />
			
			<!-- WILL ADD FUNCTIONALITY TO EDIT SUBJECT AND EMAIL LATER
			
			<label for="type">Select Message Type:</label><br>
			<select id="type">
			  <option value="precanned">Precanned</option>
			  <option value="custom">Custom</option>
			</select><br /><br />
			<input type="text" id="subject" name="subject" value="WetStone Dataset Update Release"><br>
			<textarea name="message" rows="10" cols="100">New datasets are available for download. Please go to our Customer SupportPortal to access the latest datasets. 
			
Our team of dedicated Marlware Analysts update our Malware Repository with new datasets every month. Please be sure to update your datasets to ensure you are getting the most out of your products.
			
For instructions, please view our How To: Download Datasets video. If you need further assistance, emails us at sales@wetstonetech.com.
			
Thank you,
The WetStone Team
			
www.wetstonetech.com
sales@wetstonetech.com
571-340-3469
			</textarea>  -->
			
		<?php 
		
		$toEmail = [];
		$users = get_users( array( 'fields' => array( 'ID' ) ) );
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[116];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(116);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[115];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(115);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[633];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(633);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[621];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(621);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
		
		$result = array_unique($toEmail);
		$tooMail = implode(",",$result);
		
		//echo $tooMail;
		
		submit_button(__('Send Email'), 'primary', 'mailcustomer', true, ['id' => 'mailcustomersub']);	?>
		</form>


<?php
	
}

function wetstone_post_mail_dataset() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-mail-dataset'))
		return wp_nonce_ays('wetstone-mail-dataset');
	$toEmail = [];
	$users = get_users( array( 'fields' => array( 'ID' ) ) );
	
	if ($_POST['group'] == 'gargoylemp' || $_POST['group'] == 'all') {		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[116];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(116);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[633];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(633);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
	}
	
	if ($_POST['group'] == 'stegohunt' || $_POST['group'] == 'all') {		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[115];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(115);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
	}	
		
	if ($_POST['group'] == 'gd' || $_POST['group'] == 'all') {		
		foreach($users as $user_id){
				$myproducts = get_user_meta( $user_id->ID, 'wetstone_products', true);
				$mycustomer = get_user_by('id', $user_id->ID);
				$myinfo = $myproducts[621];
				$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false;
				$productName = get_the_title(621);
				if ($owned) array_push($toEmail,$mycustomer->user_email);
		}
	}	
		
$body = '<pre>';	
	$body .= "Dear WetStone Customer,<br /><br />New datasets are available for download. Please go to our <a href='http://www.wetstonetech.com/portal' target='_blank'>Customer Support Portal</a> to access the latest datasets.<br /><br />Our team of dedicated Malware Analysts update our Malware Repository with new datasets every month. Please be sure to update your datasets to ensure you are getting the most out of your products.<br /><br />For instructions, please view our <a href='http://youtu.be/ukObb95Wclc' target='_blank'>How To: Download Datasets</a> video. If you need further assistance, email us at <a href='mailto:sales@wetstonetech.com'>sales@wetstonetech.com</a>.<br /><br />Thank you,<br />The WetStone Team<br /><br /><a href='http://www.wetstonetech.com' target='_blank'>www.wetstonetech.com</a><br /><a href='mailto:sales@wetstonetech.com'>sales@wetstonetech.com</a><br />571-340-3469";
	$emailWidth = wetstone_get_option('form_handling', 'email_width');
	$result = array_unique($toEmail);
	$counter = count($result);
	$tooMail = implode(",",$result);	
	$subject = 'WetStone Dataset Update Release';
	$tootMail = 'wconklin@allencorporation.com';	

	if(wetstone_dataset_mail($tooMail,$subject,$body)) {
			wp_safe_redirect(add_query_arg(['page' => 'dataset_notification_menu', 'emailsent' => true ,'counter' => $counter], 'admin.php?page=dataset_notification_menu'));			
	} else {
			//go back to form with old data
			wp_safe_redirect(add_query_arg(['page' => 'dataset_notification_menu', 'problem' => true], 		'admin.php?page=dataset_notification_menu'));
			exit;
			} 
	
}

add_action('admin_post_wetstone-mail-dataset', 'wetstone_post_mail_dataset');

function dataset_report_menu_page() {	
	global $wpdb;
	$wpdb->show_errors();
	echo "<h1>Dataset Report</h1>";
	
	// Will return to this to add what files are in DB //
/*	$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets ORDER BY date_added DESC');		
	if (!$result) {
		echo "There is a problem with the database."; 
		$wpdb->print_error();
	} else { 
		$wpdb->hide_errors();
		$aca_table = '<div style="margin: 5% 5%">
				<table style="width:95%; 
						margin-bottom: 1.5em;
						border-spacing: 0;
						border: 1px solid #ddd;">
				<caption>Available Files</caption>
				<thead style="background-color: rgba(29,150,178,1);
						border: 1px solid rgba(29,150,178,1);
						font-weight: normal;
						text-align: center;
						color: white;">
					<tr>
						<th scope="col">Filename</th>
						<th scope="col">Date Added</th>
						<th scope="col">Number of Downloads</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">';
      
			foreach ( $result as $page )
			{
			   $aca_table .= '<tr>
								<th scope="row">'. $page->dataset_name. '</th>
								<td>'. $page->date_added. '</td>
								<td>'. $page->times_downloaded. '</td>
							  </tr>';		   
			}
			
		$aca_table .= '</tbody></table></div>';

		echo $aca_table;

		
	} */
		
	if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
	//we give the value of the starting row to 0 because nothing was found in URL
		$startrow = 0;
		
	//otherwise we take the value from the URL
	} else {
	  $startrow = (int)$_GET['startrow'];
	}
	$prev = $startrow - 25;
	$result2 = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets_log ORDER BY download_date DESC LIMIT '.$startrow.', 25');		
	if (!$result2) {
		echo "There is a problem with the database."; 
		$wpdb->print_error();
	} else { 
		$wpdb->hide_errors();

		$aca_table2 = '<div style="margin: 5% 2%"><form id="download-csv" action="'. esc_url( admin_url("admin-post.php") ) .'" method="post">
				   <input type="hidden" name="action" value="wetstone-download-csv">'.
				   wp_nonce_field("wetstone_post_download_csv") .
				   '<input type="submit" value="Download CSV" style="float: right"><br>
			</form>
				<table style="width:99%; 
						margin-bottom: 1.5em;
						border-spacing: 0;
						border: 1px solid #ccc;">
				<caption style="font-weight:bold">Dataset and file downloads</caption>
				<thead style="background-color: rgba(29,150,178,1);
						border: 1px solid rgba(29,150,178,1);
						font-weight: normal;
						text-align: center;
						color: white;">
					<tr>
						<th scope="col">User Name</th>
						<th scope="col">User Email</th>
						<th scope="col">IP Address</th>
						<th scope="col">Asset</th>
						<th scope="col">Download Date</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">';
			$counter = 0;
			foreach ( $result2 as $page )
			{			
				$counter++;				
				
			   $dataset = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets WHERE dataset_id=\''.$page->asset_id.'\'');
			   $userData = get_userdata($page->user_id);
			   if ($counter % 2 == 0) {
			   $aca_table2 .= '<tr>'; } else {
			   $aca_table2 .= '<tr style="background-color:#ddd">';}
			   $aca_table2 .= '	<th scope="row">'. $userData->first_name . ' ' . $userData->last_name . '</th>
								<td>'. $userData->user_email . '</td>
								<td>'. $page->user_ip . '</td>
								<td>'. $dataset[0]->dataset_name . '</td>
								<td>'. $page->download_date . '</td>
							  </tr>';		   
			}
			
		$aca_table2 .= '</tbody></table>';
		$aca_table2 .= '<span style="float:right; margin-right:25px;">';
		if ($prev >= 0) {		
		$aca_table2 .= '<a href="'.$_SERVER["PHP_SELF"].'?page=dataset_report_menu&startrow='.$prev.'" style="text-decoration:none"><< Previous 25</a> &nbsp; &nbsp; &nbsp';
		}

		if (count($result2) == 25) {
			$aca_table2 .= '<a href="'.$_SERVER["PHP_SELF"].'?page=dataset_report_menu&startrow='.($startrow+25).'"  style="text-decoration:none">Next 25 >></a>';
			$aca_table2 .= '</span></div>';
		}
		echo $aca_table2;

		
	}
		
}

function ws_inactive_users_page() {	
	global $wpdb;
	$wpdb->show_errors();
	echo "<h1>Users Report</h1>";

	if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
	//we give the value of the starting row to 0 because nothing was found in URL
		$startrow = 0;
		
	//otherwise we take the value from the URL
	} else {
	  $startrow = (int)$_GET['startrow'];
	}
	$prev = $startrow - 25;
	$result2 = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY ID ASC LIMIT '.$startrow.', 25');		
	if (!$result2) {
		echo "There is a problem with the database."; 
		$wpdb->print_error();
	} else { 
		$wpdb->hide_errors();
		//$wpdb->print_error();

		$aca_table2 = '<div style="margin: 5% 2%"><form id="download-user-csv" action="'. esc_url( admin_url("admin-post.php") ) .'" method="post">
				   <input type="hidden" name="action" value="wetstone-download-user-csv">'.
				   wp_nonce_field("wetstone_post_download_user_csv") . '<input type="submit" value="Download CSV" style="float: right"><br>
			 </form>
				<table style="width:99%; 
						margin-bottom: 1.5em;
						border-spacing: 0;
						border: 1px solid #ccc;">
				<caption style="font-weight:bold">Last Login by Customer</caption>
				<thead style="background-color: rgba(29,150,178,1);
						border: 1px solid rgba(29,150,178,1);
						font-weight: normal;
						text-align: left;
						padding: 10px;
						color: white;">
					<tr>
						<th scope="col" style="text-align:left; padding: 10px;">User Name</th>
						<th scope="col" style="text-align:left; padding: 10px;">User Email</th>
						<th scope="col" style="text-align:left; padding: 10px;">Licensed Products</th>
						<th scope="col" style="text-align:left; padding: 10px;">License Expiration</th>
						<th scope="col" style="text-align:left; padding: 10px;">Last Login</th>
					</tr>
				</thead>
				<tbody style="text-align: left;">';
			$counter = 0;
			foreach ( $result2 as $page )
			{			
			   $counter++;	
			   $userData = get_userdata($page->ID);
			   $lastLogin = array_values($userData->session_tokens);
				if ($lastLogin) {   $lastLogin = date("Y-m-d H:i:s",$lastLogin[0]['login']); 
				} else { $lastLogin = " - "; }
			   $arrayValues = array_values($userData->wetstone_products);
			   $arrayKeys = array_keys($userData->wetstone_products);
			   $licensedProducts = "";
			   $expirations = "";
			   $expired = "";
			   
			   for ($x = 0; $x< count($arrayKeys); $x++) {
				   $exploded = explode(": ",get_the_title($arrayKeys[$x]));				   				   
				   if (strtotime($arrayValues[$x]['expiry']) < time()) { $expired = "<span style='color:red; font-weight:bold;'> - Expired</span>"; }
				   $licensedProducts .= $exploded[0] . "<br />";
				   $expirations .= $arrayValues[$x]['expiry'] . $expired ."<br />";
			   
			   
			   }
			  // if ($licensedProducts) {   $licensedProducts = count($licensedProducts) . " " . $licensedProducts[1]['expiry']; 
			 //	} else { $lastLogin = " - "; }
			   
			   
			   if ($counter % 2 == 0) {
			   $aca_table2 .= '<tr>'; } else {
			   $aca_table2 .= '<tr style="background-color:#ddd">';}
			   $aca_table2 .= "	<th scope='row' style='text-align:left; padding: 10px;'>". $userData->first_name . " " . $userData->last_name . "</th>
								<td style='text-align:left; padding: 10px;'>". $userData->user_email . "</td>
								<td style='text-align:left; padding: 10px;'>". $licensedProducts . "</td>
								<td style='text-align:left; padding: 10px;'>". $expirations . "</td>
								<td  style='text-align:left; padding: 10px;'>". $lastLogin . "</td>
							  </tr>";		   
			}
			
		$aca_table2 .= '</tbody></table>';
		$aca_table2 .= '<span style="float:right; margin-right:25px;">';
		if ($prev >= 0) {		
		$aca_table2 .= '<a href="'.$_SERVER["PHP_SELF"].'?page=ws_inactive_users_menu&startrow='.$prev.'" style="text-decoration:none"><< Previous 25</a> &nbsp; &nbsp; &nbsp';
		}

		if (count($result2) == 25) {
			$aca_table2 .= '<a href="'.$_SERVER["PHP_SELF"].'?page=ws_inactive_users_menu&startrow='.($startrow+25).'"  style="text-decoration:none">Next 25 >></a>';
			$aca_table2 .= '</span></div>';
		}
		echo $aca_table2;

		
	}
		
}

function wetstone_post_download_csv() {
	global $wpdb;
	$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets_log ORDER BY download_date DESC');
	
	$delimiter = ",";
    $filename = "downloads_" . date('Y-m-d') . ".csv";
	//create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('User Name', 'User Email', 'User IP', 'Asset', 'Download Date');
    fputcsv($f, $fields, $delimiter);
	
	foreach ( $result as $page )
			{	
		$dataset = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets WHERE dataset_id=\''.$page->asset_id.'\'');
		$userData = get_userdata($page->user_id);
			
		$lineData = array($userData->first_name . ' ' . $userData->last_name, $userData->user_email, $page->user_ip, $dataset[0]->dataset_name, $page->download_date);
		fputcsv($f, $lineData, $delimiter);
			}
	    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}	

add_action('admin_post_wetstone-download-csv', 'wetstone_post_download_csv');


function wetstone_post_download_user_csv() {
	global $wpdb;
	$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY ID ASC');
	
	$delimiter = ",";
    $filename = "inactive_users_" . date('Y-m-d') . ".csv";
	//create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('User Name', 'User Email', 'Licensed Product', 'License Expiration Date', 'License Expired?', 'Last Login');
    fputcsv($f, $fields, $delimiter);
	$counter = 0;
	foreach ( $result as $page )
			{	
			   $userData = get_userdata($page->ID);
			   $lastLogin = array_values($userData->session_tokens);
			   $accountType = array_keys($userData->wetstone_wp_capabilities);
				if ($lastLogin) {   $lastLogin = date("Y-m-d H:i:s",$lastLogin[0]['login']); 
				} else { $lastLogin = "Not Recorded"; }
				
			   $arrayValues = array_values($userData->wetstone_products);
			   $arrayKeys = array_keys($userData->wetstone_products);

			   if ($accountType[0] != 'administrator') {
				  for ($x = 0; $x< count($arrayKeys); $x++) {
						$licensedProducts = "";
						$expirations = "";
						$expired = "";
						
					   $exploded = explode(": ",get_the_title($arrayKeys[$x]));				   				   
					   if (strtotime($arrayValues[$x]['expiry']) < time()) { 
							$expired = "Expired"; 
					   } else { 
							$expired = ""; 
					   }
					   $licensedProducts = str_replace("™","",$exploded[0]);
					   $licensedProducts = str_replace("&#8211;","",$licensedProducts);
					   $expirations = $arrayValues[$x]['expiry'];
				   
						$lineData = array($userData->first_name . ' ' . $userData->last_name, $userData->user_email,$licensedProducts, $expirations, $expired, $lastLogin);
						fputcsv($f, $lineData, $delimiter);
				   } 
			   }
			}
	    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}	

add_action('admin_post_wetstone-download-user-csv', 'wetstone_post_download_user_csv');

//include modules
include('wetstone-security.php');
include('wetstone-customer-meta.php');
include('wetstone-form-handling.php');
include('wetstone-post-types.php');
include('wetstone-downloads.php');
include('wetstone-shortcodes.php');