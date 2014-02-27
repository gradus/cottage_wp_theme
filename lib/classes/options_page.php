<?php
/**
 * class thesis_page_options
 *
 * @package Thesis
 * @since 1.7
 * @deprecated 1.8
 */
class thesis_page_options {
	var $terms_table = 'thesis_terms';

	function get_options() {
		$saved_options = maybe_unserialize(get_option('thesis_pages'));
		if (!empty($saved_options) && is_object($saved_options)) {
			foreach ($saved_options as $option_name => $value)
				$this->$option_name = $value;
		}
	}

	function upgrade_terms() {
		global $wpdb;
		$table = $wpdb->prefix . $this->terms_table;

		if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
			if (is_array($this->categories)) {
				foreach ($this->categories as $id => $category) {
					$object = &get_category($id);
					if ($object && is_array($category['head'])) {
						$robots = (is_array($category['head']['meta']['robots'])) ? serialize($category['head']['meta']['robots']) : '';
						$query = "INSERT INTO $table (term_id, name, taxonomy, title, description, keywords, robots, headline, content) VALUES ('$id', '$object->name', '$object->taxonomy', '" . urldecode($category['head']['title']) . "', '" . urldecode($category['head']['meta']['description']) . "', '" . urldecode($category['head']['meta']['keywords']) . "', '$robots', '', '')";
						$wpdb->query($query);
					}
				}
			}

			if (is_array($this->tags)) {
				foreach ($this->tags as $id => $tag) {
					$object = &get_tag($id);
					if ($object && is_array($tag['head'])) {
						$robots = (is_array($tag['head']['meta']['robots'])) ? serialize($tag['head']['meta']['robots']) : '';
						$query = "INSERT INTO $table (term_id, name, taxonomy, title, description, keywords, robots, headline, content) VALUES ('$id', '$object->name', '$object->taxonomy', '" . urldecode($tag['head']['title']) . "', '" . urldecode($tag['head']['meta']['description']) . "', '" . urldecode($tag['head']['meta']['keywords']) . "', '$robots', '', '')";
						$wpdb->query($query);
					}
				}
			}
		}
	}
}