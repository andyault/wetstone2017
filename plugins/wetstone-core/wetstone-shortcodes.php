<?php
//carousel
function wetstone_shortcode_carousel($attrs) {
	if(!isset($attrs['ids']) || empty($attrs['ids']))
		return;

	$attachments = get_posts([
		'include' => $attrs['ids'],
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'post_mime_type' => 'image'
	]);

	if(count($attachments)) {
		ob_start();
		?>

		<section id="gallery" class="post-gallery carousel">
			<a href="#" id="post-gallery-prev" class="carousel-arrow carousel-arrow-prev"></a>

			<div id="post-gallery-slides" class="post-gallery-slides carousel-slides">
				<?php
					foreach($attachments as $attachment)
						include(locate_template('template-parts/gallery-preview.php'));
				?>
			</div>
			
			<a href="#" id="post-gallery-next" class="carousel-arrow carousel-arrow-next"></a>

			<ul class="carousel-dots">
				<?php
					foreach($attachments as $attachment)
						echo '<li class="carousel-dot"></li>';
				?>
			</ul>
		</section>

		<div class="post-gallery-clearfix"></div>

		<script src="<?php echo wetstone_get_asset('/js/siema.min.js'); ?>"></script>

		<script>
			var carouselEl = document.getElementById('post-gallery-slides');
			var dots = document.getElementsByClassName('carousel-dot');

			var onChange = function(shouldHash) {
				//highlight dots
				for(var i = 0; i < dots.length; i++) {
					if(i == carousel.currentSlide)
						dots[i].classList.add('active');
					else
						dots[i].classList.remove('active');
				}
			}

			var carousel = new Siema({
				selector: carouselEl,
				duration: 500,
				loop:     true,
				onChange: onChange
			});

			//arrows
			document.getElementById('post-gallery-prev').onclick = function() { carousel.prev(); return false; }
			document.getElementById('post-gallery-next').onclick = function() { carousel.next(); return false; }

			//dots
			for(var i = 0; i < dots.length; i++) {
				dots[i].index = i;

				dots[i].onclick = function() { carousel.goTo(this.index); }
			}

			//init
			onChange(false);

			//auto scroll
			var amt = 0;

			var timer = setInterval(function() {
				//if hovered, pause
				if(carouselEl.parentElement.querySelector(':hover') !== carouselEl)
					amt += 100;

				//otherwise, wait 5s
				if(amt >= 5000) {
					amt = -1000;

					carousel.next();
				}
			}, 100);

			document.getElementById('gallery').onclick = function() { clearInterval(timer); }
		</script>

		<?php
		return ob_get_clean();
	}
}

add_shortcode('wetstone_carousel', 'wetstone_shortcode_carousel');

//adding tabs to posts
function wetstone_shortcode_tabs($attrs, $content = null) {
	if(empty($content)) return;

	//can't use has_shortcode
	if(strpos($content, '[wetstone_tab') == false) return;

	//here we go
	ob_start();
	
	$nameClass = isset($attrs['name']) ? ('-' . $attrs['name']) : '';
	$first = true;

	$tabbed = preg_replace_callback(
		'/\[wetstone_tab\s+name="([^"]*)"[^\]]*\]/i',
		function($matches) use (&$first) {
			ob_start();

			$name = $matches[1];
			$id = strtolower(preg_replace('/[^\w\s]/', '', implode('-', explode(' ', $name))));

			if(!$first)
				echo '</div>';
			?>

			<input type="radio"
			       name="tab-input<?php echo $nameClass; ?>"
			       id="tab-input<?php echo $nameClass . '-' . $id; ?>"
			       class="tab-input"
			       <?php
			       		if(isset($_GET['keygen'])) {
			       			if(strpos(strtolower($name), 'gen') !== false)
			       				echo 'checked';
			       		} elseif($first)
			       			echo'checked';

			       		$first = false;
			       	?>>

			<label for="tab-input<?php echo $nameClass . '-' . $id; ?>" class="tab-label">
				<?php echo $name; ?>
			</label>

			<div class="tab-content body-content">

			<?php

			return ob_get_clean();
		},
		$content
	);

	?>

	<div class="tabbed">
		<?php echo do_shortcode($tabbed); ?>
		</div>
	</div>

	<?php

	return ob_get_clean();
}

