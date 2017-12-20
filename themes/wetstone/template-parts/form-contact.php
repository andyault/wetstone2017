<?php
	//get subject and placeholder ready
	$subject = strtolower(sanitize_text_field($_GET['subject']));
	$context = strtolower(sanitize_text_field($_GET['context']));

	//make it pretty
	switch($context) {
		case 'product':
			$subjectVal = 'Inquiry about %s';
			$placeholder = 'Hi, I\'m interested in your product %s...';
			break;

		case 'service':
			$subjectVal = 'Inquiry about %s';
			$placeholder = 'Hi, I\'m interested in %s...';
			break;

		default:
			$subjectVal = ($subject ? '%s' : 'WetStone Contact Form');
			$placeholder = 'Hi, I have a question about' . ($subject ? ' %s...' : '...');
			break;
	}

	$subjectVal = sprintf($subjectVal, $subject);
	$placeholder = sprintf($placeholder, ucwords($subject));
?>

<form name="contact" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
	<input type="hidden" name="action" value="wetstone-contact-form">
	<?php wp_nonce_field('wetstone-contact-form'); ?>

	<input type="hidden" name="subject" value="<?php echo $subjectVal; ?>">

	<!-- form table -->
	<table class="form-table form-table-collapse">
		<tr>
			<td colspan="2" class="text-center">Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<?php if($_GET['errmsg']) { ?>
			<tr class="form-error">
				<td colspan="2" class="text-center"><?php echo esc_html($_GET['errmsg']); ?></td>
			</tr>

			<tr class="form-error-spacer"><td></td></tr>
		<?php } ?>

		<!-- begin form fields -->
		<tr>
			<td><?php echo wetstone_form_make_input('fname', 'text', 'First Name', 'John', true); ?></td>
			<td><?php echo wetstone_form_make_input('lname', 'text', 'Last Name', 'Doe', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('title', 'text', 'Title', 'CEO'); ?></td>
			<td><?php echo wetstone_form_make_input('company', 'text', 'Company', 'Google Inc.', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('phone', 'tel', 'Phone', '(555) 867-5309', true); ?></td>
			<td><?php echo wetstone_form_make_input('email', 'email', 'Email', 'john.doe@example.com', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('address1', 'text', 'Address Line 1', '1600 Pennsylvania Ave NW', true); ?></td>
			<td><?php echo wetstone_form_make_input('address2', 'text', 'Address Line 2', 'Apt. 123'); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('city', 'text', 'City', 'Washington D.C.', true); ?></td>
			<td>
				<div class="form-multiple">
					<?php
						echo wetstone_form_make_input('state', 'text', 'State/Province', 'MD', true, ['size' => 7]);
						echo wetstone_form_make_input('zip', 'text', 'Zip/Postal', '20500', true, ['size' => 10]);
					?>
				</div>
			</td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('country', 'text', 'Country', 'U.S.A.', true); ?></td>
			<td><?php echo wetstone_form_make_input('referrer', 'text', 'How did you hear about us?', 'Google'); ?></td>
		</tr>

		<tr>
			<?php
				$interests = [
					'Malware investigation',
					'Steganography investigation',
					'Live acquisition and triage',
					'Forensic time',
					'Reselling opportunities',
					'Other (please specify below)'
				];
			?>

			<td>
				<?php
					echo wetstone_form_make_checkboxes(
						'interests[]',
						'Please mark your area(s) of interest:',
						$interests,
						true
					);
				?>
			</td>

			<td>
				<?php
					echo wetstone_form_make_textarea(
						'comments', 
						'Questions/Comments', 
						$placeholder, 
						false,
						['style' => sprintf('height: calc(%d * 1.5em)', count($interests))]
					); 
				?>
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