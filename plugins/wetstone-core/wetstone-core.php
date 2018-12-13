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
 * Extend WordPress search to include custom fields
 *
 * https://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

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