<?php

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

		<section id="gallery" class="post-gallery carousel section-invert">
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
		</script>

		<?php
		return ob_get_clean();
	}
}

add_shortcode('wetstone_carousel', 'wetstone_shortcode_carousel');

//add excerpts to posts
function wetstone_add_page_excerpts() {
	add_post_type_support('page', 'excerpt');
}

add_action('init', 'wetstone_add_page_excerpts');