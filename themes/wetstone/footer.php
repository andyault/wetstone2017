		</main>

		<footer class="site-footer section-invert">
			<div class="site-footer-inner site-content">
			</div>
		</footer>

		<script>
			//header sub menu toggles
			var links = document.querySelectorAll('.site-header-sub');

			//toggle class when button clicked
			for(var i = 0; i < links.length; i++) {
				links[i].onclick = function(e) {
					this.classList.toggle('active');

					return false;
				}
			}

			//remove classes when clicked anywhere else
			document.body.addEventListener('click', function(e) {
				var classList = e.target.classList;

				//weird step to prevent removing and toggling in the same event
				if(!(classList.contains('site-header-sub') && classList.contains('active'))) {
					var activeMenus = document.querySelectorAll('.site-header-sub.active');

					for(var i = 0; i < activeMenus.length; i++)
						activeMenus[i].classList.remove('active');
				}
			}, true);
		</script>

		<?php wp_footer(); ?>
	</body>
</html>