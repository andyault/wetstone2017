<?php

//add options
wetstone_add_option('form_handling', 'email_width',    75);
wetstone_add_option('form_handling', 'sender_email',   'postmaster@wetstonetech.com');
//wetstone_add_option('form_handling', 'receiver_email', 'smbsales@allencorp.com');
wetstone_add_option('form_handling', 'receiver_email', 'wconklin@allencorporation.com');
wetstone_add_option('form_handling', 'default_name',   'WetStone Technologies');

//handle post - TODO: verify required fields
//  contact
function wetstone_post_contact_form() {
	
		if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-contact-form'))
			return wp_nonce_ays('wetstone-contact-form');		
		
		//sanitize inputs
		$data = wetstone_sanitize_post([
			'subject', 'fname', 'lname', 'title', 'company', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
			'zip', 'country', 'referrer', 'interests', 'comments', 'mtcaptcha-verifiedtoken'
		]);
				
		$captcha = file_get_contents("https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-VwYnY8ywe-qCvwFNh7hRhZfxoT3kWZgkOxItHxkd42vvHH9sK1i4WG9OGtOM&token=".$data['mtcaptcha-verifiedtoken']);
		
		$captchaJson = json_decode($captcha);

		if($captchaJson->{'success'} != 1) {
			
			$data['errmsg'] = 'Please ensure the Captcha is completed';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			
		} else {

		//turn into pretty table
		$emailWidth = wetstone_get_option('form_handling', 'email_width');

		$comments = wetstone_pop_value($data, 'comments');

		$subject = wetstone_pop_value($data, 'subject');

		$fields = '<pre>';
		$fields .= wetstone_columnify($data);

		if(!empty($comments))
			$fields .= "\ncomments: \n</pre><p>" . htmlspecialchars($comments) . '</p>';

		$fullName = $data['fname'] . ' ' . $data['lname'];

		if(wetstone_send_mail($subject, $fullName, $data['email'], wordwrap($fields, $emailWidth)))
			wp_redirect(get_permalink(get_page_by_path('thank-you')));
		else {
			//unpop comments
			$data['comments'] = $comments;

			//add error
			$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
		}
	}
}

add_action('admin_post_wetstone-contact-form', 'wetstone_post_contact_form');
add_action('admin_post_nopriv_wetstone-contact-form', 'wetstone_post_contact_form');

// BETA SIGNUP FORM -------------------------------------
function wetstone_mp_beta_form() {
	
		if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-mp-beta'))
			return wp_nonce_ays('wetstone-mp-beta');		
		
		//sanitize inputs
		$data = wetstone_sanitize_post([
			'subject', 'fname', 'lname', 'company', 'phone', 'email', 'mtcaptcha-verifiedtoken'
		]);
				
		$captcha = file_get_contents("https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-VwYnY8ywe-qCvwFNh7hRhZfxoT3kWZgkOxItHxkd42vvHH9sK1i4WG9OGtOM&token=".$data['mtcaptcha-verifiedtoken']);
		
		$captchaJson = json_decode($captcha);

		if($captchaJson->{'success'} != 1) {
			
			$data['errmsg'] = 'Please ensure the Captcha is completed';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			
		} else {

		//turn into pretty table
		$emailWidth = wetstone_get_option('form_handling', 'email_width');

		$subject = wetstone_pop_value($data, 'subject');

		$fields = '<pre>';
		$fields .= wetstone_columnify($data);

		$fullName = $data['fname'] . ' ' . $data['lname'];

		if(wetstone_send_mail2($subject, $fullName, $data['email'], wordwrap($fields, $emailWidth)))
			wp_redirect(get_permalink(get_page_by_path('thank-you')));
		else {

			//add error
			$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
		}
	}
}

add_action('admin_post_wetstone-mp-beta', 'wetstone_mp_beta_form');
add_action('admin_post_nopriv_wetstone-mp-beta', 'wetstone_mp_beta_form');

// DEMO SIGNUP FORM -------------------------------------
function wetstone_mp_demo_form() {
	
		if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-mp-demo'))
			return wp_nonce_ays('wetstone-mp-demo');		
		
		//sanitize inputs
		$data = wetstone_sanitize_post([
			'subject', 'fname', 'lname', 'company', 'website', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
			'zip', 'country', 'mtcaptcha-verifiedtoken'
		]);
				
		$captcha = file_get_contents("https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-VwYnY8ywe-qCvwFNh7hRhZfxoT3kWZgkOxItHxkd42vvHH9sK1i4WG9OGtOM&token=".$data['mtcaptcha-verifiedtoken']);
		
		$captchaJson = json_decode($captcha);

		if($captchaJson->{'success'} != 1) {
			
			$data['errmsg'] = 'Please ensure the Captcha is completed';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			
		} else {

		//turn into pretty table
		$emailWidth = wetstone_get_option('form_handling', 'email_width');

		$subject = wetstone_pop_value($data, 'subject');

		$fields = '<pre>';
		$fields .= wetstone_columnify($data);

		$fullName = $data['fname'] . ' ' . $data['lname'];

		if(wetstone_send_mail2($subject, $fullName, $data['email'], wordwrap($fields, $emailWidth)))
			wp_redirect(get_permalink(get_page_by_path('thank-you')));
		else {

			//add error
			$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
		}
		}
}

