<?php
/*
  Plugin Name: GL Custom Footer
  Plugin URI: 
  Description: Customize Footer to User's wants.
  Version: 1.0
  Author: Gino Labiste
  Author URI: 
  License: GPLv2+
  Text Domain: 
*/

class GL_Custom_Footer{

//Constructor

	function __construct(){
			
			
			add_action ( 'get_footer', array( $this, 'gl_add_footer'));
			add_action ( 'wp_enqueue_scripts', array( $this, 'gl_add_scripts'));
			register_activation_hook(__FILE__, array( $this, 'gl_install'));
			register_deactivation_hook(__FILE__, array($this, 'gl_uninstall'));
			add_action( 'admin_init', array($this, 'glfc_settings_init') );
			add_action( 'admin_menu', array($this, 'glfc_options_page') );
			
	}
	
	function glfc_settings_init() {
		// register a new setting for "glfc" page
		register_setting( 'glfc', 'glfc_options' );
 
		// register a new section in the "glfc" page
		add_settings_section(
			'glfc_section_developers',
			__( 'Code your custom footer here..', 'glfc' ),
			array(__CLASS__,'glfc_section_developers_cb'),
			'glfc'
		);
 
		// register a new field in the "glfc_section_developers" section, inside the "glfc" page
		add_settings_field(
			'glfc_field_pill', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			null,
			array(__CLASS__, 'glfc_field_pill_cb'),
			'glfc',
			'glfc_section_developers',
			[
			'label_for' => 'glfc_field_pill',
			'class' => 'glfc_row',
			'glfc_custom_data' => 'custom',
			]
		);
	}
	
	static function glfc_section_developers_cb( $args ) {
?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Text Editor is recommended for this...', 'glfc' ); ?></p>
<?php
	}
	
	static function glfc_field_pill_cb( $args ) {
		// get the value of the setting we've registered with register_setting()
		$options = get_option( 'glfc_options' );
		// output the field
		$settings = array(
		'quicktags' => array('buttons' => 'em,strong,link',),
		'textarea_name'=>'glfc_options[' . esc_attr( $args['label_for'] ) . ']',//name you want for the textarea
		'quicktags' => true,
		'tinymce' => true
		);
		$id = 'editor-test';
		wp_editor($options['glfc_field_pill'], $id, $settings);
	}
	
	function glfc_options_page() {
		// add top level menu page
		add_menu_page(
		'GL Custom Footer',
		'GLFC Options',
		'manage_options',
		'glfc',
		array(__CLASS__, 'glfc_options_page_html'), plugins_url('images/logo.png', __FILE__), '2.3.9'
		);
	}
	
	static function glfc_options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// add error/update messages
		// check if the user have submitted the settings
		// wordpress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'glfc_messages', 'glfc_message', __( 'Settings Saved', 'glfc' ), 'updated' );
		}
 
		// show error/update messages
		settings_errors( 'glfc_messages' );
?>
		<style>
			.glfc_row th{
				display:none;
			}
		</style>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
<?php
			// output security fields for the registered setting "glfc"
			settings_fields( 'glfc' );
			// output setting sections and their fields
			// (sections are registered for "glfc", each field is registered to a specific section)
			do_settings_sections( 'glfc' );
			// output save settings button
			submit_button( 'Save Settings' );
?>
			</form>
		</div>
 <?php
}
	/*
	 * Actions perform at loading of admin menu
	 */

	
	/*
	 * Inserts HTML view to template
	 */
	function gl_add_scripts(){
		//wp_enqueue_style('styles', plugins_url('/css/styles.css', __FILE__), array());
	}
	/*
	 * Inserts HTML view to footer
	 */
	function gl_add_footer(){
		//This must be user inputed text
		//html is migrated to index.php inside the public folder
		//include_once( plugin_dir_path( __FILE__ ) . 'public/index.php' );
		
		$options = get_option( 'glfc_options' );
		echo $options['glfc_field_pill'];
		
	}
	
    /*
     * Actions perform on activation of plugin
     */
    function gl_install() {}

    /*
     * Actions perform on de-activation of plugin
     */
    function gl_uninstall() {}
	
}
new GL_Custom_Footer();
	
?>
