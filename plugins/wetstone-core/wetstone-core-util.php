<?php

//handling posts
function wetstone_name_to_label($name) {
	return ucwords(preg_replace('/_/', ' ', $name));
}

//form handling
function wetstone_pop_value(&$arr, $key) {
	$ret = $arr[$key];
	unset($arr[$key]);
	return $ret;
}

function wetstone_sanitize_flatten($val) {
	if(is_array($val))
		$val = implode(', ', $val);

	return esc_attr($val);
}

function wetstone_sanitize_post($keys) {
	return array_filter(array_map(
		'wetstone_sanitize_flatten',
		array_intersect_key(
			$_POST,
			array_flip($keys)
		)
	));
}