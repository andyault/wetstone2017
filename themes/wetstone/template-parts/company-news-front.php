<div class="news-front">
	<h3 class="news-header">
		<?php
			$slug = urlencode($wp_query->query['pagename']);
			$href = add_query_arg('back', $slug, get_the_permalink());
		?>
		
		<a href="<?php echo $href; ?>" class="link link-body">
			<?php the_date(); ?> - <?php the_title(); ?>
		</a>
	</h3>

	<p class="news-excerpt"><?php the_excerpt(); ?></p>
</div>