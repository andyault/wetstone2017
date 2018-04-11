<div class="download-item">
	<span class="download-item-title"><?php
		echo $dlm_download->get_the_title()." test";

		if($version = $dlm_download->get_the_version)
			echo '(Version ' . $version . ')';
	?></span>

	<a href="<?php $dlm_download->the_download_link(); ?>" class="download-item-link link link-button">Download</a>
</div>