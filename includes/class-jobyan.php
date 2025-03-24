<?php
/**
 * Main Jobyan Plugin Class
 */
class Jobyan {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize plugin components
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Load required files
        $this->load_dependencies();

        // Register hooks
        $this->register_hooks();
    }

    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        // Include post types
        require_once JOBYAN_PLUGIN_DIR . 'includes/post-types/class-jobyan-job-post-type.php';
        
        // Include meta boxes
        require_once JOBYAN_PLUGIN_DIR . 'includes/admin/class-jobyan-meta-boxes.php';
        
        // Include shortcodes
        require_once JOBYAN_PLUGIN_DIR . 'includes/shortcodes/class-jobyan-shortcodes.php';
        
        // Include template functions
        require_once JOBYAN_PLUGIN_DIR . 'includes/class-jobyan-template-loader.php';
    }

    /**
     * Register all plugin hooks
     */
    private function register_hooks() {
        // Initialize post types
        $job_post_type = new Jobyan_Job_Post_Type();
        $job_post_type->register();
        
        // Initialize meta boxes
        $meta_boxes = new Jobyan_Meta_Boxes();
        $meta_boxes->register();
        
        // Initialize shortcodes
        $shortcodes = new Jobyan_Shortcodes();
        $shortcodes->register();
        
        // Initialize template loader
        $template_loader = new Jobyan_Template_Loader();
        $template_loader->register();
        
        // Load text domain
        add_action('init', array($this, 'load_textdomain'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('jobyan', false, dirname(JOBYAN_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        wp_enqueue_style('jobyan-frontend', JOBYAN_PLUGIN_URL . 'assets/css/frontend.css', array(), JOBYAN_VERSION);
        wp_enqueue_script('jobyan-frontend', JOBYAN_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), JOBYAN_VERSION, true);
        
        // Add dashicons for the frontend
        wp_enqueue_style('dashicons');
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on job post type pages
        global $post;
        
        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if (isset($post) && $post->post_type == 'job') {
                wp_enqueue_style('jobyan-admin', JOBYAN_PLUGIN_URL . 'assets/css/admin.css', array(), JOBYAN_VERSION);
                wp_enqueue_script('jobyan-admin', JOBYAN_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), JOBYAN_VERSION, true);
                
                // Add datepicker for deadline field
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
            }
        }
    }
}
