<?php
/**
 * Plugin Name: Jobyan - Job Board Plugin
 * Description: A comprehensive job board plugin for WordPress with job postings, applications, user profiles, and search features.
 * Version: 1.0.0
 * Author: Jobyan Team
 * Text Domain: jobyan
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JOBYAN_VERSION', '1.0.0');
define('JOBYAN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('JOBYAN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JOBYAN_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include files
require_once JOBYAN_PLUGIN_DIR . 'includes/class-jobyan.php';

// Initialize the plugin
function jobyan_init() {
    $jobyan = new Jobyan();
    $jobyan->init();
}
add_action('plugins_loaded', 'jobyan_init');

// Register activation hook
register_activation_hook(__FILE__, 'jobyan_activate');
function jobyan_activate() {
    // Flush rewrite rules on activation
    flush_rewrite_rules();
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'jobyan_deactivate');
function jobyan_deactivate() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
}
