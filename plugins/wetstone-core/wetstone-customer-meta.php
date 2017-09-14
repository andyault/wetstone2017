<?php

//make submenu page
function wetstone_add_customer_page() {
	add_users_page(
		'Add New Customer',
		'Add New Customer',
		'create_users',
		'user-new-customer',
		'wetstone_add_customer_content'
	);
}

add_action('admin_menu', 'wetstone_add_customer_page');

//add page content
$fields = [
	//name => [type, label, placeholder, required]
	'fname' => ['text', 'First Name', 'John'],
	'lname' => ['text', 'Last Name', 'Doe'],
	'email' => ['email', 'Email Address', 'john.doe@example.com', true],
	'username' => ['text', 'Username', 'johndoe123', true]
];

$acctypes = ['Customer', 'Dataset Subscriber', 'Not For Retail', 'Academic'];

//filling in the page
function wetstone_add_customer_content() {
	global $fields;
	global $acctypes;

	do_action('login_enqueue_scripts');

	wp_enqueue_script('utils');
	wp_enqueue_script('user-profile');

	?>

	<div class="wrap">
		<h1>Add New Customer</h1>

		<form method="POST" name="newcustomer" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" autocomplete="off">
			<input type="hidden" name="action" value="wetstone-customer-registration">
			<?php wp_nonce_field('wetstone-customer-registration'); ?>

			<table class="form-table">
				<tr><th scope="row"><h3>Account Info</h3></th></tr>

				<?php
					//user fields
					foreach($fields as $name => $info) {
						//1: name, 2: label, 3: type, 4: placeholder, 5: req, 6: req again
						echo sprintf(
							'<tr class="form-field">
								<th scope="row">
									<label for="%1$s">%2$s %6$s</label>
								</th>
								<td>
									<input type="%3$s" id="%1$s" name="%1$s" placeholder="%4$s" class="regular-text" %5 autocomplete="off" %5$s>
								</td>
							</tr>',

							$name,
							$info[1],
							$info[0],
							$info[2],
							$info[3] ? 'required' : '',
							$info[3] ? '<span class="description">(required)</span>' : ''
						);
					}
				?>

				<tr class="user-pass1-wrap">
					<td colspan="2">
						<label>
							New Password

							<div class="wp-pwd">
								<span class="password-input-wrapper">
									<input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" class="input form-input" size="20" value="" autocomplete="new-password" />
								</span>

								<div id="pass-strength-result" class="hide-if-no-js">Strength indicator</div>
							</div>
						</label>
					</td>
				</tr>

				<tr class="user-pass2-wrap">
					<td colspan="2">
						<label>
							Confirm new password

							<input type="password" name="pass2" id="pass2" class="input form-input" size="20" value="" autocomplete="off" />
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<?php echo wp_get_password_hint(); ?>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e( 'Send User Notification' ) ?></th>
					<td>
						<input type="checkbox" name="send_user_notification" id="send_user_notification" value="1" checked />
						<label for="send_user_notification"><?php _e( 'Send the new user an email about their account.' ); ?></label>
					</td>
				</tr>

				<tr><th scope="row"><h3>Customer Info</h3></th></tr>

				<tr class="form-field">
					<th scope="row">
						<label for="acctype">Account Type:</label>
					</th>

					<td>
						<select id="acctype" name="acctype">
							<?php
								foreach($acctypes as $acctype) {
									//1: acctype
									echo sprintf(
										'<option value="%1$s">%1$s</option>',

										$acctype
									);
								}
							?>
						</select>
					</td>
				</tr>

				<tr>
					<td colspan="99" style="padding: 0;">
						<table>
							<thead>
								<tr>
									<th style="width: 0;">Product Name</th>
									<th style="width: 0; padding-right: 4em;">Product Owned?</th>
									<th>Expiry Date</th>
									<th>Licenses Owned</th>
									<th>Licenses Used</th>
								</tr>
							</thead>

							<tbody>
								<?php
									//products - todo - loop through edd downloads?
									//all products will need: expiry date, num licenses owned, num licenses used?

									$products = get_posts([
										'post_type'      => 'product',
										'posts_per_page' => -1
									]);

									foreach($products as $key => $product) {
										$meta = get_post_meta($product->ID);

										?>

										<tr>
											<td><?php echo $product->post_title; ?></td>

											<td style="text-align: center; padding-right: 4em;"><input type="checkbox" name="product[<?php echo $key ?>][owned]" class="product-owned-checkbox"></td>

											<td style="padding: 0;">
												<input type="date" name="product[<?php echo $key ?>][expiry]" value="<?php echo sprintf('%s-%s-%s', date('Y') + 1, date('m'), date('d')); ?>" disabled>
											</td>

											<td style="padding: 0;"><input type="number" name="product[<?php echo $key ?>][num_owned]" value="1" disabled></td>
											<td style="padding: 0;"><input type="number" name="product[<?php echo $key ?>][num_used]" value="1" disabled></td>
										</tr>

										<?php
									}
								?>
							</tbody>
						</table>
					</td>
				</tr>
			</table>

			<?php submit_button(__('Add New Customer'), 'primary', 'createcustomer', true, ['id' => 'createcustomersub']); ?>
		</form>
	</div>

	<script>
		//enable inputs on checkbox check
		var checkboxes = document.getElementsByClassName('product-owned-checkbox');

		for(var i = 0; i < checkboxes.length; i++) {
			var checkbox = checkboxes[i];

			checkbox.onchange = function() {
				var row = this.parentElement.parentElement;
				var inputs = row.querySelectorAll('input:not([type=checkbox])');

				for(var j = 0; j < inputs.length; j++)
					inputs[j].disabled = !this.checked;
			}
		}
	</script>

	<?php
}

//handling form post
function wetstone_post_customer_registration() {
	//todo - handle post data
	//https://codex.wordpress.org/Function_Reference/wp_insert_user
	
	$user = [
		'user_pass' => $_POST['']
	];

	var_dump($user);
}

add_action('admin_post_wetstone-customer-registration', 'wetstone_post_customer_registration');






