<?php
	get_header();
	the_post();

	if(get_the_content())
		get_template_part('template-parts/basic', 'page');

	//first, see if we have a post type meta
	$postType = get_post_meta($post->ID, 'page_posttype', true);

	if($postType) {
		//then make sure there are posts with the post type
		$posts = wetstone_get_children(
			$post,
			['posts_per_page' => -1] //todo
		);

		if(count($posts)) {
			?>

			<section class="page-posts site-content site-content-small">
				<h2 class="section-header">
					<?php
						//todo - default to 'our _', allow %s, remove if no value?
						$label = get_post_meta($post->ID, 'page_listlabel', true);

						if($label)
							echo $label;
						else
							echo 'Our ' . get_post_type_object($postType)->labels->name;
					?>
				</h2>

				<div class="page-list">
					<?php
						$postMetaKey = get_post_meta($post->ID, 'page_metakey', true);
						$postMetaVal = get_post_meta($post->ID, 'page_metaval', true);

						//potential templates - loads page_postcat-page/front, page_posttype-page/front, postcat-page/front
						$templates = [
							'template-parts/' . $postType . '-page.php',
							'template-parts/' . $postType . '-front.php'
						];

						if($postMetaKey == 'category_name') {
							$sanitized = sanitize_title_with_dashes($postMetaVal);

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
								if($postMetaKey != 'category_name' || $cat->name != $postMetaVal) {
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