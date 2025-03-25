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
    
    // Check if we need to flush rewrite rules
    jobyan_maybe_flush_rules();
}
add_action('plugins_loaded', 'jobyan_init');

// Register activation hook
register_activation_hook(__FILE__, 'jobyan_activate');
function jobyan_activate() {
    // Get current version
    $current_version = get_option('jobyan_version');
    
    // If version has changed or doesn't exist
    if ($current_version !== JOBYAN_VERSION) {
        // Update version
        update_option('jobyan_version', JOBYAN_VERSION);
        
        // Register post types first so WordPress knows about them
        require_once JOBYAN_PLUGIN_DIR . 'includes/post-types/class-jobyan-job-post-type.php';
        $job_post_type = new Jobyan_Job_Post_Type();
        $job_post_type->register_post_type();
        
        // Flush rewrite rules immediately
        flush_rewrite_rules();
    }
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'jobyan_deactivate');
function jobyan_deactivate() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
    
    // Remove flush flag
    delete_option('jobyan_flush_rewrite_rules');
}

/**
 * Check if rewrite rules need to be flushed and do it if necessary
 */
function jobyan_maybe_flush_rules() {
    // Check if we need to flush rewrite rules
    if (get_option('jobyan_flush_rewrite_rules') === 'yes') {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Remove the flush flag
        update_option('jobyan_flush_rewrite_rules', 'no');
    }
}