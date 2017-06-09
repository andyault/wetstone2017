<?php

//kills me to use snake case

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

function wetstone_get_asset($url) {
	return sprintf(
		'%s/assets%s',

		get_template_directory_uri(),
		$url
	);
}

//form functions
function wetstone_form_make_select($name, $label, $options, $required = false) {
	ob_start();
	?>

	<td>
		<label class="form-label">
			<?php if($required) echo '<i class="req">*</i>'; ?>
			<?php echo $label; ?>
			<br />
			<select name="<?php echo name ?>" class="form-input form-input-select" <?php if($required) echo 'required'; ?>>
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
	</td>

	<?php
	return ob_get_clean();
}

function wetstone_form_make_checkboxes($name, $label, $options, $required = false, $radio = false, $rowspan = null) {
	ob_start();
	?>

	<td <?php if($rowspan) echo 'rowspan="' . $rowspan . '"'; ?>>
		<div class="form-checkboxes">
			<div class="form-label">
				<?php if($required) echo '<i class="req">*</i>'; ?>
				<?php echo $label; ?>
			</div>

			<?php
				foreach($options as $option) {
					//1: value, 2: input type
					echo sprintf(
						'<label class="form-label">
							<input type="%2$s" name="interests" value="%1$s">
							%1$s
						</label><br />',

						$option,
						$radio ? 'radio' : 'checkbox'
					);
				}
			?>
		</div>
	</td>

	<?php
	return ob_get_clean();
}