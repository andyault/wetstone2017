<?php $productColor = get_post_meta($post->ID, 'product_color', true); ?>

<section class="product-single-banner" style="background: <?php echo $productColor; ?>;">
	<!-- ??? todo -->

	<?php //var_dump($post); ?>
</section>

<section class="product-single-description">
	<h1 class="section-header wetstone-font"><?php the_title(); ?></h1>

	<div class="body-content">
		<?php the_content(); ?>
	</div>
</section>