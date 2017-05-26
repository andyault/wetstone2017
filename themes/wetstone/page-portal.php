<?php 
	get_header();
	//the_post();

	$user = wp_get_current_user();
?>

<header class="site-header site-header-page">
	<ul class="site-header-nav site-header-nav-page site-content">
		<li class="page-header-link-welcome">
			<a class="site-header-link page-header-link">
				<?php
					echo sprintf(
						'Welcome back, %s %s',

						$user->user_firstname,
						$user->user_lastname
					);
				?>
			</a>
		</li>

		<?php
			$pages = get_pages([
				'parent'      => get_page_by_path('portal')->ID,
				'sort_column' => 'menu_order',
				'sort_order'  => 'ASC'
			]);

			foreach($pages as $subpage) {
				echo sprintf(
					'<li>
						<a href="%s" class="site-header-link link link-site-header link-header-page">%s</a>
					</li>',

					get_permalink($subpage->ID),
					get_the_title($subpage->ID)
				);
			}
		?>

		<li>
			<a href="<?php echo wp_logout_url(); ?>"
			   class="site-header-link link link-site-header link-header-page">
				Sign out
			</a>
		</li>		
	</ul>
</header>

<section class="page-overview site-content">
	<div class="body-content">
		<?php //the_content(); ?>
	</div>
</section>

<?php get_footer(); ?>