add_shortcode('wetstone_tabs', 'wetstone_shortcode_tabs');

//generating keys
wetstone_add_option('key_generation', 'to_emails', 'licensekeyRequest@wetstonetech.com');

function wetstone_shortcode_gen_key_form($attrs) {
	ob_start();
	
	include(get_template_directory() . '/generate-key.php');

	return ob_get_clean();
}

add_shortcode('wetstone_gen_key_form', 'wetstone_shortcode_gen_key_form');

//add excerpts to posts
function wetstone_add_page_excerpts() {
	add_post_type_support('page', 'excerpt');
}

add_action('init', 'wetstone_add_page_excerpts');

//Pull data from datasets table
function getResults($query) {
	global $wpdb;
	$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'datasets WHERE dataset_name=\''.$query.'\'');
	return $result;
}

//Inserts data into datasets table
function insertDataset($id, $direct, $file) {
	global $wpdb;
	$wpdb->insert(
				$wpdb->prefix.'datasets', 
				array(
					'dataset_id' => '',
					'product_id' => $id,
					'dataset_name' => $file,
					'md5_hash' =>  md5_file('protected/'.$direct.'/'.$file),
					'date_added' => date("Y-m-d H:i:s")
				)
			);
}

//add Dataset info
add_shortcode("GDS", "display_GDS");
 
function display_GDS($atr){
	ob_start();
		$productID = 116;
		$productDir = 'Gargoyle Investigator';
        $dataset = $productDir.'/Dataset Updates';
		$pdfEng = $productDir.'/Release Notes';
		$pdfSpa = $productDir.'/Release Notes - Spanish';
		$hashes = $productDir.'/Supplemental Gargoyle Hashes';

		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
		$hashFiles = scan_dir("protected/".$hashes);		
		
		$result0 = getResults($hashFiles[0]);
		
		if (!$result0) {
				insertDataset($productID, $hashes, $hashFiles[0]);
			}
		$result0 = getResults($hashFiles[0]);
		
		$aca_table = "";
		$aca_table .= '<table style="border: 1px solid black">';
		$aca_table .= '<tr style="border: 1px solid black">';
		$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">Supplemental Gargoyle Hashes</th>';
		$aca_table .= '</tr>';
		$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
		$aca_table .= '<a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
		$aca_table .= '<td style="padding-left:7px; width:475px; height:25px;"><a href="../../product-dl.php?asset='. $result0[0]->dataset_id .'&file='.urlencode($hashes.'/'.$hashFiles[0]).'" target="_blank" style="text-decoration:none; color:green;">Hash File</a>';
		$aca_table .= '<br /><span style="font-size:12px">MD5 - ' . $result0[0]->md5_hash .'</span>';
		$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td>';
		$aca_table .= '</tr>';
		$aca_table .= '</table>';
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}



add_shortcode("GMP", "display_GMP");
 
