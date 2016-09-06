<?php
/***************************************************** 
 *  Plugin name : admin_only
 *  File name : index.php
 *  Summery   : This is displayed only when admin login.
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{admin_only($string[,$class]);}}}
 * Example : {{{admin_only('This is Admin Only','xh_warning');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function admin_only($string, $class ='xh_warning'){

	global $adm;

	$class_val = ($class !='') ? ' class="' . $class . '"' : '';

    if (!$adm) {
		return '';
	}else{
		return '<span' . $class_val . '>[Display only admin] ' . $string .'</span>';
	}

}
?>