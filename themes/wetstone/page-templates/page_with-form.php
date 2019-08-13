<?php
/**
 * Template Name: Page with form
 */

	get_header();
	wp_reset_postdata();

	if(get_the_content())
		get_template_part('template-parts/basic', 'page-nobody');
?>
<script>
    var mtcaptchaConfig = {
      "sitekey": "MTPublic-VwYnY8ywe"
   };
   (function(){var mt_service = document.createElement('script');mt_service.async = true;mt_service.src = 'https://service.mtcaptcha.com/mtcv1/client/mtcaptcha.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service);
   var mt_service2 = document.createElement('script');mt_service2.async = true;mt_service2.src = 'https://service2.mtcaptcha.com/mtcv1/client/mtcaptcha2.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service2);}) ();
</script>
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