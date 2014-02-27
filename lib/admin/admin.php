<?php

function thesis_admin_setup() {
	global $thesis_terms;
	add_action('admin_menu', 'thesis_add_menu');
	add_action('admin_menu', array('thesis_post_options', 'add_meta_boxes'));
	add_action('admin_post_thesis_options', array('thesis_site_options', 'save_options'));
	add_action('admin_post_thesis_design_options', array('thesis_design_options', 'save_options'));
	add_action('admin_post_thesis_upgrade', 'thesis_upgrade');
	add_action('admin_post_thesis_file_editor', array('thesis_custom_editor', 'save_file'));
	$thesis_terms->actions();
	new thesis_dashboard_rss;
	add_action('init', 'thesis_options_head');
}

function thesis_add_menu() {
	global $menu, $wp_version, $thesis_header, $thesis_favicon;
	if (version_compare($wp_version, '2.9', '>=')) 
		$menu[30] = array('', 'read', 'separator-thesis', '', 'wp-menu-separator');

	add_menu_page(__('Thesis', 'thesis'), __('Thesis', 'thesis'), 'edit_themes', 'thesis-options', array('thesis_site_options', 'options_page'), THESIS_IMAGES_FOLDER . '/icon-swatch.png', 31); #wp
	add_submenu_page('thesis-options', __('Site Options', 'thesis'), __('Site Options', 'thesis'), 'edit_themes', 'thesis-options', array('thesis_site_options', 'options_page')); #wp
	add_submenu_page('thesis-options', __('Design Options', 'thesis'), __('Design Options', 'thesis'), 'edit_themes', 'thesis-design-options', array('thesis_design_options', 'options_page')); #wp
	add_submenu_page('thesis-options', __('Header Image', 'thesis'), __('Header Image', 'thesis'), 'edit_themes', 'thesis-header-image', array($thesis_header, 'options_page')); #wp
	add_submenu_page('thesis-options', __('Favicon Uploader', 'thesis'), __('Favicon Uploader', 'thesis'), 'edit_themes', 'thesis-favicon', array($thesis_favicon, 'options_page')); #wp
	add_submenu_page('thesis-options', __('Custom File Editor', 'thesis'), __('Custom File Editor', 'thesis'), 'edit_themes', 'thesis-file-editor', array('thesis_custom_editor', 'options_page')); #wp
	add_submenu_page('thesis-options', __('Manage Options', 'thesis'), __('Manage Options', 'thesis'), 'edit_themes', 'options-manager', array('thesis_options_manager', 'options_page')); #wp
}

function thesis_options_head() {
	wp_enqueue_style('thesis-options-stylesheet', THESIS_CSS_FOLDER . '/options.css'); #wp

	if ($_GET['page'] == 'thesis-file-editor')
		wp_enqueue_script('color-picker', THESIS_SCRIPTS_FOLDER . '/jscolor/jscolor.js'); #wp
	elseif ($_GET['page'] == 'options-manager') {
		$manager = new thesis_options_manager;
		$manager->add_js();
		$manager->manage_options();
	}
	else {
		wp_enqueue_script('jquery-ui-core'); #wp
		wp_enqueue_script('jquery-ui-sortable'); #wp
		wp_enqueue_script('jquery-ui-tabs'); #wp
		wp_enqueue_script('thesis-admin-js', THESIS_SCRIPTS_FOLDER . '/thesis.js'); #wp

		if ($_GET['page'] == 'thesis-design-options')
			wp_enqueue_script('color-picker', THESIS_SCRIPTS_FOLDER . '/jscolor/jscolor.js'); #wp
	}
}

/*---:[ random admin file functions that will probably have a new home at some point as Thesis grows ]:---*/

function thesis_version_indicator($depth = 1) {
	$indent = str_repeat("\t", $depth);
	echo "$indent<span id=\"thesis_version\">" . sprintf(__('You are rocking Thesis version <strong>%1$s</strong>', 'thesis'), thesis_version()) . "</span>\n";
}

function thesis_options_title($title, $switch = true, $depth = 1) {
	$indent = str_repeat("\t", $depth);
	$master_switch = ($switch) ? ' <a id="master_switch" href="" title="' . __('Big Ass Toggle Switch', 'thesis') . '"><span class="pos">+</span><span class="neg">&#8211;</span></a>' : '';
	echo "$indent<h2>$title$master_switch</h2>\n";
}

