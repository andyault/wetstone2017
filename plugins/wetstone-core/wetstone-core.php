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
	
	$result2 = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets_log ORDER BY download_date DESC');		
	if (!$result2) {
		echo "There is a problem with the database."; 
		$wpdb->print_error();
	} else { 
		$wpdb->hide_errors();

		$aca_table2 = '<div style="margin: 5% 2%"><form id="download-csv" action="'. esc_url( admin_url("admin-post.php") ) .'" method="post">
				   <input type="hidden" name="action" value="wetstone-download-csv">'.
				   wp_nonce_field("wetstone-download-csv") .
				   submit_button(__("Download CSV"), "primary", "download-csv", true) .'<br>
			</form><br />
				<table style="width:99%; 
						margin-bottom: 1.5em;
						border-spacing: 0;
						border: 1px solid #ddd;">
				<caption style="font-weight:bold">Dataset and file downloads</caption>
				<thead style="background-color: rgba(29,150,178,1);
						border: 1px solid rgba(29,150,178,1);
						font-weight: normal;
						text-align: center;
						color: white;">
					<tr>
						<th scope="col">User Name/Email</th>
						<th scope="col">User Email</th>
						<th scope="col">IP Address</th>
						<th scope="col">Asset</th>
						<th scope="col">Download Date</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">';
      
			foreach ( $result2 as $page )
			{			
				
			   $dataset = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets WHERE dataset_id=\''.$page->asset_id.'\'');
			   $userData = get_userdata($page->user_id);
			   $aca_table2 .= '<tr>
								<th scope="row">'. $userData->first_name . ' ' . $userData->last_name . '</th>
								<td>'. $userData->user_email . '</td>
								<td>'. $page->user_ip . '</td>
								<td>'. $dataset[0]->dataset_name . '</td>
								<td>'. $page->download_date . '</td>
							  </tr>';		   
			}
			
		$aca_table2 .= '</tbody></table></div>';

		echo $aca_table2;

		
	}
		
}

function wetstone_post_download_csv() {
	$delimiter = ",";
    $filename = "downloads_" . date('Y-m-d') . ".csv";
	//create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('ID', 'Name', 'Email', 'Phone', 'Created', 'Status');
    fputcsv($f, $fields, $delimiter);
	
	$status = ($row['status'] == '1')?'Active':'Inactive';
    $lineData = array('test', 'test', 'test', 'test', 'test', $status);
    fputcsv($f, $lineData, $delimiter);
	
	    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}	

add_action('admin_post_wetstone-download-csv', 'wetstone_post_download_csv');

//include modules
include('wetstone-security.php');
include('wetstone-customer-meta.php');
include('wetstone-form-handling.php');
include('wetstone-post-types.php');
include('wetstone-downloads.php');
include('wetstone-shortcodes.php');