<?php
/***************************************************** 
 * CMSimple Very Very Simple Plugin Sample
 *****************************************************
 *  Plugin name : hellomyplugin 
 *  File name : index.php
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function hellomyplugin(){
    return 'Hello My Plugin!';
}
?>