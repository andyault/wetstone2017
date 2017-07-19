<div id="<?php echo $post->post_name; ?>" class="product-preview-slide" style="background: <?php echo get_post_meta($post->ID, 'product_color', true); ?>">
	<div class="product-background-filter"></div>

	<div class="product-preview-inner site-content">
		<div class="product-preview-image" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>)"></div>

		<div class="product-preview-info">
			<h2 class="product-preview-title wetstone-font"><?php the_title(); ?></h2>

			<div class="product-preview-excerpt body"><?php the_excerpt(); ?></div>

			<a href="<?php the_permalink(); ?>" class="product-preview-button link link-button">Go to product page</a>
		</div>
	</div>
</div>