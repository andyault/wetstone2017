<?php
	get_header();

	//does this count as hard coded?
	$postType = 'product';

	$posts = get_posts([
		'post_type'      => $postType,
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC'
	]);
?>

<section id="product-preview" class="product-preview carousel section-invert">
	<a href="#" id="product-preview-prev" class="carousel-arrow carousel-arrow-prev"></a>

	<div id="product-preview-slides" class="product-preview-slides carousel-slides">
		<?php
			global $post;

			foreach($posts as $post) {
				setup_postdata($post);
				if($post->ID >= 612 && $post->ID <= 633) { // IDs of unwanted products
				} else {
					get_template_part('template-parts/' . $postType, 'preview');
				}
			}
		?>
	</div>
	
	<a href="#" id="product-preview-next" class="carousel-arrow carousel-arrow-next"></a>

	<ul class="carousel-dots">
		<?php
			foreach($posts as $post)
				if($post->ID >= 612 && $post->ID <= 633) { // IDs of unwanted products
					} else {
				echo '<li class="carousel-dot"></li>';
					}
		?>
	</ul>
</section>

<section class="page-posts site-content site-content-padded">
	<?php
		//group products by category
		$grouped = wetstone_group_by_cat($posts);

		$other = wetstone_pop_value($grouped, 'Uncategorized');
		$grouped['Other Products'] = $other;

		//for each cat, spit out a header and our products
		foreach($grouped as $group => $posts) { ?>
			<h2 class="section-header"><?php echo $group; ?></h2>

			<div class="page-list flex flex-center">
				<?php
					foreach($posts as $post) {
						setup_postdata($post);
						if($post->ID >= 612 && $post->ID <= 633) { // IDs of unwanted products
						} else {
							get_template_part('template-parts/' . $postType, 'page');
						}
					}
				?>
			</div>
		<?php }
	?>
</section>

<!-- smooth scrolling anchors, carousel -->
<script src="<?php echo wetstone_get_asset('/js/smoothscroll.js'); ?>"></script>
<script src="<?php echo wetstone_get_asset('/js/siema.min.js'); ?>"></script>

<script>
	var carouselEl = document.getElementById('product-preview-slides');
	var dots = document.getElementsByClassName('carousel-dot');
	var btns = document.getElementsByClassName('product-page-button-view');

	var onChange = function(shouldHash) {
		//highlight dots
		for(var i = 0; i < dots.length; i++) {
			if(i == carousel.currentSlide)
				dots[i].classList.add('active');
			else
				dots[i].classList.remove('active');
		}

		if(shouldHash !== false) {
			var newSlide = carousel.innerElements[carousel.currentSlide];

			var newHash = newSlide.id;
			newSlide.id = undefined;
			location.replace('#' + newHash);
			newSlide.id = newHash;
		}
	}

	var carousel = new Siema({
		selector: carouselEl,
		duration: 500,
		loop:     true,
		onChange: onChange
	});

	//arrows
	document.getElementById('product-preview-prev').onclick = function() { carousel.prev(); return false; }
	document.getElementById('product-preview-next').onclick = function() { carousel.next(); return false; }

	//dots
	for(var i = 0; i < dots.length; i++) {
		dots[i].index = i;

		dots[i].onclick = function() { carousel.goTo(this.index); }
	}

	//'quick view' buttons
	for(var i = 0; i < btns.length; i++) {
		btns[i].index = i;

		btns[i].onclick = function() {
			carousel.goTo(this.index);

			window.scroll({top: 0, left: 0, behavior: 'smooth'});
		}
	}

	//init
	if(location.hash) {
		var id = location.hash.substr(1);
		var elem = document.getElementById(id);
		var slide = elem.parentElement;

		var i = 0;

		while((slide = slide.previousSibling) != null)
			i++;

		if(i > 0)
			carousel.goTo(i);
		else
			onChange(false);
	} else
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

	document.getElementById('product-preview').onclick = function() { clearInterval(timer); }
</script>

<?php
	get_footer();
?>