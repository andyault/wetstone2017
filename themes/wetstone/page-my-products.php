<?php
	get_header();

	$products = 1;
?>

<section class="site-content site-content-padded">
	<ul class="myproducts-list">
		<?php
			var_dump(wp_get_current_user());
			echo "\n--\n";
			var_dump(get_user_mata($wp_get_current_user->ID));
		?>
	</ul>
</section>

<?php get_footer(); ?>