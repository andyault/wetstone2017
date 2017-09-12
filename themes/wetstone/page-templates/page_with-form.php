<?php
/**
 * Template Name: Page with form
 */

	get_header();
	wp_reset_postdata();

	if(get_the_content())
		get_template_part('template-parts/basic', 'page-nobody');
?>

<section class="page-posts site-content site-content-small site-content-padded">
	<h2 class="section-header">
		<?php
			//todo - default to 'our _', allow %s, remove if no value?
			$label = get_post_meta($post->ID, 'page_listlabel', true);

			if($label)
				echo $label;
			else
				echo 'Inquiry Form';
		?>
	</h2>

	<?php get_template_part('template-parts/form', $post->post_name); ?>
</section>

<?php get_footer(); ?>