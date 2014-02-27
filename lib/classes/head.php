<?php
/**
 * class thesis_head (Formerly called Head, and Document before that. Keeping notes? I didn't think so.)
 *
 * @package Thesis
 * @since 1.2
 */
class thesis_head {
	function build() {
		$head = new thesis_head;
		$head->title();
		$head->meta();
		$head->conditional_styles();
		$head->stylesheets();
		$head->links();
		$head->scripts();
		
		echo "<head " . apply_filters('thesis_head_profile', 'profile="http://gmpg.org/xfn/11"') . ">\n"; #filter
		echo '<meta http-equiv="Content-Type" content="' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset') . '" />' . "\n"; #wp
		$head->output();
		wp_head(); #hook #wp
		echo "</head>\n";
		
		$head->add_ons(); // this is bogus and will disappear once I get this all figured out
	}

	function title() {
		global $thesis_site, $thesis_terms, $wp_query;
		$site_name = get_bloginfo('name'); #wp
		$separator = ($thesis_site->head['title']['separator']) ? urldecode($thesis_site->head['title']['separator']) : __('&#8212;', 'thesis');

		if ($wp_query->is_home || is_front_page()) { #wp
			$tagline = get_bloginfo('description'); #wp
			$home_title = ($thesis_site->home['head']['title']) ? urldecode($thesis_site->home['head']['title']) : ($tagline ? "$site_name $separator $tagline" : $site_name);

			if (get_option('show_on_front') == 'page' && is_front_page()) #wp
				$page_title = get_post_meta(get_option('page_on_front'), 'thesis_title', true); #wp
			elseif (get_option('show_on_front') == 'page' && $wp_query->is_home) #wp
				$page_title = get_post_meta(get_option('page_for_posts'), 'thesis_title', true); #wp

			$output = trim(wptexturize(($page_title) ? strip_tags(stripslashes($page_title)) : $home_title)); #wp
		}
		elseif ($wp_query->is_category || $wp_query->is_tax || $wp_query->is_tag) { #wp
			$title = trim(wptexturize(($thesis_terms->terms[$wp_query->queried_object->taxonomy][$wp_query->queried_object->term_id]['title']) ? $thesis_terms->terms[$wp_query->queried_object->taxonomy][$wp_query->queried_object->term_id]['title'] : $wp_query->queried_object->name)); #wp
			$output = ($thesis_site->head['title']['branded']) ? "$title $separator $site_name" : $title;
		}
		elseif ($wp_query->is_search) { #wp
			$search_title = __('Search:', 'thesis') . ' ' . esc_html($wp_query->query_vars['s']); #wp
			$output = ($thesis_site->head['title']['branded']) ? "$search_title $separator $site_name" : $search_title;
		}
		else {
			global $post; #wp
			$custom_title = ($wp_query->is_single || $wp_query->is_page) ? strip_tags(stripslashes(get_post_meta($post->ID, 'thesis_title', true))) : false; #wp
			$page_title = trim(($custom_title) ? wptexturize($custom_title) : wp_title('', false)); #wp
			$output = ($thesis_site->head['title']['branded']) ? "$page_title $separator $site_name" : $page_title;
		}

		if ($wp_query->query_vars['paged'] > 1) #wp
			$output .= " $separator " . __('Page', 'thesis') . " {$wp_query->query_vars['paged']}"; #wp

		$this->title['title'] = '<title>' . apply_filters('thesis_title', $output, $separator) . '</title>'; #filter
	}

