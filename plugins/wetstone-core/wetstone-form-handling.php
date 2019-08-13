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
				
		
		if($data['fname'] == $data['lname']) {
			
			wp_redirect(get_permalink(get_page_by_path('thank-you')));
			
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
	
	///} else { /* the reCAPTCHA answer is wrong or there are some other errors */
	///	echo $check_result; /* display the error message or do other necessary actions in case when the reCAPTCHA test was failed */
	///}
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
			'subject', 'fname', 'lname', 'company', 'phone', 'email'
		]);

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

add_action('admin_post_wetstone-mp-beta', 'wetstone_mp_beta_form');
add_action('admin_post_nopriv_wetstone-mp-beta', 'wetstone_mp_beta_form');

// DEMO SIGNUP FORM -------------------------------------
function wetstone_mp_demo_form() {
	
		if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-mp-demo'))
			return wp_nonce_ays('wetstone-mp-demo');		
		
		//sanitize inputs
		$data = wetstone_sanitize_post([
			'subject', 'fname', 'lname', 'company', 'website', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
			'zip', 'country'
		]);

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

add_action('admin_post_wetstone-mp-demo', 'wetstone_mp_demo_form');
add_action('admin_post_nopriv_wetstone-mp-demo', 'wetstone_mp_demo_form');

//  resell form
function wetstone_post_resell_form() {
	
		//$check_result = apply_filters( 'gglcptch_verify_recaptcha', true, 'string' );
		// if ( true === $check_result ) { /* the reCAPTCHA answer is right */
		//	echo '';
		
		if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-resell-form'))
			return wp_nonce_ays('wetstone-resell-form');

		//sanitize inputs
		$data = wetstone_sanitize_post([
			'subject', 'fname', 'lname', 'company', 'website', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
			'zip', 'country', 'referrer', 'customers', 'marketing', 'comments', 'description', 'territories'
		]);

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
	//	} else { /* the reCAPTCHA answer is wrong or there are some other errors */
	//	echo $check_result; /* display the error message or do other necessary actions in case when the reCAPTCHA test was failed */
	//	}
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
	$data = wetstone_sanitize_post(['product', 'context', 'comments']);

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

	if(wetstone_send_mail($subject, $fullName, $fromMail, wordwrap($body, $emailWidth)))
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

//send email
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
	$res = wp_signon();

	if(is_wp_error($res)) {
   		wp_safe_redirect(add_query_arg('success', 'false', get_permalink(get_page_by_path('sign-in'))));
	} else
		wp_safe_redirect('https://www.wetstonetech.com/portal/');
}

add_action('admin_post_wetstone-login', 'wetstone_post_login');
add_action('admin_post_nopriv_wetstone-login', 'wetstone_post_login');

//this is stupid if you ask me
function wetstone_register_login_param() { 
	global $wp;

	$wp->add_query_var('loginerror'); 
}

add_action('init','wetstone_register_login_param');