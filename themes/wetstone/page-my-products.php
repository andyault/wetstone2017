<?php
	get_header();
	wp_reset_postdata();

	$baseurl = get_the_permalink();

	$user = wp_get_current_user();
	$products = get_user_meta($user->ID, 'wetstone_products', true);

	//allow viewing of single product
	$view = $_GET['view'];
	
	function getDataset($id, $type) {
		global $wpdb;
		//$wpdb->show_errors();
		$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets WHERE product_id=\''.$id.'\' AND dataset_name LIKE \'%'.$type.'\' ORDER BY date_added DESC');
		//$wpdb->print_error();
		return $result;
	}
	
	function getFile($id) {
		global $wpdb;
		//$wpdb->show_errors();
		$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets WHERE dataset_id=\''.$id.'\'');
		//$wpdb->print_error();
		return $result;
	}
	
	function getDownload($id, $id2) {
		global $wpdb;
		//$wpdb->show_errors();
		$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets_log WHERE user_id=\''.$id.'\' AND asset_id=\''.$id2.'\' ORDER BY download_date DESC LIMIT 1');
		//$wpdb->print_error();
		return $result;
	}
	
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

								if ($id == 1815) { 	
								echo "<p style='color:red'>Your contract expired on ". date('F d, Y', strtotime($products[$id]['expiry'])) .". To renew, please contact <a href=\"mailto:sales@wetstonetech.com\" class=\"link link-body\">sales@wetstonetech.com</a>.";
								echo "<br /><br /><hr />";
								} else {
								echo "<p style='color:red'>Your license for <strong>" . get_the_title($id). "</strong> expired on ". date('F d, Y', strtotime($products[$id]['expiry'])) .". To renew, please contact <a href=\"mailto:sales@wetstonetech.com\" class=\"link link-body\">sales@wetstonetech.com</a>.";
								echo "<br /><br /><hr />";	
								}
							
							
							} else {
								
							$start = time();
							$end = strtotime($products[$id]['expiry']);
							$days_between = ceil(abs($end - $start) / 86400);	
							
							get_template_part('template-parts/my-product', 'list');			
							
							if ($id == 1815) { 							
							echo "Contract Expiration: " . date('F d, Y', strtotime($products[$id]['expiry']));
							echo "<br /> Status Level: <strong>" . $products[$id]['license_type'] . "</strong><br />";
							} 
							else {
							echo "License Expiration: " . date('F d, Y', strtotime($products[$id]['expiry']));
							}
							
							if ($days_between <= 90) {
								
								if ($id == 1815) { 									
								echo "<span style='color:red'>Your contract expires in <strong>\"". $days_between . " day(s)\"</strong>. To renew, please contact <a href=\"mailto:sales@wetstonetech.com\" class=\"link link-body\">sales@wetstonetech.com</a>.</span>"; 
								} else {
								echo "<span style='color:red'> - Your license for <strong>" . get_the_title($id) . "</strong> expires in <strong>\"". $days_between . " day(s)\"</strong>. To renew, please contact <a href=\"mailto:sales@wetstonetech.com\" class=\"link link-body\">sales@wetstonetech.com</a>.</span>"; 
								}
								}		
							
							echo "<br /><br />";
							
							if ($id == 633 || $id == 634 || $id == 1054) 
							{							
								$result2 = getDataset(633, 'zip');
								$dataArray = array();
								
								foreach ( $result2 as $page )
									{									
										$exploder = explode("_", $page->dataset_name);
										$dataArray[] = array(strtotime($exploder[1] .' '. $exploder[0]), $page->dataset_name, $page->dataset_id, $page->date_added);										 
									} 
									
								rsort($dataArray);							
								echo '<strong>Latest Datasets</strong><br />';
								
								echo $dataArray[0][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[0][3]))) . '<br />';
								echo $dataArray[1][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[1][3]))) . '<br /><br />';
								
								$download1 = getDownload($user->ID,$dataArray[0][2]);
								$download2 = getDownload($user->ID,$dataArray[1][2]);
								
								if (!$download1 && !$download2) {
									echo '<strong>Please select the "View Product" button to download the most recent dataset.</strong>';
								} else {				
									
									echo '<strong>Last Downloaded Dataset </strong><br />';
									if ($download1) {
										$theFile = getFile($download1[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download1[0]->download_date))) .'<br />';									
									}
									if ($download2) {
										$theFile = getFile($download2[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download2[0]->download_date)));
									}
								}

							}					
							
							if ($id == 613 || $id == 116) 
							{							
								$result2 = getDataset(116, 'cab');
								$dataArray = array();
								
								foreach ( $result2 as $page )
									{									
										$exploder = explode("_", $page->dataset_name);
										$dataArray[] = array(strtotime($exploder[1] .' '. $exploder[0]), $page->dataset_name, $page->dataset_id, $page->date_added);										 
									} 
									
								rsort($dataArray);							
								echo '<strong>Latest Dataset</strong><br />';
								
								echo $dataArray[0][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[0][3]))) . '<br /><br />';
								
								$download1 = getDownload($user->ID,$dataArray[0][2]);
								
								if (!$download1) {
									echo '<strong>Please select the "View Product" button to download the most recent dataset.</strong>';
								} else {				
									
									echo '<strong>Last Downloaded Dataset </strong><br />';
									if ($download1) {
										$theFile = getFile($download1[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download1[0]->download_date))) .'<br />';									
									}
								}
							}
							
							if ($id == 612 || $id == 115) 
							{							
								$result2 = getDataset(115, 'cab');
								$dataArray = array();
								
								foreach ( $result2 as $page )
									{									
										$exploder = explode("_", $page->dataset_name);
										$dataArray[] = array(strtotime($exploder[1] .' '. $exploder[0]), $page->dataset_name, $page->dataset_id, $page->date_added);										 
									} 
									
								rsort($dataArray);							
								echo '<strong>Latest Dataset</strong><br />';
								
								echo $dataArray[0][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[0][3]))) . '<br /><br />';
								
								$download1 = getDownload($user->ID,$dataArray[0][2]);
								
								if (!$download1) {
									echo '<strong>Please select the "View Product" button to download the most recent dataset.</strong>';
								} else {				
									
									echo '<strong>Last Downloaded Dataset </strong><br />';
									if ($download1) {
										$theFile = getFile($download1[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download1[0]->download_date))) .'<br />';									
									}
								}
							}
							
							if ($id == 123) 
							{							
								$result2 = getDataset(123, 'cab');
								$dataArray = array();
								
								foreach ( $result2 as $page )
									{									
										$exploder = explode("_", $page->dataset_name);
										$dataArray[] = array(strtotime($exploder[1] .' '. $exploder[0]), $page->dataset_name, $page->dataset_id, $page->date_added);										 
									} 
									
								rsort($dataArray);							
								echo '<strong>Latest Dataset</strong><br />';
								
								echo $dataArray[0][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[0][3]))) . '<br /><br />';
								
								$download1 = getDownload($user->ID,$dataArray[0][2]);
								
								if (!$download1) {
									echo '<strong>Please select the "View Product" button to download the most recent dataset.</strong>';
								} else {				
									
									echo '<strong>Last Downloaded Dataset </strong><br />';
									if ($download1) {
										$theFile = getFile($download1[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download1[0]->download_date))) .'<br />';									
									}
								}
							}
							
							if ($id == 614) 
							{							
								$result2 = getDataset(614, 'zip');
								
								$dataArray = array();
								foreach ( $result2 as $page )
									{									
										$exploder = explode("_", $page->dataset_name);
										$dataArray[] = array(strtotime($exploder[1] .' '. $exploder[0]), $page->dataset_name, $page->dataset_id, $page->date_added);										 
									} 
									
								rsort($dataArray);							
								echo '<strong>Latest Dataset</strong><br />';
								
								echo $dataArray[0][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[0][3]))) . '<br /><br />';
								
								$download1 = getDownload($user->ID,$dataArray[0][2]);
								
								if (!$download1) {
									echo '<strong>Please select the "View Product" button to download the most recent dataset.</strong>';
								} else {				
									
									echo '<strong>Last Downloaded Dataset </strong><br />';
									if ($download1) {
										$theFile = getFile($download1[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download1[0]->download_date))) .'<br />';									
									}
								}
							}
							
							if ($id == 621) 
							{							
								$result2 = getDataset(621, 'zip');
								$dataArray = array();
								foreach ( $result2 as $page )
									{									
										$exploder = explode("-", $page->dataset_name);
										$dataArray[] = array(strtotime($exploder[1] .' '. $exploder[0]), $page->dataset_name, $page->dataset_id, $page->date_added);										 
									} 
									
								rsort($dataArray);							
								echo '<strong>Latest Dataset</strong><br />';
								
								echo $dataArray[0][1] . ' - Added: ' . date("m-d-Y",(strtotime($dataArray[0][3]))) . '<br /><br />';
								
								$download1 = getDownload($user->ID,$dataArray[0][2]);
								
								if (!$download1) {
									echo '<strong>Please select the "View Product" button to download the most recent dataset.</strong>';
								} else {				
									
									echo '<strong>Last Downloaded Dataset </strong><br />';
									if ($download1) {
										$theFile = getFile($download1[0]->asset_id);
										echo $theFile[0]->dataset_name . ' - Downloaded On: '. date("m-d-Y",(strtotime($download1[0]->download_date))) .'<br />';									
									}
								}
							}
							
						}
							
							echo "<br /><br /><hr />";
							}
						
					} else {
					echo '<p class="text-center">You don\'t have any products yet.</p>'; }
				?>
			</ul>
			
		</section>
	<?php }

	get_footer();
?>