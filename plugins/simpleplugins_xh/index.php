<?php
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

/**
 * simpleplugins_xh - main index.php
 *
 * This plugin is to use Very Simple plugins 
 * index.php is called by pluginloader and returns (X)HTML META ELEMENTS to template.
 *
 * PHP versions 5
 *
 * @category  CMSimple_XH
 * @package   simpleplugins_xh
 * @author    Takashi Uchiyama <http://cmsimple-xh.org/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   
 * @link      http://cmsimple-xh.org/
 */


/* utf8-marker = äöüß */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * The plugin version.
 */
define('SIMPLEPLUIGNS_XH_VERSION', '1.02');


/*
 * Add a tab for admin-menu.
 */

if($plugin_cf['simpleplugins_xh']['tab_show'] =='true'){
	$pd_router->add_tab(
	    $plugin_tx['simpleplugins_xh']['tab'],
	    $pth['folder']['plugins'] . 'simpleplugins_xh/Simpleplugins_view.php'
	);
}

/*
 * read simpleplugins_library.
 */
$dir_names  = Simpleplugins_library_List();

foreach( $dir_names as $plugin_name ) {
//	require_once $filename;
	@include_once $plugin_name .'/index.php';
}





function Simpleplugins_library_List()
{
    global $pth, $plugin_cf;

    $pcf = $plugin_cf['simpleplugins_xh'];

    if ($pcf['library_folder'] == '') {
        $fn = $pth['folder']['plugins'] . 'simpleplugins_xh/simpleplugins_library/';
    } else {
        $fn = $pth['folder']['base'] . $pcf['library_folder'];
    }

    if (substr($fn, -1) != '/') {
        $fn .= '/';
    }

	(array)$dir_name = glob($fn.'*',GLOB_ONLYDIR ); //ex. ..simpleplugins_xh/simpleplugins_library/hellomyplugin

//	foreach( $dir_name as $plugin_name ) {
//	//	require_once $filename;
//		@include_once $plugin_name .'/index.php';
//	}
	return $dir_name;
}

?>
