<?php
/**
 * UAEL Navigation Menu Crocobock Extension.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\NavMenu;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Nav_Menu_Crocobock
 * 
 * Extends the Nav Menu widget with Crocobock theme part support
 */
class Nav_Menu_Crocobock {

    /**
     * Instance of the class.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Get instance of the class
     *
     * @return object
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Add filters to extend menu content types
        add_filter( 'uael_nav_menu_get_content_type', array( $this, 'add_crocobock_content_type' ) );
        
        // Add content type to repeater
        add_action( 'elementor/element/uael-nav-menu/section_menu/before_section_end', array( $this, 'add_crocobock_controls' ), 10, 2 );
        
        // Handle content rendering
        add_filter( 'uael_nav_menu_custom_content_render', array( $this, 'render_crocobock_content' ), 10, 2 );
    }

    /**
     * Add Crocobock theme part option to content types
     *
     * @param array $content_types Existing content types.
     * @return array Modified content types.
     */
    public function add_crocobock_content_type( $content_types ) {
        $content_types['crocobock_parts'] = __( 'Crocobock Theme Part', 'uael' );
        return $content_types;
    }

    /**
     * Add Crocobock controls to the Nav Menu widget
     *
     * @param object $element Element instance.
     * @param array  $args Widget arguments.
     */
    public function add_crocobock_controls( $element, $args ) {
        
        $element->start_injection(
            array(
                'at' => 'after',
                'of' => 'content_saved_container',
            )
        );

        $element->add_control(
            'content_crocobock_parts',
            array(
                'label'     => __( 'Select Theme Part', 'uael' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $this->get_crocobock_theme_parts(),
                'default'   => '-1',
                'condition' => array(
                    'menu_content_type' => 'crocobock_parts',
                    'item_type'         => 'item_submenu',
                ),
            )
        );

        $element->end_injection();
    }

    /**
     * Render Crocobock theme part content
     *
     * @param string $output The current output.
     * @param array  $item Menu item data.
     * @return string The modified output.
     */
    public function render_crocobock_content( $output, $item ) {
        if ( isset( $item['menu_content_type'] ) && 'crocobock_parts' === $item['menu_content_type'] ) {
            $theme_part_id = $item['content_crocobock_parts'];
            
            if ( function_exists( 'crocobock_render_theme_part' ) && ! empty( $theme_part_id ) && '-1' !== $theme_part_id ) {
                ob_start();
                crocobock_render_theme_part( $theme_part_id );
                $theme_part_content = ob_get_clean();
                $output .= do_shortcode( $theme_part_content );
            }
        }
        
        return $output;
    }

    /**
     * Get Crocobock theme parts list
     *
     * @return array List of theme parts.
     */
    public function get_crocobock_theme_parts() {
        $theme_parts = array( '-1' => __( 'Select', 'uael' ) );
        
        // Check if Crocobock theme is active or its functions are available
        if ( function_exists( 'crocobock_get_theme_parts' ) ) {
            $parts = crocobock_get_theme_parts();
            
            if ( ! empty( $parts ) && is_array( $parts ) ) {
                foreach ( $parts as $part_id => $part_data ) {
                    $theme_parts[ $part_id ] = isset( $part_data['title'] ) ? $part_data['title'] : $part_id;
                }
            }
        }
        
        return $theme_parts;
    }
}

// Initialize the extension
add_action( 'plugins_loaded', function() {
    Nav_Menu_Crocobock::get_instance();
});

/**
 * Add hooks to the UAEL Nav Menu class to support extensions
 */
function uael_nav_menu_add_extension_hooks() {
    // Make sure the class exists before adding filters
    if ( ! class_exists( '\UltimateElementor\Modules\NavMenu\Widgets\Nav_Menu' ) ) {
        return;
    }
    
    // Filter content types
    add_filter( 'uael_nav_menu_get_content_type', function( $content_type ) {
        return apply_filters( 'uael_nav_menu_get_content_type', $content_type );
    }, 10, 1 );
    
    // Filter custom content rendering in the get_custom_style method
    add_filter( 'uael_nav_menu_custom_content_render', function( $output, $item ) {
        return apply_filters( 'uael_nav_menu_custom_content_render', $output, $item );
    }, 10, 2 );
}
add_action( 'init', 'uael_nav_menu_add_extension_hooks' );
