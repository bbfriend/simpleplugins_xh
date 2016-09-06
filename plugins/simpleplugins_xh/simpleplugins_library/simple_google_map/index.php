<?php
/***************************************************** 
 *  Plugin name : simple_google_map
 *  File name : index.php
 *  Summery   : Display Google MAP 
 *  Version   : 0.3
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{simple_google_map($string [,$zoom,$width,$height,$enablescrollwheel,$disablecontrols]);}}}
 * Example : {{{simple_google_map('Tokyo');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Displays the map
*/

function simple_google_map(	$address ,
							$zoom = 15 ,
							$width = '100%',
							$height = '400px',
							$enablescrollwheel = 'true' ,
							$disablecontrols = 'false'		) {

	/****************************************
	 * 	Set Your Google Map API Key .
	 * https://developers.google.com/maps/documentation/javascript/get-api-key
	 *****************************************/

	$key = 'YOUR_API_KEY';

	/*****************************************/


	global $hjs,$bjs;

	$google_maps_api = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=' . $key . '"></script>';

	if( $address  ) :

		//Read the jQuery to the end of </body>, if the jQuery is not loaded
		//http://jsfiddle.net/bbfriend/pj4922uv/
		//http://blazechariot.wp.xdomain.jp/post-900
		$check_jquery = <<<EOF
<script type="text/javascript">
var checkReady = function(callback) {
  if (window.jQuery) {
    callback(jQuery);
  } else {
    var a = document.createElement('script');
    a.type = "text/javascript";
    a.src = "//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js";
    document.body.appendChild(a);
  }
};
</script>
EOF;

		if( !strstr($hjs,'jquery.min.js')){
			global $pth;
			include_once $pth['folder']['plugins'] . 'jquery/jquery.inc.php';
			include_jQuery();
		}

		if( !strstr($hjs,'maps.googleapis.com/maps/api/js') ){
//			$hjs =  $google_maps_api . pw_map_css() . $hjs . $check_jquery;
			$hjs =  $google_maps_api . pw_map_css() . $hjs ;
		}

		$coordinates = pw_map_get_coordinates( $address );

		if( !is_array( $coordinates ) )
			return $coordinates;

		$map_id = uniqid( 'pw_map_' ); // generate a unique ID for this map

		ob_start(); ?>
		<div class="pw_map_canvas" id="<?php echo $map_id ; ?>" style="height: <?php echo $height; ?>; width: <?php  echo $width; ?>"></div>
		<script type="text/javascript">
			var map_<?php echo $map_id; ?>;
			function pw_run_map_<?php echo $map_id ; ?>(){
				var location = new google.maps.LatLng("<?php echo $coordinates['lat']; ?>", "<?php echo $coordinates['lng']; ?>");
				var map_options = {
					zoom: <?php echo $zoom; ?>,
					center: location,
					scrollwheel: <?php echo 'true' === strtolower( $enablescrollwheel ) ? '1' : '0'; ?>,
					disableDefaultUI: <?php echo 'true' === strtolower( $disablecontrols ) ? '1' : '0'; ?>,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				map_<?php echo $map_id ; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id ; ?>"), map_options);
				var marker = new google.maps.Marker({
				position: location,
				map: map_<?php echo $map_id ; ?>
				});
			}
			pw_run_map_<?php echo $map_id ; ?>();
		</script>
		<?php

		return ob_get_clean() ;

	else :
		return  'This Google Map cannot be loaded because the maps API does not appear to be loaded';
	endif;

}


/**
 * Retrieve coordinates for an address
 *
 * Coordinates are cached using transients and a hash of the address
 *
 * @access      private
 * @since       1.0
 * @return      void
*/

function pw_map_get_coordinates( $address, $force_refresh = true ) {

	$data = array();

	if ( $force_refresh  ) {

		$args       =  array( 'address' => urlencode( $address ), 'sensor' => 'false' ) ;
		$url        = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $address ) . '&sensor=false';
		$response 	= json_decode( file_get_contents( $url ) );


		if ( !is_null($response) ){

			if ( $response->status === 'OK' ) {


				$coordinates = $response->results[0]->geometry->location;

				$data['lat'] 	= $coordinates->lat;
				$data['lng'] 	= $coordinates->lng;
				$data['address'] = (string) $response->results[0]->formatted_address;



			} elseif ( $response->status === 'ZERO_RESULTS' ) {
				return 'No location found for the entered address.';
			} elseif( $response->status === 'INVALID_REQUEST' ) {
				return 'Invalid request. Did you enter an address?';
			} else {
				return 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.';
			}

		} else {
			return 'Unable to contact Google API service.';
		}

	} else {
	   $data = $coordinates;
	}

	return $data;
}


/**
 * Fixes a problem with responsive themes
 *
 * @access      private
 * @since       1.0.1
 * @return      void
*/

function pw_map_css() {

	return "\n" . '<style type="text/css">
.pw_map_canvas img {
	max-width: none;
}</style>' . "\n";

}

/*
Plugin Name: Simple Google Maps Short Code
Plugin URL: http://pippinsplugins.com/simple-google-maps-short-code
Description: Adds a simple Google Maps short code
Version: 1.3
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
Contributors: mordauk
*/
