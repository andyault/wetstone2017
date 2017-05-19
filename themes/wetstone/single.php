<?php
	get_header();
	the_post();

	//load single template
	$postType = get_post_type();

	if(locate_template(sprintf('template-parts/%s-single.php', $postType)) != '') {
		get_template_part(
			sprintf('template-parts/%s', $postType),
			'single'
		);
	} else { ?>
		<section class="single-description">
			<h1 class="page-header"><?php the_title(); ?></h1>

			<div class="body-content">
				<?php the_content(); ?>
			</div>
		</section>
	<?php }
?>

<!-- prev/next links, inquire link -->
<section class="single-footer site-content">
	<?php
		$prev = get_previous_post(); 
		$next = get_next_post();
	?>

	<?php if($prev) { ?>
		<a href="<?php echo get_permalink($prev); ?>" class="link link-body">
			&larr;&nbsp;&nbsp;&nbsp; <?php echo get_the_title($prev); ?>
		</a>
	<?php } ?>

	<span class="fix">&nbsp;</span>

	<a href="" class="single-inquire link link-button">
		<?php
			echo sprintf(
				'Inquire about this %s',
				get_post_type_object($postType)->labels->singular_name
			);
		?>
	</a>

	<?php if($next) { ?>
		<a href="<?php echo get_permalink($next); ?>" class="link link-body">
			<?php echo get_the_title($next); ?> &nbsp;&nbsp;&nbsp;&rarr;
		</a>
	<?php } ?>
</section>

<?php get_footer(); ?>