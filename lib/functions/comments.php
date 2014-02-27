<?php

function thesis_comments_link() {
	global $thesis_design;
	if (!is_single() && !is_page()) { #wp
		if (comments_open() || (!comments_open() && $thesis_design->display['comments']['show_closed'])) {
			$num_comments = get_comments_number(); #wp
			$link = (comments_open()) ? '<a href="' . get_permalink() . '#comments" rel="nofollow">' . thesis_num_comments($num_comments, true) . '</a>' : apply_filters('thesis_comments_link_closed', __('Comments on this entry are closed', 'thesis')); #wp #filter
			echo "<p class=\"to_comments\">" . apply_filters('thesis_comments_link', $link) . "</p>\n"; #filter
		}
	}
}

function default_skin_comments_link($link) {
	return "<span class=\"bracket\">{</span> $link <span class=\"bracket\">}</span>";
}

function default_skin_edit_comment_link($link) {
	return "[$link]";
}

function thesis_num_comments($num_comments, $span = false) {
	$number = ($span) ? "<span>$num_comments</span>" : $num_comments;
	$text = ($num_comments == 1) ?  __('comment', 'thesis') : __('comments', 'thesis');
	return "$number $text";
}

function thesis_comments_intro($number, $open, $type = 'comments') {
	if ($type == 'comments') {
		$id = 'comments_intro';
		$singular = __('comment', 'thesis');
		$plural = __('comments', 'thesis');
	}
	elseif ($type == 'trackbacks') {
		$id = 'trackbacks_intro';
		$singular = __('trackback', 'thesis');
		$plural = __('trackbacks', 'thesis');
	}

	$comments_text = "<span>$number</span> " . ($number == 1 ? $singular : $plural);

	if ($open && $type == 'comments') {
		if ($number == 0)
			$add_link = '&#8230; <a href="#respond" rel="nofollow">' . __('add one now', 'thesis') . '</a>';
		elseif ($number == 1)
			$add_link = '&#8230; ' . __('read it below or ', 'thesis') . '<a href="#respond" rel="nofollow">' . __('add one', 'thesis') . '</a>';
		elseif ($number > 1)
			$add_link = '&#8230; ' . __('read them below or ', 'thesis') . '<a href="#respond" rel="nofollow">' . __('add one', 'thesis') . '</a>';
	}
	else
		$add_link = '';

	$output = "\t\t\t\t<div id=\"$id\" class=\"comments_intro\">\n";
	$output .= "\t\t\t\t\t<p>" . apply_filters('thesis_comments_intro', $comments_text . $add_link, $number) . "</p>\n"; #filter
	$output .= "\t\t\t\t</div>\n\n";

	echo $output;
}

function default_skin_comments_intro($text) {
	return "<span class=\"bracket\">{</span> $text <span class=\"bracket\">}</span>";
}

function thesis_trackback_link($comment) {
	$output = '<a href="' . $comment->comment_author_url . '" rel="nofollow">' . $comment->comment_author . '</a>';
	return apply_filters('thesis_trackback_link', $output, $comment); #filter
}

function thesis_trackback_date($comment) {
	global $thesis_design;
	if ($thesis_design->comments['trackbacks']['options']['date'])
		return apply_filters('thesis_trackback_date', date(stripslashes($thesis_design->comments['trackbacks']['options']['date_format']), strtotime($comment->comment_date)), $comment); #filter
}

function default_skin_trackback_date($date) {
	return " <span>$date</span>";
}

function thesis_comments_navigation($position = 1) {
	if (get_option('page_comments')) { // Output navigation only if comment pagination is enabled.
		global $wp_query;
		$total_pages = get_comment_pages_count();
		$current_page = $wp_query->query_vars['cpage'];

		if ($total_pages > 1) {
			$nav = "\t\t\t\t<div id=\"comment_nav_$position\" class=\"prev_next\">\n";

			if ($current_page == $total_pages) {
				$nav .= "\t\t\t\t\t<p class=\"previous\">";
				$nav .= get_previous_comments_link('&larr; ' . __('Previous Comments', 'thesis'));
				$nav .= "</p>\n";
			}
			elseif ($current_page == 1) {
				$nav .= "\t\t\t\t\t<p class=\"next\">";
				$nav .= get_next_comments_link(__('Next Comments', 'thesis') . ' &rarr;');
				$nav .= "</p>\n";
			}
			elseif ($current_page < $total_pages) {
				$nav .= "\t\t\t\t\t<p class=\"previous floated\">";	
				$nav .= get_previous_comments_link('&larr; ' . __('Previous Comments', 'thesis'));
				$nav .= "</p>\n";
			
				$nav .= "\t\t\t\t\t<p class=\"next\">";
				$nav .= get_next_comments_link(__('Next Comments', 'thesis') . ' &rarr;');
				$nav .= "</p>\n";
			}

			$nav .= "\t\t\t\t</div>\n\n";

			echo apply_filters('thesis_comments_navigation', $nav, $position); #filter
		}
	}
}