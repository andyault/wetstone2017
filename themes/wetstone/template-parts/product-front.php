<?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), [256, 256]); ?>

<div class="product-front" style="
	/* background-color: <?php echo get_post_meta($post->ID, 'product_color', true); ?>; */
	background-image: url(<?php echo $thumb[0] ?>);
	background-size: <?php echo sprintf('%spx %spx', $thumb[1], $thumb[2]); ?>
">
	<div class="product-background-filter"></div>
	<?php //the_post_thumbnail([256, 256], ['class' => 'product-front-img']); ?>

	<div class="product-front-content">
		<h3 class="product-front-header wetstone-font"><?php the_title(); ?></h3>

		<div class="product-front-body">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="product-front-button link link-button">
			Go to product page
		</a>
	</div>
</div>