<?php
	get_header();
	the_post();

	//does this count as hard coded?
	$postType = 'product';
?>

<section id="product-preview" class="product-preview"> 
	<div class="product-front-filter"></div>

	<div class="product-preview-inner site-content">
		<div id="product-preview-image" class="product-preview-image"></div>

		<div class="product-preview-info">
			<h2 id="product-preview-title" class="product-preview-title wetstone-font"></h2>

			<p id="product-preview-excerpt" class="product-preview-excerpt body"></p>

			<a id="product-preview-button" class="product-preview-button link link-button">Go to product page</a>
		</div>
	</div>
</section>

<?php
	if(get_the_content())
		get_template_part('template-parts/basic', 'page');

	$posts = get_posts([
		'post_type'      => $postType,
		'posts_per_page' => -1
	]);
?>

<section class="page-posts site-content">
	<h2 class="section-header">
		<?php
			//todo - default to 'our _', allow %s, remove if no value?
			$label = get_post_meta($post->ID, 'page_listlabel', true);

			if($label)
				echo $label;
			else
				echo 'Our ' . get_post_type_object($postType)->labels->name;
		?>
	</h2>

	<div class="page-list flex">
		<?php
			$jsonPosts = [];

			global $post;

			foreach($posts as $key => $post) {
				setup_postdata($post);

				get_template_part('template-parts/' . $postType, 'page');

				$jsonPosts[$key] = [
					'title'   => get_the_title(),
					'excerpt' => get_the_excerpt(),
					'image'   => get_the_post_thumbnail_url($post, 'full'),
					'color'   => get_post_meta($post->ID, 'product_color', true),
					'url'     => get_the_permalink()
				];
			}
		?>
	</div>
</section>

<script>
	//todo - babel etc?
	var products = <?php echo json_encode($jsonPosts); ?>;

	var preview = {
		curPreview:  0,
		numPreviews: products.length,

		elems: {
			section: document.getElementById('product-preview'),
			image:   document.getElementById('product-preview-image'),
			title:   document.getElementById('product-preview-title'),
			excerpt: document.getElementById('product-preview-excerpt'),
			button:  document.getElementById('product-preview-button')
		}
	};

	preview.next = function() { preview.setPreview(preview.curPreview + 1); }
	preview.prev = function() { preview.setPreview(preview.curPreview - 1); }

	preview.setPreview = function(newPreview) {
		preview.curPreview = newPreview;

		if(preview.curPreview < 0) preview.curPreview += preview.numPreviews;
		preview.curPreview %= preview.numPreviews;

		preview.update();
	}

	preview.update = function() {
		var curProduct = products[preview.curPreview];

		preview.elems.section.style.background = curProduct.color;
		preview.elems.image.style.backgroundImage = 'url(' + curProduct.image + ')';
		preview.elems.title.innerText = curProduct.title;
		preview.elems.excerpt.innerText = curProduct.excerpt;
		preview.elems.button.href = curProduct.url;
	}

	preview.update();
</script>

<?php
	get_footer();
?>