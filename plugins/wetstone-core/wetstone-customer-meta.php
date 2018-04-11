<?php

//make customer role
add_role(
	'customer',
	'Customer',
	[] //everything defaults to false, right?
);

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
	'first_name' => ['text', 'First Name', 'John'],
	'last_name' => ['text', 'Last Name', 'Doe'],
	'company' => ['text', 'Company', 'Google Inc.'],
	'phone' => ['tel', 'Phone', '(555) 867-5309'],
	//'address1' => ['text', 'Address Line 1', 'Google Inc.'], //maybe
	'user_email' => ['email', 'Email Address', 'john.doe@example.com', true],
	'user_login' => ['text', 'Username', 'johndoe123', true],
];

$acctypes = ['Customer', 'Dataset Subscriber', 'Not For Retail', 'Academic'];

//filling in the page
function wetstone_echo_customer_form_fields($userId = null) {
	if(isset($userId))
		$myproducts = get_user_meta($userId, 'wetstone_products', true);

	//table's already open
	?>

		<!-- <tr class="form-field">
			<th scope="row">
				<label for="acctype">Account Type:</label>
			</th>

			<td>
				<select id="acctype" name="acctype">
					< ?php
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
		</tr> -->

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

							$expiryDate = sprintf(
								'%s-%s-%s',

								date('Y') + 1,
								date('m'),
								date('d')
							);
							
							// Array of products that appear on the page in the order requested, to add new product simply place the product ID in the order you wish them to appear
							$pidarray = array(613,116,612,115,617,616,121,120,123,37,141,620,619,618,623,622,621,614);
							// Simple array that lets the list know after which products to put a break for visual purposes.
							$linebreaks = array(116,616,141,618);
							$pidcount = 0;							
							
							foreach($products as $key => $product) {
								//$pid = $pidarray[$pidcount];
								$pid = $product->ID;
								$productName = get_the_title($pid);
								$meta = get_post_meta($pid);
								$myinfo = $myproducts[$pid];

								$owned = $myinfo ? strtotime($myinfo['expiry']) > time() : false; //for sure need to test this

								?>

								<tr>
																		
									<td><?php echo $productName ?></td>

									<td style="text-align: center; padding-right: 4em;">
										<input type="checkbox" class="product-owned-checkbox" <?php if($owned) echo 'checked'; ?>>
									</td>

									<td style="padding: 0;">
										<input type="date"
										       name="product[<?php echo $pid ?>][expiry]"
										       value="<?php echo $myinfo ? $myinfo['expiry'] : $expiryDate; ?>" disabled>
									</td>

									<td style="padding: 0;">
										<input type="number" name="product[<?php echo $pid ?>][num_owned]" value="<?php echo $myinfo ? $myinfo['num_owned'] : 1; ?>" disabled>
									</td>

									<td style="padding: 0;">
										<input type="number" name="product[<?php echo $pid ?>][num_used]" value="<?php echo $myinfo ? $myinfo['num_used'] : 0; ?>" disabled>
									</td>									
								</tr>										

								<?php
								
								//if (in_array($pid, $linebreaks)) {
										//echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
								//	}
								
								//$pidcount++;
								
							}
						?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>

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

			checkbox.onchange();
		}
	</script>

	<?php
}

function wetstone_add_customer_content() {
	global $fields;
	global $acctypes;

	if(!current_user_can('create_users'))
		echo '<div class="wrap"><h1>Add New Customer</h1> <p>You are not allowed to create users.</p></div>';

	if($_GET['customeradded'] == true)
		echo '<div class="notice notice-success is-dismissible"><p>Customer successfully added.</p></div>';

	?>

	<div class="wrap">
		<h1>Add New Customer</h1>

		<form method="POST" id="createuser" name="newcustomer" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" autocomplete="off">
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

				<tr class="form-field">
					<th scope="row">
						<label for="user_pass">Password <span class="description">(required)</span></label>
					</th>

					<td>
						<input type="text" id="user_pass" name="user_pass" class="regular-text" value="<?php echo esc_attr(wp_generate_password(24)); ?>" required autocomplete="new-password">
					</td>
				</tr>

				<tr>
					<td colspan="2" style="padding-left: 0;">Passwords are sent to new users with a link to change them.</td>
				</tr>

				<tr><th scope="row"><h3>Customer Info</h3></th></tr>

				<?php
					wetstone_echo_customer_form_fields();

				submit_button(__('Add New Customer'), 'primary', 'createcustomer', true, ['id' => 'createcustomersub']);
			?>
		</form>
	</div>

	<?php
}

