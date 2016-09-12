<?php
/***************************************************** 
 *  Plugin name : recently_changed
 *  File name : index.php
 *  Summery   : facilitates to display a list of pages that have most recently been changed. 
 *              Only the page headings are listed as links to the respective page. 
 *  Auther    : Christoph M. Becker / T.Uchiyama 
 *  License : GPLv3
 *****************************************************
 * Useage : {{{recently_changed([$count]);}}} 
 * Example : {{{recently_changed();}}} 
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if (!function_exists('recently_changed')) {

	/**
	 * @copyright 2016 Christoph M. Becker
	 * @license   GPLv3
	 * @URL  http://cmsimpleforum.com/viewtopic.php?f=12&t=10543
	 */
	function recently_changed($count = 5)
	{
	    global $pd_router;

	    $pageData = $pd_router->find_all();
	    uasort($pageData, function ($a, $b) {
	        return $b['last_edit'] - $a['last_edit'];
	    });

	    $pages = array();
	    $i = 0;
	    foreach (array_keys($pageData) as $page) {
	        if ($i <= $count) {					// Change '<' to '<=' :201608 T.Uchiyama
	            if (!hide($page)) {
	                $pages[] = $page;
	            }
	            $i++;
	        } else {
	            break;
	        }
	    }

	    global $sn, $h, $u;

	    $html = '<ul class="recently_changed">';
	    foreach ($pages as $page) {
	        $html .= '<li><a href="' . "$sn?$u[$page]" . '">' . $h[$page] . '</a></li>';
	    }
	    $html .= '</ul>';
	    return $html;
	}
}
?>