add_action('admin_post_wetstone-mp-demo', 'wetstone_mp_demo_form');
add_action('admin_post_nopriv_wetstone-mp-demo', 'wetstone_mp_demo_form');

//  resell form
function wetstone_post_resell_form() {
		
		if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-resell-form'))
			return wp_nonce_ays('wetstone-resell-form');

		//sanitize inputs
		$data = wetstone_sanitize_post([
			'subject', 'fname', 'lname', 'company', 'website', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
			'zip', 'country', 'referrer', 'customers', 'marketing', 'comments', 'description', 'territories', 'mtcaptcha-verifiedtoken'
		]);
				
		$captcha = file_get_contents("https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-VwYnY8ywe-qCvwFNh7hRhZfxoT3kWZgkOxItHxkd42vvHH9sK1i4WG9OGtOM&token=".$data['mtcaptcha-verifiedtoken']);
		
		$captchaJson = json_decode($captcha);

		if($captchaJson->{'success'} != 1) {
			
			$data['errmsg'] = 'Please ensure the Captcha is completed';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			
		} else {

		//turn into pretty table
		$emailWidth = wetstone_get_option('form_handling', 'email_width');

		$subject = wetstone_pop_value($data, 'subject');

		$fields = '<pre>';
		$fields .= wetstone_columnify($data);

		$fullName = $data['fname'] . ' ' . $data['lname'];

		if(wetstone_send_mail($subject, $fullName, $data['email'], wordwrap($fields, $emailWidth)))
			wp_redirect(get_permalink(get_page_by_path('thank-you')));
		else {
			//add error
			$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
		}

		}
}

add_action('admin_post_wetstone-resell-form', 'wetstone_post_resell_form');
add_action('admin_post_nopriv_wetstone-resell-form', 'wetstone_post_resell_form');


//support page
function wetstone_post_support() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-customer-support'))
		return wp_nonce_ays('wetstone-customer-support');

	//make sure ids match
	$user = wp_get_current_user();
	$id = $user->ID;
	$firstname = $user->first_name;
	$lastname =  $user->last_name;
	$emailaddress = $user->user_email;

	if($_POST['ID'] === $id)
		wp_die('<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1><p>' . __( 'Sorry, you can only submit forms from your own account.' ) . '</p>', 403);

	//building email body
	$data = wetstone_sanitize_post(['product', 'context', 'comments'
		]);
	

	$comments = wetstone_pop_value($data, 'comments');

	$body = '<pre>';	
	$body .= wetstone_columnify($data);	
	$body .= "\ncomments: \n</pre><p>" . htmlspecialchars($comments) . "</p>";
	$body .= "<p>".$firstname." ".$lastname.": ".$emailaddress."</p>";

	//getting email info
	$subject = 'Customer Support';
	$fullName = $user->first_name . ' ' . $user->last_name;
	$fromMail = $user->user_email;
	
	$emailWidth = wetstone_get_option('form_handling', 'email_width');

	if(wetstone_support_mail($subject, $fullName, $fromMail, wordwrap($body, $emailWidth)))
		wp_redirect(get_permalink(get_page_by_path('thank-you')));
	else {
		//unpop comments
		$data['comments'] = $comments;

		//add error
		$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

		//go back to form with old data
		wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
		}
}

add_action('admin_post_wetstone-customer-support', 'wetstone_post_support');

