<?php
	get_header();
	the_post();

	get_template_part('template-parts/page', 'nobody');
?>

<section class="page-posts site-content site-content-small">
	<h2 class="section-header">Inquiry Form</h2>

	<?php get_template_part('template-parts/form', 'contact'); ?>
</section>

<?php get_footer(); ?>