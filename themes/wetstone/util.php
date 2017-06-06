<?php

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

function wetstone_form_make_checkboxes($name, $label, $options, $required = false) {
	ob_start();
	?>

	<td rowspan="<?php echo count($options); ?>">
		<div class="form-checkboxes">
			<div class="form-label">
				<?php if($required) echo '<i class="req">*</i>'; ?>
				<?php echo $label; ?>
			</div>

			<?php
				foreach($options as $option) {
					echo sprintf(
						'<label class="form-label">
							<input type="checkbox" name="interests" value="%1$s">
							%1$s
						</label><br />',

						$option
					);
				}
			?>
		</div>
	</td>

	<?php
	return ob_get_clean();
}