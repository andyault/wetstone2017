<?php
	get_header();
	the_post();

	//load single template - in order, try posttype-single, basic-single, then basic-page
	$postType = get_post_type();

	$templates = [
		'template-parts/' . $postType . '-single.php',
		'template-parts/basic-single.php',
		'template-parts/basic-page.php'
	];

	locate_template($templates, true, false);
?>

<!-- prev/next links, inquire link -->
<section class="single-footer site-content site-content-padded">
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

		//for posts - back to posts button
		//for products/services - inquire button
		if(in_array($postType, ['product', 'service'])) {
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
		} else {
			$back = sanitize_text_field($_GET['back']);

			if($back) {
				$page = get_page_by_path($back);

				echo sprintf(
					'<a href="%s" class="single-inquire link link-button">Back to %s</a>',

					get_permalink($page),
					$page->post_title
				);
			}
		}

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