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

add_shortcode('wetstone_list_posts', 'wetstone_list_posts');

function wetstone_list_posts($attrs) {
	$attrs = shortcode_atts([
		'type'     => 'post'
	], $attrs);

	//make sure there are posts with the post type
	$posts = get_posts([
		'post_type'      => $attrs['type'],
		'category_name'  => $attrs['category'],
		'posts_per_page' => -1 //todo - https://codex.wordpress.org/Pagination
	]);

	if(count($posts)) {
		ob_start();
		?>

		</div>

		<section class="page-posts site-content site-content-small">
			<h2 class="section-header">
				<?php echo sprintf('Our %s', get_post_type_object($attrs['type'])->labels->name); ?>
			</h2>

			<div class="page-list">
				<?php
					//weird with global but it works
					global $post;

					foreach($posts as $post) {
						setup_postdata($post);

						get_template_part('template-parts/' . $attrs['type'], 'page'); //big todo, pls escape
					}
				?>
			</div>
		</section>

		<?php

		return ob_get_clean();
	}
}

//add excerpts to posts
function wetstone_add_page_excerpts() {
	add_post_type_support('page', 'excerpt');
}

add_action('init', 'wetstone_add_page_excerpts');