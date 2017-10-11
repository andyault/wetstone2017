<li class="myproduct-list-item">
	<div style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>);" class="myproduct-overview-image"></div>

	<h3 class="myproduct-list-item-title"><?php the_title(); ?></h3>

	<div class="myproduct-list-item-link">
		<a href="<?php echo add_query_arg('view', $post->ID); ?>" class="link link-button">View Customer Page</a>
	</div>
</li>