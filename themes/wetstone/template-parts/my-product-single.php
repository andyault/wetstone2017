<section class="site-content site-content-small site-content-padded">
	<h2 class="section-header wetstone-font">
	<?php 
	$exTitle = explode(": ", get_the_title($post->ID));
	if ($post->ID == 633 || $post->ID == 634 || $post->ID == 1054) {
		echo $exTitle[0] . " 7.4";
	} elseif ($post->ID == 1807 || $post->ID == 115) {
		echo $exTitle[0] . " 7.1.2";
	} else {
	echo $exTitle[0];
	
	}
	?>
	</h2>

	<div class="myproduct-overview">
		<div style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>);" class="myproduct-overview-image"></div>

		<div class="myproduct-overview-info"><?php
		if ($post->ID == 1815) {
				the_excerpt(); 
		} 
		
		if ($post->ID == 633 || $post->ID == 634) {
				echo '<a href="https://video.wetstonetech.com/video-categories/gargoyle-investigator/" target="_blank" border="0" class="link link-body"><img src="https://www.wetstonetech.com/wp-content/uploads/2021/01/gargVideoSite.png" alt="Gargoyle Investigator MP How To Videos" width="433" height="100"></a></center>';
		} 
		
		if ($post->ID == 115 || $post->ID == 1807) {
				echo '<a href="https://video.wetstonetech.com/video-categories/stegohuntmp/" target="_blank" border="0"><img src="https://www.wetstonetech.com/wp-content/uploads/2021/01/stegoVideoSite.png" alt="StegoHunt MP How To Videos" width="433" height="100"></a></center>';
		} 
		
		?></div>
	</div>

	<div>
		<?php
			$content = get_post_meta($post->ID, 'wetstone_product_customerinfo', true);

			//not a real shortcode so we can't use has_shortcode
			echo do_shortcode($content);
		?>
	</div>
</section>