<?php
	get_header();
	wp_reset_postdata();

	$baseurl = get_the_permalink();

	$user = wp_get_current_user();
	$products = get_user_meta($user->ID, 'wetstone_products', true);

	//allow viewing of single product
	$view = $_GET['view'];

	if(isset($view) && isset($products[$view])) {
		$post = get_post($view);
		setup_postdata($post);

		get_template_part('template-parts/my-product', 'single');

		?>

		<section class="single-footer site-content site-content-padded">
			<?php
				//have to get prev/next links weird
				//set current to our product
				while(key($products) != $view) next($products);

				$cur = key($products);

				//if prev was false then we were at the first elem anyway
				if(prev($products)) {
					$prev = get_post(key($products));

					next($products);
				} else
					reset($products);

				if(next($products))
					$next = get_post(key($products));
				
				//show links
				if(!empty($prev)) {
					echo sprintf(
						'<a href="%s" class="link link-body"><i class="larr"></i> %s</a>',

						add_query_arg('view', $prev->ID),
						get_the_title($prev)
					);
				}

				//fix for flex alignment
				echo '<span>&nbsp;</span>';

				//for posts - back to posts button
				//for products/services - inquire button
				echo sprintf('<a href="' . $baseurl . '" class="single-inquire link link-button">Back to My Products</a>');

				if(!empty($next)) {
					echo sprintf(
						'<a href="%s" class="link link-body">%s <i class="rarr"></i></a>',

						add_query_arg('view', $next->ID),
						get_the_title($next)
					);
				}
			?>
		</section>

		<?php
	} else { ?>
		<section class="site-content site-content-small site-content-padded">
			<h2 class="section-header"><?php the_title(); ?></h2>

			<ul class="myproducts-list list-basic">
				<?php
					if($products) {
						foreach($products as $id => $info) {
							$post = get_post($id);
							setup_postdata($post);
							
							if (strtotime($products[$id]['expiry']) < time()) {
								
								echo "<p style='color:red'>Your license for <strong>" . get_the_title($id). "</strong> expired on ". date('F d, Y', strtotime($products[$id]['expiry'])) .". To renew, please contact <a href=\"mailto:sales@wetstonetech.com\" class=\"link link-body\">sales@wetstonetech.com</a>.";						
							} else {
								
							$start = time();
							$end = strtotime($products[$id]['expiry']);
							$days_between = ceil(abs($end - $start) / 86400);	
							
							get_template_part('template-parts/my-product', 'list');			
							
							echo "License Expiration: " . date('F d, Y', strtotime($products[$id]['expiry']));
							
							if ($days_between <= 90) {
								echo "<span style='color:red'> - Your license for <strong>" . get_the_title($id) . "</strong> expires in <strong>\"". $days_between . " day(s)\"</strong>. To renew, please contact <a href=\"mailto:sales@wetstonetech.com\" class=\"link link-body\">sales@wetstonetech.com</a>.</span>"; 
								}
							
							echo "<br /><br />";
							}
						}
					} else
						echo '<p class="text-center">You don\'t have any products yet.</p>';
				?>
			</ul>
			
		</section>
	<?php }

	get_footer();
?>