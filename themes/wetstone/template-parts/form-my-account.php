<?php $data = wp_get_current_user(); ?>

<form name="my-account" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
	<input type="hidden" name="action" value="wetstone-my-account">
	<?php wp_nonce_field('wetstone-my-account'); ?>

	<input type="hidden" name="ID" value="<?php echo $data->ID; ?>">

	<table class="form-table form-table-collapse">
		<tr>
			<td colspan="2" class="text-center">Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<?php if($_GET['updated'] == true) { ?>
			<tr class="form-success">
				<td colspan="2" class="text-center">Your account has been updated.</td>
			</tr>

			<tr class="form-error-spacer"><td></td></tr>
		<?php } ?>

		<tr>
			<td><?php echo wetstone_form_make_input('first_name', 'text', 'First Name', 'John', true, ['value' => $data->first_name]); ?></td>
			<td><?php echo wetstone_form_make_input('last_name', 'text', 'Last Name', 'Doe', true, ['value' => $data->last_name]); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('wetstone_company', 'text', 'Company', 'Google Inc.', true, ['value' => get_user_meta($data->ID, 'wetstone_company', true)]); ?></td>
			<td><?php echo wetstone_form_make_input('wetstone_phone', 'tel', 'Phone', '(555) 867-5309', true, ['value' => get_user_meta($data->ID, 'wetstone_phone', true)]); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('user_email', 'email', 'Email', 'john.doe@example.com', true, ['value' => $data->user_email]); ?></td>
			<td>
				<label class="form-label">
					Password Change: <br />
					<a href="<?php echo add_query_arg('action', 'lostpassword', get_permalink()); ?>" class="link link-body form-input text-center" style="border-color: transparent;">
						Click here to change your password.
					</a>
				</label>
			</td>
		</tr>

		<tr>
			<td colspan="2" class="table-footer">
				<div class="inline-flex">
					<button type="reset" class="form-reset link link-button link-button-input link-button-grey">Reset</button>
					<button type="submit" class="link link-button link-button-input">Submit</button>
				</div>
			</td>
		</tr>
	</table>
</form>

<script>var oldVals = <?php echo json_encode($_GET); ?></script>
<script src="<?php echo wetstone_get_asset('/js/form-repopulate.js'); ?>"></script>
