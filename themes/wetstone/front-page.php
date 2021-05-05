<?php
	get_header();

	$err = 'Your browser does not support the video tag, please update your browser to view content.';
?>
<style>
* {
  box-sizing: border-box;
}


.embed-container {
  position: relative;
  padding-bottom: 56.25%;
  height: 0;
  overflow: hidden;
  max-width: 100%;
}

.embed-container iframe,
.embed-container object,
.embed-container embed {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 70%;
}


.boxX {
	width:80%;
	max-width:650px;
	height:auto;
	margin:0 auto;	
	display:flex;
	flex-wrap:wrap;
}


.columnX {
  padding: 10px;
  display: flex;
  flex:66%
}


.iconX {
  float: left;
  flex:33%
}

@media (max-width: 300px) {
  .columnX, .iconX {
    flex: 100%;
  }
}

</style>
<section class="hero fullpage fullpage-hero" style="background-image: url(<?php echo wetstone_get_asset('/img/background/solid.png'); ?>);">
				 <center>
				 <img class="hero-logo" src="<?php echo wetstone_get_asset('/img/biglogo.png'); ?>" alt="WetStone Technologies: A division of Allen Corporation" style="width:100%; max-width:448px; height:auto; padding-bottom:60px;"><br />
				 <div class="boxX" style="background-color: #1fb04c;">
					<div class="iconX">
						<a href="https://www.wetstonetech.com/dfir"class="link link-body" target="_blank"><img src="https://www.wetstonetech.com/wp-content/uploads/2021/03/dfir_green.png" border="0"/></a>
					</div>
					<div class="columnX">
						<div>
						<strong><a href="https://www.wetstonetech.com/dfir"class="link link-body" style="font-size: 18px; color:white" target="_blank">WetStone Forensic Software</strong><br /><br />
						<p style="font-size: 16px; color:white">WetStone is an industry leader in cyber security services and computer forensics solutions.</p><p style="text-decoration:underline;">Click to learn more</p></a>
						</div>
					</div>
				
				</div>
				<br /><br />
				 <div class="embed-container">					 
				 <iframe style="position:absolute; top:0px; left:14%; width:74%; height:74%; border:0;" id="vimeoVideoPlayer" src="https://player.vimeo.com/video/411007100" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>				
				</div>
				
				<br /><br />
				<p></p>
				<div class="boxX" style="background-color: #d21302;">
					<div class="iconX">
						<a href="https://www.wetstonetech.com/mcafee"class="link link-body" target="_blank"><img src="https://www.wetstonetech.com/wp-content/uploads/2021/03/mcafee_red.png" border="0"/></a>
					</div>
					<div class="columnX">
						<div>
						<strong><a href="https://www.wetstonetech.com/mcafee"class="link link-body" style="font-size: 18px; color:white" target="_blank">McAfee Solutions and Services</strong><br /><br />
						<p style="font-size: 16px; color:white">WetStone is one of McAfee's highest rated professional services partners.</p><p style="text-decoration:underline;">Click to learn more</p></a>
						</div>
					</div>
				</div>
				<br /><br />
				<div class="embed-container">
				 <iframe style="top:0px; left:14%; width:74%; height:74%; border:0;" id="vimeoVideoPlayer" src="https://player.vimeo.com/video/415135655" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
				</div>

				
				
				
				</center>		
<br /><br />				
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
		<p>WetStone Technologies is the award-winning Cyber Security Division of Allen Corporation of America. Since 1998, WetStone has developed <a href="https://www.wetstonetech.com/products" class="link link-body">software solutions</a> that support investigators and analysts engaged in cyber-crime investigations, digital forensics, and incident response activities. We also provide comprehensive consulting services for the best practices implementation and operation of security solutions from <a href="https://www.wetstonetech.com/mcafee" class="link link-body">McAfee</a> and other industry leaders to protect our customers’ critical information assets.</p>
	</div>

	<a href="<?php the_permalink(); ?>" class="link link-button box-center">See more about us</a>
</section>

<section id="testimonials" class="testimonials-preview section-invert ">
<h2 class="section-header">News</h2>
	<section id="news" class="news-preview site-content flex flex-center flex-responsive">
		

		<div class="news-preview-news" style="width:75%;">
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
<a href="<?php echo esc_url(get_permalink(get_page_by_path('news'))); ?>" class="link link-button box-center">
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