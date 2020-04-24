<?php
	get_header();

	$err = 'Your browser does not support the video tag, please update your browser to view content.';
?>
		<div style="background-color:#FFFFFF; color:#000000; padding:20px 20px 20px 20px; font-size:14px; width:100%; overflow:auto; ">
		<h2 style="color:#000000; text-align:left;">A Message from Allen Corporation’s CEO on COVID-19</h2>
			<div style="height:300px; width:220px; float:left; padding:20px 20px 20px 20px; "><img src="https://www.wetstonetech.com/wp-content/uploads/2020/04/KC_Headshot.png"></div>
			<div style="padding:0px 0px 40px 20px; float:left; width:65%">
			<p>The pandemic we are living through is an extraordinary time for all of us. No one has been untouched by COVID-19, and it is important – maybe more than ever – that we are here for one another.  We are particularly grateful to the first responders and medical personnel who are putting themselves on the front-line of this pandemic to help safely guide us through this difficult period.<p> 
			<p>We are monitoring the changing situation closely, and doing everything we can to support the well-being of our Allen employees and their families.  Our business continuity planning and migration of critical services to the cloud has enabled us to transition most of our employees to teleworking and to continue supporting the missions of our valued customers and partners. </p>
			<p>Accelerated by the current crisis, the workplace is evolving into a teleworking model. Allen, through its Mitel partnership, offers the services and solutions to implement a highly secure, work from anywhere, collaborative environment for our customers.  Complemented by the McAfee expertise of our Cyber Security division, WetStone Technologies, Allen can provide the solutions that will enable employees to work remotely, productively, and securely.<p> 
			<p>As we enter a new and challenging phase of our nation’s response to the present crisis, keep closely guarded, your safety and health. We will be back together face-to-face soon. Until then, I hope you are all well and safe.<p> 
			<p>All the best,<br />
			KC <br /><br />
			K.C. Vaughey<br />
			President/CEO</p>
			</div>
		</div>
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
				 <center>
				 <br />
				 <img class="hero-logo" src="<?php echo wetstone_get_asset('/img/biglogo.png'); ?>" alt="WetStone Technologies: A division of Allen Corporation" width="448" height="134"><br />
				 <div style="position:relative; overflow:hidden; padding-top:56.25%;">				 					
					<iframe style="position:absolute; top:30px; left:20%; width:65%; height:65%; border:0;" id="vimeoVideoPlayer" src="https://player.vimeo.com/video/411007100" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
				</div>
				</center>
				 
			</div>
		</div>
	</div>

	<div class="hero-info">
		 <!--<img class="hero-logo" src="<?php //echo wetstone_get_asset('/img/biglogo.svg'); ?>" alt="WetStone Technologies: A division of Allen Corporation">-->



		<!--<p class="hero-desc"><?php //echo get_bloginfo('description'); ?></p> -->
		
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