<?php

/**
 * Short Code - module Short Code_view
 *
 * Show ShortCodelist in the menu tab
 * 
 *
 * PHP versions 5
 *
 * @category  CMSimple_XH
 * @package   shortcodes_xh
 * @author    Takashi Uchiyama <http://cmsimple-xh.org/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   
 * @link      http://cmsimple-xh.org/
 */

/* utf8-marker = äöüß */

function Simpleplugins_view()
{
    global $plugin_tx, $pth,$plugin_cf;

    $pcf = $plugin_cf['simpleplugins_xh'];
    if ($pcf['library_folder'] == '') {
        $fn = $pth['folder']['plugins'] . 'simpleplugins_xh/simpleplugins_library/';
    } else {
        $fn = $pth['folder']['base'] . $pcf['library_folder'];
    }

	$var = $plugin_tx['simpleplugins_xh']['available'] .' ' . $fn . '<br>';
	$dir_names  = Simpleplugins_library_List();

	foreach ($dir_names as $simple_plugin) {

		$var .= '<span style="background-color: #ffff99;">' . ltrim(strrchr($simple_plugin ,'/'),'/') . '</span> ';

	}
	$var .= '<hr>'. $plugin_cf['simpleplugins_xh']['memo'] ;
	$var .= '<hr>'. $plugin_tx['simpleplugins_xh']['more_information'] ;
    return $var;
}



?>
