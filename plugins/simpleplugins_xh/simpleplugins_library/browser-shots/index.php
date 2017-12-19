<?php
/***************************************************** 
 *  Plugin name : browser-shots
 *  File name : index.php
 *  Summery   : Automate the process of taking website screenshots. 
 *  Version   : 0.1
 *  Auther    : Takashi Uchiyama <http://cmsimple-xh.org/>
 *  License : GPLv3.
 *****************************************************
 * Useage : {{{browser_shots($string [,$width, $height]);}}}
 * Example : {{{browser_shots('http://cmsimple-jp.org',300,225);}}}
******************************************************/
/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


function browser_shots($url , $width = 300 , $height = 225) {

	$func = new BrowserShots2();

	$imageUrl = 'http://s0.wordpress.com/mshots/v1/' . urlencode($func->esc_url($url)) . '?w=' . $width . '&h=' . $height ;
//	$imageUrl = 'http://s0.wordpress.com/mshots/v1/' . urlencode($url) . '?w=' . $width . '&h=' . $height ;

	if ($imageUrl == '') {
		return '';
	} else {
		return '<img src="' . $imageUrl . '" alt="' . $url . '" />';
	}

}


class BrowserShots2 {
	/**
	 * Perform a deep string replace operation to ensure the values in $search are no longer present
	 *
	 * Repeats the replacement operation until it no longer replaces anything so as to remove "nested" values
	 * e.g. $subject = '%0%0%0DDD', $search ='%0D', $result ='' rather than the '%0%0DD' that
	 * str_replace would return
	 *
	 * @since 2.8.1
	 * @access private
	 *
	 * @param string|array $search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles.
	 * @param string $subject The string being searched and replaced on, otherwise known as the haystack.
	 * @return string The string with the replaced svalues.
	 */
	public function _deep_replace( $search, $subject ) {
		$subject = (string) $subject;

		$count = 1;
		while ( $count ) {
			$subject = str_replace( $search, '', $subject, $count );
		}

		return $subject;
	}

	/**
	 * Checks and cleans a URL.
	 *
	 * A number of characters are removed from the URL. If the URL is for displaying
	 * (the default behaviour) ampersands are also replaced. The 'clean_url' filter
	 * is applied to the returned cleaned URL.
	 *
	 * @since 2.8.0
	 *
	 * @param string $url The URL to be cleaned.
	 * @param array $protocols Optional. An array of acceptable protocols.
	 *		Defaults to 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn' if not set.
	 * @param string $_context Private. Use esc_url_raw() for database usage.
	 * @return string The cleaned $url after the 'clean_url' filter is applied.
	 */
	public function esc_url( $url, $protocols = null, $_context = 'display' ) {
		$original_url = $url;

		if ( '' == $url )
			return $url;
		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = $this -> _deep_replace($strip, $url);
		$url = str_replace(';//', '://', $url);
		/* If the URL doesn't appear to contain a scheme, we
		 * presume it needs http:// appended (unless a relative
		 * link starting with /, # or ? or a php file).
		 */
		if ( strpos($url, ':') === false && ! in_array( $url[0], array( '/', '#', '?' ) ) &&
			! preg_match('/^[a-z0-9-]+?\.php/i', $url) )
			$url = 'http://' . $url;

		// Replace ampersands and single quotes only when displaying.
		if ( 'display' == $_context ) {
			$url = $this -> wp_kses_normalize_entities( $url );
			$url = str_replace( '&amp;', '&#038;', $url );
			$url = str_replace( "'", '&#039;', $url );
		}

		if ( '/' === $url[0] ) {
			$good_protocol_url = $url;
		} else {
			if ( ! is_array( $protocols ) )
	//			$protocols = wp_allowed_protocols();
				$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn', 'tel', 'fax', 'xmpp' );
			$good_protocol_url = $this -> wp_kses_bad_protocol( $url, $protocols );
			if ( strtolower( $good_protocol_url ) != strtolower( $url ) )
				return '';
		}

		/**
		 * Filter a string cleaned and escaped for output as a URL.
		 *
		 * @since 2.3.0
		 *
		 * @param string $good_protocol_url The cleaned URL to be returned.
		 * @param string $original_url      The URL prior to cleaning.
		 * @param string $_context          If 'display', replace ampersands and single quotes only.
		 */
	//	return apply_filters( 'clean_url', $good_protocol_url, $original_url, $_context );
		return $good_protocol_url;
	}

