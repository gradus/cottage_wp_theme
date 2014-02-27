<?php

function thesis_show_multimedia_box() {
	global $thesis_design;

	if ($thesis_design->multimedia_box['status'] || $_GET['template']) // Here, $_GET['template'] forces this to show on the theme preview
		return true;
	else {
		if (is_single() || is_page() || (get_option('show_on_front') == 'page' && is_home())) {
			if (get_option('show_on_front') == 'page' && is_home())
				$id = get_option('page_for_posts');
			else {
				global $post;
				$id = $post->ID;
			}

			$image = get_post_meta($id, 'thesis_image', true);
			$video = get_post_meta($id, 'thesis_video', true);
			$custom_code = get_post_meta($id, 'thesis_custom_code', true);
			$custom_hook = get_post_meta($id, 'thesis_custom_hook', true);

			$deprecated_image = get_post_meta($id, thesis_get_custom_field_key('image'), true);
			$deprecated_video = get_post_meta($id, thesis_get_custom_field_key('video'), true);
			$deprecated_custom = get_post_meta($id, thesis_get_custom_field_key('html'), true);

			return ($image || $video || $custom_code || $custom_hook || $deprecated_image || $deprecated_video || $deprecated_custom) ? true : false;
		}
		else
			return false;
	}
}

function thesis_multimedia_box() {
	global $thesis_design;
	
	if ($thesis_design->multimedia_box['status'])
		$box_type = $thesis_design->multimedia_box['status'];
	elseif ($_GET['template'])
		$box_type = 'image';

	if (is_single() || is_page() || (get_option('show_on_front') == 'page' && is_home())) {
		if (get_option('show_on_front') == 'page' && is_home())
			$id = get_option('page_for_posts');
		else {
			global $post;
			$id = $post->ID;
		}

		$image = get_post_meta($id, 'thesis_image', true);
		$video = get_post_meta($id, 'thesis_video', true);
		$custom_code = get_post_meta($id, 'thesis_custom_code', true);
		$custom_hook = get_post_meta($id, 'thesis_custom_hook', true);

		$deprecated_image = get_post_meta($id, thesis_get_custom_field_key('image'), true);
		$deprecated_video = get_post_meta($id, thesis_get_custom_field_key('video'), true);
		$deprecated_custom = get_post_meta($id, thesis_get_custom_field_key('html'), true);

		if ($box_type || $image || $video || $custom_code || $custom_hook || $deprecated_image || $deprecated_video || $deprecated_custom || $_GET['template']) {
			if ($image)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"image_box\">\n" . thesis_image_box($image) . "\t\t\t</div>\n";
			elseif ($video)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"video_box\">\n" . thesis_video_box($video) . "\t\t\t</div>\n";
			elseif ($custom_code)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"custom_box\">\n" . thesis_custom_box($custom_code) . "\t\t\t</div>\n";
			elseif ($custom_hook)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"custom_box\">\n" . thesis_custom_box('php') . "\t\t\t</div>\n";
			elseif ($deprecated_image)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"image_box\">\n" . thesis_image_box($deprecated_image) . "\t\t\t</div>\n";
			elseif ($deprecated_video)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"video_box\">\n" . thesis_video_box($deprecated_video) . "\t\t\t</div>\n";
			elseif ($deprecated_custom)
				echo "\t\t\t<div id=\"multimedia_box\" class=\"custom_box\">\n" . thesis_custom_box($deprecated_custom) . "\t\t\t</div>\n";
			elseif ($box_type)
				thesis_default_box($box_type);
			elseif ($_GET['template']) // This condition is hacky and only intended for theme previews inside WordPress
				echo "\t\t\t<div id=\"multimedia_box\" class=\"image_box\">\n" . thesis_image_box() . "\t\t\t</div>\n";

			thesis_hook_after_multimedia_box(); #hook
		}
	}
	elseif ($box_type) {
		thesis_default_box($box_type);
		thesis_hook_after_multimedia_box(); #hook
	}
}

function thesis_default_box($box_type = 'image') {
	if ($box_type == 'image')
		echo "\t\t\t<div id=\"multimedia_box\" class=\"image_box\">\n" . thesis_image_box() . "\t\t\t</div>\n";
	elseif ($box_type == 'video')
		echo "\t\t\t<div id=\"multimedia_box\" class=\"video_box\">\n" . thesis_video_box() . "\t\t\t</div>\n";
	elseif ($box_type == 'custom')
		echo "\t\t\t<div id=\"multimedia_box\" class=\"custom_box\">\n" . thesis_custom_box() . "\t\t\t</div>\n";
}

