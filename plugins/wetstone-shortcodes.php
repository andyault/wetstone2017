<?php
/**
 * @package WetStone_Core
 * @version 1.0
 */
/*
Plugin Name: WetStone Shortcodes
Description: Adds a number of shortcodes used on the WetStone website.
Author: Andrew Ault
Version: 1.0
*/

//big todo

/*
add_shortcode('wetstone_list_posts', 'wetstone_list_posts');

function wetstone_list_posts($attrs) {
	//make sure we have a post type
	$postType = $attrs['post_type'];

	if(!$postType) return;

	//make sure there are posts with the post type
	$posts = get_posts([
		'post_type'      => $postType,
		'posts_per_page' => -1 //todo
	]);

	if(count($posts)) {
		//output buffering seems cool
		ob_start();

		?>

		<section class="page-posts site-content site-content-small font-reset">
			<h2 class="section-header">
				<?php echo sprintf('Our %s', get_post_type_object($postType)->labels->name); ?>
			</h2>

			<div class="page-list">
				<?php
					//weird with global but it works
					global $post;

					foreach($posts as $post) {
						setup_postdata($post);

						get_template_part(
							sprintf('template-parts/%s', $postType),
							'page'
						);
					}
				?>
			</div>
		</section>

		<?php
	}

	return ob_get_clean();
}
*/