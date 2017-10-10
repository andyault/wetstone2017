<?php

//add theme support
add_action('after_setup_theme', 'wetstone_add_theme_support');

function wetstone_add_theme_support() {
	//add thumbnails
	add_theme_support('post-thumbnails');
}

//register taxonomies
add_action('init', 'wetstone_register_post_types');

function wetstone_register_post_types() {
	//testimonials
	register_post_type('testimonial', [
		'public'      => true,
		'labels'      => [
			'name'          => 'Testimonials', 
			'singular_name' => 'Testimonial', 
			'add_new_item'  => 'Add New Testimonial',
			'edit_item'     => 'Edit Testimonial',
			'new_item'      => 'New Testimonial',
			'view_item'     => 'View Testimonial'
		],
		'supports'    => ['title', 'editor'],
		'rewrite'     => ['slug' => 'testimonials'],
		'exclude_from_search' => true,
		'has_archive' => false
	]);

	//products
	register_post_type('product', [
		'public'     => true,
		'labels'     => [
			'name'          => 'Products', 
			'singular_name' => 'Product', 
			'add_new_item'  => 'Add New Product',
			'edit_item'     => 'Edit Product',
			'new_item'      => 'New Product',
			'view_item'     => 'View Product'
		],
		'supports'    => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
		'rewrite'     => ['slug' => 'products'],
		'taxonomies'  => ['category'],
		'has_archive' => false
	]);

	//services
	register_post_type('service', [
		'public'     => true,
		'labels'     => [
			'name'          => 'Services', 
			'singular_name' => 'Service', 
			'add_new_item'  => 'Add New Service',
			'edit_item'     => 'Edit Service',
			'new_item'      => 'New Service',
			'view_item'     => 'View Service'
		],
		'supports'   => ['title', 'editor', 'excerpt'],
		'has_archive' => true
	]);
}

//add meta boxes
add_action('add_meta_boxes', 'wetstone_add_meta_boxes');

function wetstone_add_meta_boxes() {
	foreach(['testimonial', 'product', 'page'] as $meta) {
		add_meta_box(
			sprintf('%s_meta', $meta),
			sprintf('%s Info', ucfirst($meta)),
			sprintf('wetstone_meta_%s_content', $meta),
			$meta,
			'side'
		);
	}

	//add another body to products
	add_meta_box(
		'product_customerinfo',
		'Customer Info',
		'wetstone_meta_product_customerinfo',
		'product',
		'normal'
	);
}

//meta box content
//  testimonials
function wetstone_meta_testimonial_content($post) {
	wetstone_meta_content(
		'testimonial',
		[
			'author'    => ['type' => 'text'],
			'title'     => ['type' => 'text'],
			'ispreview' => ['label' => 'Show on front page?', 'type' => 'checkbox']
		],
		get_post_meta($post->ID)
	);
}

//  products
function wetstone_meta_product_content($post) {
	wetstone_meta_content(
		'product',
		[
			//'color'     => ['label' => 'Product color', 'type' => 'color'],
			'ispreview'   => ['label' => 'Show on front page?', 'type' => 'checkbox'],
			'purchasable' => ['label' => 'Can be purchased?', 'type' => 'checkbox']
		],
		get_post_meta($post->ID)
	);
}

function wetstone_meta_product_customerinfo($post) {
	?>

	<label class="wp-heading-inline">
		<p class="post-attributes-label">Customer Page Excerpt</p>

		<textarea id="wetstone_product_customerinfo_excerpt" rows="2" cols="40" name="wetstone_product_customerinfo_excerpt"><?php
			echo get_post_meta($post->ID, 'wetstone_product_customerexcerpt', true);
		?></textarea>
	</label>

	<label class="wp-heading-inline">
		<p class="post-attributes-label">Customer Page Content</p>

		<?php
			wp_editor(
				get_post_meta($post->ID, 'wetstone_product_customerinfo', true),
				'wetstone_product_customerinfo_editor'
			);
		?>
	</label>

	<?php
}

//  pages
function wetstone_meta_page_content($post) {
	wetstone_meta_content(
		'page',
		[
			'showinheader' => ['label' => 'Show in header?', 'type' => 'checkbox'],
			'posttype'     => ['label' => 'Post type to list'],
			'metakey'      => ['label' => 'Post filter meta key'],
			'metaval'      => ['label' => 'Post filter meta value'],
			'listlabel'    => ['label' => 'Post list label'] 
		],
		get_post_meta($post->ID)
	);
}

//convenience
function wetstone_meta_content($name, $fields, $old) {
	wp_nonce_field('wetstone_meta_nonce', '_wp_ws_nonce');

	foreach($fields as $field => $info) {
		//prep info
		$metaKey   = sprintf('%s_%s', $name, $field);
		$fieldName = sprintf('_meta[%s]', $metaKey);
		$oldValue  = $old[$metaKey] ? $old[$metaKey][0] : '';

		//different strings for different things
		$str = '';

		switch($info['type']) {
			case 'checkbox':
				$str = '<label class="%s">
					<input type="hidden" name="%3$s" value="false">
					<input type="checkbox" name="%3$s" value="true" %4$s />
					%2$s
				</label> <br /><br />';

				$oldValue = $oldValue ? 'checked' : '';
				break;

			case 'color':
				$str = '<label class="%s">
					<strong>%s:</strong> <br />
					<input type="color" name="%s" value="%s" />
				</label> <br /><br />';
				break;

			case 'text':
			default:
				$str = '<label class="%s">
					<strong>%s:</strong> <br />
					<input type="text" name="%s" value="%s" size="32" />
				</label> <br /><br />';
				break;
		}

		echo sprintf(
			$str,
			'',
			ucfirst($info['label'] ? $info['label'] : $field),
			$fieldName,
			$oldValue
		);
	}
}

//saving meta content?
function wetstone_meta_save($id) {
	//don't save if autosave, revision, or nonce doesn't match
	$isValidNonce = wp_verify_nonce($_POST['_wp_ws_nonce'], 'wetstone_meta_nonce');

	if(wp_is_post_autosave($id) || wp_is_post_revision($id) || !$isValidNonce || !current_user_can('edit_post', $id))
		return;

	//save all data in _meta[]
	foreach($_POST['_meta'] as $key => $value) {
		if(!empty($value) && $value !== 'false') //this is really bad but hopefully it won't bite me
			update_post_meta($id, $key, sanitize_text_field($value));
		else
			delete_post_meta($id, $key);
	}
}

add_action('save_post', 'wetstone_meta_save');

//save product info
function wetstone_product_customerinfo_save($id) {
	//don't save if autosave, revision, or nonce doesn't match
	$isValidNonce = wp_verify_nonce($_POST['_wp_ws_nonce'], 'wetstone_meta_nonce');

	if(wp_is_post_autosave($id) || wp_is_post_revision($id) || !$isValidNonce || !current_user_can('edit_post', $id))
		return;

	foreach(['customerinfo', 'customerexcerpt'] as $name) {
		$key = 'wetstone_product_' . $name;
		$value = $_POST[$key];

		if(!empty($value))
			update_post_meta($id, $key, $value);
	}
}

add_action('save_post', 'wetstone_product_customerinfo_save');

//adding styles to admin page
function wetstone_admin_styles() {
	echo '<style>
		#wetstone_product_customerinfo_excerpt {
			display: block;
			margin: 12px 0 0;
			height: 6em;
			width: 100%;
		}
	</style>';
}

add_action('admin_head', 'wetstone_admin_styles');