function thesis_image_box($image_url = false) {
	$open_box = "\t\t\t\t<div id=\"image_box\">\n";
	$close_box = "\t\t\t\t</div>\n";

	if ($image_url) {
		global $thesis_design;

		// If the server cannot handle files specified via URL,
		// break our URL into a (hopefully) local path.
		if (!$thesis_design->image['fopen']) {
			$image_path = explode($_SERVER['SERVER_NAME'], $image_url);
			$image_path = $_SERVER['DOCUMENT_ROOT'] . $image_path[1];
		}
		else
			$image_path = $image_url;

		if (@getimagesize($image_path)) {
			$image_info = @getimagesize($image_path);
			$image['src'] = $image_url;
			$image['width'] = $image_info[0];
			$image['height'] = $image_info[1];
			
			if (thesis_get_image_size_class($image['width'], $image['height']))
				$image['class'] = 'class="' . thesis_get_image_size_class($image['width'], $image['height']) . '" ';
			
			$output = '<img ' . $image['class'] . 'src="' . $image['src'] . '" alt="Custom image" />' . "\n";
		}
	}
	else
		$output = thesis_image_rotator();

	return $open_box . $output . $close_box;
}

function thesis_image_rotator() {
	global $thesis_design;
	$rotator_dir = opendir(THESIS_ROTATOR);

	while (($file = readdir($rotator_dir)) !== false) {
		$haystack = strtolower($file);
		if (strpos($haystack, '.jpg') || strpos($haystack, '.jpeg') || strpos($haystack, '.png') || strpos($haystack, '.gif'))  {
			$images[$file]['url'] = THESIS_ROTATOR_FOLDER . '/' . $file;
			$image_path = THESIS_ROTATOR . '/' . $file;
			$image_size = @getimagesize($image_path);
			$images[$file]['width'] = $image_size[0];
			$images[$file]['height'] = $image_size[1];
			
			if (thesis_get_image_size_class($image_size[0], $image_size[1]))
				$images[$file]['class'] = 'class="' . thesis_get_image_size_class($image_size[0], $image_size[1]) . '" ';
		}
	}

	if ($images)
		$random_image = array_rand($images);

	$images[$random_image]['alt'] = ($thesis_design->multimedia_box['alt_tags'][$random_image]) ? stripslashes($thesis_design->multimedia_box['alt_tags'][$random_image]) : $random_image;
	$img = '<img ' . $images[$random_image]['class'] . 'src="' . esc_url(stripslashes($images[$random_image]['url'])) . '" alt="' . esc_attr($images[$random_image]['alt']) . '" />';

	if ($thesis_design->multimedia_box['link_urls'][$random_image] != '') {
		$link_before = '<a href="' . esc_url($thesis_design->multimedia_box['link_urls'][$random_image]) . '">';
		$link_after = '</a>' . "\n";
	}
	else
		$img .= "\n";

	return $link_before . $img . $link_after;
}

function thesis_video_box($video_code = false) {
	global $thesis_design, $thesis_data;

	if ($video_code || $thesis_design->multimedia_box['video']) {
		$open_box = "\t\t\t\t<div id=\"video_box\">\n";
		$close_box = "\n\t\t\t\t</div>\n";
		$output = ($video_code) ? $thesis_data->strip_js($video_code) : (($thesis_design->multimedia_box['video']) ? $thesis_design->multimedia_box['video'] : '');
		return "$open_box<p>" . stripslashes($output) . "</p>$close_box";
	}
}

function thesis_custom_box($custom_code = false) {
	global $thesis_design, $thesis_data;
	$open_box = "\t\t\t\t<div id=\"custom_box\">\n";
	$close_box = "\n\t\t\t\t</div>\n";

	if (($custom_code && $custom_code !== 'php') || (!$custom_code && $thesis_design->multimedia_box['code'])) {
		$code = ($custom_code) ? $thesis_data->strip_js($custom_code) : $thesis_design->multimedia_box['code'];
		return $open_box . stripslashes($code) . $close_box;
	}
	else {
		ob_start();
		thesis_hook_multimedia_box();
		$output = ob_get_clean();
		return $open_box . $output . $close_box;
	}
}

function thesis_get_image_size_class($width, $height) {
	if ($width && $height) {
		$ratio = $width / $height;
		if (1.3125 < $ratio && $ratio < 1.3548)
			return 'four_by_three';
		elseif (0.7412 < $ratio && $ratio < 0.759)
			return 'three_by_four';
		elseif (1.4737 < $ratio && $ratio < 1.5273)
			return 'three_by_two';
		elseif (0.6587 < $ratio && $ratio < 0.6746)
			return 'two_by_three';
		elseif (1.2353 < $ratio && $ratio < 1.2651)
			return 'five_by_four';
		elseif (0.7925 < $ratio && $ratio < 0.8077)
			return 'four_by_five';
		elseif (1.7406 < $ratio && $ratio < 1.8166)
			return 'sixteen_by_nine';
		elseif (0.5558 < $ratio && $ratio < 0.5693)
			return 'nine_by_sixteen';
		elseif (1.981 < $ratio && $ratio < 2.019)
			return 'two_by_one';
		elseif (0.498 < $ratio && $ratio < 0.502)
			return 'one_by_two';
		elseif (0.995 < $ratio && $ratio < 1.005)
			return 'square';
	}
}