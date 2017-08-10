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
				<?php
					//normal links
					function make_header_link() {
						$id = $post->ID;
						$isActive = is_page($id) || $page->post_parent == $id;
						$activeClass = $isActive ? 'active' : '';

						return sprintf(
							'<a href="%s" class="header-link link link-header-site %s">%s</a>', 

							get_the_permalink(),
							$activeClass,
							get_the_title()
						);
					}

					//recursion is fun
					function make_header_links_list($pages, $depth = 0) {
						global $post;

						//if it's a submenu, make it sub nav and add a click thru link
						if($depth > 0) {
							echo '<ul class="header-sub-nav-site">';
							echo '<li class="header-link-clickthru header-link-separated">';
							echo make_header_link();
							echo '<li>';
						} else
							echo '<ul class="header-nav header-nav-site">';

						//making links
						foreach($pages as $post) {
							echo '<li>';
							echo make_header_link();

							if(count($children = wetstone_get_children($post)))
								make_header_links_list($children, $depth + 1);

							echo '</li>';
						}

						echo '</ul>';
					}

					//logo
					function make_header_logo() {
						return sprintf(
							'<a href="%s" class="header-link header-logo-link %s"><img src="%s" class="header-logo-img"></a>',

							get_the_permalink(),
							$activeClass,
							wetstone_get_asset('/img/logo.svg')
						);
					}
				?>

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

					if(is_user_logged_in())
						array_push($pages, get_page_by_path('portal'));
					else
						array_push($pages, get_page_by_path('sign-in'));

					//save home page
					$homeID = get_option('page_on_front');

					//split by home link
					$right = $pages;
					$left = [];
					$count = count($right);

					for($i = 0; $i < $count; $i++) {
						$page = array_shift($right);

						//before we hit the home page
						if($page->ID != $homeID)
							$left[] = $page;
						else {
							//we've hit home - make left side
							make_header_links_list($left);

							//then the middle
							$post = $page;
							setup_postdata($post);
							echo make_header_logo();

							//then the right side
							make_header_links_list($right);

							break;
						}
					}
				?>
			</div>

			<div class="header-site-mobile">
				<?php
					$post = get_post($homeID);
					setup_postdata($post);
					echo make_header_logo();
				?>

				<div class="header-site-dropdown">
					<a href class="header-link link link-header-site header-site-dropdown-selected">
						<?php echo $wp_query->post->post_title; ?>
						<span class="caret"></span>
					</a>

					<ul class="header-sub-nav-site">
						<?php
							foreach($pages as $post) {
								echo '<li class="header-link-separated">';
								echo make_header_link();
								echo '</li>';

								foreach(wetstone_get_children($post) as $post) {
									echo '<li>';
									echo make_header_link();
									echo '</li>';
								}
							}
						?>
					</ul>
				</div>
			</div>
		</header>

		<main class="site-main">
			<?php
				//if they made it this far, show member header
				if($isRestricted)
					get_header('portal');
			?>