<?php	
	include_once(dirname(__FILE__) . '/util.php');

	//all children of /portal are auth-only, restrict access
	$isRestricted = false;

	$portalID = get_page_by_path('portal')->ID;

	if($post->post_parent == $portalID || $post->ID == $portalID) {
		$isRestricted = true;

		if(!is_user_logged_in()) {
			//todo - add message
			wp_safe_redirect(get_permalink(get_page_by_path('sign-in')));

			exit();
		}
	}
?>

<!DOCTYPE html>

<!-- todo: https://www.w3.org/TR/html-aria/ -->

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php wp_title('-', true, 'right'); echo get_bloginfo('name'); ?></title>

		<!-- css -->
		<link rel="stylesheet" href="<?php echo wetstone_get_asset('/css/lib/normalize.css'); ?>">
		<link rel="stylesheet" href="<?php echo wetstone_get_asset('/fonts/fonts.css'); ?>">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/media.css'; ?>">

		<!-- wordpress -->
		<?php wp_head(); ?>

		<style>/* body.admin-bar { margin-top: -32px; } #wpadminbar { opacity: 0.5; } */</style>
	</head>

	<body <?php body_class(); ?>>
		<header class="header">
			<?php global $post; ?>

			<div class="header-site-full">
				<ul class="header-nav header-nav-site">
					<?php
						//get all root pages - maybe todo?
						$pages = get_pages([
							'parent'      => 0,
							'exclude'     => [
								get_page_by_path('sign-in')->ID,
								get_page_by_path('portal')->ID,
								get_page_by_path('thank-you')->ID
							], 
							'sort_column' => 'menu_order',
							'sort_order'  => 'ASC'
						]);

						//for each page, set up postdata
						foreach($pages as $post) {
							setup_postdata($post);

							$isLogo = $post->ID == get_option('page_on_front');

							if($isLogo)
								echo '</ul>';
							else
								echo '<li>';

							//include the actual link
							get_template_part('template-parts/header', 'link');

							//handle submenus
							if(count($subPages = wetstone_get_children($post))) {
								echo '<ul class="header-sub-nav-site">';

								//make a click through link
								echo '<li class="header-link-clickthru">';

								get_template_part('template-parts/header', 'link');

								echo '</li>';

								//go through sub pages
								foreach($subPages as $post) {
									setup_postdata($post);

									echo '<li>';
									
									get_template_part('template-parts/header', 'link');

									echo '</li>';
								}

								echo '</ul>';
							}

							if($isLogo)
								echo '<ul class="header-nav header-nav-site">';
							else
								echo '</li>';
						}
					?>

					<li>
						<?php
							global $post;

							if(is_user_logged_in())
								$post = get_page_by_path('portal');
							else
								$post = get_page_by_path('sign-in');

							setup_postdata($post);
							get_template_part('template-parts/header', 'link');
						?>
					</li>
				</ul>
			</div>

			<div class="header-site-mobile">
				<?php
					$post = get_post(get_option('page_on_front'));

					get_template_part('template-parts/header', 'link');
				?>

				<div class="header-site-dropdown">
					<a href class="header-link link link-site-header header-site-dropdown-selected">
						<?php echo $wp_query->post->post_title; ?>
					</a>
				</div>
			</div>
		</header>

		<main class="site-main">
			<?php
				//if they made it this far, show member header
				if($isRestricted)
					get_header('portal');
			?>