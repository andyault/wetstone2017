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

add_filter( 'password_reset_expiration', 'adjust_exp');

function adjust_exp ($expiration) {
	return MONTH_IN_SECONDS;
}

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

//include modules
include('wetstone-security.php');
include('wetstone-customer-meta.php');
include('wetstone-form-handling.php');
include('wetstone-post-types.php');
include('wetstone-downloads.php');
include('wetstone-shortcodes.php');