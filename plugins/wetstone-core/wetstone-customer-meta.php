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

	wp_enqueue_script('wp-ajax-response');
	wp_enqueue_script('user-profile');

	?>

	<div class="wrap">
		<h1>Add New Customer</h1>

		<form method="POST" name="newcustomer" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
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
									<input type="%3$s" id="%1$s" name="%1$s" placeholder="%4$s" class="regular-text" %5$s>
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

				<!-- https://github.com/WordPress/WordPress/blob/746edb23f2fd9fceef473aea742ad471b9599730/wp-admin/user-new.php#L425 -->
				<tr class="form-field form-required user-pass1-wrap">
					<th scope="row">
						<label for="pass1">
							<?php _e( 'Password' ); ?>
							<span class="description hide-if-js"><?php _e( '(required)' ); ?></span>
						</label>
					</th>
					<td>
						<input class="hidden" value=" " /><!-- #24364 workaround -->
						<button type="button" class="button wp-generate-pw hide-if-no-js"><?php _e( 'Show password' ); ?></button>
						<div class="wp-pwd hide-if-js">
							<?php $initial_password = wp_generate_password( 24 ); ?>
							<span class="password-input-wrapper">
								<input type="password" name="pass1" id="pass1" class="regular-text" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
							</span>
							<button type="button" class="button wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
								<span class="dashicons dashicons-hidden"></span>
								<span class="text"><?php _e( 'Hide' ); ?></span>
							</button>
							<button type="button" class="button wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change' ); ?>">
								<span class="text"><?php _e( 'Cancel' ); ?></span>
							</button>
							<div style="display:none" id="pass-strength-result" aria-live="polite"></div>
						</div>
					</td>
				</tr>
				<tr class="form-field form-required user-pass2-wrap hide-if-js">
					<th scope="row"><label for="pass2"><?php _e( 'Repeat Password' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
					<td>
					<input name="pass2" type="password" id="pass2" autocomplete="off" />
					</td>
				</tr>
				<tr class="pw-weak">
					<th><?php _e( 'Confirm Password' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="pw_weak" class="pw-checkbox" />
							<?php _e( 'Confirm use of weak password' ); ?>
						</label>
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
									<th>Product Name</th>
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

											<td style="padding: 0;">
												<input type="date" name="product[<?php echo $key ?>][expiry]" value="<?php echo sprintf('%s-%s-%s', date('Y') + 1, date('m'), date('d')); ?>">
											</td>

											<td style="padding: 0;"><input type="number" name="product[<?php echo $key ?>][owned]" value="0"></td>
											<td style="padding: 0;"><input type="number" name="product[<?php echo $key ?>][used]" value="0"></td>
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

	<?php
}

//handling form post
function wetstone_post_customer_registration() {
	//todo - handle post data
	//https://codex.wordpress.org/Function_Reference/wp_insert_user
	
	var_dump($_POST);
}

add_action('admin_post_wetstone-customer-registration', 'wetstone_post_customer_registration');






