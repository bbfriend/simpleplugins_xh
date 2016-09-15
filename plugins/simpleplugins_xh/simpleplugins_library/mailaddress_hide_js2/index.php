<?php
/***************************************************** 
 *  Plugin name : mailaddress_hide_js2
 *  File name : index.php
 *  Summary :   Converts email addresses characters to HTML entities to block spam bots by jQuery
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{mailaddress_hide_js2($mail_address);}}}
 * Example : {{{mailaddress_hide_js2('abc@xxxxx.xxxx');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function mailaddress_hide_js2($address)
{
    global $hjs , $bjs;

	list($name,$domain) = explode('@',$address);

$add_js = <<<EOF
<script type="text/javascript">
    jQuery(function($){
      var delimiter = ",";  //Set the split character to a comma
      if($('span.mhj').length !== 0) { //run only when there is e-mail address 
        $('span.mhj').each(function() {
          if($(this).text() !== ''){
            var mhj_strings = $(this).text().split(delimiter);  // split in the split character
            var pre = $.trim(mhj_strings[0]);  //remove the blank from the first part
            var domain = "&#64;" + $.trim(mhj_strings[1]);  
            //@（&#64;）And linking those to remove the blank from the back of the part
            var mhj_address =  pre + domain;    //e-mail address
            $(this).html('<a href="ma' + 'ilto:' + mhj_address + '">' + mhj_address + '</a>');
          }
        }); 
      }
    });
</script>
EOF;

	if( !strstr($hjs,'jquery.min.js')){
		global $pth;
		include_once $pth['folder']['plugins'] . 'jquery/jquery.inc.php';
		include_jQuery();
	}

	if( !strstr($bjs,'var mhj_strings')){
		$bjs .= $add_js;
	}

	return '<span class="mhj">' . $name .',' . $domain . '</span>';

}
?>