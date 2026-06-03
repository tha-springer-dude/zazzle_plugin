<?php
/*
Plugin Name: Zazzle Store Builder
Description: Display Zazzle products via collections or product IDs.
Version: 1.0
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/fetch.php';
require_once plugin_dir_path(__FILE__) . 'includes/render.php';

// shortcode
add_shortcode('zazzle', 'zazzle_shortcode');
add_action('admin_menu', 'zazzle_register_menu');
add_action('admin_init', 'zazzle_register_settings');

function zazzle_shortcode($atts) {
    return zazzle_render($atts);
}