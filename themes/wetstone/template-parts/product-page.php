<div class="product-page">
	<div class="product-page-img" style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>)"></div>

	<div class="product-page-inner">
		<h3 class="product-page-header wetstone-font"><?php the_title(); ?></h3>

		<div class="product-page-info">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="product-page-button link link-button">Go to product page</a>
	</div>
</div>
