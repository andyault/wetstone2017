<?php
	$id = $post->ID;
	$isActive = is_page($id);
	$activeClass = $isActive ? 'active' : '';

	if($id == get_option('page_on_front')) {
		echo sprintf(
			'<a href="%s" class="site-header-link %s"><img src="%s" class="site-header-logo"></a>',
			get_the_permalink(),
			$activeClass,
			get_template_directory_uri() . '/assets/img/logo.svg'
		);
	} else {
		echo sprintf(
			'<a href="%s" class="site-header-link link link-site-header %s">%s</a>',
			get_the_permalink(),
			$activeClass,
			get_the_title()
		);

		if(count($subPages)) {
			echo '<a href class="site-header-link site-header-sub link link-site-header">&raquo;</a>';
		}
	}
?>