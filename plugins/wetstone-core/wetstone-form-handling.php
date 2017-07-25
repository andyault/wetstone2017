<?php

//add options
wetstone_add_option('form_handling', 'email_width',    80);
wetstone_add_option('form_handling', 'sender_email',   'noreply@wetstonetech.com');
wetstone_add_option('form_handling', 'receiver_email', 'aault@allencorp.com');
wetstone_add_option('form_handling', 'default_name',   'WetStone Customer');

//handle post
function wetstone_post_contact_form() {
	if(!wp_verify_nonce($_POST['_wpnonce'], 'wetstone-contact-form'))
		return wp_nonce_ays('wetstone-contact-form');

	$emailWidth = wetstone_get_option('form_handling', 'email_width');

	//sanitize inputs
	$data = wetstone_sanitize_post([
		'subject', 'fname', 'lname', 'title', 'company', 'phone', 'email', 'address1', 'address2', 'city', 'state', 
		'zip', 'country', 'referrer', 'interests', 'comments'
	]);

	//turn into pretty table
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

	var_dump(wetstone_send_mail(
		$subject,
		$data['fname'] . ' ' . $data['lname'],
		$data['email'],
		wordwrap($fields, $emailWidth)
	));
}

add_action('admin_post_wetstone-contact-form', 'wetstone_post_contact_form');
add_action('admin_post_nopriv_wetstone-contact-form', 'wetstone_post_contact_form');

//send email
function wetstone_send_mail($subject, $fromName, $fromAddress, $body) {
	if(!isset($fromName) || empty($fromName))
		$fromName = wetstone_get_option('form_handling', 'default_name');

	return wp_mail(
		wetstone_get_option('form_handling', 'receiver_email'),
		$subject,
		$body,
		[
			'Sender: ' . wetstone_get_option('form_handling', 'sender_email'),
			sprintf('From: %s via Contact Form <%s>', $fromName, $fromAddress),
			sprintf('Reply-to: %s <%s>', $fromName, $fromAddress),
			sprintf('To: Sales Support <%s>', wetstone_get_option('form_handling', 'receiver_email')),
			'Content-type: text/html'
		]
	);
}