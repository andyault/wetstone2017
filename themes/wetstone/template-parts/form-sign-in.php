<?php if(!is_user_logged_in()) { ?>
	<form method="POST" name="login" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
		<input type="hidden" name="action" value="wetstone-login">
		<?php wp_nonce_field('wetstone-login'); ?>

		<table class="form-table">
			<?php if(isset($_GET['loginerror'])) { //if we got redirected to the same page ?>
				<tr>
					<td colspan="2" class="text-center" style="color: #f00;">Invalid username or password.</td>
				</tr>
			<?php } ?>

			<tr>
				<td colspan="2">
					<label class="form-label">
						Username or Email
						<input type="text" name="log" placeholder="john.doe@example.com" class="form-input" required>
					</label>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<label class="form-label">
						Password
						<input type="password" name="pwd" placeholder="password" class="form-input" required>
					</label>
				</td>
			</tr>

			<tr>
				<td>
					<label class="form-label">
						<input type="checkbox" name="rememberme" value="forever" checked>
						Remember Me
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
					<button type="submit" name="wp-submit" class="link link-button">Log in</a>
				</td>
			</tr>
		</table>
	</form>
<?php } else { ?>
	<p class="body-content text-center">You are already logged in.</p>
<?php } ?>