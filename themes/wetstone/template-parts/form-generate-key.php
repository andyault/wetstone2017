<?php
	$user = wp_get_current_user();

	$productId = $_GET['view'];

	$products = get_user_meta($user->ID, 'wetstone_products', true);
	$product = $products[$productId];

	$owned = $product['num_owned'];
	$used = $product['num_used'];

	$available = $owned - $used;

	if($available <= 0)
		echo '<div class="text-center">You have ' . $available . ' out of ' . $owned . ' licenses remaining.</div>';
	else { ?>
		<form name="generate-key" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
			<input type="hidden" name="action" value="wetstone-generate-key">
			<?php wp_nonce_field('wetstone-generate-key'); ?>

			<input type="hidden" name="product" value="<?php echo $_GET['view']; ?>">

			<p class="text-center">
				Please enter your product information below.
				<br />
				You have <?php echo $available; ?> out of <?php echo $owned; ?> licenses remaining.
			</p>

			<p>
				<?php
					echo wetstone_form_make_input(
						'regcode',
						'text',
						'Registration Code',
						'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
						true,
						[
							'size' => 32,
							'maxlength' => 32
						]
					);
				?>
			</p>

			<p class="text-right"><button type="submit" class="link link-button">Submit</button></p>
		</form>
	<?php }
