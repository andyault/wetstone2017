<?php
	$isPOST = $_SERVER['REQUEST_METHOD'] == 'POST';

	if($isPOST) {
		//get data from post
		$product = $_POST['product'];
		$post = get_post($product);

		//get user
		$user = wp_get_current_user();
		$fullname = $user->user_firstname . ' ' . $user->user_lastname;

		//see how many licenses we have
		$info = get_user_meta($user->ID, 'wetstone_products', true);
		$productinfo = &$info[$product];

		if(!$productinfo || $productinfo['num_used'] >= $productinfo['num_owned'])
			wp_die('You\'re not allowed to generate a license for that product.');

		//extra info
		$adminemail = get_option('wetstone_key_generation_to_emails');
		$date = date('Y-m-d G:i:s');
		
		////
		$productCode = substr($post->post_title,0, -3);
		$productID = $post->ID;
		if ($productID == 633) {
			$productCode = 'GargoyleMP';
			$adminemail = 'support@wetstonetech.com';			
		}
		if ($productID == 634) {
			$productCode = 'GargoyleMPFlash';
			$adminemail = 'support@wetstonetech.com';			
		}
		////
		
		//var_dump($adminemail);

$subject = 'WetStoneTech.com - ' . $productCode . ' License Key';

		$headers = array('Content-Type: text/plain; charset=iso-8859-1; Content-Transfer-Encoding: 7bit');
		$body = "\n". "Date/Time: " . $date . "\t\n";
		$body .= "Customer Name: " . $fullname . "\t\n";
		$body .= "Customer Email: " . $user->user_email . "\t\n";
		$body .= "Product Code: " . $productCode . "\t\n";
		$body .= "Registration Code: " . $_POST['regcode'] . PHP_EOL;		

		//try to send
		if(wp_mail($adminemail, $subject, $body, $headers)) {
			//if it sent, assume it worked and add one to our license count
			$productinfo['num_used']++;

			update_user_meta($user->ID, 'wetstone_products', $info);

			wp_redirect(add_query_arg('keygen', 'true', $_POST['_wp_http_referer']));
			exit;
		} else
			echo '<div class="text-center" style="line-height: 1.5em;"><strong>ERROR</strong>: Unable to send email.</div>';
	} elseif($_GET['keygen']) {
		echo '<div class="text-center"><strong>Thank you!</strong><br />Your license key will be sent shortly.</div>';
		return;
	}
	
	//load page
	get_template_part('template-parts/form', 'generate-key');
