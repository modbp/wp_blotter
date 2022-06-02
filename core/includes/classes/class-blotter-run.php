<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 *
 * This class is used to bring your plugin to life.
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 *
 * Within the add_hooks() function, you can register all of
 * your WordPress related actions and filters as followed:
 *
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 *
 * Once added, you can create the callback function, within this class, as followed:
 *
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 *
 *
 * HELPER COMMENT END
 */

/**
 * Class Blotter_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		BLOTTER
 * @subpackage	Classes/Blotter_Run
 * @author		Modern Bit and Pixel LLC
 * @since		1.0.0
 */
class Blotter_Run{

	/**
	 * Our Blotter_Run constructor
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
		add_action( 'wp_enqueue_scripts', array( $this,'enqueue_scripts' ) );
		add_filter( 'script_loader_tag', array( $this,'add_defer_attr_to_script_tag' ), 10, 2 );
		add_filter( 'the_content', array( $this,'insert_blotter_comments_after_post' ), 10, 1 );
		add_action( 'admin_menu', array( $this,'blotter_admin_menu') );
		add_action( 'admin_init', array( $this, 'blotter_admin_init' ) );
		add_filter( 'plugin_action_links_' . BLOTTER_PLUGIN_BASE, array( $this,'blotter_settings_link' ) );
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Enqueue the Blotter script for this plugin.
	 * All of the added scripts will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_scripts() {
    wp_enqueue_script('blotter-comments', 'https://useblotter.com/static/js/blotterComments.js');
	}

	/**
	 * Add defer attribute to the Blotter script tag
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return  string The tag text (if Blotter script: with defer attr added)
	 */
	public function add_defer_attr_to_script_tag($tag, $handle) {
		if ($handle === 'blotter-comments') {
			$tag = str_replace('></', ' defer></', $tag);
		}
		return $tag;
	}

	/**
	 * Add Blotter Comments after blog post content
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	string The Blotter div tag that the script attaches to (if on a blog post page)
	 */
	public function insert_blotter_comments_after_post($content) {
		if (is_singular('post')) {
			$blotterSettings = get_option( 'blotter_commenting_option_name' );
			$hostId = $blotterSettings['blotter_id_0'];
			if ($hostId) {
				$content .= '<div id="blotter" data-hostid="' . $hostId . '"></div>';
			}
		}
		return $content;
	}

	function blotter_admin_menu() {
		add_comments_page('Blotter Commenting Settings', 'Blotter', 'manage_options', 'blotter/blotter-admin-page.php', array($this,'blotter_admin_page'));
	}

	// some of this code generated at http://jeremyhixon.com/wp-tools/option-page/
	function blotter_admin_page() {
    $this->blotter_commenting_options = get_option( 'blotter_commenting_option_name' ); ?>

		<div class="wrap">
			<h2>Blotter Commenting</h2>
			<p>The Blog Comment engine powered by Twitter. Learn more at https://useblotter.com.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'blotter_commenting_option_group' );
					do_settings_sections( 'blotter-commenting-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php
	}

	/**
	 * Add link to Blotter settings on plugin
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	string[] An array of links to display with the plugin
	 */
	public function blotter_settings_link( array $links ) {
    $url = menu_page_url('blotter/blotter-admin-page.php');
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'textdomain') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

	public function blotter_admin_init() {
		register_setting(
			'blotter_commenting_option_group', // option_group
			'blotter_commenting_option_name', // option_name
			array( $this, 'blotter_commenting_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'blotter_commenting_setting_section', // id
			'Settings', // title
			array( $this, 'blotter_commenting_section_info' ), // callback
			'blotter-commenting-admin' // page
		);

		add_settings_field(
			'blotter_id_0', // id
			'Blotter ID', // title
			array( $this, 'blotter_id_0_callback' ), // callback
			'blotter-commenting-admin', // page
			'blotter_commenting_setting_section' // section
		);
	}

	public function blotter_commenting_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['blotter_id_0'] ) ) {
			$sanitary_values['blotter_id_0'] = sanitize_text_field( $input['blotter_id_0'] );
		}

		return $sanitary_values;
	}

	public function blotter_commenting_section_info() {

	}

	public function blotter_id_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="blotter_commenting_option_name[blotter_id_0]" id="blotter_id_0" value="%s">',
			isset( $this->blotter_commenting_options['blotter_id_0'] ) ? esc_attr( $this->blotter_commenting_options['blotter_id_0']) : ''
		);
	}

}
