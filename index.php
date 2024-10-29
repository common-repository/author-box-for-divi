<?php
/*
Plugin Name: Author Box WP Lens
Plugin URI: https://wordpress.org/plugins/author-box-for-divi/
Description: A plugin which provides an author box for your WordPress blog.
Version: 2.0.1
Text Domain: author-box-for-divi
Domain Path: /languages
Author: Andrej
Author URI: https://divitheme.net
*/

class ABFD
{
	static $social_networks;
	static $is_pro = false;

	static function load()
	{
		self::$social_networks = include 'social-networks.php';

		// activate hook
		register_activation_hook(__FILE__, array('ABFD', 'activate'));

		add_action('init', function () {
			global $allowedtags;
			$allowedtags['a']['target'] = true;
		}, 0);

		add_action('plugins_loaded', array('ABFD', 'plugins_loaded'));
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array('ABFD', 'plugin_action_links'));
		add_action('admin_notices', array('ABFD', 'admin_notice'));
		add_action('wp_ajax_abfd_dismiss_notice', array('ABFD', 'dismiss_notice'));
		add_action('wp_ajax_abfd_dismiss_notice_rating', array('ABFD', 'dismiss_notice_rating'));

		if (is_admin()) {
			add_action('admin_init', array('ABFD', 'admin_init'));
			add_action('admin_menu', array('ABFD', 'admin_menu'));
			add_action('admin_enqueue_scripts', array('ABFD', 'wp_admin_enqueue_scripts'));
			add_action('personal_options_update', array('ABFD', 'abfd_user_save'));
			add_action('edit_user_profile_update', array('ABFD', 'abfd_user_save'));
			add_action('show_user_profile', array('ABFD', 'abfd_user_page'));
			add_action('edit_user_profile', array('ABFD', 'abfd_user_page'));
			// add ajax action abfd_preview to admin
			add_action('wp_ajax_abfd_preview', array('ABFD', 'abfd_refresh_preview'));
		} else {
			add_action('wp_enqueue_scripts', array('ABFD', 'wp_enqueue_scripts'));
			add_action('the_content', array('ABFD', 'the_content'), PHP_INT_MAX);
		}
		add_action('wp_head', array('ABFD', 'wp_head'), PHP_INT_MAX);
		add_action('admin_head', array('ABFD', 'wp_head'), PHP_INT_MAX);
	}

	// activate function just to reset the dismiss notice option
	static function activate()
	{
		delete_option('abfd-dismiss-notice');
		delete_option('abfd-dismiss-notice-rating');
	}

	static function plugins_loaded()
	{
		// use ABWLPro::is_pro if class exists, otherwise return false
		self::$is_pro = class_exists('ABWLPro') ? ABWLPro::is_pro() : false;

		load_plugin_textdomain('author-box-for-divi', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	static function admin_menu()
	{
		add_menu_page(__('Author Box WP Lens', 'author-box-for-divi'), __('Author Box WP Lens', 'author-box-for-divi'), 'manage_options', 'abfd', array('ABFD', 'abfd_menu_page'));

		if (!self::$is_pro) {
			add_submenu_page(
				'abfd',
				__('Settings', 'author-box-for-divi'),
				__('Settings', 'author-box-for-divi'),
				'manage_options',
				'abfd',
				array('ABFD', 'abfd_menu_page')
			);
			add_submenu_page(
				'abfd',
				__('Upgrade to Pro', 'author-box-for-divi'),
				'<span style="color: #1abc9c; font-weight: bold;">' . __('Upgrade to Pro', 'author-box-for-divi') . '</span>',
				'manage_options',
				'abfd-upgrade',
				function() {
				}
			);
		}
	}

	static function plugin_action_links($links)
	{
		$settings_link = '<a href="' . admin_url('admin.php?page=abfd') . '">' . __('Settings', 'author-box-for-divi') . '</a>';
		array_unshift($links, $settings_link);
		if (!self::$is_pro) {
			$upgrade_link = '<a href="https://wplens.com" style="color: #1abc9c; font-weight: bold;">' . __('Upgrade to Pro', 'author-box-for-divi') . '</a>';
			array_unshift($links, $upgrade_link);
		}
		return $links;
	}

	static function admin_notice()
	{
		if (!self::$is_pro && !get_option('abfd-dismiss-notice')) {
			?>
			<div class="notice notice-info is-dismissible" id="abfd-notice">
				<p>
					<?php _e('Author Box WP Lens is a free plugin. For more features and customization options, check out the Pro version.', 'author-box-for-divi'); ?>
					<a href="https://wplens.com" target="_blank"><?php _e('Upgrade to Pro', 'author-box-for-divi'); ?></a>
				</p>
			</div>
			<script>
				jQuery(document).on('click', '#abfd-notice .notice-dismiss', function() {
					jQuery.post(ajaxurl, {
						action: 'abfd_dismiss_notice'
					});
				});
			</script>
			<?php
		}

		if (!self::$is_pro && !get_option('abfd-dismiss-notice-rating')) {
			?>
			<div class="notice notice-info is-dismissible" id="abfd-notice-rating">
    <p>
        <?php
            printf(
                __('Do you enjoy using the Author Box WP Lens plugin? A %s5-star rating on WordPress.org%s would be great!', 'author-box-for-divi'),
                '<a href="https://wordpress.org/support/plugin/author-box-for-divi/reviews/?filter=5#new-post" target="_blank">', '</a>'
            );
        ?>
    </p>
</div>
			<script>
				jQuery(document).on('click', '#abfd-notice-rating .notice-dismiss', function() {
					jQuery.post(ajaxurl, {
						action: 'abfd_dismiss_notice_rating'
					});
				});
			</script>
			<?php
		}
	}

	static function dismiss_notice()
	{
		update_option('abfd-dismiss-notice', true);
		wp_die();
	}

	static function dismiss_notice_rating()
	{
		update_option('abfd-dismiss-notice-rating', true);
		wp_die();
	}

	static function admin_init() {
		// Check if the current user has the required capability and if the request is for the abfd-upgrade page
		if (isset($_GET['page']) && $_GET['page'] == 'abfd-upgrade') {
			// Redirect to the plugin page
			wp_redirect('https://wplens.com');
			exit;
		}

		// save settings
		if (!empty($_POST['abfd-submit']) && wp_verify_nonce($_POST['abfd-nonce'], 'abfd')) {
			foreach ($_POST as $key => $value) {
				if (strstr($key, 'abfd-option-') !== false) {
					$key = sanitize_key($key);

					if (!is_array($value)) {
						$value = sanitize_text_field(stripslashes($value));
					}

					if (!empty($value) || $value === 0 || $value === '0') {
						update_option($key, $value);
					} else {
						delete_option($key);
					}
				}
			}

			if (empty($_POST['abfd-option-disable-on-post-types'])) {
				update_option('abfd-option-disable-on-post-types', array());
			}

			if (!isset($_POST['abfd-option-new-tab'])) {
				update_option('abfd-option-new-tab', false);
			}

			if (!isset($_POST['abfd-option-email-icon'])) {
				update_option('abfd-option-email-icon', false);
			}

			if (!isset($_POST['abfd-option-website-icon'])) {
				update_option('abfd-option-website-icon', false);
			}
			if (!isset($_POST['abfd-option-social-icon-as-original'])) {
				update_option('abfd-option-social-icon-as-original', 0);
			}
			if (!isset($_POST['abfd-option-hyperlink-author-page'])) {
				update_option('abfd-option-hyperlink-author-page', false);
			}
			if (!isset($_POST['abfd-option-guest-authors'])) {
				update_option('abfd-option-guest-authors', false);
			}
			if (!isset($_POST['abfd-option-multiple-authors'])) {
				update_option('abfd-option-multiple-authors', false);
			}
			if (!isset($_POST['abfd-option-author-posts-page-link'])) {
				update_option('abfd-option-author-posts-page-link', false);
			}
		}
	}

	static function abfd_menu_page()
	{
		if (!empty($_POST['abfd-submit']) && wp_verify_nonce($_POST['abfd-nonce'], 'abfd')) {
			?>

			<div class="notice notice-success">
				<p>
					<?php _e('Settings Saved.', 'author-box-for-divi'); ?>
				</p>
			</div>
			<?php
		}

		$google_fonts = array(
			'Open Sans',
			'Roboto',
			'Lato',
			'Montserrat',
			'Oswald',
			'Poppins',
			'Raleway',
			'PT Sans',
			'Alegreya',
			'Source Sans Pro',
			'--',
			'Abel',
			'Alegreya Sans',
			'Alfa Slab One',
			'Amiri',
			'Anton',
			'Arimo',
			'Arvo',
			'Armata',
			'Assistant',
			'Asap',
			'Baloo 2',
			'Barlow',
			'Barlow Condensed',
			'Bebas Neue',
			'Bangers',
			'Bitter',
			'Cabin',
			'Cairo',
			'Catamaran',
			'Chakra Petch',
			'Chewy',
			'Cinzel',
			'Comfortaa',
			'Cormorant Garamond',
			'Crimson Text',
			'Dosis',
			'DM Sans',
			'Eczar',
			'Exo',
			'Exo 2',
			'Fira Mono',
			'Fira Sans',
			'Fjalla One',
			'Fredoka One',
			'Gothic A1',
			'Hammersmith One',
			'Heebo',
			'Hind',
			'IBM Plex Sans',
			'Inconsolata',
			'Istok Web',
			'Josefin Sans',
			'Kanit',
			'Karla',
			'Libre Baskerville',
			'Libre Franklin',
			'Lobster',
			'Lora',
			'Manrope',
			'Maven Pro',
			'Merriweather',
			'Mukta',
			'Muli',
			'Nanum Gothic',
			'Noto Sans',
			'Noto Sans JP',
			'Noto Sans KR',
			'Noto Serif',
			'Nunito',
			'Oxygen',
			'Pacifico',
			'Padauk',
			'Pathway Gothic One',
			'Playfair Display',
			'Prompt',
			'Public Sans',
			'Questrial',
			'Quicksand',
			'Rajdhani',
			'Rokkitt',
			'Rubik',
			'Saira',
			'Sarabun',
			'Secular One',
			'Signika',
			'Slabo 27px',
			'Sora',
			'Spectral',
			'Tajawal',
			'Teko',
			'Titillium Web',
			'Ubuntu',
			'Varela Round',
			'Vollkorn',
			'Work Sans',
			'Yanone Kaffeesatz',
			'Yeseva One',
			'Zilla Slab'
	);

		// include templates/settings.php
		include plugin_dir_path( __FILE__ ) . 'templates/settings.php';
	}

	static function abfd_user_page($profileuser)
	{
		$disable = get_user_meta($profileuser->ID, 'abfd-user-disable', true);

		// include templates/settings.php
		include plugin_dir_path( __FILE__ ) . 'templates/user_page.php';
	}

	static function abfd_user_save($user_id)
	{
		if (wp_verify_nonce($_POST['abfd-nonce'], 'abfd')) {
			foreach ($_POST as $key => $value) {
				if (strstr($key, 'abfd-user-') !== false) {
					$key = sanitize_key($key);

					if ($key == 'abfd-user-photograph' || strpos($key, 'abfd-user-social-networks-') === 0) {
						$value = esc_url_raw(stripslashes($value));
					} else {
						$value = sanitize_text_field(stripslashes($value));
					}

					if (!empty($value) || $value === 0 || $value === '0') {
						update_user_meta($user_id, $key, $value);
					} else {
						delete_user_meta($user_id, $key);
					}
				}
			}
		}
	}

	static function wp_enqueue_scripts()
	{
		wp_enqueue_style('abfd-author', plugins_url(null, __FILE__) . '/css/author.css');
		// enqueue fontwesome free brands from cdn
		wp_enqueue_style('abfd-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
	}

	static function wp_admin_enqueue_scripts($hook)
	{
		// Ensure that these scripts are only loaded on the abfq admin page and on posts
		$current_screen = get_current_screen();
		if (
			(isset($_GET['page']) && $_GET['page'] == 'abfd') || 
			($current_screen && ($current_screen->base === 'post' || $current_screen->base === 'post-new'))
		) {
			wp_enqueue_style('abfd-author', plugins_url(null, __FILE__) . '/css/author.css');
			// Load the color picker
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			// enqueue fontwesome free brands from cdn
			wp_enqueue_style('abfd-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
		}

		// Enqueue select2 for Profile and Edit User pages
		if ($hook == 'profile.php' || $hook == 'user-edit.php' || $hook == 'post-new.php' || $hook == 'post.php') {
			wp_enqueue_media();
			wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
			wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'));
			// enqueue fontwesome free brands from cdn
			wp_enqueue_style('abfd-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
		}
	}

	static function the_content($content)
	{
		// if on Divi Builder, return the content
		if (is_admin() && isset($_GET['et_fb']) && $_GET['et_fb'] == '1') {
			return $content;
		}

		$html = self::get_current_page_author_box_html();

		if (!$html) {
			return $content;
		}

		// if is pro version, check the abfd-option-author-box-position option
		if (self::$is_pro) {
			$position = get_option('abfd-option-author-box-position', 'bottom');
			if ($position == 'top') {
				$content = $html . $content;
			} else {
				$content .= $html;
			}
		} else {
			$content .= $html;
		}

		return $content;
	}

	static function get_current_page_author_box_html( $users = false, $bypass_checks = false )
	{
		if( ! is_single() && ! is_page() ) {
			return false;
		}

		global $post;
		$user_id = $post->post_author;

		if( ! $bypass_checks ) {
			$disable_on_post_types = (array) get_option('abfd-option-disable-on-post-types', array());

			if (in_array($post->post_type, $disable_on_post_types)) {
				return false;
			}

			$exclude_categories = (array) get_option('abfd-option-exclude-categories', array());

			foreach ($exclude_categories as $category_id) {
				if (in_category($category_id, $post->ID)) {
					return false;
				}
			}
		}

		// get list of users from a filter, defaults to array with the post author
		if( ! $users ) {
			$users = apply_filters('abfd_authors', array($user_id), $post->ID);
		}

		$html = '';
		foreach ($users as $user_id) {
			if( empty($user_id) ) {
				continue;
			}
			$disable = get_user_meta($user_id, 'abfd-user-disable', true);
			if ($disable == 'yes') {
				continue;
			}

			$html .= self::get_author_box_html($user_id);
		}

		return empty($html) ? false : $html;
	}

	static function get_author_box_html($user_id, $settings = array()) {
		$fields = array();

		if('demo' === $user_id) {
			// DEMO User
			$author_link = 'https://wplens.com';
			$fields['photograph'] = plugin_dir_url( __FILE__ ) . 'images/alex-sample-profile-pic.jpg';
			$user = new stdClass();
			$user->ID = 'demo';
			$user->display_name = 'Alex Richmond';
			$user->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis semper, ligula vulputate imperdiet rutrum, purus mauris tincidunt nulla, et ultricies mauris diam sit amet neque. Curabitur sollicitudin malesuada mattis. <a href="https://wplens.com/" target="_blank">Link</a> ut vel nisi quis erat condimentum interdum.';
			$user->user_email = 'alex.richmond@mail.com';
			$user->user_url = 'https://wplens.com';

			foreach (ABFD::$social_networks as $key => $value) {
				$meta_key = 'abfd-user-social-networks-' . $key;
				$meta_value = '#';
				$fields['social-networks'][$key] = esc_url($meta_value);
			}
		} else if( is_array($user_id)) { // for guest authors
			$author_link = $user_id['author_link'];
			$fields = $user_id['fields'];
			$user = $user_id['user'];
		} else {
			// Current User
			$user = get_user_by('id', $user_id);

			$fields['photograph'] = get_user_meta($user->ID, 'abfd-user-photograph', true);
			if(empty($fields['photograph'])) {
				$fields['photograph'] = get_avatar_url($user->user_email);
			}
			if (!empty($fields['photograph'])) {
				$fields['photograph'] = esc_url($fields['photograph']);
			}
	
			foreach (ABFD::$social_networks as $key => $value) {
				$meta_key = 'abfd-user-social-networks-' . $key;
				$meta_value = get_user_meta($user->ID, $meta_key, true);
				if (!empty($meta_value)) {
					$fields['social-networks'][$key] = esc_url($meta_value);
				}
			}
			$author_link = get_author_posts_url($user->ID);
		}

		$name_prefix = $settings['abfd-option-name-prefix'] ?? get_option('abfd-option-name-prefix', '');
		$icon_shape = $settings['abfd-option-icon-shape'] ?? get_option('abfd-option-icon-shape', 'icon');
		$icon_color = $settings['abfd-option-icon-color'] ?? esc_attr(get_option('abfd-option-icon-color', '#000000'));
		$icon_original = $settings['abfd-option-social-icon-as-original'] ?? get_option('abfd-option-social-icon-as-original', true);
		$email_icon = $settings['abfd-option-email-icon'] ?? get_option('abfd-option-email-icon', false);
		$website_icon = $settings['abfd-option-website-icon'] ?? get_option('abfd-option-website-icon', false);
		$open_new_tab = $settings['abfd-option-new-tab'] ?? get_option('abfd-option-new-tab', true);
		$link_attrs = $open_new_tab ? 'target="_blank"' : '';
		if (self::$is_pro) {
			$link_attrs_options = $settings['abfd-option-link-attributes'] ?? get_option('abfd-option-link-attributes', array());
			if (!empty($link_attrs_options)) {
				$link_attrs .= ' rel="' . implode(' ', $link_attrs_options) . '"';
			}
		}

		$hyperlink_author_page = $settings['abfd-option-hyperlink-author-page'] ?? get_option('abfd-option-hyperlink-author-page', false);
		if(!$hyperlink_author_page) {
			$author_link = "#";
		}

		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/author-box.php';
		$html = ob_get_clean();

		// remove all break lines
		$html = preg_replace('/\s+/', ' ', $html);

		return $html;
	}

	static function wp_head($settings = array())
	{
		$text_color = esc_html($settings['abfd-option-text-color'] ?? get_option('abfd-option-text-color'));
		$background_color = esc_html($settings['abfd-option-background-color'] ?? get_option('abfd-option-background-color'));
		$border_color = esc_html($settings['abfd-option-border-color'] ?? get_option('abfd-option-border-color'));
		$border_radius = esc_html($settings['abfd-option-border-radius'] ?? get_option('abfd-option-border-radius'));

		$css = '';

		if (!empty($text_color)) {
			$css .= 'color: ' . $text_color . ';' . "\n";
		}

		if (!empty($background_color)) {
			$css .= 'background-color: ' . $background_color . ';' . "\n";
		}

		if (!empty($border_color)) {
			$css .= 'border-color: ' . $border_color . ';' . "\n";
		}

		if (!empty($border_radius)) {
			if (ctype_digit($border_radius) === true) {
				$border_radius .= 'px';
			}

			$css .= 'border-radius: ' . $border_radius . ';' . "\n";
		}

		if (!empty($css)) {
			?>
			<style id="abfd-custom-styles" type="text/css">
				.abfd-container {
					<?php echo $css; ?>
				}
			</style>
			<?php
		}

	}

	static function abfd_refresh_preview()
	{
		// unserialize post data
		parse_str($_POST['data'], $settings);
		$html = self::get_author_box_html('demo', $settings);
		// get css from the custom styles
		ob_start();
		self::wp_head($settings);
		$css = ob_get_clean();
		// if pro, get the css from the custom styles
		$css_pro = '';
		if (self::$is_pro) {
			ob_start();
			ABWLPro::wp_head($settings);
			$css_pro = ob_get_clean();
		}

		$response = array(
			'html' => $html,
			'css' => $css,
			'css_pro' => $css_pro
		);

		wp_send_json($response);
		wp_die();
	}
}

ABFD::load();
?>