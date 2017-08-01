<?php

//add options
wetstone_add_option('form_handling', 'email_width',    75);
wetstone_add_option('form_handling', 'sender_email',   'noreply@wetstonetech.com');
wetstone_add_option('form_handling', 'receiver_email', 'aault@allencorp.com');
wetstone_add_option('form_handling', 'default_name',   'WetStone Customer');

//handle post
//  contact
function wetstone_post_contact_form() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-contact-form'))
		return wp_nonce_ays('wetstone-contact-form');

	//sanitize inputs
	$data = wetstone_sanitize_post([
		'subject', 'fname', 'lname', 'title', 'company', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
		'zip', 'country', 'referrer', 'interests', 'comments'
	]);

	//turn into pretty table
	$emailWidth = wetstone_get_option('form_handling', 'email_width');

	$len = min(max(array_map('mb_strlen', array_flip($data))), floor($emailWidth / 2));

	$comments = wetstone_pop_value($data, 'comments');
	$subject = wetstone_pop_value($data, 'subject');

	$fields = '<pre>';

	foreach($data as $name => $value) {
		$fields .= $name . ': ';
		$fields .= str_repeat(' ', max($len - mb_strlen($name), 0));
		$fields .= $value . "\n";
	}

	if(!empty($comments))
		$fields .= "\ncomments: \n</pre><p>" . htmlspecialchars($comments) . '</p>';

	$fullName = $data['fname'] . ' ' . $data['lname'];

	if(wetstone_send_mail($subject, $fullName, $data['email'], wordwrap($fields, $emailWidth))) {
		error_log(get_permalink(get_page_by_path('thank-you')));

		wp_redirect(get_permalink(get_page_by_path('thank-you')));
	} else {
		//unpop comments
		$data['comments'] = $comments;

		//add error
		$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

		//go back to form with old data
		wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
	}
}

add_action('admin_post_wetstone-contact-form', 'wetstone_post_contact_form');
add_action('admin_post_nopriv_wetstone-contact-form', 'wetstone_post_contact_form');

//  resell form
function wetstone_post_resell_form() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-resell-form'))
		return wp_nonce_ays('wetstone-resell-form');

	//sanitize inputs
	$data = wetstone_sanitize_post([
		'subject', 'fname', 'lname', 'company', 'website', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
		'zip', 'country', 'referrer', 'customers', 'marketing', 'description', 'territories'
	]);

	//turn into pretty table
	$emailWidth = wetstone_get_option('form_handling', 'email_width');

	$len = min(max(array_map('mb_strlen', array_flip($data))), floor($emailWidth / 2));

	$subject = wetstone_pop_value($data, 'subject');

	$fields = '<pre>';

	foreach($data as $name => $value) {
		$fields .= $name . ': ';
		$fields .= str_repeat(' ', max($len - mb_strlen($name), 0));
		$fields .= $value . "\n";
	}

	$fullName = $data['fname'] . ' ' . $data['lname'];

	if(wetstone_send_mail($subject, $fullName, $data['email'], wordwrap($fields, $emailWidth))) {
		wp_redirect(get_permalink(get_page_by_path('thank-you')));
	} else {
		//add error
		$data['errmsg'] = 'Unable to send email - possible server error. Please wait and try again.';

		//go back to form with old data
		wp_safe_redirect(wp_get_referer() . '?' . http_build_query($data));
	}
}

add_action('admin_post_wetstone-resell-form', 'wetstone_post_resell_form');
add_action('admin_post_nopriv_wetstone-resell-form', 'wetstone_post_resell_form');

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
		'Sender: ' . wetstone_get_option('form_handling', 'sender_email'),
		sprintf('From: %s via Contact Form <%s>', $fromName, $fromAddress),
		sprintf('Reply-to: %s <%s>', $fromName, $fromAddress),
		//substr($toHeader, 0, -1),
		'Content-type: text/html'
	];
	
	return wp_mail(
		wetstone_get_option('form_handling', 'receiver_email'),
		$subject,
		$body,
		$headers
	);
}