<?php
/**
 * @package WetStone_Core
 * @version 1.0
 */
/*
Plugin Name: WetStone Post Type Archive Settings
Description: Enables adding page content to custom post type archive pages.
Author: Andrew Ault
Version: 1.0
*/

//register submenus for custom post types with archive pages
add_action('admin_menu', 'wetstone_add_archive_settings_pages');

function wetstone_add_archive_settings_pages() {
	//get all custom post types
	$postTypes = get_post_types([
		'public'      => true,
		'_builtin'    => false,
		'has_archive' => true
	], 'objects');

	//add pages
	foreach($postTypes as $postType => $info) {
		add_submenu_page(
			sprintf('edit.php?post_type=%s', $postType),
			sprintf('%s Page', $info->labels->name),
			sprintf('%s Page', $info->labels->name),
			'edit_posts',
			sprintf('%s-page', $postType),
			'wetstone_archive_content'
		);
	}
}

//adding content to archive settings pages
function wetstone_archive_content() {
	$screen = get_current_screen();
	$post_type = $screen->post_type;
	?>

	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>aaaa</h2>
		<form action="options.php" method="POST">
				<?php settings_fields( 'ptad_descriptions' ); ?>
				<?php do_settings_sections( $post_type . '-description' ); ?>
				<?php submit_button(); ?>
		</form>
	</div> <?php
	wp_editor('', 'idk_yet', ['class' => 'wp-editor-area wp-editor']);
}