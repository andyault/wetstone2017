<section class="site-content site-content-small site-content-padded">
	<h2 class="section-header wetstone-font">
	<?php 
	$exTitle = explode(": ", get_the_title($post->ID));
	if ($post->ID == 633 || $post->ID == 634 || $post->ID == 1054) {
		echo $exTitle[0] . " 7.4";
	} else {
	
	echo $exTitle[0];
	
	}
	?>
	</h2>

	<div class="myproduct-overview">
		<div style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>);" class="myproduct-overview-image"></div>

		<div class="myproduct-overview-info"><?php
			if ($post->ID == 1815) {
				the_excerpt(); 
			} else {				
				echo get_post_meta($post->ID, 'wetstone_product_customerexcerpt', true);
			}
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