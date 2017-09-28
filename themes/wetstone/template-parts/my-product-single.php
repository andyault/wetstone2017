<section class="site-content site-content-small site-content-padded">
	<h2 class="section-header"><?php the_title(); ?></h2>

	<?php
		$content = get_post_meta($post->ID, 'wetstone_product_customerinfo', true);

		//not a real shortcode so we can't use has_shortcode
		if(strpos($content, '[wetstone_tab') !== false) {
			//get an array of tab names and tab content
			preg_match_all(
				'/\[wetstone_tab\s+name="([^"]*)"[^]]*\]([^\[]+)/i',
				$content,
				$matches
			);

			//turn it into stuff we can use
			$tabs = [];

			foreach($matches[1] as $key => $name) {
				$tabs[$key] = [
					'name' => $name,
					'content' => $matches[2][$key],
					'id' => strtolower(preg_replace('/[^\w\s]/', '', implode('-', explode(' ', $name))))
				];
			}

			?>

			<div class="tabbed">
				<?php
					//radio buttons
					foreach($tabs as $tab)
						echo sprintf('<input type="radio" id="input-%s" name="myproduct-tabs" class="tabbed-input">', $tab['id']);
				?>

				<ul class="tabbed-tabs">
					<?php
						//tab elements
						foreach($tabs as $tab)
							echo sprintf('<li id="tab-%s" class="tabbed-tab">%s</li>', $tab['id'], $tab['name']);
					?>
				</ul>

				<ul class="tabbed-contents">
					<?php
						//tab contents
						foreach($tabs as $tab)
							echo sprintf('<li id="content-%s" class="tabbed-content">%s</li>', $tab['id'], $tab['content']);
					?>
				</ul>
			</div>

			<?php
		} else
			echo ''; //$content;
	?>
</section>