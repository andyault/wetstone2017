<?php
	global $edd_login_redirect;

	$edd_login_redirect = get_permalink(get_page_by_path('portal'));

	$redirect = $_GET['redir'] ? $_GET['redir'] : $edd_login_redirect;

	if(!is_user_logged_in()) {
		edd_print_errors();

		?>

		<form class="form" name="login" action="" method="post">
			<?php do_action('edd_login_fields_before'); ?>

			<input type="hidden" name="edd_redirect" value="<?php echo esc_url($redirect); ?>"/>
			<input type="hidden" name="edd_login_nonce" value="<?php echo wp_create_nonce('edd-login-nonce'); ?>"/>
			<input type="hidden" name="edd_action" value="user_login"/>

			<table class="form-table">
				<tr>
					<td colspan="2">
						<label class="form-label">
							<?php _e('Username or Email', 'easy-digital-downloads'); ?>
							<input type="text" name="edd_user_login" placeholder="john.doe@example.com" class="form-input" required>
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<label class="form-label">
							<?php _e('Password', 'easy-digital-downloads'); ?>
							<input type="password" name="edd_user_pass" placeholder="password" class="form-input" required>
						</label>
					</td>
				</tr>

				<tr>
					<td>
						<label class="form-label">
							<input type="checkbox" name="rememberme" value="forever">
							<?php _e('Remember Me', 'easy-digital-downloads'); ?>
						</label>
					</td>

					<td class="text-right">
						<a href="<?php echo wp_lostpassword_url(); ?>" class="link link-body">
							<?php _e('Lost Password?', 'easy-digital-downloads'); ?>
						</a>
					</td>
				</tr>

				<tr>
					<td colspan="2" class="text-center">
						<a href="#" onclick="document.login.submit(); return false;" class="link link-button">
							<?php _e('Log In', 'easy-digital-downloads'); ?>
						</a>
					</td>
				</tr>
			</table>

			<?php do_action( 'edd_login_fields_after' ); ?>
		</form>
	<?php } else { ?>
		<p class="body-content text-center"><?php _e('You are already logged in', 'easy-digital-downloads'); ?></p>
	<?php }
?>