function wetstone_mpfeedback_form() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-mpfeedback-form'))
		return wp_nonce_ays('wetstone-mpfeedback-form');
	$surv = "Completed";
	//make sure ids match
	$user = wp_get_current_user();
	$id = $user->ID;
	$firstname = $user->first_name;
	$lastname =  $user->last_name;
	$emailaddress = $user->user_email;

	//building email body
	$data = wetstone_sanitize_post(['element_2', 'explain_2', 'element_3', 'explain_3', 'element_4', 'explain_4', 'element_5', 'explain_5', 'element_6', 'explain_6', 'element_7', 'explain_7', 'element_1']);
	
	if (!$data['element_1']) { $comments1 = "No response"; } else { $comments1 = htmlspecialchars(wetstone_pop_value($data, 'element_1')); }
	
	$comments2 = htmlspecialchars(wetstone_pop_value($data, 'explain_2'));
	$comments3 = htmlspecialchars(wetstone_pop_value($data, 'explain_3'));
	$comments4 = htmlspecialchars(wetstone_pop_value($data, 'explain_4'));
	$comments5 = htmlspecialchars(wetstone_pop_value($data, 'explain_5'));
	$comments6 = htmlspecialchars(wetstone_pop_value($data, 'explain_6'));
	$comments7 = htmlspecialchars(wetstone_pop_value($data, 'explain_7'));
	
	
	if (!$data['element_2']) { $element_2 = "No response"; } else { $element_2 = $data['element_2']; }
	if (!$data['element_3']) { $element_3 = "No response"; } else { $element_3 = $data['element_3']; }
	if (!$data['element_4']) { $element_4 = "No response"; } else { $element_4 = $data['element_4']; }
	if (!$data['element_5']) { $element_5 = "No response"; } else { $element_5 = $data['element_5']; }
	if (!$data['element_6']) { $element_6 = "No response"; } else { $element_6 = $data['element_6']; }
	if (!$data['element_7']) { $element_7 = "No response"; } else { $element_7 = $data['element_7']; }

	$body = '<pre>';	
	$body .= "<p>".$firstname." ".$lastname.": ".$emailaddress."</p>";
	$body .= "<p>How would you rate the overall ease of installation? - " . $element_2. "</p>";
	if ($comments2) $body .= "<p>Additional Feedback?</p><p> " . $comments2 . "</p>";
	$body .= "<p>How would you rate the new interface? - " . $element_3 . "</p>";
	if ($comments3) $body .= "<p>Additional Feedback?</p><p>" . $comments3 . "</p>";
	$body .= "<p>How would you rate the scan speed? - " . $element_4 . "</p>";
	if ($comments4) $body .= "<p>Additional Feedback?</p><p>" . $comments4 . "</p>";
	$body .= "<p>How would you rate the reporting options? - " . $element_5 . "</p>";
	if ($comments5) $body .= "<p>Additional Feedback?</p><p>" . $comments5 . "</p>";
	$body .= "<p>How would you rate the malware discovery results? - " . $element_6 . "</p>";
	if ($comments6) $body .= "<p>Additional Feedback?</p><p>" . $comments6 . "</p>";
	$body .= "<p>How would you rate the overall satisfaction with the new product? - " . $element_7 . "</p>";
	if ($comments7) $body .= "<p>Additional Feedback? - </p><p>" . $comments7 . "</p>";
	$body .= "<p>Any comments you would like to add?</p><p>" . $comments1 . "</p>"; 
	
	//getting email info
	$subject = 'Gargoyle MP Feedback Survey';
	$fullName = $user->first_name . ' ' . $user->last_name;
	$fromMail = $user->user_email;
	
	$emailWidth = wetstone_get_option('form_handling', 'email_width');

	if ($_POST['submit'] == "Do Not Ask Again") {
		$body = '<pre>';	
		$body .= "<p>".$firstname." ".$lastname.": ".$emailaddress." - Declined survey</p>";
		$surv = "Declined";
	}
	
	if ($_POST['submit'] == "Ask Me Later") {
		$surv = "Later";
	}
	
	if ($surv == "Later") { wp_redirect(get_permalink(get_page_by_path('portal'))); } else {
	
		if(wetstone_send_mail2($subject, $fullName, $fromMail,wordwrap($body, $emailWidth))) {
			update_user_meta($id, 'mpsurvey1', $surv);
			
			if ($surv == "Declined") {
				wp_redirect(get_permalink(get_page_by_path('portal')));
			} else {
				wp_redirect(get_permalink(get_page_by_path('feedback')));
			}
			
			 
			}
			
			
		else {
			//unpop comments
			//$data['comments'] = $comments;

			//add error
			$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

			//go back to form with old data
			wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
			}
	}
}

add_action('admin_post_wetstone-mpfeedback-form', 'wetstone_mpfeedback_form');



