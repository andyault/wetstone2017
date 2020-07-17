<?php $productColor = get_post_meta($post->ID, 'product_color', true); 
$exTitle = explode(": ", get_the_title());
?>

<!-- todo: side buttons (overview, screenshots, free trial, etc) -->

<section class="product-single-description">
	<div style="min-height: 150px; height: auto"><h1 class="section-header <?php if($post->ID != 141) echo 'wetstone-font'; ?>"><?php echo $exTitle[0] . "<br />" . $exTitle[1]; ?></h1></div>

	<div class="body-content">
		<?php the_content(); ?>
	</div>
</section>