	/**
	 * Converts and fixes HTML entities.
	 *
	 * This function normalizes HTML entities. It will convert `AT&T` to the correct
	 * `AT&amp;T`, `&#00058;` to `&#58;`, `&#XYZZY;` to `&amp;#XYZZY;` and so on.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Content to normalize entities
	 * @return string Content with normalized entities
	 */
	private function wp_kses_normalize_entities($string) {
		// Disarm all entities by converting & to &amp;

		$string = str_replace('&', '&amp;', $string);

		// Change back the allowed entities in our entity whitelist

//		$string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2});/', 'wp_kses_named_entities', $string);
//		$string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', 'wp_kses_normalize_entities2', $string);
//		$string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', 'wp_kses_normalize_entities3', $string);
// +++ Warning: preg_replace_callback(): Requires argument 2

		//array($this, '***')  >= PHP5.2
		$string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2});/', array($this, 'wp_kses_named_entities'), $string);
		$string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', array($this, 'wp_kses_normalize_entities2'), $string);
		$string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', array($this, 'wp_kses_normalize_entities3'), $string);

		// "self:: ****" >= PHP5.3
//		$string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2});/', "self::wp_kses_named_entities", $string);
//		$string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', "self::wp_kses_normalize_entities2", $string);
//		$string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', "self::wp_kses_normalize_entities3", $string);


		return $string;
	}

	/**
	 * Callback for wp_kses_normalize_entities() regular expression.
	 *
	 * This function only accepts valid named entity references, which are finite,
	 * case-sensitive, and highly scrutinized by HTML and XML validators.
	 *
	 * @since 3.0.0
	 *
	 * @param array $matches preg_replace_callback() matches array
	 * @return string Correctly encoded entity
	 */
	private function wp_kses_named_entities($matches) {
		global $allowedentitynames;

		if ( empty($matches[1]) )
			return '';

		$i = $matches[1];
		return ( ( ! in_array($i, $allowedentitynames) ) ? "&amp;$i;" : "&$i;" );
	}
	/**
	 * Callback for wp_kses_normalize_entities() regular expression.
	 *
	 * This function helps {@see wp_kses_normalize_entities()} to only accept 16-bit
	 * values and nothing more for `&#number;` entities.
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @param array $matches preg_replace_callback() matches array
	 * @return string Correctly encoded entity
	 */
	private function wp_kses_normalize_entities2($matches) {
		if ( empty($matches[1]) )
			return '';

		$i = $matches[1];
		if (valid_unicode($i)) {
			$i = str_pad(ltrim($i,'0'), 3, '0', STR_PAD_LEFT);
			$i = "&#$i;";
		} else {
			$i = "&amp;#$i;";
		}

		return $i;
	}

	/**
	 * Callback for wp_kses_normalize_entities() for regular expression.
	 *
	 * This function helps wp_kses_normalize_entities() to only accept valid Unicode
	 * numeric entities in hex form.
	 *
	 * @access private
	 *
	 * @param array $matches preg_replace_callback() matches array
	 * @return string Correctly encoded entity
	 */
	private function wp_kses_normalize_entities3($matches) {
		if ( empty($matches[1]) )
			return '';

		$hexchars = $matches[1];
		return ( ( ! valid_unicode(hexdec($hexchars)) ) ? "&amp;#x$hexchars;" : '&#x'.ltrim($hexchars,'0').';' );
	}
	/**
	 * Helper function to determine if a Unicode value is valid.
	 *
	 * @param int $i Unicode value
	 * @return bool True if the value was a valid Unicode number
	 */
	private function valid_unicode($i) {
		return ( $i == 0x9 || $i == 0xa || $i == 0xd ||
				($i >= 0x20 && $i <= 0xd7ff) ||
				($i >= 0xe000 && $i <= 0xfffd) ||
				($i >= 0x10000 && $i <= 0x10ffff) );
	}
	/**
	 * Sanitize string from bad protocols.
	 *
	 * This function removes all non-allowed protocols from the beginning of
	 * $string. It ignores whitespace and the case of the letters, and it does
	 * understand HTML entities. It does its work in a while loop, so it won't be
	 * fooled by a string like "javascript:javascript:alert(57)".
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Content to filter bad protocols from
	 * @param array $allowed_protocols Allowed protocols to keep
	 * @return string Filtered content
	 */
	private function wp_kses_bad_protocol($string, $allowed_protocols) {
		$string = $this -> wp_kses_no_null($string);
		$iterations = 0;

		do {
			$original_string = $string;
			$string = $this -> wp_kses_bad_protocol_once($string, $allowed_protocols);
		} while ( $original_string != $string && ++$iterations < 6 );

		if ( $original_string != $string )
			return '';

		return $string;
	}
	/**
	 * Removes any invalid control characters in $string.
	 *
	 * Also removes any instance of the '\0' string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string
	 * @return string
	 */
	private function wp_kses_no_null($string) {
		$string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $string);
		$string = preg_replace('/(\\\\0)+/', '', $string);

		return $string;
	}
	/**
	 * Sanitizes content from bad protocols and other characters.
	 *
	 * This function searches for URL protocols at the beginning of $string, while
	 * handling whitespace and HTML entities.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Content to check for bad protocols
	 * @param string $allowed_protocols Allowed protocols
	 * @return string Sanitized content
	 */
	private function wp_kses_bad_protocol_once($string, $allowed_protocols, $count = 1 ) {
		$string2 = preg_split( '/:|&#0*58;|&#x0*3a;/i', $string, 2 );
		if ( isset($string2[1]) && ! preg_match('%/\?%', $string2[0]) ) {
			$string = trim( $string2[1] );
			$protocol = $this -> wp_kses_bad_protocol_once2( $string2[0], $allowed_protocols );
			if ( 'feed:' == $protocol ) {
				if ( $count > 2 )
					return '';
				$string = $this -> wp_kses_bad_protocol_once( $string, $allowed_protocols, ++$count );
				if ( empty( $string ) )
					return $string;
			}
			$string = $protocol . $string;
		}

		return $string;
	}

	/**
	 * Callback for wp_kses_bad_protocol_once() regular expression.
	 *
	 * This function processes URL protocols, checks to see if they're in the
	 * whitelist or not, and returns different data depending on the answer.
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @param string $string URI scheme to check against the whitelist
	 * @param string $allowed_protocols Allowed protocols
	 * @return string Sanitized content
	 */
	private function wp_kses_bad_protocol_once2( $string, $allowed_protocols ) {
		$string2 = $this -> wp_kses_decode_entities($string);
		$string2 = preg_replace('/\s/', '', $string2);
		$string2 = $this -> wp_kses_no_null($string2);
		$string2 = strtolower($string2);

		$allowed = false;
		foreach ( (array) $allowed_protocols as $one_protocol )
			if ( strtolower($one_protocol) == $string2 ) {
				$allowed = true;
				break;
			}

		if ($allowed)
			return "$string2:";
		else
			return '';
	}
	/**
	 * Convert all entities to their character counterparts.
	 *
	 * This function decodes numeric HTML entities (`&#65;` and `&#x41;`).
	 * It doesn't do anything with other entities like &auml;, but we don't
	 * need them in the URL protocol whitelisting system anyway.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Content to change entities
	 * @return string Content after decoded entities
	 */
	private function wp_kses_decode_entities($string) {

//		$string = preg_replace_callback('/&#([0-9]+);/', '_wp_kses_decode_entities_chr', $string);
//		$string = preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', '_wp_kses_decode_entities_chr_hexdec', $string);
// +++ Warning: preg_replace_callback(): Requires argument 2

		//array($this, '***')  >= PHP5.2
		$string = preg_replace_callback('/&#([0-9]+);/', array($this, '_wp_kses_decode_entities_chr'), $string);
		$string = preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', array($this,'_wp_kses_decode_entities_chr_hexdec'), $string);


		// "self:: ****" >= PHP5.3
//		$string = preg_replace_callback('/&#([0-9]+);/', "self::_wp_kses_decode_entities_chr", $string);
//		$string = preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', "self::_wp_kses_decode_entities_chr_hexdec", $string);

//		$string = preg_replace_callback('/&#([0-9]+);/', array(get_class($this), '_wp_kses_decode_entities_chr'), $string);
//		$string = preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', array(get_class($this), '_wp_kses_decode_entities_chr_hexdec'), $string);
		return $string;
	}

	/**
	 * Regex callback for wp_kses_decode_entities()
	 *
	 * @param array $match preg match
	 * @return string
	 */
	function _wp_kses_decode_entities_chr( $match ) {
		return chr( $match[1] );
	}

	/**
	 * Regex callback for wp_kses_decode_entities()
	 *
	 * @param array $match preg match
	 * @return string
	 */
	function _wp_kses_decode_entities_chr_hexdec( $match ) {
		return chr( hexdec( $match[1] ) );
	}

}


?>
