<a href="<?php the_permalink(); ?>" class="product-page link link-body">
<?php $exTitle = explode(": ", get_the_title($post->ID)); ?>
	<h3 class="product-page-header <?php if($post->ID != 141) echo 'wetstone-font'; ?>"><?php echo $exTitle[0]; ?></h3>

	<div class="product-page-img" style="background-image: url(<?php the_post_thumbnail_url('small'); ?>)"></div>

	<!-- <div class="product-page-buttons">
		<a class="product-page-button product-page-button-view link link-button link-button-grey">Preview</a>
		<a href="<?php the_permalink(); ?>" class="product-page-button link link-button">View</a>
	</div> -->
</a>
