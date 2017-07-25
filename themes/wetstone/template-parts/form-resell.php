<?php
	//name, type, label, placeholder, required
	$structure = [
		[
			['fname', 'text', 'First Name', 'John', true],
			['lname', 'text', 'Last Name', 'Doe', true]
		],
		[
			['company', 'text', 'Company', 'Google Inc.', true],
			['website', 'text', 'Website', 'http://google.com', true]
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
						<input type="text" name="state" placeholder="MD" size="7" class="form-input">
					</label>

					<label class="form-label">
						<i class="req">*</i> Zip/Postal: <br />
						<input type="text" name="zip" placeholder="20500" size="10" class="form-input">
					</label>
				</div>
			</td>'
		],
		[
			['country', 'text', 'Country', 'U.S.A.', true],
			['referrer', 'text', 'How did you hear about us?', 'Google', true]
		],
		[
			wetstone_form_make_select(
				'customers',
				'Who is your typical customer?',
				[
					'Government',
					'Educational Institutions',
					'Financial Institutions',
					'Healthcare Facilities',
					'Individuals',
					'Commercial Markets'
				],
				true
			),
			wetstone_form_make_checkboxes(
				'interests',
				'What is your main marketing campaign?',
				[
					'Trade Shows',
					'Print Advertising',
					'TV Advertising',
					'Direct Mail',
					'Newsletter',
					'Seminars/Trainings',
					'Telemarketing',
					'Web'
				],
				true,
				true,
				3
			)
		],
		[
			wetstone_form_make_select(
				'description',
				'Which best describes your company?',
				[
					'Systems Integration',
					'Consulting Firm',
					'Reseller',
					'Distributor',
					'Retail',
					'Service Provider',
					'Educational Institution',
					'Other (please specify below)'
				],
				true
			)
		],
		[
			['territories', 'text', 'What territories do you support?', 'South East U.S.', true]
		]
	];
?>

<form name="contact" class="form">
	<?php wp_nonce_field('wetstone-contact-form'); ?>

	<input type="hidden" name="subject" value="<?php echo $subjectVal; ?>">

	<table class="form-table">
		<tr>
			<td colspan="2" class="text-center">Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<?php
			foreach($structure as $row) {
				echo '<tr>';

				foreach($row as $input) {
					if(gettype($input) == 'array') {
						echo sprintf(
							'<td>
								<label class="form-label">
									%s%s%s 
									<input type="%s" name="%s" placeholder="%s" class="form-input" %s>
								</label>
							</td>',

							$input[4] ? '<i class="req">*</i> ' : '',
							$input[2],
							substr($input[2], -1) == '?' ? '' : ':',
							$input[1],
							$input[0],
							$input[3],
							$input[4] ? 'required' : ''
						);
					} else
						echo $input;
				}

				echo '</tr>';
			}
		?>

		<tr>

			<!-- 
			<td>
				<label class="form-label">
					Questions/Comments:
					<textarea class="form-textarea"
					          style="height: calc(<?php echo count($interests); ?> * 1.5em)"
					          placeholder="<?php echo $placeholder; ?>"></textarea>
				</label>
			</td>
			-->
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