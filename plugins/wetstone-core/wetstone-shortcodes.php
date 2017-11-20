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


//add Dataset info
add_shortcode("GDS", "display_GDS");
 
function display_GDS($atr){
	
        $dataset = '../../protected';
		$pdfEng = '../../protected';
		$pdfSpa = '../../protected';
		$hashes = '../../protected';
		
		$datasetFiles = scandir($dataset);
		$pdfEngFiles = scandir($pdfEng);
		$pdfSpaFiles = scandir($pdfSpa);
		$hashFiles = scandir($hashes);		
		
		$aca_table = "";
		$aca_table .= '<table style="border: 1px solid black">';
		$aca_table .= '<tr style="border: 1px solid black">';
		$aca_table .= '<th colspan="2" style="padding-left:7px;">Supplemental Gargoyle Hashes</th>';
		$aca_table .= '</tr>';
		$aca_table .= '<tr style="border: 1px solid black">';
		$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$hashes.'/'.$hashFiles[0].'" target="_blank" style="text-decoration:none; color:green;">Hash File</a></td><td><img src="http://104.131.53.0/wp-content/uploads/2017/11/zip-icon.png" /></td>';
		$aca_table .= '</tr>';
		$aca_table .= '</table>';
		
		echo $aca_table;
		
		for ($aca_i = 2; $aca_i < 5; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black">';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<th colspan="2" style="padding-left:7px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$dataset.'/'.$datasetFiles[$aca_i].'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a></td><td><img src="http://104.131.53.0/wp-content/uploads/2017/11/cab-icon.png" /></td>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$pdfEng.'/'.$pdfEngFiles[$aca_i].'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a></td><td><img src="http://104.131.53.0/wp-content/uploads/2017/11/pdf-icon.png" /></td>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$pdfSpa.'/'.$pdfSpaFiles[$aca_i].'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a></td><td><img src="http://104.131.53.0/wp-content/uploads/2017/11/pdf-icon.png" /></td>';
			$aca_table .= '</tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}

}