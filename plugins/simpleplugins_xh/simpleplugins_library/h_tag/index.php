<?php
/***************************************************** 
 *  Plugin name : h_tag
 *  File name : index.php
 *  Summary : H1,H2,H3 tag 
 *  Auther    : Takashi Uchiyama <http://cmsimple-xh.org/>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{h_tag($string [, $h123 , $class]) }}}
 * Example : {{{h_tag('Title2' , 2 , 'myclass') }}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function h_tag($string , $h123 = 1 , $class =''){

	$class_val = ($class !='') ? ' class="' . $class . '"' : '';

	return '<h' . $h123 . $class_val .'>'.$string.'</h' . $h123 .'>';

}
?>