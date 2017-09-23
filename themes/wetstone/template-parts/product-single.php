<?php $productColor = get_post_meta($post->ID, 'product_color', true); ?>

<!-- todo: side buttons (overview, screenshots, free trial, etc) -->

<!-- <section class="product-single-banner" style="background: <?php echo $productColor; ?>;">
</section> -->

<section class="product-single-description">
	<h1 class="section-header <?php if($post->ID != 141) echo 'wetstone-font'; ?>"><?php the_title(); ?></h1>

	<div class="body-content">
		<?php the_content(); ?>
	</div>
</section>