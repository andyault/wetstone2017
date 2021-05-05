<?php
	$products = get_user_meta(wp_get_current_user()->ID, 'wetstone_products', true);

	if(empty($products))
		$products = [];
	else {
		$products = array_column(
			get_posts([
				'post_type' => 'product',
				'include' => array_keys($products)
			]),

			'post_title'
		);
	}
?>

			<form name="my-account" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form ">
	<input type="hidden" name="action" value="wetstone-customer-support">
	<?php wp_nonce_field('wetstone-customer-support'); ?>

	<input type="hidden" name="ID" value="<?php echo $data->ID; ?>">

	<table class="form-table form-table-collapse">
		<tr>
			<td colspan="2" class="text-center">Before submitting a support request, you may be able to find your question answered in one of our product FAQ's or in our How To Video series.</td>
		</tr>
		<tr>
			<td><h3 class="wetstone-font">Gargoyle Investigator™ MP</h3><h3 class="wetstone-font">StegoHunt™ MP</h3></td>
			<td><a class="link link-button popmake-2241">FAQ</a>&nbsp;&nbsp;<a class="link link-button" target="_blank" href="https://video.wetstonetech.com/video-categories/gargoyle-investigator/">How To Videos</a><br /><br /><a class="link link-button popmake-2260">FAQ</a>&nbsp;&nbsp;<a class="link link-button" href="https://video.wetstonetech.com/video-categories/stegohuntmp/" target="_blank">How To Videos</a></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<tr>
			<td colspan="2">
				<!-- BEGIN Podio web form -->
				<script src="https://podio.com/webforms/16859089/1133593.js"></script>
				<script type="text/javascript">
				  _podioWebForm.render("1133593")
				</script>
				<noscript>
				  <a href="https://podio.com/webforms/16859089/1133593" target="_blank">Please fill out the form</a>
				</noscript>
				<!-- END Podio web form -->
			</td>
		</tr>
	</table>
</form>