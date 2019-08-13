<h2 class="section-header">Sign In</h2>

<?php if(!is_user_logged_in()) {
	if($_GET['success'] == 'false')
		echo '<p class="text-center" style="line-height: 1.5em;"><strong>ERROR</strong>: Invalid username or password.<br />
		      <a href="' . add_query_arg('action', 'lostpassword', get_permalink()) . '" class="link link-body">Lost your password?</a>';
	elseif($_GET['passwordchanged'])
		echo '<p class="text-center">Your password has been changed.</p>';
	elseif($_GET['loggedout'])
		echo '<p class="text-center">You are now logged out.</p>'; ?>
<script>
    var mtcaptchaConfig = {
      "sitekey": "MTPublic-VwYnY8ywe"
   };
   (function(){var mt_service = document.createElement('script');mt_service.async = true;mt_service.src = 'https://service.mtcaptcha.com/mtcv1/client/mtcaptcha.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service);
   var mt_service2 = document.createElement('script');mt_service2.async = true;mt_service2.src = 'https://service2.mtcaptcha.com/mtcv1/client/mtcaptcha2.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service2);}) ();
</script>
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
				<td class="cell-nostretch">
					<label class="form-label">
						<input type="checkbox" name="rememberme" value="forever" checked>
						Remember Me
					</label>
				</td>

				<td class="cell-nostretch text-right">
					<a href="<?php echo add_query_arg('action', 'lostpassword', get_permalink()); ?>" class="link link-body">
						Lost Password?
					</a>
				</td>
			</tr>
		<tr>
			<td colspan="2" class="table-footer">
				<div class="inline-flex">				
					<div class="mtcaptcha"></div>
				</div>
			</td>
		</tr>
			<tr>
				<td colspan="2" class="text-center">
					<button type="submit" class="link link-button">Log in</button>
				</td>
			</tr>
		</table>
	</form>
<?php } else { 
	wp_safe_redirect('https://www.wetstonetech.com/portal/'); }
?>