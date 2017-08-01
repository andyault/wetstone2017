<?php
	$subject = strtolower(sanitize_text_field($_GET['subject']));
	$context = strtolower(sanitize_text_field($_GET['context']));

	//make it pretty
	switch($context) {
		case 'product':
			$subjectVal = 'Inquiry about %s';
			$placeholder = 'Hi, I\'m interested in your product %s...';
			break;

		case 'service':
			$subjectVal = 'Inquiry about %s services';
			$placeholder = 'Hi, I\'m interested in your %s services...';
			break;

		default:
			$subjectVal = ($subject ? '%s' : 'WetStone Contact Form');
			$placeholder = 'Hi, I have a question about' . ($subject ? ' %s...' : '...');
			break;
	}

	$subjectVal = sprintf($subjectVal, $subject);
	$placeholder = sprintf($placeholder, ucwords($subject));

	//name, type, label, placeholder, required
	$structure = [
		[
			['fname', 'text', 'First Name', 'John', true],
			['lname', 'text', 'Last Name', 'Doe', true]
		],
		[
			['title', 'text', 'Title', 'Mr.'],
			['company', 'text', 'Company', 'Google Inc.', true]
		],
		[
			['phone', 'tel', 'Phone', '(555) 867-5309', true],
			['email', 'email', 'Email', 'john.doe@example.com', true]
		],
		[
			['address1', 'text', 'Address Line 1', '1600 Pennsylvania Ave NW', true],
			['address2', 'text', 'Address Line 2', 'Apt. 123']
		],
		[
			['city', 'text', 'City', 'Washington D.C.', true],
			'<td>
				<div class="form-multiple">
					<label class="form-label">
						<i class="req">*</i> State/Province: <br />
						<input type="text" name="state" placeholder="MD" size="7" class="form-input" value="' . $_GET['state'] . '">
					</label>

					<label class="form-label">
						<i class="req">*</i> Zip/Postal: <br />
						<input type="text" name="zip" placeholder="20500" size="10" class="form-input" value="' . $_GET['zip'] . '">
					</label>
				</div>
			</td>'
		],
		[
			['country', 'text', 'Country', 'U.S.A.', true],
			['referrer', 'text', 'How did you hear about us?', 'Google']
		]
	];
?>

<form name="contact" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
	<input type="hidden" name="action" value="wetstone-contact-form">
	<?php wp_nonce_field('wetstone-contact-form'); ?>

	<input type="hidden" name="subject" value="<?php echo $subjectVal; ?>">

	<table class="form-table">
		<tr>
			<td colspan="2" class="text-center">Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<?php if($_GET['errmsg']) { ?>
			<tr class="form-error">
				<td colspan="2" class="text-center"><?php echo $_GET['errmsg']; ?></td>
			</tr>

			<tr class="form-error-spacer"><td></td></tr>
		<?php }

			foreach($structure as $row) {
				echo '<tr>';

				foreach($row as $input) {
					if(gettype($input) == 'array') {
						//1: label class, 2: label asterisk, 3: label, 4: input type, 5: input name
						//6: placeholder, 7: input class, 8: input value, 9: input required
						echo sprintf(
							'<td>
								<label class="%s">
									%s%s: 
									<input type="%s" name="%s" placeholder="%s" class="%s" value="%s" %s>
								</label>
							</td>',

							'form-label',
							$input[4] ? '<i class="req">*</i> ' : '',
							$input[2],
							$input[1],
							$input[0],
							$input[3],
							'form-input',
							$_GET[$input[0]],
							$input[4] ? 'required' : ''
						);
					} else {
						echo $input;
					}
				}

				echo '</tr>';
			}
		?>

		<tr>
			<?php
				$interests = [
					'Malware investigation',
					'Steganography investigation',
					'Live acquisition and triage',
					'Forensic time',
					'Reselling opportunities',
					'Hosting a training',
					'Other (please specify below)'
				];

				echo wetstone_form_make_checkboxes(
					'interests[]',
					'Please mark your area(s) of interest:',
					$interests,
					true
				);
			?>

			<td>
				<label class="form-label">
					Questions/Comments:
					<textarea name="comments" class="form-textarea"
					          style="height: calc(<?php echo count($interests); ?> * 1.5em)"
					          placeholder="<?php echo $placeholder; ?>"><?php echo $_GET['comments']; ?></textarea>
				</label>
			</td>
		</tr>

		<tr>
			<td colspan="2" class="table-footer">
				<div class="inline-flex">
					<a href="#" onclick="return !!document.contact.reset();" class="form-reset link link-button link-button-grey">Reset</a>
					<a href="#" onclick="return !!document.contact.submit();" class="link link-button">Submit</a>
				</div>
			</td>
		</tr>
	</table>
</form>