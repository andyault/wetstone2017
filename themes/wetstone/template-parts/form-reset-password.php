<?php
	//load wordpress's login scripts
	do_action('login_enqueue_scripts');

	wp_enqueue_script('utils');
	wp_enqueue_script('user-profile');
?>

<h2 class="section-header">New Password</h2>

<p class="text-center">Enter your new password below.</p>

<form method="POST" name="reset" action="" autocomplete="off" class="form">
	<input type="hidden" id="user_login" value="<?php echo esc_attr($login); ?>" autocomplete="off" />

	<table class="form-table">
		<tr class="user-pass1-wrap">
			<td colspan="2">
				<label>
					New Password

					<div class="wp-pwd">
						<span class="password-input-wrapper">
							<input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" class="input form-input" size="20" value="" autocomplete="off" />
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

		<input type="hidden" name="rp_key" value="<?php echo esc_attr($_GET['key']); ?>" />

		<tr>
			<td colspan="2" class="text-center">
				<button type="submit" class="link link-button">Reset Password</button>
			</td>
		</tr>
	</table>
</form>
