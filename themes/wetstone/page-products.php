<?php
	get_header();

	//does this count as hard coded?
	$postType = 'product';

	$posts = get_posts([
		'post_type'      => $postType,
		'posts_per_page' => -1
	]);
?>

<section id="product-preview" class="product-preview carousel">
	<a href="#" id="product-preview-prev" class="carousel-arrow carousel-arrow-prev"></a>

	<div id="product-preview-slides" class="product-preview-slides carousel-slides">
		<?php
			global $post;

			foreach($posts as $post) {
				setup_postdata($post);

				get_template_part('template-parts/' . $postType, 'preview');
			}
		?>
	</div>
	
	<a href="#" id="product-preview-next" class="carousel-arrow carousel-arrow-next"></a>

	<ul class="carousel-dots">
		<?php
			foreach($posts as $post)
				echo '<li class="carousel-dot"></li>';
		?>
	</ul>
</section>

<?php
	wp_reset_postdata();

	if(get_the_content())
		get_template_part('template-parts/basic', 'page');
?>

<section class="page-posts site-content site-content-padded">
	<h2 class="section-header">
		<?php
			//todo - default to 'our _', allow %s, remove if no value?
			$label = get_post_meta($post->ID, 'page_listlabel', true);

			if($label)
				echo $label;
			else
				echo 'Our ' . get_post_type_object($postType)->labels->name;
		?>
	</h2>

	<div class="page-list flex">
		<?php
			foreach($posts as $post) {
				setup_postdata($post);

				get_template_part('template-parts/' . $postType, 'page');
			}
		?>
	</div>
</section>

<!-- smooth scrolling anchors -->
<script src="<?php echo wetstone_get_asset('/js/smoothscroll.js'); ?>"></script>

<!-- carousel -->
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
</script>

<?php
	get_footer();
?>