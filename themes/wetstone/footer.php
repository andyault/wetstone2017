		</main>

		<footer class="site-footer section-invert">
			<div class="site-footer-inner site-content site-content-padded">
				<?php
					function footer_link($page, $depth) {
						$template = '<a href="%s" class="link link-footer">%s</a>';

						if(gettype($page) == 'array') {
							echo sprintf($template, $page[1], $page[0]);

							return;
						}
						if($page->ID >= 612 && $page->ID <= 634 || $page->ID == 1054 || $page->ID == 116 || $page->ID == 1815 || $page->ID == 1667 || $page->ID == 1807 || $page->ID == 2455 || $page->ID == 2439 || $page->ID == 2514) { // IDs of unwanted product in Footer
						 if ($page->ID == 633) {
							$exTitle = explode(": ", get_the_title($page));
							echo sprintf($template, get_permalink($page), $exTitle[0]);
							}
						 }
						 
						 else if ($page->ID == 2457){							
							echo sprintf($template, get_permalink($page), "McAfee");
						}
						 
						 
						else {
							$exTitle = explode(": ", get_the_title($page));
							echo sprintf($template, get_permalink($page), $exTitle[0]);
						}
												
						//this sucks
						if($page->ID == 84)
							$children = get_pages(['parent' => $page->ID]);
						else
							$children = wetstone_get_children($page);

						//recursion is fun
						if($depth != 0 && $children)
							footer_list($children, $depth - 1);
					}

					function footer_list($pages, $depth = 0) {
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
					footer_list(['home', 'about-us', 'news']);
					footer_list(['products','mcafee']);
					footer_list(['video-library', 'services', 'partners-resellers']); 
					footer_list(['sign-in', ['Sign Out', wp_logout_url(get_permalink(get_page_by_path('sign-in')))], 'portal']);
				?>
			</div>
		</footer>

		<script src="<?php echo wetstone_get_asset('/js/navigation.js'); ?>"></script>

		<?php wp_footer(); ?><script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
	</body>
</html>