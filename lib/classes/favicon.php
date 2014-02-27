<?php

$thesis_favicon = new thesis_favicon;

class thesis_favicon {
	function thesis_favicon() {
		$saved_favicon = get_option('thesis_favicon'); #wp
		if (!empty($saved_favicon))
			$this->favicon = $saved_favicon;
	}

	function __construct() {
		$saved_favicon = get_option('thesis_favicon'); #wp
		if (!empty($saved_favicon))
			$this->favicon = $saved_favicon;
	}

	function process() {
		if (isset($_POST['upload'])) {
			check_admin_referer('thesis-favicon-upload', '_wpnonce-thesis-favicon-upload'); #wp
			$overrides = array('test_form' => false);
			$file = wp_handle_upload($_FILES['import'], $overrides); #wp

			if (isset($file['error']))
				wp_die($file['error'], __('Favicon Upload Error', 'thesis')); #wp

			if ($file['type'] == 'image/x-icon' || $file['type'] == 'image/png') {
				$this->url = $file['url'];
				$this->save($file['file']);
			}
			else
				$this->error = true; 
		}
		elseif ($_GET['remove']) {
			check_admin_referer('thesis-remove-favicon'); #wp
			unset($this->favicon);
			delete_option('thesis_favicon'); #wp
			$this->removed = true;
		}
	}

	function save($image) {
		if (!$image) return;
		$this->favicon = esc_url($this->url); #wp
		update_option('thesis_favicon', $this->favicon); #wp
		$this->updated = true;
	}

	function options_page() {
		$this->process();
		$rtl = (get_bloginfo('text_direction') == 'rtl') ? ' rtl' : ''; #wp
		echo "<div id=\"thesis_options\" class=\"wrap$rtl\">\n";
		thesis_version_indicator();
		thesis_options_title(__('Thesis Favicon Uploader', 'thesis'), false);
		thesis_options_nav();

		if ($this->updated)
			echo "<div id=\"updated\" class=\"updated fade\">\n\t<p>" . __('Favicon updated!', 'thesis') . ' <a href="' . get_bloginfo('url') . '/">' . __('Check out your site &rarr;', 'thesis') . "</a></p>\n</div>\n"; #wp
		elseif ($this->removed)
			echo "<div id=\"updated\" class=\"updated fade\">\n\t<p>" . __('Favicon removed!', 'thesis') . ' <a href="' . get_bloginfo('url') . '/">' . __('Check out your site &rarr;', 'thesis') . "</a></p>\n</div>\n"; #wp
		elseif ($this->error)
			echo "<div class=\"warning\"><p>" . __('<strong>Whoops!</strong> Your favicon was not saved because you attempted to upload an improper file type. Thesis will accept favicons with a <code>.ico</code> or <code>.png</code> extension.', 'thesis') . "</p></div>\n";
?>
	<div class="one_col">
		<div class="control_area">
<?php
		if ($this->favicon)
			echo "<p id=\"favicon\">\n\t<img src=\"{$this->favicon}\" width=\"16\" height=\"16\" alt=\"favicon preview\" title=\"favicon preview\" />\n\t" . __('&larr; That&#8217;s your favicon.', 'thesis') . " <a href=\"" . wp_nonce_url(admin_url('admin.php?page=thesis-favicon&remove=true'), 'thesis-remove-favicon') . "\" title=\"" . __('Click here to remove favicon', 'thesis') . "\">" . __('Click here to remove it.', 'thesis') . "</a>\n</p>\n"; #wp
?>
			<form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo admin_url('admin.php?page=thesis-favicon'); ?>">
				<p class="remove_bottom_margin">
					<label for="upload"><?php _e('Choose a <code>.ico</code> or <code>.png</code> image file with a square aspect ratio from your computer:', 'thesis'); ?></label>
					<input type="file" class="text" id="upload" name="import" />
					<?php wp_nonce_field('thesis-favicon-upload', '_wpnonce-thesis-favicon-upload') ?>
					<input type="submit" class="ui_button positive" name="upload" value="<?php esc_attr_e('Upload', 'thesis'); ?>" />
				</p>
			</form>
		</div>
	</div>
<?php
		echo "</div>\n";
	}
}