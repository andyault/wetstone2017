<?php 
	$name = $attachment->post_name;
	$link = wp_get_attachment_image_src($attachment->ID, [1040, 400]);
	$fulllink = wp_get_attachment_image_src($attachment->ID, 'full');

	if($link) {
		?>

		<div id="<?php echo $name; ?>" class="post-gallery-slide">
			<a href="<?php echo $fulllink[0]; ?>"
			   class="post-gallery-inner site-content luminous-link"
			   style="background-image: url(<?php echo $link[0]; ?>)">
			</a>
		</div>

		<?php
	}
?>