<?php

$thesis_header = new thesis_header_image;

class thesis_header_image {
	function thesis_header_image() {
		$saved_header = maybe_unserialize(get_option('thesis_header')); #wp
		if (!empty($saved_header) && is_array($saved_header))
			$this->header = $saved_header;
	}

	function __construct() {
		$saved_header = maybe_unserialize(get_option('thesis_header')); #wp
		if (!empty($saved_header) && is_array($saved_header))
			$this->header = $saved_header;
	}

	function process() {
		if (isset($_POST['upload'])) {
			check_admin_referer('thesis-header-upload', '_wpnonce-thesis-header-upload'); #wp
			$overrides = array('test_form' => false);
			$file = wp_handle_upload($_FILES['import'], $overrides); #wp

			if (isset($file['error']))
				wp_die($file['error'], __('Image Upload Error', 'thesis')); #wp
				
			if ($file['type'] == 'image/jpeg' || $file['type'] == 'image/pjpeg' || $file['type'] == 'image/png' || $file['type'] == 'image/gif') {
				$this->url = $file['url'];
				$image = $file['file'];
				list($this->width, $this->height) = getimagesize($image);

				if ($this->width <= $this->optimal_width)
					$this->save($image);
				elseif ($this->width > $this->optimal_width) {
					if (apply_filters('thesis_crop_header', true)) { #filter
						$this->ratio = $this->width / $this->optimal_width;
						$cropped = wp_crop_image($image, 0, 0, $this->width, $this->height, $this->optimal_width, $this->height / $this->ratio, false, str_replace(basename($image), 'cropped-' . basename($image), $image)); #wp
						if (is_wp_error($cropped)) #wp
							wp_die(__('Your image could not be processed. Please go back and try again.', 'thesis'), __('Image Processing Error', 'thesis')); #wp

						$this->url = str_replace(basename($this->url), basename($cropped), $this->url);
						$this->width = round($this->width / $this->ratio);
						$this->height = round($this->height / $this->ratio);
						$this->save($cropped);
						@unlink($image);
					}
					else
						$this->save($image);
				}
			}
			else
				$this->error = true;
		}
		elseif ($_GET['remove']) {
			check_admin_referer('thesis-remove-header'); #wp
			unset($this->header);
			delete_option('thesis_header'); #wp
			global $thesis_design;
			if (!$thesis_design->display['header']['tagline'] && apply_filters('thesis_header_auto_tagline', true)) { #filter
				$thesis_design->display['header']['tagline'] = true;
				update_option('thesis_design_options', $thesis_design); #wp
			}
			thesis_generate_css();
			$this->removed = true;
		}
	}

	function save($image) {
		if (!$image) return;
		global $thesis_design;
		$this->header = array('url' => esc_url($this->url), 'width' => $this->width, 'height' => $this->height); #wp
		update_option('thesis_header', $this->header); #wp
		if ($thesis_design->display['header']['tagline'] && apply_filters('thesis_header_auto_tagline', true)) { #filter
			$thesis_design->display['header']['tagline'] = false;
			update_option('thesis_design_options', $thesis_design); #wp
		}
		thesis_generate_css();
		$this->updated = true;
	}

	function options_page() {
		$css = new Thesis_CSS;
		$css->baselines();
		$css->widths();
		$this->optimal_width = $css->widths['container'] - ($css->base['page_padding'] * 2);
		$this->process();

		$rtl = (get_bloginfo('text_direction') == 'rtl') ? ' rtl' : ''; #wp
		echo "<div id=\"thesis_options\" class=\"wrap$rtl\">\n";
		thesis_version_indicator();
		thesis_options_title(__('Thesis Header Image', 'thesis'), false);
		thesis_options_nav();

		if ($this->updated)
			echo "<div id=\"updated\" class=\"updated fade\">\n\t<p>" . __('Header image updated!', 'thesis') . ' <a href="' . get_bloginfo('url') . '/">' . __('Check out your site &rarr;', 'thesis') . "</a></p>\n</div>\n"; #wp
		elseif ($this->removed)
			echo "<div id=\"updated\" class=\"updated fade\">\n\t<p>" . __('Header image removed!', 'thesis') . ' <a href="' . get_bloginfo('url') . '/">' . __('Check out your site &rarr;', 'thesis') . "</a></p>\n</div>\n"; #wp
		elseif ($this->error)
			echo "<div class=\"warning\"><p>" . __('<strong>Whoops!</strong> You tried to upload an unrecognized file type. The header image uploader only accepts <code>.jpg</code>, <code>.png</code>, or <code>.gif</code> files.', 'thesis') . "</p></div>";

		if ($this->header)
			echo "<div id=\"header_preview\">\n\t<img src=\"{$this->header['url']}\" width=\"{$this->header['width']}\" height=\"{$this->header['height']}\" alt=\"header image preview\" title=\"header image preview\" />\n\t<a href=\"" . wp_nonce_url(admin_url('admin.php?page=thesis-header-image&remove=true'), 'thesis-remove-header') . "\" title=\"" . __('Click here to remove this header image', 'thesis') . "\">" . __('Remove Image', 'thesis') . "</a>\n</div>\n";
?>
	<div class="one_col">
		<div class="control_area">
			<p><?php printf(__('Based on your <a href="%1$s">current layout settings</a>, the optimal header image width is <strong>%2$d pixels</strong>. If your image is wider than this, don&#8217;t worry&#8212;Thesis will automatically resize it for you!', 'thesis'), admin_url('admin.php?page=thesis-design-options#layout-constructor'), $this->optimal_width); ?></p>
			<form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo admin_url('admin.php?page=thesis-header-image'); ?>">
				<p class="remove_bottom_margin">
					<label for="upload"><?php _e('Choose an image from your computer:', 'thesis'); ?></label>
					<input type="file" class="text" id="upload" name="import" />
					<?php wp_nonce_field('thesis-header-upload', '_wpnonce-thesis-header-upload') ?>
					<input type="submit" class="ui_button positive" name="upload" value="<?php esc_attr_e('Upload', 'thesis'); ?>" />
				</p>
			</form>
		</div>
	</div>
<?php
		echo "</div>\n";
	}
}