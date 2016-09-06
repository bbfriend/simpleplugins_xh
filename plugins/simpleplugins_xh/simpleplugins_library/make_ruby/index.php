<?php
/***************************************************** 
 *  Plugin name : make_ruby
 *  File name : index.php
 *  Summery   : Display the ruby(HTML TAG) code
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-xh.org/>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{make_ruby($string , $rp_string);}}}
 * Example : {{{make_ruby('Guten Tag','guutn taak');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function make_ruby($string , $rp_string = '') {

	$string = preg_replace("/_/"," ",$string);
	$rp_string = preg_replace("/_/"," ",$rp_string);
	return "<ruby>{$string} <rp>(</rp><rt>{$rp_string}</rt><rp>)</rp></ruby>";

}
?>