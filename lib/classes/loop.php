<?php

/**
 * class thesis_loop
 *
 * @since 1.8
 */
class thesis_loop {
	function thesis_loop() { // PHP 4 constructor, grr...
		global $wp_query;
		$loop = ($wp_query->is_page) ? ((is_front_page()) ? 'front' : 'page') : (($wp_query->is_home) ? 'home' : (($wp_query->is_single) ? (($wp_query->is_attachment) ? 'attachment' : 'single') : (($wp_query->is_category) ? 'category' : (($wp_query->is_tag) ? 'tag' : (($wp_query->is_tax) ? 'tax' : (($wp_query->is_archive) ? (($wp_query->is_day) ? 'day' : (($wp_query->is_month) ? 'month' : (($wp_query->is_year) ? 'year' : (($wp_query->is_author) ? 'author' : 'archive')))) : (($wp_query->is_search) ? 'search' : (($wp_query->is_404) ? 'fourohfour' : 'nothing'))))))));
		call_user_func(apply_filters('thesis_custom_loop', array($this, $loop)));
	}

	function __construct() { // PHP 5 constructor
		global $wp_query;
		$loop = ($wp_query->is_page) ? ((is_front_page()) ? 'front' : 'page') : (($wp_query->is_home) ? 'home' : (($wp_query->is_single) ? (($wp_query->is_attachment) ? 'attachment' : 'single') : (($wp_query->is_category) ? 'category' : (($wp_query->is_tag) ? 'tag' : (($wp_query->is_tax) ? 'tax' : (($wp_query->is_archive) ? (($wp_query->is_day) ? 'day' : (($wp_query->is_month) ? 'month' : (($wp_query->is_year) ? 'year' : (($wp_query->is_author) ? 'author' : 'archive')))) : (($wp_query->is_search) ? 'search' : (($wp_query->is_404) ? 'fourohfour' : 'nothing'))))))));
		call_user_func(apply_filters('thesis_custom_loop', array($this, $loop)));
	}

	function front() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'page')));
	}

	function page() {
		global $post, $thesis_design;
		while (have_posts()) { #wp
			the_post(); #wp
			$post_image = thesis_post_image_info('image');

			thesis_hook_before_post_box();
			echo "\t\t\t<div class=\"post_box top\" id=\"post-" . get_the_ID() . "\">\n"; #wp
			thesis_hook_post_box_top();
			thesis_headline_area(false, $post_image);
			echo "\t\t\t\t<div class=\"format_text\">\n";
			thesis_post_content(false, $post_image);
			echo "\t\t\t\t</div>\n";
			thesis_hook_post_box_bottom();
			echo "\t\t\t</div>\n";
			thesis_hook_after_post_box();

			if (!$thesis_design->display['comments']['disable_pages'])
				comments_template(); #wp
		}
	}

	function home() {
		$post_count = 1;
		$teaser_count = 1;

		while (have_posts()) { #wp
			the_post(); #wp

			if (apply_filters('thesis_is_teaser', thesis_is_teaser($post_count))) {
				if (($teaser_count % 2) == 1) {
					$top = ($post_count == 1) ? ' top' : '';
					$open_box = "\t\t\t<div class=\"teasers_box$top\">\n\n";
					$close_box = '';
					$right = false;
				}
				else {
					$open_box = '';
					$close_box = "\t\t\t</div>\n\n";
					$right = true;
				}

				if ($open_box != '') {
					echo $open_box;
					thesis_hook_before_teasers_box($post_count);
				}

				thesis_teaser($classes, $post_count, $right);

				if ($close_box != '') {
					echo $close_box;
					thesis_hook_after_teasers_box($post_count);
				}

				$teaser_count++;
			}
			else {
				$classes = 'post_box';

				if ($post_count == 1)
					$classes .= ' top';

				thesis_post_box($classes, $post_count);
			}

			$post_count++;
		}

		if ((($teaser_count - 1) % 2) == 1)
			echo "\t\t\t</div>\n\n";
	}

	function single() {
		while (have_posts()) { #wp
			the_post(); #wp
			$classes = 'post_box top';
			thesis_post_box($classes);
			comments_template(); #wp
		}
	}

	function image() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'single')));
	}

	function attachment() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'single')));
	}

	function category() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function tag() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function tax() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function archive() {
		global $thesis_design;

		thesis_archive_intro();

		if ($thesis_design->display['archives']['style'] == 'titles') {
			$post_count = 1;

			while (have_posts()) {
				the_post();
				$classes = 'post_box';
				$post_image = thesis_post_image_info('image');

				if ($post_count == 1)
					$classes .= ' top';

				thesis_hook_before_post_box($post_count);
?>
			<div <?php post_class($classes); ?> id="post-<?php the_ID(); ?>">
<?php thesis_headline_area($post_count, $post_image); ?>
			</div>

<?php
				thesis_hook_after_post_box($post_count);

				$post_count++;
			}
		}
		else
			call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'home')));
	}

	function day() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function month() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function year() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function author() {
		call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
	}

	function search() {
		if (have_posts())
			call_user_func(apply_filters('thesis_custom_loop', array('thesis_loop', 'archive')));
		else {
			global $wp_query;
?>
			<div class="post_box top">
				<div class="headline_area">
					<h2><?php esc_html_e($wp_query->query_vars['s']); ?></h2>
				</div>
				<div class="format_text">
					<p><?php _e('Sorry, but no results were found.', 'thesis'); ?></p>
				</div>
			</div>

<?php
		}
	}

	function fourohfour() {
?>
			<div class="post_box top">
				<?php thesis_headline_area(); ?>
				<div class="format_text">
<?php thesis_hook_404_content(); ?>
				</div>
			</div>

<?php
	}

	function nothing() {
?>
			<div class="post_box top">
				<div class="headline_area">
					<h2><?php _e('There&#8217;s nothing here.', 'thesis'); ?></h2>
				</div>
				<div class="format_text">
					<p><?php printf(__('If there were posts in the database, you&#8217;d be seeing them. Try <a href="%s">creating a post</a>, and see if that solves your problem.', 'thesis'), get_bloginfo('url') . '/wp-admin/post-new.php'); ?></p>
				</div>
			</div>

<?php
	}
}

/**
 * class thesis_custom_loop
 *
 * Simple API for über-minimal custom loops. Supported class extension methods (which
 * correspond with potential $loop values) are: front, page, home, single, image,
 * attachment, category, tag, day, month, year, author, archive, search, fourohfour,
 * and nothing.
 * 
 * @since 1.8
 * @uses $loop a pre-calculated array from Thesis specifying which loop to run
 */
class thesis_custom_loop {
	function thesis_custom_loop() { // PHP 4 constructor
		add_filter('thesis_custom_loop', array($this, 'loop'));
	}

	function __construct() { // PHP 5 constructor
		add_filter('thesis_custom_loop', array($this, 'loop'));
	}

	function loop($loop) {
		return (method_exists($this, $loop[1])) ? array($this, $loop[1]) : $loop;
	}
}