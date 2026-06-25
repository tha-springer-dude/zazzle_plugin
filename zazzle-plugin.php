<?php
/*
Plugin Name: Zazzle Store Builder
Description: Display Zazzle products via collections or product IDs.
Version: 1.0
Author: Willie Springer
*/

if (!defined('ABSPATH')) exit;

function zazzle_enqueue_styles() {
wp_enqueue_style(
    'zazzle-style',
    plugin_dir_url(__FILE__) . 'assets/style.css',
    [],
    time() // 👈 forces fresh load every time
);
}

add_action('wp_enqueue_scripts', 'zazzle_enqueue_styles');

require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/render.php';



// shortcode
add_shortcode('zazzle', 'zazzle_shortcode');
add_action('admin_menu', 'zazzle_register_menu');
add_action('admin_init', 'zazzle_register_settings');

function zazzle_shortcode($atts) {
    return zazzle_render($atts);
}