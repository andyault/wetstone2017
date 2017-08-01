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
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/assets/css/lib/normalize.css'; ?>">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/assets/fonts/fonts.css'; ?>">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/media.css'; ?>">

		<!-- wordpress -->
		<?php wp_head(); ?>

		<style>/* body.admin-bar { margin-top: -32px; } #wpadminbar { opacity: 0.5; } */</style>
	</head>

	<body <?php body_class(); ?>>
		<header class="header header-site">
			<ul class="header-nav header-nav-site">
				<?php
					//get all root pages - maybe todo?
					$pages = get_pages([
						'parent'      => 0,
						'exclude'     => [19, 84, 112], //exclude sign in, portal, thank you
						'sort_column' => 'menu_order',
						'sort_order'  => 'ASC'
					]);

					//for each page, set up postdata
					global $post;

					foreach($pages as $post) {
						setup_postdata($post);

						echo '<li>';

						//include the actual link
						get_template_part('template-parts/header', 'link');

						//handle submenus
						if(count($subPages = wetstone_get_children($post))) {
							echo '<ul class="header-sub-nav-site">';

							//go through posts
							global $post;

							foreach($subPages as $post) {
								setup_postdata($post);

								echo '<li>';
								
								get_template_part('template-parts/header', 'link');

								echo '</li>';
							}

							echo '</ul>';
						}

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
		</header>

		<main class="site-main">
			<?php
				//if they made it this far, show member header
				if($isRestricted)
					get_header('portal');
			?>