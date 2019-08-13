<script src="<?php echo wetstone_get_asset('/js/form-update.js'); ?>"></script>
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
			<td><?php echo wetstone_form_make_input('website', 'url', 'Company Website', 'http://google.com', true); ?></td>
		</tr>

		<tr>
			<td><?php echo wetstone_form_make_input_emailone('email', 'email', 'Company Email', 'john.doe@example.com', true); ?></td>
			<td><?php echo wetstone_form_make_input_emailtwo('email2', 'email', 'Verify Company Email', 'john.doe@example.com', true); ?></td>
		</tr>
		<tr>
			<td><?php echo wetstone_form_make_select_country(
						'country', 
						'Country', 
						[
							'Afghanistan',
							'Aland Islands',
							'Albania',
							'Algeria',
							'American Samoa',
							'Andorra',
							'Angola',
							'Anguilla',
							'Antarctica',
							'Antigua',
							'Argentina',
							'Armenia',
							'Aruba',
							'Australia',
							'Austria',
							'Azerbaijan',
							'Bahamas',
							'Bahrain',
							'Bangladesh',
							'Barbados',
							'Barbuda',
							'Belarus',
							'Belgium',
							'Belize',
							'Benin',
							'Bermuda',
							'Bhutan',
							'Bolivia',
							'Bosnia',
							'Botswana',
							'Bouvet Island',
							'Brazil',
							'British Indian Ocean Trty.',
							'Brunei Darussalam',
							'Bulgaria',
							'Burkina Faso',
							'Burundi',
							'Caicos Islands',
							'Cambodia',
							'Cameroon',
							'Canada',
							'Cape Verde',
							'Cayman Islands',
							'Central African Republic',
							'Chad',
							'Chile',
							'China',
							'Christmas Island',
							'Cocos (Keeling) Islands',
							'Colombia',
							'Comoros',
							'Congo',
							'Congo, Democratic Republic of the',
							'Cook Islands',
							'Costa Rica',
							'Cote d\'Ivoire',
							'Croatia',
							'Cuba',
							'Cyprus',
							'Czech Republic',
							'Denmark',
							'Djibouti',
							'Dominica',
							'Dominican Republic',
							'Ecuador',
							'Egypt',
							'El Salvador',
							'Equatorial Guinea',
							'Eritrea',
							'Estonia',
							'Ethiopia',
							'Falkland Islands (Malvinas)',
							'Faroe Islands',
							'Fiji',
							'Finland',
							'France',
							'French Guiana',
							'French Polynesia',
							'French Southern Territories',
							'Futuna Islands',
							'Gabon',
							'Gambia',
							'Georgia',
							'Germany',
							'Ghana',
							'Gibraltar',
							'Greece',
							'Greenland',
							'Grenada',
							'Guadeloupe',
							'Guam',
							'Guatemala',
							'Guernsey',
							'Guinea',
							'Guinea-Bissau',
							'Guyana',
							'Haiti',
							'Heard',
							'Herzegovina',
							'Holy See',
							'Honduras',
							'Hong Kong',
							'Hungary',
							'Iceland',
							'India',
							'Indonesia',
							'Iran (Islamic Republic of)',
							'Iraq',
							'Ireland',
							'Isle of Man',
							'Israel',
							'Italy',
							'Jamaica',
							'Jan Mayen Islands',
							'Japan',
							'Jersey',
							'Jordan',
							'Kazakhstan',
							'Kenya',
							'Kiribati',
							'Korea',
							'Korea (Democratic)',
							'Kuwait',
							'Kyrgyzstan',
							'Lao',
							'Latvia',
							'Lebanon',
							'Lesotho',
							'Liberia',
							'Libyan Arab Jamahiriya',
							'Liechtenstein',
							'Lithuania',
							'Luxembourg',
							'Macao',
							'Macedonia',
							'Madagascar',
							'Malawi',
							'Malaysia',
							'Maldives',
							'Mali',
							'Malta',
							'Marshall Islands',
							'Martinique',
							'Mauritania',
							'Mauritius',
							'Mayotte',
							'McDonald Islands',
							'Mexico',
							'Micronesia',
							'Miquelon',
							'Moldova',
							'Monaco',
							'Mongolia',
							'Montenegro',
							'Montserrat',
							'Morocco',
							'Mozambique',
							'Myanmar',
							'Namibia',
							'Nauru',
							'Nepal',
							'Netherlands',
							'Netherlands Antilles',
							'Nevis',
							'New Caledonia',
							'New Zealand',
							'Nicaragua',
							'Niger',
							'Nigeria',
							'Niue',
							'Norfolk Island',
							'Northern Mariana Islands',
							'Norway',
							'Oman',
							'Pakistan',
							'Palau',
							'Palestinian Territory, Occupied',
							'Panama',
							'Papua New Guinea',
							'Paraguay',
							'Peru',
							'Philippines',
							'Pitcairn',
							'Poland',
							'Portugal',
							'Principe',
							'Puerto Rico',
							'Qatar',
							'Reunion',
							'Romania',
							'Russian Federation',
							'Rwanda',
							'Saint Barthelemy',
							'Saint Helena',
							'Saint Kitts',
							'Saint Lucia',
							'Saint Martin (French part)',
							'Saint Pierre',
							'Saint Vincent',
							'Samoa',
							'San Marino',
							'Sao Tome',
							'Saudi Arabia',
							'Senegal',
							'Serbia',
							'Seychelles',
							'Sierra Leone',
							'Singapore',
							'Slovakia',
							'Slovenia',
							'Solomon Islands',
							'Somalia',
							'South Africa',
							'South Georgia',
							'South Sandwich Islands',
							'Spain',
							'Sri Lanka',
							'Sudan',
							'Suriname',
							'Svalbard',
							'Swaziland',
							'Sweden',
							'Switzerland',
							'Syrian Arab Republic',
							'Taiwan',
							'Tajikistan',
							'Tanzania',
							'Thailand',
							'The Grenadines',
							'Timor-Leste',
							'Tobago',
							'Togo',
							'Tokelau',
							'Tonga',
							'Trinidad',
							'Tunisia',
							'Turkey',
							'Turkmenistan',
							'Turks Islands',
							'Tuvalu',
							'Uganda',
							'Ukraine',
							'United Arab Emirates',
							'United Kingdom',
							'United States',
							'Uruguay',
							'US Minor Outlying Islands',
							'Uzbekistan',
							'Vanuatu',
							'Vatican City State',
							'Venezuela',
							'Vietnam',
							'Virgin Islands (British)',
							'Virgin Islands (US)',
							'Wallis',
							'Western Sahara',
							'Yemen',
							'Zambia',
							'Zimbabwe'
						],
						true
					); 
				?>
			</td>
			<td><?php echo wetstone_form_make_input('phone', 'tel', 'Phone', '(555) 867-5309', true); ?></td>
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
			<td><?php
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
				?></td>

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
							'Web',
							'Other (please specify in comments)'
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
			<td>
				<?php echo wetstone_form_make_input('referrer', 'text', 'How did you hear about us?', '', false); ?>
			</td>
			<td>
				<?php 	echo wetstone_form_make_textarea(
						'comments', 
						'Questions/Comments', 
						'I have a question about...', 
						false,
						['style' => sprintf('height: calc(%d * 1.5em)', 5)]
					); ?>
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
