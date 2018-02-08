<form name="contact" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
	<input type="hidden" name="action" value="wetstone-resell-form">
	<?php wp_nonce_field('wetstone-resell-form'); ?>

	<input type="hidden" name="subject" value="Reseller Application">

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

		<tr>
			<td><?php echo wetstone_form_make_input('fname', 'text', 'First Name', 'John', true); ?></td>
			<td><?php echo wetstone_form_make_input('lname', 'text', 'Last Name', 'Doe', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input('company', 'text', 'Company', 'Google Inc.', true); ?></td>
			<td><?php echo wetstone_form_make_input('website', 'url', 'Website', 'http://google.com', true); ?></td>
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
						echo wetstone_form_make_select_state(
							'state', 
							'State/Province', 
							[
								'AL',
								'AK',
								'AZ',
								'AR',
								'CA',
								'CO',
								'CT',
								'DE',
								'DC',
								'FL',
								'GA',
								'HI',
								'ID',
								'IL',
								'IN',
								'IA',
								'KS',
								'KY',
								'LA',
								'ME',
								'MD',
								'MA',
								'MI',
								'MN',
								'MS',
								'MO',
								'MT',
								'NE',
								'NV',
								'NH',
								'NJ',
								'NM',
								'NY',
								'NC',
								'ND',
								'OH',
								'OK',
								'OR',
								'PA',
								'RI',
								'SC',
								'SD',
								'TN',
								'TX',
								'UT',
								'VT',
								'VA',
								'WA',
								'WV',
								'WI',
								'WY'
							],
						true
					);
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
			<td>
				<?php
					echo wetstone_form_make_select(
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
					);
				?>
			</td>

			<td rowspan="3">
				<?php
					echo wetstone_form_make_checkboxes(
						'marketing',
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
						true
					);
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php
					echo wetstone_form_make_select(
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
							'Other (please specify in comments)'
						],
						true
					);
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php 
					echo wetstone_form_make_input(
						'territories', 
						'text', 
						'What territories do you support?', 
						'South East U.S.', 
						true
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
