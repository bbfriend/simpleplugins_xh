<?php
/***************************************************** 
 *  Plugin name : mailaddress_hide($mail)
 *  File name : index.php
 *  Summary :   Converts email addresses characters to HTML entities to block spam bots
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-jp.org>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{mailaddress_hide($mail_address);}}}
 * Example : {{{mailaddress_hide('abc@xxxxx.xxxx');}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function mailaddress_hide($mail) {

	$func = new antispambot_mailhide();

	$mailto_mail = 'mailto:'.$mail;
    $mailto_mail = $func -> antispambot($mailto_mail);
    $text		 = $func -> antispambot($mail);

	return '<a href="' .$mailto_mail . '">' .$text .'</a>';
}


class antispambot_mailhide {
/*****Original : From  Wordpress wp-includes\formatting.php ****/
	/**
	 * Converts email addresses characters to HTML entities to block spam bots.
	 *
	 * @since 0.71
	 *
	 * @param string $email_address Email address.
	 * @param int $hex_encoding Optional. Set to 1 to enable hex encoding.
	 * @return string Converted email address.
	 */
	//function antispambot( $email_address, $hex_encoding = 0 ) {
	function antispambot( $email_address, $hex_encoding = 0 ) {
		$email_no_spam_address = '';
		for ( $i = 0, $len = strlen( $email_address ); $i < $len; $i++ ) {
			$j = rand( 0, 1 + $hex_encoding );
			if ( $j == 0 ) {
				$email_no_spam_address .= '&#' . ord( $email_address[$i] ) . ';';
			} elseif ( $j == 1 ) {
				$email_no_spam_address .= $email_address[$i];
			} elseif ( $j == 2 ) {
	//			$email_no_spam_address .= '%' . zeroise( dechex( ord( $email_address[$i] ) ), 2 );
				$email_no_spam_address .= '%' . $this -> zeroise( dechex( ord( $email_address[$i] ) ), 2 );
			}
		}

		$email_no_spam_address = str_replace( '@', '&#64;', $email_no_spam_address );

		return $email_no_spam_address;
	}
	/**
	 * Add leading zeros when necessary.
	 *
	 * If you set the threshold to '4' and the number is '10', then you will get
	 * back '0010'. If you set the threshold to '4' and the number is '5000', then you
	 * will get back '5000'.
	 *
	 * Uses sprintf to append the amount of zeros based on the $threshold parameter
	 * and the size of the number. If the number is large enough, then no zeros will
	 * be appended.
	 *
	 * @since 0.71
	 *
	 * @param mixed $number Number to append zeros to if not greater than threshold.
	 * @param int $threshold Digit places number needs to be to not have zeros added.
	 * @return string Adds leading zeros to number if needed.
	 */
	//function zeroise($number, $threshold) {
	function zeroise($number, $threshold) {
		return sprintf('%0'.$threshold.'s', $number);
	}
}

?>