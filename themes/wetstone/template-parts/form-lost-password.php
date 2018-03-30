<h2 class="section-header">Lost Password</h2>

<?php
	if($_GET['action'] == 'checkmail') {
		echo '<p class="text-center">Check your email for the confirmation link.</p>';
	} else {
		global $errors;

		if(is_wp_error($errors) && $errors->get_error_code()) { 
			foreach($errors->get_error_codes() as $code) {
				foreach($errors->get_error_messages($code) as $message) {
					echo '<p class="text-center">' . $message . '</p>';
				}
			}
		} else {
			echo '<p class="text-center">Please enter your username or email address. You will receive a link to create a new password via email.</p>';
		} ?>

		<form method="POST" name="lostpasswordform" action="" class="form">
			<table class="form-table">
				<tr>
					<td colspan="2">
						<label class="form-label">
							Username or Email
							<input type="text" name="user_login" placeholder="john.doe@example.com" class="form-input" required>
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2" class="text-center">
						<center><?php echo apply_filters( 'gglcptch_display_recaptcha', '' ); ?></center>
						<button type="submit" name="wp-submit" class="link link-button">Get New Password</button>
					</td>
				</tr>
			</table>
		</form>
<?php
	}
?>