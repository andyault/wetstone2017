<?php get_header(); ?>

<section class="page-posts site-content site-content-small">
	<h2 class="section-header">What's New</h2>

	<div class="page-list">
		<?php
			$posts = get_posts([
				'post_type' => 'post',
				'posts_per_page' => -1
			]);

			global $post;

			foreach($posts as $post) {
				setup_postdata($post);

				//get_template_part('template-parts/post', 'page'); //todo
			}
		?>
	</div>
</section>

<?php get_footer(); ?>