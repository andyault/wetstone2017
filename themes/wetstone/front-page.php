<?php get_header(); ?>

<section class="hero fullpage fullpage-hero">
	<div class="hero-info">
		<img class="hero-logo" src="<?php echo get_template_directory_uri() . '/assets/img/biglogo.svg'; ?>">

		<p class="hero-desc"><?php echo get_bloginfo('description'); ?></p>
	</div>

	<div class="hero-scroll box-center">scroll down</div>
</section>

<section id="products" class="products-preview section-invert">
	<div class="site-content">
	<?php
		$products = get_posts([
			'post_type'      => 'product',
			'meta_key'       => 'product_ispreview',
			'meta_value'     => 'true',
			'orderby'        => 'rand',
			'posts_per_page' => -1
		]);

		foreach($products as $product) {
			global $post;
			$post = $product;
			setup_postdata($post);

			get_template_part('template-parts/product', 'front');
		}
	?>
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

	<div class="testimonials-preview-testimonials">
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

	<a href="<?php echo get_post_type_archive_link('testimonial'); ?>" class="link link-button box-center">
		See more testimonials
	</a>
</section>

<section id="showcase" class="showcase-preview section-half">
</section>

<section id="news" class="news-preview section-half">
</section>

<?php get_footer(); ?>