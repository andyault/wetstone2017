		</main>

		<footer class="site-footer section-invert">
			<div class="site-footer-inner site-content site-content-padded">
				<?php
					function footer_link($page, $depth) {
						echo sprintf(
							'<a href="%s" class="link link-footer">%s</a>',

							get_permalink($page),
							get_the_title($page)
						);

						//this sucks
						if($page->ID == 84)
							$children = get_pages(['parent' => $page->ID]);
						else
							$children = wetstone_get_children($page);

						//recursion is fun
						if($depth != 0 && $children)
							footer_list($children, $depth - 1);
					}

					function footer_list($pages, $depth) {
						echo '<ul class="list-footer">';

						foreach($pages as $page) {
							echo '<li>';

							if(gettype($page) == 'string')
								$page = get_page_by_path($page);

							echo footer_link($page, $depth);

							echo '</li>';
						}

						echo '</ul>';
					}

					//sgo
					footer_list(['home', 'about-us', 'contact'], 1);
					footer_list(['products', 'services'], 1);
					footer_list(['corporate'], 1); 
					footer_list(['sign-in', 'portal'], 1);
				?>
			</div>
		</footer>

		<script src="<?php echo wetstone_get_asset('/js/navigation.js'); ?>"></script>

		<?php wp_footer(); ?>
	</body>
</html>