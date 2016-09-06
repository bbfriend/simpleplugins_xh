<?php
/***************************************************** 
 *  Plugin name : add_qrcode
 *  File name : index.php
 *  Summery   ; Display the QR code
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{add_qrcode($string[,$size]);}}}
 * Example : {{{add_qrcode('http://cmsimple-jp.org',80);}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function add_qrcode($url , $size = 80) {
	return '<img src="https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . $url . '&choe=UTF-8 " alt="QR Code"/>';
}
?>