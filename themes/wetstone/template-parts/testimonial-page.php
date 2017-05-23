<div class="testimonial-page body-content">
	<div class="testimonial-body">
		<?php the_content(); ?>
	</div>

	<div class="testimonial-info">
		<div class="testimonial-author"><?php echo get_post_meta($post->ID, 'testimonial_author', true); ?></div>
		<div class="testimonial-title"><?php echo get_post_meta($post->ID, 'testimonial_title', true); ?></div>
	</div>
</div>