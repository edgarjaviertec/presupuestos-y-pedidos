<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('is_active')) {
	function is_active($uri)
	{
		$ci = get_instance();
		return $ci->uri->uri_string()  === $uri;
	}
}



if (!function_exists('remove_commas')) {
	function remove_commas($str)
	{
		return str_replace(",", "", $str);
	}
}




if (!function_exists('text_truncate')) {
	function text_truncate($in,$length=50)
	{
		$out = strlen($in) > $length ? substr($in,0,$length)."..." : $in;
		return $out;
	}
}




if (!function_exists('get_timestamp')) {
	function get_timestamp()
	{
		return date('Y-m-d H:i:s');
	}
}

if (!function_exists('load_page')) {
	function load_page($page)
	{
		$ci = get_instance();
		if (isset($page)) {
			$ci->load->view('pages/' . $page);
		}
	}
}

if (!function_exists('get_external_css')) {
	function get_external_css($css_files)
	{
		if (isset($css_files) && is_array($css_files)) {
			foreach ($css_files as $css) {
				echo '<link rel="stylesheet" href="' . $css . '">';
			}
		} else if (isset($css_files) && is_string($css_files)) {
			echo '<link rel="stylesheet" href="' . $css_files . '">';
		}
	}
}

if (!function_exists('get_external_js')) {
	function get_external_js($js_files)
	{
		if (isset($js_files) && is_array($js_files)) {
			foreach ($js_files as $js) {
				echo '<script src="' . $js . '"></script>';
			}
		} else if (isset($js_files) && is_string($js_files)) {
			echo '<script src="' . $js_files . '"></script>';
		}
	}
}

?>