function display_GMP($atr){
	ob_start();
		global $wpdb;
		$productID = 633;
		$productDir = 'GargoyleMP';
        $dataset = $productDir.'/Dataset Updates/7_2';
		$pdfEng = $productDir.'/Release Notes';
		$pdfSpa = $productDir.'/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
				
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

add_shortcode("GMP71", "display_GMP71");
 
function display_GMP71($atr){
	ob_start();
		$productID = 633;
		$productDir = 'GargoyleMP';
        $dataset = $productDir.'/Dataset Updates/7_1';
		$pdfEng = $productDir.'/Release Notes';
		$pdfSpa = $productDir.'/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
				
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

//add Dataset info
add_shortcode("GMPF", "display_GMPF");
 
function display_GMPF($atr){
	ob_start();
		$productID = 634;
		$productDir = 'GargoyleMP Flash';
        $dataset = 'GargoyleMP/Dataset Updates/7_2';
		$pdfEng = 'GargoyleMP/Release Notes';
		$pdfSpa = 'GargoyleMP/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
				
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

//add Dataset info
add_shortcode("GMPF71", "display_GMPF71");
 
function display_GMPF71($atr){
	ob_start();
		$productID = 634;
		$productDir = 'GargoyleMP Flash';
        $dataset = 'GargoyleMP/Dataset Updates/7_1';
		$pdfEng = 'GargoyleMP/Release Notes';
		$pdfSpa = 'GargoyleMP/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
				
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

add_shortcode("STEGMP", "display_STEGMP");

function display_STEGMP($atr){
	ob_start();
		$productID = 1667;
		$productDir = 'StegoHuntMP';
        $dataset = $productDir.'/Dataset Updates';
		$pdfEng = $productDir.'/Release Notes';
		$pdfSpa = $productDir.'/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
		
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 1; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}


add_shortcode("STEG", "display_STEG");

function display_STEG($atr){
	ob_start();
		$productID = 115;
		$productDir = 'StegoHunt';
        $dataset = $productDir.'/Dataset Updates';
		$pdfEng = $productDir.'/Release Notes';
		$pdfSpa = $productDir.'/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
		
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

add_shortcode("DISC", "display_DISC");

function display_DISC($atr){
	ob_start();
		$productID = 123;
		$productDir = 'Discover the Hidden';
        $dataset = $productDir.'/Dataset Updates';
		$pdfEng = $productDir.'/Release Notes';
		$pdfSpa = $productDir.'/Release Notes - Spanish';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		$pdfEngFiles = scan_dir("protected/".$pdfEng);
		$pdfSpaFiles = scan_dir("protected/".$pdfSpa);
		
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			if (!$result2) {
				insertDataset($productID, $pdfEng, $pdfEngFiles[$aca_i]);
			}
			if (!$result3) {
				insertDataset($productID, $pdfSpa, $pdfSpaFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);
			$result2 = getResults($pdfEngFiles[$aca_i]);
			$result3 = getResults($pdfSpaFiles[$aca_i]);			
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result2[0]->dataset_id .'&file='.urlencode($pdfEng.'/'.$pdfEngFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result2[0]->md5_hash .'</span>';
			$aca_table .= '<hr /></td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result3[0]->dataset_id .'&file='.urlencode($pdfSpa.'/'.$pdfSpaFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result3[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/pdf-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

add_shortcode("IDEAL", "display_IDEAL");

function display_IDEAL($atr){
	ob_start();
		$productID = 614;
		$productDir = 'IDEAL';
        $dataset = $productDir.'/Dataset Updates';
				
		$datasetFiles = scan_dir("protected/".$dataset);
		
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);	
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}


add_shortcode("GDD", "display_GDD");

function display_GDD($atr){
	ob_start();
		$productID = 621;
		$productDir = 'GD Datasets';
        $dataset = $productDir;
				
		$datasetFiles = scan_dir("protected/".$dataset);
		
		$aca_table = "";		
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);			
			$result = getResults($datasetFiles[$aca_i]);
			
			if (!$result) {
				insertDataset($productID, $dataset, $datasetFiles[$aca_i]);
			}
			
			$result = getResults($datasetFiles[$aca_i]);	
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black;">';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<th colspan="2" style="padding-left:7px; font-size: 16px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black; font-size: 16px;">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="../../product-dl.php?asset='. $result[0]->dataset_id .'&file='.urlencode($dataset.'/'.$datasetFiles[$aca_i]).'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a>';
			$aca_table .= '<br /><span style="font-size:12px">MD5 - ' .  $result[0]->md5_hash .'</span>';
			$aca_table .= '</td><td><img src="https://www.wetstonetech.com/wp-content/uploads/2017/11/zip-icon.png" /></td></tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}
	return ob_get_clean();
}

function scan_dir($dir) {
    $ignored = array('.', '..','.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}