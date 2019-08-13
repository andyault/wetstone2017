

<form name="mp-beta" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
	<input type="hidden" name="action" value="wetstone-mp-beta">
	<?php wp_nonce_field('wetstone-mp-beta'); ?>

	<input type="hidden" name="subject" value="Gargoyle Investigator MP BETA Signup Request">

	<table class="form-table form-table-collapse">
		<tr>
			<td colspan="2" class="text-center">
			Are you a current Gargoyle user, and would like to be a part of our <br />Beta testing program? Please fill out the request form below.<br /><br />			
			Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<?php if($_GET['errmsg']) { ?>
			<tr class="form-error">
				<td colspan="2" class="text-center"><?php echo esc_html($_GET['errmsg']); ?></td>
			</tr>

			<tr class="form-error-spacer"><td></td></tr>
		<?php } ?>

		<tr>
			<td><?php echo wetstone_form_make_input('fname', 'text', 'First Name', 'John', true); ?></td>
			<td><?php echo wetstone_form_make_input('lname', 'text', 'Last Name', 'Doe', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('company', 'text', 'Company', 'Google Inc.', true); ?></td>
			<td><?php echo wetstone_form_make_input('phone', 'tel', 'Phone', '(555) 867-5309', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input_emailone('email', 'email', 'Email', 'john.doe@example.com', true); ?></td>
			<td><?php echo wetstone_form_make_input_emailtwo('email2', 'email', 'Verify Email', 'john.doe@example.com', true); ?></td>
		</tr>
		<tr>
			<td colspan="2" class="table-footer">
				<div class="inline-flex">				
					<div class="mtcaptcha"></div>
				</div>
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
