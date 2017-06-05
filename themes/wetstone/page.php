<?php
	get_header();

	the_post();

	get_template_part('template-parts/basic', 'page');

	//showing posts - todo - maybe turn this into a shortcode? idk?
	//first, see if we have a post type meta
	$postType = get_post_meta($post->ID, 'page_posttype', true);
	$postCat = get_post_meta($post->ID, 'page_postcat', true);

	if($postType) {
		//then make sure there are posts with the post type
		$posts = get_posts([
			'post_type'      => $postType,
			'category_name'  => $postCat,
			'posts_per_page' => -1 //todo - https://codex.wordpress.org/Pagination
		]);

		if(count($posts)) {
			?>

			<section class="page-posts site-content site-content-small">
				<h2 class="section-header">
					<?php echo sprintf('Our %s', get_post_type_object($postType)->labels->name); ?>
				</h2>

				<div class="page-list">
					<?php
						//potential templates - loads page_postcat-page/front, page_posttype-page/front, postcat-page/front
						$templates = [
							'template-parts/' . $postType . '-page.php',
							'template-parts/' . $postType . '-front.php'
						];

						if($postCat) {
							$sanitized = sanitize_title_with_dashes($postCat);

							array_unshift(
								$templates,
								'template-parts/' . $sanitized . '-page.php',
								'template-parts/' . $sanitized . '-front.php'
							);
						}

						//weird with global but it works
						global $post;

						foreach($posts as $post) {
							$postTemplates = $templates;
							$postCats = get_the_category($post->ID);

							foreach($postCats as $cat) {
								if($cat->name != $postCat) {
									$sanitized = sanitize_title_with_dashes($cat->name);

									array_push(
										$postTemplates,
										'template-parts/' . $sanitized . '-page.php',
										'template-parts/' . $sanitized . '-front.php'
									);
								}
							}

							setup_postdata($post);

							locate_template(
								$postTemplates,
								true,
								false
							);
						}
					?>
				</div>
			</section>

			<?php
		}
	}

	get_footer();
?>