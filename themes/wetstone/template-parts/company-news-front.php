<div class="news-front">
	<h3 class="news-header">
		<a href="<?php the_permalink(); ?>" class="link link-body">
			<?php the_date(); ?> - <?php the_title(); ?>
		</a>
	</h3>

	<p class="news-excerpt"><?php the_excerpt(); ?></p>
</div>