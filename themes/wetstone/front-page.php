<?php
	get_header();

	$err = 'Your browser does not support the video tag, please update your browser to view content.';
?>

<section class="hero fullpage fullpage-hero">
	<div class="hero-background">
		<div class="video-container">
			<!--<div class="video-filter"></div>

			<video id="hero-video" class="video-video" autoplay loop>
				<source src="<?php //echo wetstone_get_asset('/img/background/Love-Coding.mp4'); ?>" type="video/mp4" /><?php //echo $err; ?>
				<source src="<?php //echo wetstone_get_asset('/img/background/Love-Coding.webm'); ?>" type="video/webm" /><?php //echo $err; ?>
			</video>-->

			<div id="hero-poster" class="video-poster hidden"
			     style="background-image: url(<?php echo wetstone_get_asset('/img/background/solid.png'); ?>);">
				 <video width="720" height="480" controls>
					  <source src="<?php echo wetstone_get_asset('/img/background/Gargoyle_MP_Trial.mp4'); ?>" type="video/mp4" /><?php echo $err; ?>
				</video>  
				 
			</div>
		</div>
	</div>

	<div class="hero-info">
		 <!--<img class="hero-logo" src="<?php //echo wetstone_get_asset('/img/biglogo.svg'); ?>" alt="WetStone Technologies: A division of Allen Corporation">-->



		<!--<p class="hero-desc"><?php //echo get_bloginfo('description'); ?></p> -->
		<p class="hero-desc" style="margin:500px 0px 0px 0px; width:550px;">
		<span style="font-size: 24px; line-height: 150%;"><strong>Our NEW Multi-Platform Malware Discovery Tool</strong></span><br />
		<span style="font-size:36px; line-height: 150%; font-family:wetstone; color:#1fb04c"><a class="link link-body" href="https://www.wetstonetech.com/products/gargoyle-mp/">Gargoyle Investigator&trade; MP</a></span><br />
		<span style="font-size: 24px; line-height: 150%"><strong>Available Now!</strong></span>
		</p>
	</div>

	<div class="hero-scroll box-center">scroll down</div>
</section>

<section id="products" class="products-front section-invert">
	<div class="products-front-products">
		<?php
			$products = get_posts([
				'post_type'      => 'product',
				'meta_key'       => 'product_ispreview',
				'meta_value'     => 'true',
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'posts_per_page' => -1
			]);

			foreach($products as $product) {
				global $post;
				$post = $product;
				setup_postdata($post);

				get_template_part('template-parts/product', 'front');
			}
		?>
	</div>
</section>

<section id="about" class="about-preview">
	<?php
		//this is stupid, but there's no way to get auto excerpt without setup_postdata
		//the_excerpt only returns manual excerpts, $post->post_content doesn't support more tags
		global $post;
		$post = get_page_by_path('about-us');
		setup_postdata($post);
	?>

	<h2 class="section-header"><?php the_title(); ?></h2>

	<div class="body-content">
		<?php the_content('', true); ?>
	</div>

	<a href="<?php the_permalink(); ?>" class="link link-button box-center">See more about us</a>
</section>

<section id="testimonials" class="testimonials-preview section-invert">
	<h2 class="section-header">Testimonials</h2>

	<div class="testimonials-preview-testimonials flex flex-center flex-responsive">
		<?php 
			$testimonials = get_posts([
				'post_type'      => 'testimonial',
				'meta_key'       => 'testimonial_ispreview',
				'meta_value'     => 'true',
				'orderby'        => 'rand',
				'posts_per_page' => 2
			]);

			foreach($testimonials as $test) {
				global $post;
				$post = $test;
				setup_postdata($post);

				get_template_part('template-parts/testimonial', 'front');
			} 
		?>
	</div>

	<a href="<?php echo esc_url(get_permalink(get_page_by_path('corporate/testimonials'))); ?>" class="link link-button box-center">
		See more testimonials
	</a>
</section> 
<div class="site-content site-content-padded flex flex-center flex-responsive">
	<!--<section id="showcase" class="showcase-preview section-half">
		<h2 class="section-header">Showcase</h2>

		<p class="text-center" style="display: block; width: 100%; height: 64px; background: #eee; line-height: 64px">Coming soon!</p>
	</section>-->

	<section id="news" class="news-preview">
		<h2 class="section-header">News</h2>

		<div class="news-preview-news">
			<?php 
				$news = get_posts([
					'orderby'        => 'date',
					'order'          => 'DESC',
					'category_name'  => 'Company News',
					'posts_per_page' => 4
				]);

				global $post;

				foreach($news as $post) {
					setup_postdata($post);

					get_template_part('template-parts/company-news', 'front');
				} 
			?>
		</div>

		<a href="<?php echo esc_url(get_permalink(get_page_by_path('corporate/news'))); ?>" class="link link-button box-center">
			See more news
		</a>
	</section>
</div> 


<script>
	//hero video
	var video = document.getElementById('hero-video');

	window.onresize = function() {
		if(video) {
			//ie???
			try {
				//see if we even want to load the video
				if(document.body.clientWidth > 480)
					video.attributes.autoplay.value = 'true';
				else
					video.attributes.autoplay.value = undefined;
			} catch(e) {}

			//get size of parent container
			var parentW = video.parentNode.clientWidth;
			var parentH = video.parentNode.clientHeight;

			//aspect ratio of video
			var aspect = video.videoWidth / video.videoHeight;

			//default to 1080p
			if(isNaN(aspect)) aspect = 1920 / 1080;

			//calc video size
			var vidW = parentW;
			var vidH = (parentW / aspect);

			//if portrait, adjust
			if(vidH < parentH) {
				vidH = parentH;
				vidW = parentH * aspect;
			}

			//set size and pos
			video.style.width = vidW + 'px';
			video.style.height = vidH + 'px';

			video.style.top = (parentH / 2 - vidH / 2) + 'px';
			video.style.left = (parentW / 2 - vidW / 2) + 'px';
		}
	}

	window.onresize();
</script>

<?php get_footer(); ?>