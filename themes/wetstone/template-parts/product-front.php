<?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), [256, 256]); 
$exTitle = explode(": ", get_the_title($post->ID));

?>

<div class="product-front">
	<div class="product-background-filter"></div>

	<div class="product-front-content">
		<h3 class="product-front-header wetstone-font"><?php echo $exTitle[0]; ?></h3>

		<div class="product-front-body" style="background-image: url(<?php echo $thumb[0] ?>);"></div>

		<a href="<?php the_permalink(); ?>" class="product-front-button link link-button">
			Go to product page
		</a>
	</div>
</div>