//handling form post
function wetstone_post_customer_registration() {
	//https://codex.wordpress.org/Function_Reference/wp_insert_user
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-customer-registration'))
		return wp_nonce_ays('wetstone-customer-registration');

	if(!current_user_can('create_users'))
		wp_die('<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1><p>' . __( 'Sorry, you are not allowed to create users.' ) . '</p>', 403);

	//remove our meta info from the post array
	foreach(['company', 'phone'] as $key)
		$meta[$key] = $_POST[$key];

	//only use the keys we need
	foreach(['user_login', 'user_pass', 'user_email', 'first_name', 'last_name'] as $key)
		$user[$key] = $_POST[$key];

	$user['user_registered'] = date('Y-m-d H:i:s');
	$user['role'] = 'customer';

	//create user
	$id = wp_insert_user($user);

	//if successful
	if(!is_wp_error($id)) {
		//add meta
		foreach($meta as $key => $val)
			add_user_meta($id, 'wetstone_' . $key, sanitize_text_field($val), true);

		//add products
		add_user_meta($id, 'wetstone_products', $_POST['product']);

		//set up activation stuff
		$key = get_password_reset_key(get_user_by('id', $id));

		//send email		
		//$message = "Hello,\n\nYour new WetStone Technologies account has been set up.\n\nPlease click the following link to set your password:\n";
		$message = "Dear ".$user['first_name'].",\n\n Our customer support portal is designed to provide 24-hour support for our valued customers.\n\n Please find below your user information for the portal: \n\n Username: ".$user['user_login']." \n\nPlease click the following link to set your password:\n";
		$message .= add_query_arg(['action'=>'rp', 'key'=>$key, 'login'=>rawurlencode($user['user_login'])], get_permalink(get_page_by_path('sign-in')));
		$message .= " "."\n\nThis portal is where you will have access to the following:\n • Account information\n • Product downloads\n • License Keys\n • FAQ's\n • Dataset updates\n • Connectivity to customer support\n\n Once you gain access to the portal we encourage you to click on the product(s) you have purchased and navigate to the User Guide tab where you will find the product user guide to help you with installation. Also, please note for all token based products you will need to use the password provided to you for the device. Too many incorrect password attempts will result in a nonfunctioning token.\n\nIf you have further questions, please contact your Account Executive or our support department directly at support@wetstonetech.com.\n\n Sincerely,\nThe WetStone Team";

		wp_mail(
			$user['user_email'],
			'WetStone Technologies Registration',
			$message
		);
		
		wp_mail(
			'support@wetstonetech.com',
			'WetStone Technologies Registration',
			$message
		);

		//redirect to same page with notification
		wp_safe_redirect(add_query_arg(['page' => 'user-new-customer', 'customeradded' => true], 'users.php'));
		exit;
	}
	
	//otherwise
	error_log(print_r($id->get_error_messages()));
}

add_action('admin_post_wetstone-customer-registration', 'wetstone_post_customer_registration');

//editing other user profile
function wetstone_edit_user_profile($user) {
	?>

	<h2>Customer Info</h2>

	<table class="form-table">
		<?php

		wetstone_echo_customer_form_fields($user->ID);
}

add_action('show_user_profile', 'wetstone_edit_user_profile');
add_action('edit_user_profile', 'wetstone_edit_user_profile');

function wetstone_edit_user_profile_update($userId) {
	if(!current_user_can('edit_user_meta', $userId))
		return false;

	update_user_meta($userId, 'wetstone_products', $_POST['product']);
}

add_action('edit_user_profile_update', 'wetstone_edit_user_profile_update');
add_action('personal_options_update', 'wetstone_edit_user_profile_update');

//"my account" page
function wetstone_post_my_account() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-my-account'))
		return wp_nonce_ays('wetstone-my-account');

	//make sure ids match
	$id = wp_get_current_user()->ID;

	if($_POST['ID'] === $id)
		wp_die('<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1><p>' . __( 'Sorry, you can only edit your own account.' ) . '</p>', 403);

	//update userdata
	wp_update_user([
		'ID' => $id,
		'first_name' => sanitize_text_field($_POST['first_name']),
		'last_name' => sanitize_text_field($_POST['last_name']),
		'user_email' => sanitize_text_field($_POST['user_email'])
	]);

	//update user meta
	foreach(['company', 'phone'] as $key)
		update_user_meta($id, 'wetstone_' . $key, sanitize_text_field($_POST['wetstone_' . $key]));

	wp_safe_redirect(add_query_arg('updated', true, home_url('/portal/my-account/')));
}

add_action('admin_post_wetstone-my-account', 'wetstone_post_my_account');

//handle key generation
function wetstone_post_generate_key() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-generate-key'))
		return wp_nonce_ays('wetstone-generate-key');

	include(get_template_directory() . '/generate-key.php');
}

add_action('admin_post_wetstone-generate-key', 'wetstone_post_generate_key');