	function meta() {
		global $thesis_site, $thesis_terms, $wp_query; #wp

		// robots meta
		if (get_option('blog_public') != 0) { #wp
			$page_type = ($wp_query->is_home && ($wp_query->query_vars['paged'] > 1)) ? 'sub' : (($wp_query->is_author) ? 'author' : (($wp_query->is_day) ? 'day' : (($wp_query->is_month) ? 'month' : (($wp_query->is_year) ? 'year' : false)))); #wp

			if (!$page_type && ($wp_query->is_home || is_front_page())) {
				$page_id = (get_option('show_on_front') == 'page' && is_front_page()) ? get_option('page_on_front') : ((get_option('show_on_front') == 'page' && $wp_query->is_home) ? get_option('page_for_posts') : false); #wp
				$robots_override = ($page_id) ? get_post_meta($page_id, 'thesis_robots', true) : false; #wp
				$robots_meta = ($robots_override) ? $robots_override : $thesis_site->home['head']['meta']['robots']; #wp

				if (is_array($robots_meta)) {
					foreach ($robots_meta as $meta_tag => $value)
						if ($value) $content[] = $meta_tag;
				}
			}
			elseif (!$page_type && ($wp_query->is_page || $wp_query->is_single)) { #wp
				global $post; #wp
				$robots_meta = get_post_meta($post->ID, 'thesis_robots', true); #wp

				if (is_array($robots_meta)) {
					foreach ($robots_meta as $meta_tag => $value)
						if ($value) $content[] = $meta_tag;
				}
			}
			elseif ($wp_query->is_category || $wp_query->is_tax || $wp_query->is_tag) { #wp
				global $wp_taxonomies;
				$type = $wp_taxonomies[$wp_query->queried_object->taxonomy]->hierarchical ? 'category' : 'tag';
				$robots = $thesis_terms->terms[$wp_query->queried_object->taxonomy][$wp_query->queried_object->term_id]['robots'];

				if ((isset($robots['noindex']) && $robots['noindex']) || (!isset($robots['noindex']) && $thesis_site->head['meta']['robots']['noindex'][$type]))
					$content[] = 'noindex';
				if ((isset($robots['nofollow']) && $robots['nofollow']) || (!isset($robots['nofollow']) && $thesis_site->head['meta']['robots']['nofollow'][$type]))
					$content[] = 'nofollow';
				if ((isset($robots['noarchive']) && $robots['noarchive']) || (!isset($robots['noarchive']) && $thesis_site->head['meta']['robots']['noarchive'][$type]))
					$content[] = 'noarchive';
			}
			elseif ($wp_query->is_search || $wp_query->is_404) #wp
				$content[] = 'noindex, nofollow, noarchive';
			elseif ($page_type) {
				if ($thesis_site->head['meta']['robots']['noindex'][$page_type])
					$content[] = 'noindex';
				if ($thesis_site->head['meta']['robots']['nofollow'][$page_type])
					$content[] = 'nofollow';
				if ($thesis_site->head['meta']['robots']['noarchive'][$page_type])
					$content[] = 'noarchive';
			}

			if ($thesis_site->head['meta']['robots']['noodp'])
				$content[] = 'noodp';
			if ($thesis_site->head['meta']['robots']['noydir'])
				$content[] = 'noydir';

			$meta['robots'] = ($content) ? '<meta name="robots" content="' . implode(', ', $content) . '" />' : false;
		}

		// meta description and keywords
		if (!class_exists('All_in_One_SEO_Pack')) {
			if ($wp_query->is_home || is_front_page()) {
				$page_id = (get_option('show_on_front') == 'page' && is_front_page()) ? get_option('page_on_front') : ((get_option('show_on_front') == 'page' && $wp_query->is_home) ? get_option('page_for_posts') : false); #wp
				$keywords_override = ($page_id) ? get_post_meta($page_id, 'thesis_keywords', true) : false;
				$keywords = ($keywords_override) ? $keywords_override : urldecode($thesis_site->home['head']['meta']['keywords']);
				$description_override = ($page_id && !get_post_meta($page_id, 'thesis_no_description', true)) ? strip_tags(stripslashes(get_post_meta($page_id, 'thesis_description', true))) : false; #wp
				$description = ($description_override) ? $description_override : (($thesis_site->home['head']['meta']['description']) ? urldecode($thesis_site->home['head']['meta']['description']) : get_bloginfo('description')); #wp

				if ($description)
					$meta['description'] = '<meta name="description" content="' . trim(wptexturize($description)) . '" />'; #wp
				if ($keywords)
					$meta['keywords'] = '<meta name="keywords" content="' . trim(wptexturize($keywords)) . '" />';
			}
			elseif ($wp_query->is_single || $wp_query->is_page) { #wp
				global $post; #wp
				$no_description = get_post_meta($post->ID, 'thesis_no_description', true); #wp
				$description = get_post_meta($post->ID, 'thesis_description', true); #wp
				$keywords = get_post_meta($post->ID, 'thesis_keywords', true); #wp

				if (!$no_description) {
					if (strlen($description))
						$meta['description'] = '<meta name="description" content="' . trim(wptexturize(strip_tags(stripslashes($description)))) . '" />'; #wp
					else {
						setup_postdata($post); #wp
						add_filter('excerpt_length', 'thesis_meta_excerpt_length'); #wp
						$excerpt = trim(str_replace('[...]', '', wp_trim_excerpt(''))); #wp
						remove_filter('excerpt_length', 'thesis_meta_excerpt_length'); #wp
						$meta['description'] = '<meta name="description" content="' . $excerpt . '" />';
					}
				}

				if (strlen($keywords))
					$meta['keywords'] = '<meta name="keywords" content="' . trim(wptexturize(strip_tags(stripslashes($keywords)))) . '" />'; #wp
				else {
					$tags = get_the_tags(); #wp

					if ($tags)
						foreach ($tags as $tag) $keywords[] = $tag->name;

					if ($keywords)
						$meta['keywords'] = '<meta name="keywords" content="' . implode(', ', $keywords) . '" />';
				}
			}
			elseif ($wp_query->is_category || $wp_query->is_tax || $wp_query->is_tag) { #wp
				$description = trim(wptexturize($thesis_terms->terms[$wp_query->queried_object->taxonomy][$wp_query->queried_object->term_id]['description'])); #wp
				$keywords = trim(wptexturize($thesis_terms->terms[$wp_query->queried_object->taxonomy][$wp_query->queried_object->term_id]['keywords'])); #wp
				if ($description)
					$meta['description'] = '<meta name="description" content="' . $description . '" />';
				if ($keywords)
					$meta['keywords'] = '<meta name="keywords" content="' . $keywords . '" />';
			}
		}

		if ($meta)
			$this->meta = $meta;
	}
	
