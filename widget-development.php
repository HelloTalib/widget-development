<?php
/*
 * Plugin Name: Widget Development
 * Plugin URI: https://bdthemes.com/product/widget-development/
 * Description: Elementor extension for first theme
 * Version: 1.0.0
 * Author: TALIB
 * Author URI: https://talib.netlify.app
 * License: GPLv3
 * Text Domain: widget-development
 * Domain Path: /languages/
 */

namespace Elementor;

if (!defined('ABSPATH')) {
    exit(__('Direct Access is not allowed', 'widget-development'));
}

final class WP_PLUGIN_DEVELOPMENT {

    const VERSION                   = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION       = '7.0';

    private static $_instance = null;

    public static function instance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function admin_notice_minimum_php_version() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'widget-development'),
            '<strong>' . esc_html__('Plugin Name', 'widget-development') . '</strong>',
            '<strong>' . esc_html__('PHP', 'widget-development') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'widget-development'),
            '<strong>' . esc_html__('Plugin Name', 'widget-development') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'widget-development') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_missing_main_plugin() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'widget-development'),
            '<strong>' . esc_html__('Plugin Name', 'widget-development') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'widget-development') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function init() {
        load_plugin_textdomain('widget-development', false, plugin_dir_path(__FILE__) . '/languages');

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Register Widget
        add_action('elementor/widgets/register', [$this, 'init_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_new_category']);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'widget_assets_enqueue']);
    }

    /**
     * !Register Categories
     */
    public function register_new_category($elements_manager) {
        $elements_manager->add_category(
            'widget-development',
            [
                'title' => __('Panel', 'widget-development'),
            ]
        );
    }

    /**
     * !enqueue assets
     */
    public function widget_assets_enqueue() {
        wp_enqueue_style('widget-development-css', plugin_dir_url(__FILE__) . 'assets/css/style.css', null, time(), null);
        wp_enqueue_script('widget-development-js', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['jquery'], '1.0.0', true);
    }

    /**
     * ! Widgets Init
     */

    function init_widgets($widgets_manager) {
        require_once __DIR__ . '/widgets/widget.php';
        $widgets_manager->register(new ELEMENTOR_WIDGET());
    }
}

WP_PLUGIN_DEVELOPMENT::instance();
