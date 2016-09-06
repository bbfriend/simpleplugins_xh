<?php
/***************************************************** 
 *  Plugin name : mailaddress_hide_js
 *  File name : index.php
 *  Summary :   Converts email addresses characters to block spam bots By JavaScript.
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{mailaddress_hide_js($string);}}}
 * Example : {{{mailaddress_hide_js('yyy@xxxx.xxx');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function mailaddress_hide_js($mail_address){

/*
Plugin Name: mailaddress_hide
Plugin URI: http://www.kenjiroumatsushita.com/
Description: mailaddress_hide
Version: 0.1
Author: mailaddress_hide
Author URI: http://www.kenjiroumatsushita.com/
*/

	if ($mail_address!=''){

			$link_str="Mail"; // Link name

		$ofset_var=rand (1,100);

		$ofset_char = '';

		$char_small = range('a', 'z');

		$func_length=rand (5,10);

		$char_length1=rand (3,5);
		$char_length2=rand (3,5);


		$func_name="a";

		for ($i=0; $i<$func_length; $i++){
			$func_name = $func_name."".$char_small[rand(0,25)];
		} 

		$func_name = $func_name."Bc";

		for ($i=0; $i<$char_length1; $i++){
			$ofset_char = $ofset_char."".$char_small[rand(0,25)];
		} 

		$ofset_char = $ofset_char."_";

		for ($i=0; $i<$char_length2; $i++){
			$ofset_char = $ofset_char."".$char_small[rand(0,25)];
		} 


		$result_char="location.href='mailto:$mail_address'; ";

		$result_char_arr = str_split($result_char);

		for ($i=0; $i<count($result_char_arr); $i++){
			$result_char_arr[$i] = ord($result_char_arr[$i])+$ofset_var;
		} 


		$result_char=implode(" - $ofset_char," , $result_char_arr);
		$result_char="$result_char - $ofset_char";

		$result_code="<script type='text/javascript'>
		function $func_name()
		{
		var $ofset_char = $ofset_var;
		eval(String.fromCharCode($result_char));
		}
		document.write('<a href=\"javascript:void(0)\" onclick=\"$func_name(); return false;\">$link_str</a>');
		</script>";

		return $result_code;

	}

}

?>
