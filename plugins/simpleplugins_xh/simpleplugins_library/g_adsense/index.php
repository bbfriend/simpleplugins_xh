<?php
/***************************************************** 
 *  Plugin name : g_adsense
 *  File name : index.php
 *  Summery   ; Display the Google AdSense 
 *  Auther    : Takashi Uchiyama <http://cmsimple-xh.org/>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{g_adsense();}}}
 * Example : {{{g_adsense();}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function g_adsense() {

	return '<div class="mainAds">

	Please Edit Your Google AdSense Code ,
	(Here : simpleplugins_library\g_adsense\index.php)

	</div>';
/***** Example *********************
	return '<div class="mainAds">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- responsive201607 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-******"
     data-ad-slot="******"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>';
*******/
}

?>