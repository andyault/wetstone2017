<li class="myproduct-list-item">
	<div style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>);" class="myproduct-overview-image"></div>

	<h3 class="myproduct-list-item-title">	<?php 
	$exTitle = explode(": ", get_the_title($post->ID));
	if ($post->ID == 633 || $post->ID == 634 || $post->ID == 1054) {
		echo $exTitle[0] . " 7.3";
	} else {
	
	echo $exTitle[0];
	
	}
	?></h3>

	<div class="myproduct-list-item-link">
		<a href="<?php echo add_query_arg('view', $post->ID); ?>" class="link link-button">View Product</a>
	</div>
</li>