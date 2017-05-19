<div class="product-preview" style="background: <?php echo get_post_meta($post->ID, 'product_color', true); ?>;">
	<div class="product-preview-bg"></div>
	<?php the_post_thumbnail([256, 256], ['class' => 'product-preview-img']); ?>

	<div class="product-preview-content">
		<h3 class="product-preview-header wetstone-font"><?php the_title(); ?></h3>

		<div class="product-preview-body">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="product-preview-button link link-button box-center">
			Go to product page
		</a>
	</div>
</div>