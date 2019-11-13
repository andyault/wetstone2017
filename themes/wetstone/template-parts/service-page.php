<?php 
function my_page_title() {
    return 'Servicess'; // add dynamic content to this title (if needed)
}
add_action( 'pre_get_document_title', 'my_page_title' );
?>
<h2 class="section-header">
	<a href="<?php the_permalink(); ?>" class="link link-body">
		<?php the_title(); ?>
	</a>
</h2>