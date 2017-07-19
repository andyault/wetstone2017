<div class="product-page">
	<h3 class="product-page-header wetstone-font"><?php the_title(); ?></h3>

	<div class="product-page-img" style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>)"></div>

	<div class="product-page-buttons">
		<a class="product-page-button product-page-button-view link link-button link-button-grey">Quick View</a>
		<a href="<?php the_permalink(); ?>" class="product-page-button link link-button">Go to page</a>
	</div>
</div>
