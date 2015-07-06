<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('file_size'))
{
	function file_size($size)
	{
		return ($size > 1048576 ? number_format($size/1048576, 1) . ' Mb' : ($size > 1024 ? number_format($size/1024, 0) . ' kb' : $size));
	}
}
