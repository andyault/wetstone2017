<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php echo get_bloginfo('name'); ?></title>

		<!-- css -->
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/assets/css/lib/normalize.css'; ?>">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/assets/fonts/fonts.css'; ?>">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">

		<!-- wordpress -->
		<?php wp_head(); ?>

		<style>/* body.admin-bar { margin-top: -32px; } #wpadminbar { opacity: 0.5; } */</style>
	</head>

	<body <?php body_class(); ?>>
		<header class="site-header">
			<ul class="site-header-nav site-content">
				<?php
					//get all root pages - maybe todo?
					$pages = get_pages([
						'parent'      => 0,
						'sort_column' => 'menu_order',
						'sort_order'  => 'ASC'
					]);

					//for each page, set up postdata
					global $post;

					foreach($pages as $post) {
						setup_postdata($post);

						//get subpages first so we can style accordingly
						$args = ['posts_per_page' => -1];

						$postType = get_post_meta($post->ID, 'page_posttype', true);

						//either get post type or children
						if($postType)
							$args['post_type'] = $postType;
						else {
							$args['post_parent'] = $post->ID;
							$args['post_type'] = 'any';
						}

						$subPages = get_posts($args);

						//the actual link
						echo '<li>';

						//using include(locate_template()) so we still have access to $subPages
						include(locate_template('template-parts/header-link.php'));

						//handle submenus
						if(count($subPages)) { ?>
							<ul class="site-header-sub-nav">
								<?php
									//go through posts
									global $post;

									foreach($subPages as $post) {
										setup_postdata($post);

										echo '<li>';

										get_template_part('template-parts/header', 'link');

										echo '</li>';
									}
								?>
							</ul>
						<?php }

						echo '</li>';
					}
				?>
			</ul>
		</header>

		<main class="site-main">