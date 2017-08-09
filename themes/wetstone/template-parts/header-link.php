<?php
	$page = $wp_query->post;

	$id = $post->ID;
	$isActive = is_page($id) || $page->post_parent == $id;
	$activeClass = $isActive ? 'active' : '';

	if($id == get_option('page_on_front')) {
		echo sprintf(
			'<a href="%s" class="header-link header-logo-link %s"><img src="%s" class="header-logo-img"></a>',

			get_the_permalink(),
			$activeClass,
			wetstone_get_asset('/img/logo.svg')
		);
	} else {
		echo sprintf(
			'<a href="%s" class="header-link link link-header-site %s">%s</a>', 

			get_the_permalink(),
			$activeClass,
			get_the_title()
		);
	}

	echo "\n";
?>