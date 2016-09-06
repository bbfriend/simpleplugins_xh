<?php
/***************************************************** 
 *  Plugin name : responsive_iframe
 *  File name : index.php
 *  Summary : responsive iframe 
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{responsive_iframe($string [,$max_width ,$min_width]); }}}
 * Example : {{{responsive_iframe('<iframe src="https://www.google.com/maps/embed¥¥¥></iframe>',640,280);}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function responsive_iframe($string ,$max_width = 640 ,$min_width = 280){

	$map_wrapper =
		'max-width: ' . $max_width .'px;'.
		'min-width: ' . $min_width .'px;'.
		'margin: 20px auto;'.
		'padding: 4px;'.
		'border: 1px solid #CCC';

    $googlemap =
		'position: relative;'.
		'padding-bottom: 56.25%;'.
		'height: 0;'.
		'overflow: hidden;';

	$googlemap_iframe =
		'position: absolute;'.
		'top: 0;'.
		'left: 0;'.
		'width: 100% !important;'.
		'height: 100% !important';

	$replace_string = str_replace('<iframe ', '<iframe style="' .$googlemap_iframe .'" ' , $string); 

$output = <<<EOF
<div style="{$map_wrapper}">
	<div class="googlemap" style="{$googlemap}">
	 {$replace_string} 
	</div><!--end of .googlemap--> 
</div><!--end of .map_wrapper-->
EOF;
return $output;


}
?>