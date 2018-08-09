<?php

function add_custom_recaptcha_forms($forms) {
	$forms['my_custom_form'] = array("form_name" => "Custom Form Name");
	return $forms;
}

add_filter('gglcptch_add_custom_form', 'add_custom_recaptcha_forms');
//