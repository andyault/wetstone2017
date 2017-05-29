<?php

if(!is_user_logged_in()) {
	wp_safe_redirect(get_permalink(get_page_by_path('sign-in')));

	exit;
}