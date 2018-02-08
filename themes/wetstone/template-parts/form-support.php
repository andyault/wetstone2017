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
			<td colspan="2" class="text-center">Before submitting, please be sure your question isn't listed in one of our product FAQs.</td>
		</tr>

		<tr>
			<td colspan="2" class="text-center">Fields marked with <i class="req">*</i> are required.</td>
		</tr>

		<tr>
			<td>
				<?php
					echo wetstone_form_make_select(
						'product', 
						'Which product are you having issues with?', 
						$products
					);
				?>
			</td>

			<td>
				<?php
					echo wetstone_form_make_select(
						'context',
						'What type of problem are you having?',

						[
							'Technical problems',
							'Unable to activate product',
							'Other (please specify in comments)'
						]
					);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<?php
					echo wetstone_form_make_textarea(
						'comments',
						'Please describe any problems you are having',
						'I\'m having issues with...',
						true
					);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="2" class="table-footer">
				<div class="inline-flex">
					<button type="reset" class="form-reset link link-button link-button-input link-button-grey">Reset</button>
					<button type="submit" class="link link-button link-button-input">Submit</button>
				</div>
			</td>
		</tr>
	</table>
</form>