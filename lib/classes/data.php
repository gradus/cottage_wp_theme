<?php
/**
 * class thesis_data
 *
 * Handles user-submitted data formatting (input and output) for Thesis.
 *
 * @since 1.8
 * @var $allowed — HTML tags that are allowed in selected text input fields
 */
class thesis_data {
	var $allowed = '<span><em><strong><i><b><u><code><br><strike><sub><sup>';

	function i_encode($text, $allowed = false) {
		return urlencode(trim(strip_tags(stripslashes($text), ($allowed) ? $this->allowed : false)));
	}

	function i_strip($text, $allowed = true) {
		return trim(strip_tags($text, ($allowed) ? $this->allowed : false));
	}

	function o_htmlentities($text) {
		return trim(htmlentities(stripslashes($text)));
	}

	function o_texturize($text, $stripslashes = false, $decode = false) {
		return trim(wptexturize(($decode) ? urldecode($text) : (($stripslashes) ? stripslashes($text) : $text)));
	}

	function o_htmlspecialchars($text, $stripslashes = false, $decode = false) {
		return trim(htmlspecialchars(($decode) ? urldecode($text) : (($stripslashes) ? stripslashes($text) : $text)));
	}

	function o_noscripts($text) {
		return trim($this->strip_only(stripslashes($text), '<script>', true));
	}

	function strip_js($text) {
		return trim($this->strip_only($text, '<script>', true));
	}

	function strip_only($str, $tags, $stripContent = false) {
		$content = '';
		if (!is_array($tags)) {
			$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
			if (end($tags) == '') array_pop($tags);
		}
		foreach ($tags as $tag) {
			if ($stripContent) $content = '(.+</'.$tag.'[^>]*>|)';
			$str = preg_replace('#</?'.$tag.'[^>]*>'.$content.'#is', '', $str);
		}
		return $str;
	}
}