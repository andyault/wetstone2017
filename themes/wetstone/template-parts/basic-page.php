<section class="page-overview site-content">
		<h1 class="page-header">
	<?php 
	$exTitle = explode(": ", get_the_title($post->ID));
	
	echo $exTitle[0]; 	
	
	?></h1>

	<div class="body-content">
		<?php echo do_shortcode(get_the_content()); ?>
	</div>
</section>