function thesis_options_nav($depth = 1) {
	$indent = str_repeat("\t", $depth);
	echo "$indent<ul id=\"thesis_links\">\n";
	echo "$indent\t<li><a href=\"http://diythemes.com/thesis/\" title=\"" . __('Thesis news plus tutorials and advice from Thesis pros!', 'thesis') . "\">" . __('Thesis Blog', 'thesis') . "</a></li>\n";
	echo "$indent\t<li><a href=\"http://diythemes.com/thesis/rtfm/\" title=\"" . __('Documentation, tutorials, and how-tos that will help you get the most out of Thesis', 'thesis') . "\">" . __('User&#8217;s Guide', 'thesis') . "</a></li>\n";
	echo "$indent\t<li><a href=\"http://diythemes.com/forums/\" title=\"" . __('Stuck? Don&#8217;t worry&#8212;you can find expert help in our support forums.', 'thesis') . "\">" . __('Support Forums', 'thesis') . "</a></li>\n";
	echo "$indent\t<li><a href=\"http://diythemes.com/affiliate-program/\" title=\"" . __('Join the Thesis Affiliate Program and earn money selling Thesis!', 'thesis') ."\">" . __('Affiliate Program', 'thesis') . "</a></li>\n";
	echo "$indent</ul>\n";
}

function thesis_options_status_check($depth = 1) {
	$indent = str_repeat("\t", $depth);

	if ($_GET['updated']) {
		echo "$indent<div id=\"updated\" class=\"updated fade\">\n";
		echo "$indent\t<p>" . __('Options updated!', 'thesis') . ' <a href="' . get_bloginfo('url') . '/">' . __('Check out your site &rarr;', 'thesis') . "</a></p>\n";
		echo "$indent</div>\n";
	}
	elseif ($_GET['upgraded']) {
		echo "$indent<div id=\"updated\" class=\"updated fade\">\n";
		echo "$indent\t<p>" . sprintf(__('Nicely done&#8212;Thesis <strong>%1$s</strong> is ready to rock. Take a moment to browse around the options panels and check out the new awesomeness, or simply <a href="%2$s">check out your site now</a>.', 'thesis'), thesis_version(), get_bloginfo('url') . '/') . "</p>\n";
		echo "$indent</div>\n";
	}
}

function thesis_is_css_writable() {
	if (file_exists(THESIS_CUSTOM)) {
		$location = '/thesis/custom/layout.css';
		$folder = false;
	}
	elseif (file_exists(TEMPLATEPATH . '/custom-sample')) {
		$location = '/thesis/custom-sample/layout.css';
		$folder = "<div class=\"warning\">\n\t<p>" . __('<strong>Attention!</strong> In order to take advantage of all the controls that Thesis offers, you need to change the name of your <code>custom-sample</code> folder to <code>custom</code>.', 'thesis') . "</p>\n</div>\n";
	}

	if (!is_writable(THESIS_LAYOUT_CSS))
		echo "<div class=\"warning\">\n\t<p><strong>" . __('Attention!', 'thesis') . '</strong> ' . sprintf(__('Your <code>' . $location . '</code> file is not writable by the server, and in order to work the full extent of its magic, Thesis needs to be able to write to this file. All you have to do is set your <code>layout.css</code> file permissions to 666, and you&#8217;ll be good to go. After setting your file permissions, you should head to the <a href="%s">Design Options</a> page and hit the save button.', 'thesis'), admin_url('admin.php?page=thesis-design-options')) . "</p>\n</div>\n";

	if ($folder) echo $folder;
}

function thesis_save_button_text() {
	global $thesis_site, $thesis_data;
	echo ($thesis_site->save_button_text) ? $thesis_data->o_texturize($thesis_site->save_button_text, true) : __('Big Ass Save Button', 'thesis');
}

class thesis_dashboard_rss {
	var $feed = 'http://diythemes.com/thesis/feed/';

	function __construct() {
		add_action('wp_dashboard_setup', array($this, 'add'));
	}

	function thesis_dashboard_rss() {
		$this->__construct();
	}

	function add() {
		add_meta_box('thesis_news_widget', __('The latest from the <strong>DIY</strong>themes Blog', 'thesis'), array($this, 'widget'), 'dashboard', 'normal', 'high');
	}

	function widget() {
		$rss = fetch_feed($this->feed);
		if (!is_wp_error($rss)) {
			$max_items = $rss->get_item_quantity(5);
			$rss_items = $rss->get_items(0, $max_items);
		}
		$out = "<div class=\"rss-widget rss-thesis\">\n\t<ul>\n";
		if (!empty($rss_items)) {
			$date_format = get_option('date_format');
			foreach ($rss_items as $item)
				$out .= "\t\t<li><a class=\"rsswidget\" href=\"" . esc_url($item->get_permalink()) . "\" title=\"" . esc_attr__($item->get_description(), 'thesis') . "\">" . esc_attr__($item->get_title(), 'thesis') . "</a> <span class=\"rss-date\">" . esc_attr__($item->get_date($date_format), 'thesis') . "</span></li>\n";
		}
		else
			$out .= "\t\t<li><a href=\"" . $this->feed . "\">" . __('Check out the <strong>DIY</strong>themes blog!') . "</a></li>\n";
		$out .= "\t</ul>\n</div>\n";
		echo $out;
	}
}