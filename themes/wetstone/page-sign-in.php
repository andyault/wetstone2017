<?php
	get_header();
	the_post();
?>

<section class="page-posts site-content site-content-tiny">
	<h2 class="section-header"><?php the_title(); ?></h2>

	<?php get_template_part('template-parts/form', 'sign-in'); ?>
</section>

<?php get_footer(); ?>