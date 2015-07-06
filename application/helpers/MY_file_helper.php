<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_image($filename) {
    $imageexts = array('png', 'gif', 'jpg', 'jpeg', 'bmp');
    $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    return in_array($ext, $imageexts);
}

function is_doc($filename) {
    $docexts = array('pdf', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pps', 'tif', 'tiff');
    $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    return in_array($ext, $docexts);
}

function is_cad($filename) {
    $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    return ($ext == 'stl');
}
