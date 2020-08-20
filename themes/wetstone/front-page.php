<?php
	get_header();

	$err = 'Your browser does not support the video tag, please update your browser to view content.';
?>

<section class="hero fullpage fullpage-hero" style="background-image: url(<?php echo wetstone_get_asset('/img/background/solid.png'); ?>);">
				 <center>
				 <img class="hero-logo" src="<?php echo wetstone_get_asset('/img/biglogo.png'); ?>" alt="WetStone Technologies: A division of Allen Corporation" style="width:100%; max-width:448px; height:auto;"><br />
				 <div style="position:relative; overflow:hidden; padding-top:56.25%;">
				 <iframe style="position:absolute; top:30px; left:14%; width:80%; height:80%; border:0;" id="vimeoVideoPlayer" src="https://player.vimeo.com/video/411007100" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>				 
				</div>
				<div style="position:relative; overflow:hidden; padding-top:56.25%;">
				 <iframe style="position:absolute; top:0px; left:14%; width:80%; height:80%; border:0;" id="vimeoVideoPlayer" src="https://player.vimeo.com/video/415135655" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>				 
				</div>
				</center>				 
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
		<p>WetStone Technologies is the award-winning Cyber Security Division of Allen Corporation of America. Since 1998, WetStone has developed software solutions that support investigators and analysts engaged in cyber-crime investigations, digital forensics, and incident response activities. We also provide comprehensive consulting services for the best practices implementation and operation of security solutions from McAfee and other industry leaders to protect our customers’ critical information assets.</p>
	</div>

	<a href="<?php the_permalink(); ?>" class="link link-button box-center">See more about us</a>
</section>

<section id="testimonials" class="testimonials-preview section-invert ">
<h2 class="section-header">News</h2>
	<section id="news" class="news-preview site-content site-content-padded flex flex-center flex-responsive">
		

		<div class="news-preview-news" style="width:800px;">
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

		
	</section>
<a href="<?php echo esc_url(get_permalink(get_page_by_path('corporate/news'))); ?>" class="link link-button box-center">
			See more news
		</a>

</section> 

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