	function conditional_styles() {
		global $thesis_design;

		if ($thesis_design->multimedia_box['status'] && !thesis_show_multimedia_box()) {
			$css = new Thesis_CSS;
			$css->baselines();
			$padding = round(($css->line_heights['content'] / $styles->base['num']), 1);
			$conditional_styles['mm_box'] = '<style type="text/css">#sidebars .sidebar ul.sidebar_list { padding-top: ' . $padding . 'em; }</style>';
		}
		elseif (!$thesis_design->multimedia_box['status'] && thesis_show_multimedia_box())
			$conditional_styles['mm_box'] = '<style type="text/css">#sidebars .sidebar ul.sidebar_list { padding-top: 0; }</style>';

		if ($conditional_styles)
			$this->conditional_styles = $conditional_styles;
	}
	
	function stylesheets() {
		global $thesis_site;

		// Main stylesheet
		$date_modified = filemtime(TEMPLATEPATH . '/style.css');
		$styles['core'] = array(
			'url' => get_bloginfo('stylesheet_url') . (apply_filters('thesis_cache_query', false) ? '?' . date('mdy-Gis', $date_modified) : ''), #wp
			'media' => 'screen, projection'
		);

		if (file_exists(THESIS_CUSTOM)) {
			$path = THESIS_CUSTOM;
			$url = THESIS_CUSTOM_FOLDER;
		}
		elseif (file_exists(TEMPLATEPATH . '/custom-sample')) {
			$path = TEMPLATEPATH . '/custom-sample';
			$url = THESIS_SAMPLE_FOLDER;
		}

		$date_modified = filemtime("$path/layout.css");
		$styles['layout'] = array(
			'url' => "$url/layout.css" . (apply_filters('thesis_cache_query', false) ? '?' . date('mdy-Gis', $date_modified) : ''),
			'media' => 'screen, projection'
		);

		$date_modified = filemtime(THESIS_CSS . '/ie.css');
		$styles['ie'] = array(
			'url' => THESIS_CSS_FOLDER . '/ie.css' . (apply_filters('thesis_cache_query', false) ? '?' . date('mdy-Gis', $date_modified) : ''),
			'media' => 'screen, projection'
		);

		// Custom stylesheet, if applicable
		if ($thesis_site->custom['stylesheet']) {
			$date_modified = filemtime("$path/custom.css");
			$styles['custom'] = array(
				'url' => "$url/custom.css" . (apply_filters('thesis_cache_query', false) ? '?' . date('mdy-Gis', $date_modified) : ''),
				'media' => 'screen, projection'
			);
		}

		foreach ($styles as $type => $style)
			$stylesheets[$type] = ($type == 'ie') ? sprintf('<!--[if lte IE 8]><link rel="stylesheet" href="%1$s" type="text/css" media="%2$s" /><![endif]-->', $style['url'], $style['media']) : sprintf('<link rel="stylesheet" href="%1$s" type="text/css" media="%2$s" />', $style['url'], $style['media']);

		$this->stylesheets = $stylesheets;
	}
	
