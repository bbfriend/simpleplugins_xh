<?php
/***************************************************** 
 *  Plugin name : simple_google_map2
 *  File name : index.php
 *  Summery   ; Display Google MAP (js type)
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-xh.org/>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{simple_google_map2($address [,$zoom ,$description ,$width,$height ,$map_type_control]);}}}
 * Example : {{{simple_google_map2('Tokyo');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Plugin Name: Simple Map
 * Author: Takayuki Miyauchi
 * Plugin URI: https://github.com/miya0001/simple-map
 * Description: Insert google map convert from address.
 * Version: 2.14.1
 * Author URI: http://wpist.me/
 * Text Domain: simple-map
 * Domain Path: /languages
 * @package Simple Map
 */



/**
 * Displays the map
*/

function simple_google_map2($address ,
							$zoom = 16 ,					/* @var int : Default map zoom value. */
							$description = '',				/* @var string : Summery of point . */
							$width = '100%',				/* @var string : Default map width. */
							$height = '400px',				/* @var string : Default map height. */
							$map_type_control = 'false'		/* true or false */
							) {

	/****************************************
	 * 	Set Your Your Google Map API Key .
	 *****************************************/

	$key = 'YOUR_API_KEY';

	/*****************************************/



	$class_name 	= 'simplemap';	/*@var string : Default class name. */
	$max_breakpoint = 640; 			/* @var int : Default mab box max break point. */
	$infowindow		= 'close';		/* @var open/close : info Windows. */
	$map_type_id = 'ROADMAP';
	$lat = '';
	$lng = '';
	$addr = trim( $address );

	if($description != ''){
		$pont_description = $description ;
		$infowindow		= 'open';
	}else{
		$pont_description = $addr ;
	}


	global $hjs ,$bjs;

	$google_maps_api = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=' . $key . '"></script>';
	$simple_google_map_js = '<script type="text/javascript" src="./plugins/simpleplugins_xh/simpleplugins_library/simple_google_map2/js/simple-map.min.js"></script>';


	if( $address  ) {

		if( !strstr($hjs,'jquery.min.js')){
			global $pth;
			include_once $pth['folder']['plugins'] . 'jquery/jquery.inc.php';
			include_jQuery();
		}
		if( !strstr($hjs,'.simplemap img') ){
//			$hjs =  $google_maps_api . simple_google_map2() . $hjs . $check_jquery;
			$hjs .=  simple_google_map2_style() ;
		}

		if( !strstr($hjs,'maps.googleapis.com/maps/api') && !strstr($bjs,'maps.googleapis.com/maps/api')){
			$bjs .=  $google_maps_api ."\n";
		}

		
		if( !strstr($bjs,'simple-map.min.js') ){
			$bjs .=  $simple_google_map_js;
		}


		return sprintf(
			'<div class="%1$s"><div class="%1$s-content" data-breakpoint="%2$s" data-lat="%3$s" data-lng="%4$s" data-zoom="%5$s" data-addr="%6$s" data-infowindow="%7$s" data-map-type-control="%8$s" data-map-type-id="%9$s" style="width:%10$s;height:%11$s;">%12$s</div></div>',
			$class_name,
			$max_breakpoint,
			$lat ,
			$lng ,
			$zoom,
			$addr,
			$infowindow,
			$map_type_control,
			$map_type_id,
			$width,
			$height,
			$pont_description
		);

	}else {
		return '';
	}

}


/**
 * Fixes a problem with responsive themes
 *
 * @since       1.0.1
 * @return      void
*/

function simple_google_map2_style() {

	return "\n" . '<style>.simplemap img{max-width:none !important;padding:0 !important;margin:0 !important;}.staticmap,.staticmap img{max-width:100% !important;height:auto !important;}.simplemap .simplemap-content{display:none;}</style>' . "\n";

}


