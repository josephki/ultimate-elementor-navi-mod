<?php
/**
 * UAEL Navigation Menu Widget.
 *
 * @package UAEL
 */
namespace UltimateElementor\Modules\NavMenu;
use UltimateElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module.
 */
class Module extends Module_Base {
	/**
	 * Module should load or not.
	 *
	 * @since 1.21.0
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_enable() {
		return true;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 1.21.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'uael-nav-menu';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 1.21.0
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return array(
			'Nav_Menu',
		);
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		
		// Laden Sie die Crocobock-Erweiterung
/*require_once plugin_dir_path( __FILE__ ) . 'class-nav-menu-crocobock.php'; */
	} 
}