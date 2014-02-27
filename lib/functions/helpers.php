<?php

function thesis_meta_excerpt_length($length) {
	return (apply_filters('thesis_meta_excerpt_length', 40)); #filter
}

function thesis_get_categories($flip = false) {
	$raw_categories = &get_categories('type=post'); #wp
	if ($raw_categories) {
		foreach ($raw_categories as $category)
			$categories[$category->slug] = $category->cat_ID;
		return ($flip) ? array_flip($categories) : $categories;
	}
}

function thesis_get_tags($flip = false) {
	$raw_tags = &get_tags('taxonomy=post_tag'); #wp
	if ($raw_tags) {
		foreach ($raw_tags as $tag)
			$tags[$tag->slug] = $tag->term_id;
		return ($flip) ? array_flip($tags) : $tags;
	}
}

function thesis_get_author_data($author_id, $field = false) {
	if ($author_id) {
		$author = get_userdata($author_id); #wp
		return ($field && !empty($author->$field)) ? $author->$field : $author;
	}
}