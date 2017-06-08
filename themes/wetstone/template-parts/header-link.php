<?php
	$page = $wp_query->post;

	$id = $post->ID;
	$isActive = is_page($id) || $page->post_parent == $id;
	$activeClass = $isActive ? 'active' : '';

	if($id == get_option('page_on_front')) {
		echo sprintf(
			'</ul>
			<a href="%s" class="header-link %s"><img src="%s" class="header-logo-site"></a>
			<ul class="header-nav header-nav-site">',

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
?>