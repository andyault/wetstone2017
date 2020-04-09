<section class="page-overview site-content">
	<h1 class="page-header">
	<?php 
	$exTitle = explode(": ", get_the_title($post->ID));
	
	echo $exTitle[0]; 	
	
	?></h1>

	<div class="body-small">
		<?php the_content(); ?>
	</div>
</section>