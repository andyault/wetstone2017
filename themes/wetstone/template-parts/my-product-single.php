<section class="site-content site-content-small site-content-padded">
	<h2 class="section-header wetstone-font"><?php the_title(); ?></h2>

	<div class="myproduct-overview">
		<div style="bacgkround-image: url(<?php the_post_thumbnail_url('medium'); ?>);" class="myproduct-overview-image"></div>

		<div class="myproduct-overview-info"><?php
			echo get_post_meta($post->ID, 'wetstone_product_customerexcerpt', true);
		?></div>
	</div>

	<div>
		<?php
			$content = get_post_meta($post->ID, 'wetstone_product_customerinfo', true);

			//not a real shortcode so we can't use has_shortcode
			echo do_shortcode($content);
		?>
	</div>
</section>