	function links() {
		global $thesis_site, $thesis_favicon;

		if ($thesis_favicon->favicon)
			$links['favicon'] = "<link rel=\"shortcut icon\" href=\"$thesis_favicon->favicon\" />";

		// Canonical URL
		if ($thesis_site->head['links']['canonical']) {
			global $wp_query; #wp
			if ($wp_query->is_single || $wp_query->is_page) { #wp
				global $post;
				$url = ($wp_query->is_page && get_option('show_on_front') == 'page' && get_option('page_on_front') == $post->ID) ? trailingslashit(get_permalink()) : get_permalink(); #wp
			}
			elseif ($wp_query->is_author) { #wp
				$author = get_userdata($wp_query->query_vars['author']); #wp
				$url = get_author_link(false, $author->ID, $author->user_nicename); #wp
			}
			elseif ($wp_query->is_category || $wp_query->is_tax || $wp_query->is_tag) #wp
				$url = get_term_link($wp_query->queried_object, $wp_query->queried_object->taxonomy); #wp
			elseif ($wp_query->is_day) #wp
				$url = get_day_link($wp_query->query_vars['year'], $wp_query->query_vars['monthnum'], $wp_query->query_vars['day']); #wp
			elseif ($wp_query->is_month) #wp
				$url = get_month_link($wp_query->query_vars['year'], $wp_query->query_vars['monthnum']); #wp
			elseif ($wp_query->is_year) #wp
				$url = get_year_link($wp_query->query_vars['year']); #wp
			elseif ($wp_query->is_home) #wp
				$url = (get_option('show_on_front') == 'page') ? trailingslashit(get_permalink(get_option('page_for_posts'))) : trailingslashit(get_option('home')); #wp
				
			if ($url) $links['canonical'] = '<link rel="canonical" href="' . $url . '" />';
		}

		$feed_title = get_bloginfo('name') . ' RSS Feed'; #wp
		$xmlrpc = get_bloginfo('pingback_url');
		$links['alternate'] = '<link rel="alternate" type="application/rss+xml" title="' . $feed_title . '" href="' . thesis_feed_url() . '" />';
		$links['pingback'] = "<link rel=\"pingback\" href=\"$xmlrpc\" />";
		$links['rsd'] = "<link rel=\"EditURI\" type=\"application/rsd+xml\" title=\"RSD\" href=\"{$xmlrpc}?rsd\" />";
		if ($thesis_site->publishing['wlw']) $links['wlw'] = '<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="' . get_bloginfo('wpurl') . '/wp-includes/wlwmanifest.xml" />'; #wp
		$this->links = $links;
	}

	function scripts() {
		global $thesis_site, $thesis_design, $wp_query;

		if ($thesis_site->head['scripts'])
			$this->scripts['head'] = stripslashes($thesis_site->head['scripts']);

		if (($wp_query->is_single && get_option('thread_comments')) || ($wp_query->is_page && get_option('thread_comments') && !$thesis_design->display['comments']['disable_pages']))
			wp_enqueue_script('comment-reply');
	}

	function output() {
		$head_items = array();

		foreach ($this as $item)
			$head_items[] = implode("\n", $item);

		echo implode("\n", $head_items);
		echo "\n";
	}
	
	function add_ons() {
		// Feature box
		thesis_add_feature_box();
	}
}