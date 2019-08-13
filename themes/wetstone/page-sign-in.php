<?php
	//handle different actions, boy do I hate doing all this in one file
	$isPOST = $_SERVER['REQUEST_METHOD'] == 'POST';

	$action = $_GET['action'];
	$template = '';

	
	$errors = new WP_Error();
	
	$captch = $_POST['mtcaptcha-verifiedtoken'];

	$captcha = file_get_contents("https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-VwYnY8ywe-qCvwFNh7hRhZfxoT3kWZgkOxItHxkd42vvHH9sK1i4WG9OGtOM&token=".$captch['mtcaptcha-verifiedtoken']);
		
	$captchaJson = json_decode($captcha);
	
	//if we're posting
	switch($action) {
		case 'lostpassword':
		case 'retrievepassword':
		case 'checkmail':
			if($isPOST) {
				//try to reset password
				$login = $_POST['user_login'];
				
				if($captchaJson->{'success'} != 1) {
			
					$data['errmsg'] = 'Please ensure the Captcha is completed';

					//go back to form with old data
					wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			
				} else {

				if(empty($login)) {
					$errors->add('empty_username', 'ERROR: Enter a username or email address.');
				} elseif(strpos($login, '@')) {
					$data = get_user_by('email', trim(wp_unslash($login)));
				} else {
					$data = get_user_by('login', trim($login));
				}

				//note, there is no message for non-existing email/user
				if(!$errors->get_error_code()) {
					if($data) {
						$login = $data->user_login;
						$email = $data->user_email;

						$key = get_password_reset_key($data);

						if(!is_wp_error($key)) {
							$message = "Someone has requested a password reset for the following account:\r\n\r\n";
							$message .= "Username: $login\r\n\r\n";
							$message .= "If this was a mistake, just ignore this email and nothing will happen.\r\n\r\n";
							$message .= "To reset your password, visit the following address:\r\n\r\n";
							$message .= "<" . add_query_arg(['action'=>'rp', 'key'=>$key, 'login'=>rawurlencode($login)], get_permalink()) . ">\r\n";

							$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

							$title = "[$blogname] Password Reset";

							if($message && !wp_mail($email, $title, $message))
								wp_die('Email could not be sent');

							wp_safe_redirect(add_query_arg('action', 'checkmail', get_permalink()));

							exit;
						} else
							$errors = $key;
					} else {
						wp_safe_redirect(add_query_arg(['action' => 'checkmail', 'nouser'=>'true'], get_permalink()));

						exit;
					}
					}
				}
			}

			$template = 'lost-password';
			break;

		case 'resetpass':
		case 'rp':
			$key = $_GET['key'];
			$login = $_GET['login'];
			
			if($captchaJson->{'success'} != 1) {
			
			$data['errmsg'] = 'Please ensure the Captcha is completed';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			
				} else {
			//if we have the right query vars, check password
			if(isset($key) && !empty($key) && isset($login) && !empty($login)) {
				$user = check_password_reset_key($key, $login); 

				//if we're trying to reset our pass, check the hash?
				if(isset($_POST['pass1']) && !hash_equals($key, $_POST['rp_key']))
					$user = false;
			} else
				$user = false;

			//if we weren't successful, that means our key is bad
			if(!$user || is_wp_error($user)) {
				wp_safe_redirect(add_query_arg(['action' => 'lostpassword', 'rperror' => 'invalidkey'], get_permalink()));

				exit;
			}

			//the rest is all posting?
			if($isPOST) {
				//see if passwords don't match somehow
				if(isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'])
						$errors->add('password_reset_mismatch', 'The passwords do not match.');

				//call hook
				do_action('validate_password_reset', $errors, $user);

				//if we don't have errors, and we're trying to reset our password, reset our password
				if(!$errors->get_error_code() && isset($_POST['pass1']) && !empty($_POST['pass1'])) {
					reset_password($user, $_POST['pass1']);

					//redirect with a get variable
					wp_safe_redirect(add_query_arg('passwordchanged', 'true', get_permalink()));

					exit;
				}
			}
				}
			$template = 'reset-password';
			break;

		default:
			$template = 'sign-in';
			break;
		}
	if(isset($_GET['rperror']) && $_GET['rperror'] == 'invalidkey')
			$errors->add('invalidkey', 'Your password reset link appears to be invalid. Please request a new link below.');

	//load the rest of the stuff normally
	get_header();
	wp_reset_postdata();

	if(get_the_content())
		get_template_part('template-parts/basic', 'page-nobody');
?>

<section class="page-posts site-content site-content-tiny">
	<?php get_template_part('template-parts/form', $template); ?>
</section>

<?php get_footer(); ?>