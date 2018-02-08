<?php

//generic util
if(!function_exists('wetstone_pop_value')) {
	function wetstone_pop_value(&$arr, $key) {
		$ret = $arr[$key];
		unset($arr[$key]);
		return $ret;
	}
}

//page functions
function wetstone_get_children($post, $args = null) {
	$args = wp_parse_args($args, [
		'posts_per_page' => -1
	]);

	//complicated way of prioritizing page_* filters over default filters (post type, children)
	$postType = get_post_meta($post->ID, 'page_posttype', true);

	if($postType) {
		$args['post_type'] = $postType;

		$postMetaKey = get_post_meta($post->ID, 'page_metakey', true);
		$postMetaVal = get_post_meta($post->ID, 'page_metaval', true);

		if($postMetaKey && $postMetaVal)
			$args[$postMetaKey] = $postMetaVal;
	} else {
		$args['post_type'] = 'any';
		$args['post_parent'] = $post->ID;
	}
	
	return get_posts($args);
}

function wetstone_group_by_cat($posts) {
	$grouped = [];

	foreach($posts as $post) {
		$cat = get_the_category($post->ID);

		if(empty($cat))
			$cat = 'Uncategorized';
		else
			$cat = esc_html($cat[0]->name);

		$grouped[$cat][] = $post;
	}

	return $grouped;
}

function wetstone_get_asset($url) {
	return sprintf(
		'%s/assets%s',

		get_template_directory_uri(),
		$url
	);
}

//form functions
function wetstone_form_make_input($name, $type, $label, $placeholder, $required = false, $attrs = []) {
	if($required) {
		$label = '<i class="req">*</i> ' . $label;
		$required = 'required';
	} else
		$required = '';

	//mostly only used for input size
	$attrStr = '';

	foreach($attrs as $key => $val)
		$attrStr .= sprintf('%s="%s" ', $key, $val);

	//1: label, 2: type, 3: name, 4: placeholder, 5: attributes, 6: required
	return sprintf(
		'<label class="form-label">
			%s:
			<input type="%s" name="%s" placeholder="%s" class="form-input" %s %s>
		</label>',

		$label,
		$type,
		$name,
		$placeholder,
		$attrStr,
		$required
	);
}

function wetstone_form_make_textarea($name, $label, $placeholder, $required = false, $attrs = []) {
	if($required) {
		$label = '<i class="req">*</i> ' . $label;
		$required = 'required';
	} else
		$required = '';

	$attrStr = '';

	foreach($attrs as $key => $val)
		$attrStr .= sprintf('%s="%s" ', $key, $val);

	//1: label, 2: name, 3: placeholder, 4: attributes, 5: required
	return sprintf(
		'<label class="form-label">
			%s:
			<textarea name="%s" class="form-textarea" placeholder="%s" %s></textarea>
		</label>',

		$label,
		$name,
		$placeholder,
		$attrStr,
		$required
	);
}

function wetstone_form_make_select($name, $label, $options, $required = false) {
	ob_start();
	?>

	<label class="form-label">
		<?php if($required) echo '<i class="req">*</i>'; ?>
		<?php echo $label; ?>
		<br />
		<select name="<?php echo $name ?>" class="form-input form-input-select" <?php if($required) echo 'required'; ?>>
			<option value selected>Select an option</option>

			<?php
				foreach($options as $option) {
					echo sprintf(
						'<option value="%1$s">%1$s</option>',
						$option
					);
				}
			?>
		</select>
		<i class="select-symbol">&dtrif;</i>
	</label>

	<?php
	return ob_get_clean();
}

function wetstone_form_make_select_state($name, $label, $options, $required = false) {
	ob_start();
	?>

	<label class="form-label">
		<?php if($required) echo '<i class="req">*</i>'; ?>
		<?php echo $label; ?>
		<br />
		<select name="<?php echo $name ?>" class="form-input form-input-select" <?php if($required) echo 'required'; ?> style="width:140px">
			<option value selected>Select state</option>

			<?php
				foreach($options as $option) {
					echo sprintf(
						'<option value="%1$s">%1$s</option>',
						$option
					);
				}
			?>
		</select>
		<i class="select-symbol" style="right:200px">&dtrif;</i>
	</label>

	<?php
	return ob_get_clean();
}

function wetstone_form_make_checkboxes($name, $label, $options, $required = false, $radio = false) {
	$values = $_GET[preg_replace('/\[\]$/', '', $name)];
	$values = explode(', ', $values);
	$values = array_flip($values);

	ob_start();
	?>

	<div class="form-checkboxes">
		<div class="form-label">
			<?php if($required) echo '<i class="req">*</i>'; ?>
			<?php echo $label; ?>
		</div>

		<?php
			foreach($options as $option) {
				//1: name, 2: value, 3: input type
				echo sprintf(
					'<label class="form-label">
						<input type="%3$s" name="%1$s" value="%2$s">
						%2$s
					</label><br />',

					$name,
					$option,
					$radio ? 'radio' : 'checkbox'
				);
			}
		?>
	</div>

	<?php
	return ob_get_clean();
}