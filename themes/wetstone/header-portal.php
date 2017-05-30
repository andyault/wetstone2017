<?php $user = wp_get_current_user(); ?>

<header class="header header-page">
	<ul class="header-nav header-nav-page site-content">
		<li class="page-header-link-welcome">
			<a class="header-link header-link-page">
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
						<a href="%s" class="header-link link link-header-site link-header-page">%s</a>
					</li>',

					get_permalink($subpage->ID),
					get_the_title($subpage->ID)
				);
			}
		?>

		<li>
			<a href="<?php echo wp_logout_url(get_permalink(get_page_by_path('sign-in'))); ?>"
			   class="header-link link link-header-site link-header-page">
				Sign out
			</a>
		</li>		
	</ul>
</header>