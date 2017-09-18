<?php
/**
 *  simpleplugins_xh 
 *
 * @package	shortcodes_xh
 * @copyright	Copyright (c) 2015 T.Uchiyama <http://cmsimple-jp.org/>
 * @license	http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version 1.0.1
 * @link	http://cmsimple-jp.org
 */


/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
/*
 * Register the plugin menu items.
 */
if (function_exists('XH_registerStandardPluginMenuItems')) {
    XH_registerStandardPluginMenuItems(false);
}




/**
 * Returns the plugin version information view.
 *
 * @return string  The (X)HTML.
 */
function simpleplugins_xh_version()
{
    global $pth;

    return '<h1>SimplePlugins_xh</h1>'."\n"
	. tag('img src="'.$pth['folder']['plugins'].'simpleplugins_xh/help/SimplePlugins_XH.gif" style="float: left; margin: 0 20px 20px 0"')
	. '<p>This plugin is a Simple/Simplicity plugin library.' . tag('br') . ' If you create/make a simple plugin, please provide </p>'
	. '<p>Version: '.SIMPLEPLUIGNS_XH_VERSION.'</p>'."\n"
	. '<p>Copyright &copy; 2015 <a href="http://cmsimple-jp.org" target="_blank">cmsimple-jp.org</a></p>'."\n"
	. '<p style="text-align: justify">'
	. '<b>License</b>'. tag('br') . "\n"
	. ' SimplePlugins_xh License : <a href="http://www.gnu.org/licenses/" target="_blank">GPLv3.</a>'. tag('br')."\n"
	. ' Each library\'s plugin : Conform Each license of the plugin. Read index.php etc' . tag('br')."\n";
}


/*
 * Handle the plugin administration.
 */

//if (isset($simpleplugins_xh) && $simpleplugins_xh == 'true') {
if (function_exists('XH_wantsPluginAdministration')
    && XH_wantsPluginAdministration('simpleplugins_xh')
    || isset($simpleplugins_xh) && $simpleplugins_xh == 'true'){

//    $o .= print_plugin_admin('on'); //Returns the plugin menu.

//    if ($admin == 'plugin_config' || $admin == 'plugin_language') {
    if ($admin == 'plugin_language') {
        $o .= print_plugin_admin('on');
    } else {
        $o .= print_plugin_admin('off');
    }
    switch ($admin) {
    case '':
    case 'plugin_main':
	$o .= simpleplugins_xh_version() .SimplePlugins_systemCheck() ;
	break;
    default://Handles reading and writing of plugin files
	$o .= plugin_admin_common($action, $admin, $plugin);
    }
}

/**
 * Returns requirements information.
 *
 * @return string (X)HTML
 *
 * @global array The paths of system files and folders.
 * @global array The configuration of the plugins.
 * @global array The localization of the core.
 * @global array The localization of the plugins.
 */

function SimplePlugins_systemCheck()
{
    global $pth, $plugin_cf, $tx, $plugin_tx ,$badcow_shortcode;
    $pcf = $plugin_cf['simpleplugins_xh'];

    $o = tag('hr') . '<h4>' . "SimplePluginLibrary you have " . '</h4>'. PHP_EOL;

    if ($pcf['library_folder'] == '') {
        $fn = $pth['folder']['plugins'] . 'simpleplugins_xh/simpleplugins_library/';
    } else {
        $fn = $pth['folder']['base'] . $pcf['library_folder'];
    }

	$o .= $fn . '<ul>';

	$dir_names  = Simpleplugins_library_List();

	foreach ($dir_names as $simple_plugin) {
		$o .= '<li><span style="background-color: #ffff99;">' . ltrim(strrchr($simple_plugin ,'/'),'/') . '</span></li> ';
	}
	$o .= '</ul>';

    return $o;
}

?>
