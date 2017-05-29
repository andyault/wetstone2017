<?php
/**
 * Template Name: Page with form - small
 */

	get_header();
	the_post();
?>

<section class="page-posts site-content site-content-tiny">
	<h2 class="section-header"><?php the_title(); ?></h2>

	<?php get_template_part('template-parts/form', $post->post_name); ?>
</section>

<?php get_footer(); ?>