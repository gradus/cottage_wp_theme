<?php
/**
 * class thesis_fonts
 *
 * Handles the fonts available in the Thesis CSS generator.
 * Note: class Fonts, which debuted in 1.1, was replaced by this class in 1.8
 *
 * @since 1.8
 */
class thesis_fonts {
	var $fonts = array(
		'arial' => array(
			'name' => 'Arial',
			'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'arial_black' => array(
			'name' => 'Arial Black',
			'family' => '"Arial Black", "Arial Bold", Arial, sans-serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'arial_narrow' => array(
			'name' => 'Arial Narrow',
			'family' => '"Arial Narrow", Arial, "Helvetica Neue", Helvetica, sans-serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'cantarell' => array(
			'name' => 'Cantarell',
			'family' => 'Cantarell, Candara, Verdana, sans-serif',
			'web_safe' => true,
			'google' => array('regular', 'italic', 'bold', 'bolditalic'),
			'monospace' => false
		),
		'cardo' => array(
			'name' => 'Cardo',
			'family' => 'Cardo, "Times New Roman", Times, serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'courier_new' => array(
			'name' => 'Courier New',
			'family' => '"Courier New", Courier, Verdana, sans-serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => true
		),
		'crimson_text' => array(
			'name' => 'Crimson Text',
			'family' => '"Crimson Text", "Times New Roman", Times, serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'droid_sans' => array(
			'name' => 'Droid Sans',
			'family' => '"Droid Sans", "Lucida Grande", Tahoma, sans-serif',
			'web_safe' => true,
			'google' => array('regular', 'bold'),
			'monospace' => false
		),
		'droid_mono' => array(
			'name' => 'Droid Sans Mono',
			'family' => '"Droid Sans Mono", Consolas, Monaco, Courier, sans-serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => true
		),
		'droid_serif' => array(
			'name' => 'Droid Serif',
			'family' => '"Droid Serif", Calibri, "Times New Roman", serif',
			'web_safe' => true,
			'google' => array('regular', 'italic', 'bold', 'bolditalic'),
			'monospace' => false
		),
		'georgia' => array(
			'name' => 'Georgia',
			'family' => 'Georgia, "Times New Roman", Times, serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'im_fell_dw_pica' => array(
			'name' => 'IM Fell DW Pica',
			'family' => '"IM Fell DW Pica", "Times New Roman", serif',
			'web_safe' => true,
			'google' => array('regular', 'italic'),
			'monospace' => false
		),
		'im_fell_dw_pica_sc' => array(
			'name' => 'IM Fell DW Pica SC',
			'family' => '"IM Fell DW Pica SC", "Times New Roman", serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'im_fell_double_pica' => array(
			'name' => 'IM Fell Double Pica',
			'family' => '"IM Fell Double Pica", "Times New Roman", serif',
			'web_safe' => true,
			'google' => array('regular', 'italic'),
			'monospace' => false
		),
		'im_fell_double_pica_sc' => array(
			'name' => 'IM Fell Double Pica SC',
			'family' => '"IM Fell Double Pica SC", "Times New Roman", serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'im_fell_english' => array(
			'name' => 'IM Fell English',
			'family' => '"IM Fell English", "Times New Roman", serif',
			'web_safe' => true,
			'google' => array('regular', 'italic'),
			'monospace' => false
		),
		'im_fell_english_sc' => array(
			'name' => 'IM Fell English SC',
			'family' => '"IM Fell English SC", "Times New Roman", serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'im_fell_french_canon' => array(
			'name' => 'IM Fell French Canon',
			'family' => '"IM Fell French Canon", "Times New Roman", serif',
			'web_safe' => true,
			'google' => array('regular', 'italic'),
			'monospace' => false
		),
		'im_fell_french_canon_sc' => array(
			'name' => 'IM Fell French Canon SC',
			'family' => '"IM Fell French Canon SC", "Times New Roman", serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'im_fell_great_primer' => array(
			'name' => 'IM Fell Great Primer',
			'family' => '"IM Fell Great Primer", "Times New Roman", serif',
			'web_safe' => true,
			'google' => array('regular', 'italic'),
			'monospace' => false
		),
		'im_fell_great_primer_sc' => array(
			'name' => 'IM Fell Great Primer SC',
			'family' => '"IM Fell Great Primer SC", "Times New Roman", serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'inconsolata' => array(
			'name' => 'Inconsolata',
			'family' => '"Inconsolata", Consolas, Monaco, Courier, sans-serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => true
		),
		'josefin_sans' => array(
			'name' => 'Josefin Sans Std Light',
			'family' => '"Josefin Sans Std Light", "Century Gothic", Verdana, sans-serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'lobster' => array(
			'name' => 'Lobster',
			'family' => 'Lobster, Arial, sans-serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'molengo' => array(
			'name' => 'Molengo',
			'family' => 'Molengo, "Trebuchet MS", Corbel, Arial, sans-serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'nobile' => array(
			'name' => 'Nobile',
			'family' => 'Nobile, Corbel, Arial, sans-serif',
			'web_safe' => true,
			'google' => array('regular', 'italic', 'bold', 'bolditalic'),
			'monospace' => false
		),
		'ofl_sorts_mill_goudy' => array(
			'name' => 'OFL Sorts Mill Goudy TT',
			'family' => '"OFL Sorts Mill Goudy TT", Georgia, serif',
			'web_safe' => true,
			'google' => array('regular', 'italic'),
			'monospace' => false
		),
		'old_standard' => array(
			'name' => 'Old Standard TT',
			'family' => '"Old Standard TT", "Times New Roman", Times, serif',
			'web_safe' => true,
			'google' => array('regular', 'italic', 'bold'),
			'monospace' => false
		),
		'reenie_beanie' => array(
			'name' => 'Reenie Beanie',
			'family' => '"Reenie Beanie", Arial, sans-serif',
			'web_safe' => true,
			'google' => true,
			'monospace' => false
		),
		'tangerine' => array(
			'name' => 'Tangerine',
			'family' => 'Tangerine, "Times New Roman", Times, serif',
			'web_safe' => true,
			'google' => array('regular', 'bold'),
			'monospace' => false
		),
		'times_new_roman' => array(
			'name' => 'Times New Roman',
			'family' => '"Times New Roman", Times, Georgia, serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'trebuchet_ms' => array(
			'name' => 'Trebuchet MS',
			'family' => '"Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Arial, sans-serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'verdana' => array(
			'name' => 'Verdana',
			'family' => 'Verdana, sans-serif',
			'web_safe' => true,
			'google' => false,
			'monospace' => false
		),
		'vollkorn' => array(
			'name' => 'Vollkorn',
			'family' => 'Vollkorn, Georgia, serif',
			'web_safe' => true,
			'google' => array('regular', 'bold'),
			'monospace' => false
		),
		'yanone' => array(
			'name' => 'Yanone Kaffeesatz',
			'family' => '"Yanone Kaffeesatz", Arial, sans-serif',
			'web_safe' => true,
			'google' => array('200', '300', '400', '700'),
			'monospace' => false
		),
		'american_typewriter' => array(
			'name' => 'American Typewriter',
			'family' => '"American Typewriter", Georgia, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'andale' => array(
			'name' => 'Andale Mono',
			'family' => '"Andale Mono", Consolas, Monaco, Courier, "Courier New", Verdana, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => true
		),
		'baskerville' => array(
			'name' => 'Baskerville',
			'family' => 'Baskerville, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'bookman_old_style' => array(
			'name' => 'Bookman Old Style',
			'family' => '"Bookman Old Style", Georgia, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'calibri' => array(
			'name' => 'Calibri',
			'family' => 'Calibri, "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'cambria' => array(
			'name' => 'Cambria',
			'family' => 'Cambria, Georgia, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'candara' => array(
			'name' => 'Candara',
			'family' => 'Candara, Verdana, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'century_gothic' => array(
			'name' => 'Century Gothic',
			'family' => '"Century Gothic", "Apple Gothic", Verdana, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'century_schoolbook' => array(
			'name' => 'Century Schoolbook',
			'family' => '"Century Schoolbook", Georgia, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'consolas' => array(
			'name' => 'Consolas',
			'family' => 'Consolas, "Andale Mono", Monaco, Courier, "Courier New", Verdana, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => true
		),
		'constantia' => array(
			'name' => 'Constantia',
			'family' => 'Constantia, Georgia, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'corbel' => array(
			'name' => 'Corbel',
			'family' => 'Corbel, "Lucida Grande", "Lucida Sans Unicode", Arial, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'franklin_gothic' => array(
			'name' => 'Franklin Gothic Medium',
			'family' => '"Franklin Gothic Medium", Arial, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'garamond' => array(
			'name' => 'Garamond',
			'family' => 'Garamond, "Hoefler Text", "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'gill_sans' => array(
			'name' => 'Gill Sans',
			'family' => '"Gill Sans MT", "Gill Sans", Calibri, "Trebuchet MS", sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'helvetica' => array(
			'name' => 'Helvetica',
			'family' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'hoefler' => array(
			'name' => 'Hoefler Text',
			'family' => '"Hoefler Text", Garamond, "Times New Roman", Times, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'lucida_bright' => array(
			'name' => 'Lucida Bright',
			'family' => '"Lucida Bright", Cambria, Georgia, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'lucida_grande' => array(
			'name' => 'Lucida Grande',
			'family' => '"Lucida Grande", "Lucida Sans", "Lucida Sans Unicode", sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'palatino' => array(
			'name' => 'Palatino',
			'family' => '"Palatino Linotype", Palatino, Georgia, "Times New Roman", Times, serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'rockwell' => array(
			'name' => 'Rockwell',
			'family' => 'Rockwell, "Arial Black", "Arial Bold", Arial, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		),
		'tahoma' => array(
			'name' => 'Tahoma',
			'family' => 'Tahoma, Geneva, Verdana, sans-serif',
			'web_safe' => false,
			'google' => false,
			'monospace' => false
		)
	);
	
	function import_css($fonts) {
		foreach(array_unique($fonts) as $id)
			$family[] = urlencode($this->fonts[$id]['name']) . (is_array($this->fonts[$id]['google']) ? ':' . implode(',', $this->fonts[$id]['google']) : '');
		return '@import url(http://fonts.googleapis.com/css?family=' . implode('|', $family) . ");\n";
	}
}