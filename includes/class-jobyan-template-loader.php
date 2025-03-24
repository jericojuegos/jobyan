<?php
/**
 * Template Loader for Jobyan
 */
class Jobyan_Template_Loader {
    /**
     * Register template hooks
     */
    public function register() {
        // Filter for single job template
        add_filter('single_template', array($this, 'job_single_template'));
        
        // Filter for archive job template
        add_filter('archive_template', array($this, 'job_archive_template'));
        
        // Filter for taxonomy templates
        add_filter('taxonomy_template', array($this, 'job_taxonomy_template'));
    }
    
    /**
     * Single job template
     * 
     * @param string $template Template path
     * @return string
     */
    public function job_single_template($template) {
        global $post;
        
        if ($post->post_type == 'job') {
            // Check if template exists in theme
            $theme_template = locate_template(array('single-job.php'));
            
            if ($theme_template) {
                return $theme_template;
            }
            
            // Use plugin template
            $plugin_template = JOBYAN_PLUGIN_DIR . 'templates/single-job.php';
            
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Job archive template
     * 
     * @param string $template Template path
     * @return string
     */
    public function job_archive_template($template) {
        if (is_post_type_archive('job')) {
            // Check if template exists in theme
            $theme_template = locate_template(array('archive-job.php'));
            
            if ($theme_template) {
                return $theme_template;
            }
            
            // Use plugin template
            $plugin_template = JOBYAN_PLUGIN_DIR . 'templates/archive-job.php';
            
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Job taxonomy template
     * 
     * @param string $template Template path
     * @return string
     */
    public function job_taxonomy_template($template) {
        $job_taxonomies = array('job_category', 'job_type', 'job_location');
        
        if (is_tax($job_taxonomies)) {
            $term = get_queried_object();
            
            // Check for taxonomy-specific template in theme
            $taxonomy_template = locate_template(array(
                'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php',
                'taxonomy-' . $term->taxonomy . '.php',
            ));
            
            if ($taxonomy_template) {
                return $taxonomy_template;
            }
            
            // Use plugin template
            $plugin_template = JOBYAN_PLUGIN_DIR . 'templates/taxonomy-job.php';
            
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        
        return $template;
    }
}
