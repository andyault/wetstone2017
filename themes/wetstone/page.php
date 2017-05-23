<?php
	get_header();
	the_post();

	get_template_part('template-parts/basic', 'page');

	//showing posts
	//first, see if we have a post type meta
	$postType = get_post_meta($post->ID, 'page_posttype', true);

	if($postType) {
		//then make sure there are posts with the post type
		$posts = get_posts([
			'post_type'      => $postType,
			'posts_per_page' => -1 //todo
		]);

		if(count($posts)) {
			?>

			<section class="page-posts site-content site-content-small">
				<h2 class="section-header">
					<?php echo sprintf('Our %s', get_post_type_object($postType)->labels->name); ?>
				</h2>

				<div class="page-list">
					<?php
						//weird with global but it works
						global $post;

						foreach($posts as $post) {
							setup_postdata($post);

							get_template_part('template-parts/' . $postType, 'page');
						}
					?>
				</div>
			</section>

			<?php
		}
	}

	get_footer();
?>