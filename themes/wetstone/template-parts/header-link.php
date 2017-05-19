<?php
	$id = $post->ID;
	$isActive = is_page($id);
	$activeClass = $isActive ? 'active' : '';

	if($id == get_option('page_on_front')) { ?>
		<a href="<?php the_permalink(); ?>" class="site-header-link <?php echo $activeClass; ?>">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.svg" class="site-header-logo">
		</a>
	<?php } else { ?>
		<a href="<?php the_permalink(); ?>" class="site-header-link link link-site-header <?php echo $activeClass; ?>">
			<?php the_title(); ?>
		</a>

		<?php 

		if(count($subPages)) { ?>
			<a href class="site-header-link site-header-sub"></a>
		<?php }
	}
?>