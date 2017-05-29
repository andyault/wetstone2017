<?php
	get_header();
	the_post();

	//load single template - in order, try posttype-single, basic-single, then basic-page
	$postType = get_post_type();

	if(locate_template(sprintf('template-parts/%s-single.php', $postType)) != '') {
		get_template_part('template-parts/' . $postType, 'single');
	} else {
		if(locate_template('template-parts/basic-single.php') != '')
			get_template_part('template-parts/basic', 'single');
		else
			get_template_part('template-parts/basic', 'page');
	}

	//todo - https://codex.wordpress.org/Pagination
?>

<!-- prev/next links, inquire link -->
<section class="single-footer site-content">
	<?php
		$prev = get_previous_post(); 
		$next = get_next_post();
		
		if($prev) {
			echo sprintf(
				'<a href="%s" class="link link-body"><i class="larr"></i> %s</a>',
				get_permalink($prev),
				get_the_title($prev)
			);
		}

		//fix for flex alignment
		echo '<span>&nbsp;</span>';

		//inquire button
		$label = get_post_type_object($postType)->labels->singular_name;

		echo sprintf(
			'<a href="%s" class="single-inquire link link-button">%s</a>',
			esc_url(add_query_arg(
				[
					'subject' => strtolower(get_the_title()),
					'context' => strtolower($label)
				],
				get_permalink(get_page_by_path('/contact'))
			)),
			'Inquire about this ' . $label
		);

		if($next) {
			echo sprintf(
				'<a href="%s" class="link link-body">%s <i class="rarr"></i></a>',
				get_permalink($next),
				get_the_title($next)
			);
		}
	?>
</section>

<?php get_footer(); ?>