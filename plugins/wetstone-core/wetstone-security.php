<?php

$wetstone_redirect_slug = 'sign-in';

//prevent access to wp-login.php - https://wordpress.stackexchange.com/questions/62889/disable-or-redirect-wp-login-php
add_action('init', 'wetstone_prevent_wp_login');

function wetstone_prevent_wp_login() {
	global $wetstone_redirect_slug;

    // WP tracks the current page - global the variable to access it
    global $pagenow;
    // Check if a $_GET['action'] is set, and if so, load it into $action variable
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    // Check if we're on the login page, and ensure the action is not 'logout'
    if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
        // Redirect
        wp_redirect(get_permalink(get_page_by_path($wetstone_redirect_slug)));
        // Stop execution to prevent the page loading for any reason
        exit();
    }
}

//disable embedded file editing
define('DISALLOW_FILE_EDIT', true);

//disable xml-rpc - http://www.wpbeginner.com/plugins/how-to-disable-xml-rpc-in-wordpress/
add_filter('xmlrpc_enabled', '__return_false');