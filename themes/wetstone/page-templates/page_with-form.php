<?php
/**
 * Template Name: Page with form
 */

	get_header();
	the_post();

	get_template_part('template-parts/basic', 'page-nobody');
?>

<section class="page-posts site-content site-content-small">
	<h2 class="section-header">Inquiry Form</h2>

	<?php get_template_part('template-parts/form', $post->post_name); ?>
</section>

<?php get_footer(); ?>