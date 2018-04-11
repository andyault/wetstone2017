<?php

//add meta to download
function wetstone_add_downloads_meta() {
	add_meta_box(
		'wetstone_downloads_meta',
		'Customer Info',
		'wetstone_downloads_meta_content',
		'dlm_download',
		'side'
	);
}

add_action('add_meta_boxes', 'wetstone_add_downloads_meta');

function wetstone_downloads_meta_content($post) {
	$products = get_posts([
		'post_type'      => 'product',
		'posts_per_page' => -1
	]);

	$old = get_post_meta($post->ID, 'wetstone_download_mustown', true);

	//nonce
	wp_nonce_field('wetstone_meta_nonce', '_wp_ws_nonce');

	?>

	<label>
		Product required to download:

		<select name="wetstone_download_mustown" style="margin-top: 8px; width: 100%;">
			<option value="<?php if(empty($old)) echo 'selected'; ?>">Select an option</option>

			<?php
				foreach($products as $product) {
					echo sprintf(
						'<option value="%s" %s>%s</option>',

						$product->ID,
						$old == $product->ID ? 'selected' : '',
						$product->post_title
					);
				}
			?>
		</select>
	</label>

	<br />

	<?php
}


function wetstone_downloads_meta_save($id) {
	$isValidNonce = wp_verify_nonce($_POST['_wp_ws_nonce'], 'wetstone_meta_nonce');

	if(wp_is_post_autosave($id) || wp_is_post_revision($id) || !$isValidNonce || !current_user_can('edit_post', $id))
		return;

	$key = 'wetstone_download_mustown';
	$value = $_POST[$key];

	if(!empty($value))
		update_post_meta($id, $key, $value);
}

add_action('save_post', 'wetstone_downloads_meta_save');

//only let product owners download product downloads
//maybe todo - allow users to download the last version they had on their license? probably not
function wetstone_dlm_can_download($noidea, $download, $version) {
	$id = $download->post->ID;
	$mustown = get_post_meta($id, 'wetstone_download_mustown', true);

	//make sure there's a product requirement
	if(!empty($mustown)) {
		$products = get_user_meta(wp_get_current_user()->ID, 'wetstone_products', true);

		$info = $products[$mustown];
		$owned = $info ? strtotime($info['expiry']) > time() : false;

		return $owned;
	}

	return false;
}

//add_filter('dlm_can_download', 'wetstone_dlm_can_download', 10, 3);