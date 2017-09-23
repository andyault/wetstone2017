<?php 
	$name = $post->post_name;
	$color = get_post_meta($post->ID, 'product_color', true);
	$img = get_the_post_thumbnail_url($post, 'full');
?>

<div id="<?php echo $name; ?>" class="product-preview-slide" style="/* background: <?php echo $color; ?> */">
	<div class="product-background-filter product-preview-background"></div>
	<div class="product-preview-image product-preview-image-mobile" style="background-image: url(<?php echo $img; ?>)"></div>

	<div class="product-preview-inner site-content">
		<div class="product-preview-image" style="background-image: url(<?php echo $img; ?>)"></div>

		<div class="product-preview-info">
			<h2 class="product-preview-title <?php if($post->ID != 141) echo 'wetstone-font'; ?>"><?php the_title(); ?></h2>

			<div class="product-preview-excerpt body"><?php the_excerpt(); ?></div>

			<a href="<?php the_permalink(); ?>" class="product-preview-button link link-button">View product</a>
		</div>
	</div>
</div>