//send email
function wetstone_send_mail($subject, $fromName, $fromAddress, $body) {
	if(!isset($fromName) || empty($fromName))
		$fromName = wetstone_get_option('form_handling', 'default_name');
	/*/handle multiple recipients
	$recipients = explode(',', wetstone_get_option('form_handling', 'receiver_email'));

	$toHeader = 'To: ';

	foreach($recipients as $address)
		$toHeader .= 'Sales Support <' . $address . '>,';
	*/

	//email headers
	$headers = [
		//'Sender: ' . wetstone_get_option('form_handling', 'sender_email'),
		//sprintf('From: %s via Contact Form <%s>', $fromName, $fromAddress),
		//sprintf('Reply-to: %s <%s>', $fromName, $fromAddress),
		//substr($toHeader, 0, -1),
		'Content-Type: text/html; charset=UTF-8'
	];
	
	return wp_mail(
		wetstone_get_option('form_handling', 'receiver_email'),
		$subject,
		$body,
		$headers
	);
}

function wetstone_support_mail($subject, $fromName, $fromAddress, $body) {
	if(!isset($fromName) || empty($fromName))
		$fromName = wetstone_get_option('form_handling', 'default_name');
		$toAddress = 'support@wetstonetech.com,wconklin@allencorporation.com';
	/*/handle multiple recipients
	$recipients = explode(',', wetstone_get_option('form_handling', 'receiver_email'));

	$toHeader = 'To: ';

	foreach($recipients as $address)
		$toHeader .= 'Sales Support <' . $address . '>,';
	*/

	//email headers
	$headers = [
		//'Sender: ' . wetstone_get_option('form_handling', 'sender_email'),
		//sprintf('From: %s via Contact Form <%s>', $fromName, $fromAddress),
		//sprintf('Reply-to: %s <%s>', $fromName, $fromAddress),
		//substr($toHeader, 0, -1),
		'Content-Type: text/html; charset=UTF-8'
	];
	
	return wp_mail(
		//wetstone_get_option('form_handling', 'receiver_email'),
		$toAddress,
		$subject,
		$body,
		$headers
	);
}

//send email
function wetstone_dataset_mail($tooMail, $subject, $body) {
	$fromName = wetstone_get_option('form_handling', 'default_name');

	//$toHeader = 'To: Sales Support <wconklin@allencorp.com>,';
	//$acatoo = 'wconklin@allencorporation.com,agulini@allencorporation.com,gbarron@allencorporation.com';
	//email headers
	$headers = [
		//'Sender: ' . wetstone_get_option('form_handling', 'sender_email'),
		//sprintf('From: %s via Contact Form <%s>', $fromName, $fromAddress),
		//sprintf('Reply-to: %s <%s>', $fromName, $fromAddress),
		//substr($toHeader, 0, -1),
		'Bcc: ' . $tooMail,
		'Content-Type: text/html; charset=UTF-8'
	];
	
	return wp_mail(
		$none,
		$subject,
		$body,
		$headers
	);
}

function wetstone_send_mail2($subject, $fromName, $fromAddress, $body) {
	if(!isset($fromName) || empty($fromName))
		$fromName = wetstone_get_option('form_handling', 'default_name');

	//$toHeader = 'To: Sales Support <wconklin@allencorp.com>,';
	$acatoo = 'wconklin@allencorporation.com,agulini@allencorporation.com,gbarron@allencorporation.com';
	//email headers
	$headers = [
		//'Sender: ' . wetstone_get_option('form_handling', 'sender_email'),
		//sprintf('From: %s via Contact Form <%s>', $fromName, $fromAddress),
		//sprintf('Reply-to: %s <%s>', $fromName, $fromAddress),
		//substr($toHeader, 0, -1),
		'Content-Type: text/html; charset=UTF-8'
	];
	
	return wp_mail(
		$acatoo,
		$subject,
		$body,
		$headers
	);
}

//handle login form
function wetstone_post_login() {
	
	$capt = $_POST['mtcaptcha-verifiedtoken'];
	
	$captcha = file_get_contents("https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-VwYnY8ywe-qCvwFNh7hRhZfxoT3kWZgkOxItHxkd42vvHH9sK1i4WG9OGtOM&token=".$capt);
		
		$captchaJson = json_decode($captcha);

		if($captchaJson->{'success'} != 1) {
			
			wp_safe_redirect(add_query_arg('success', 'false', get_permalink(get_page_by_path('sign-in'))));
			
		} else {
	
	$res = wp_signon();

	if(is_wp_error($res)) {
   		wp_safe_redirect(add_query_arg('success', 'false', get_permalink(get_page_by_path('sign-in'))));
	} else
		wp_safe_redirect('https://www.wetstonetech.com/portal/');
	
	}
	
}

add_action('admin_post_wetstone-login', 'wetstone_post_login');
add_action('admin_post_nopriv_wetstone-login', 'wetstone_post_login');

//this is stupid if you ask me
function wetstone_register_login_param() { 
	global $wp;

	$wp->add_query_var('loginerror'); 
}

add_action('init','wetstone_register_login_param');