<?php
function my_page_title() {
    return 'WetStone Video Library'; // add dynamic content to this title (if needed)
}
add_action( 'pre_get_document_title', 'my_page_title' );
?>

<div class="page-preview">
	<h2><a href="<?php the_permalink(); ?>" class="link link-body"><?php the_title(); ?></a></h2>

	<p class="body body-small"><?php echo get_the_excerpt(); ?></p>
</div>