<?php
/**
 * Job Post Type
 */
class Jobyan_Job_Post_Type {
    /**
     * Post type name
     */
    public static $post_type = 'job';

    /**
     * Register the post type
     */
    public function register() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
    }

    /**
     * Register job post type
     */
    public function register_post_type() {
        $labels = array(
            'name'               => _x('Jobs', 'post type general name', 'jobyan'),
            'singular_name'      => _x('Job', 'post type singular name', 'jobyan'),
            'menu_name'          => _x('Jobs', 'admin menu', 'jobyan'),
            'name_admin_bar'     => _x('Job', 'add new on admin bar', 'jobyan'),
            'add_new'            => _x('Add New', 'job', 'jobyan'),
            'add_new_item'       => __('Add New Job', 'jobyan'),
            'new_item'           => __('New Job', 'jobyan'),
            'edit_item'          => __('Edit Job', 'jobyan'),
            'view_item'          => __('View Job', 'jobyan'),
            'all_items'          => __('All Jobs', 'jobyan'),
            'search_items'       => __('Search Jobs', 'jobyan'),
            'parent_item_colon'  => __('Parent Jobs:', 'jobyan'),
            'not_found'          => __('No jobs found.', 'jobyan'),
            'not_found_in_trash' => __('No jobs found in Trash.', 'jobyan')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Job listings.', 'jobyan'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-businessman',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'jobs'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true, // Enable Gutenberg editor
        );

        register_post_type(self::$post_type, $args);
    }

    /**
     * Register job taxonomies
     */
    public function register_taxonomies() {
        // Register Job Category taxonomy
        $category_labels = array(
            'name'              => _x('Job Categories', 'taxonomy general name', 'jobyan'),
            'singular_name'     => _x('Job Category', 'taxonomy singular name', 'jobyan'),
            'search_items'      => __('Search Job Categories', 'jobyan'),
            'all_items'         => __('All Job Categories', 'jobyan'),
            'parent_item'       => __('Parent Job Category', 'jobyan'),
            'parent_item_colon' => __('Parent Job Category:', 'jobyan'),
            'edit_item'         => __('Edit Job Category', 'jobyan'),
            'update_item'       => __('Update Job Category', 'jobyan'),
            'add_new_item'      => __('Add New Job Category', 'jobyan'),
            'new_item_name'     => __('New Job Category Name', 'jobyan'),
            'menu_name'         => __('Categories', 'jobyan'),
        );

        $category_args = array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'job-category'),
            'show_in_rest'      => true,
        );

        register_taxonomy('job_category', self::$post_type, $category_args);

        // Register Job Type taxonomy (Full-time, Part-time, etc.)
        $type_labels = array(
            'name'              => _x('Job Types', 'taxonomy general name', 'jobyan'),
            'singular_name'     => _x('Job Type', 'taxonomy singular name', 'jobyan'),
            'search_items'      => __('Search Job Types', 'jobyan'),
            'all_items'         => __('All Job Types', 'jobyan'),
            'parent_item'       => __('Parent Job Type', 'jobyan'),
            'parent_item_colon' => __('Parent Job Type:', 'jobyan'),
            'edit_item'         => __('Edit Job Type', 'jobyan'),
            'update_item'       => __('Update Job Type', 'jobyan'),
            'add_new_item'      => __('Add New Job Type', 'jobyan'),
            'new_item_name'     => __('New Job Type Name', 'jobyan'),
            'menu_name'         => __('Types', 'jobyan'),
        );

        $type_args = array(
            'hierarchical'      => false,
            'labels'            => $type_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'job-type'),
            'show_in_rest'      => true,
        );

        register_taxonomy('job_type', self::$post_type, $type_args);

        // Register Job Location taxonomy
        $location_labels = array(
            'name'              => _x('Job Locations', 'taxonomy general name', 'jobyan'),
            'singular_name'     => _x('Job Location', 'taxonomy singular name', 'jobyan'),
            'search_items'      => __('Search Job Locations', 'jobyan'),
            'all_items'         => __('All Job Locations', 'jobyan'),
            'parent_item'       => __('Parent Job Location', 'jobyan'),
            'parent_item_colon' => __('Parent Job Location:', 'jobyan'),
            'edit_item'         => __('Edit Job Location', 'jobyan'),
            'update_item'       => __('Update Job Location', 'jobyan'),
            'add_new_item'      => __('Add New Job Location', 'jobyan'),
            'new_item_name'     => __('New Job Location Name', 'jobyan'),
            'menu_name'         => __('Locations', 'jobyan'),
        );

        $location_args = array(
            'hierarchical'      => true,
            'labels'            => $location_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'job-location'),
            'show_in_rest'      => true,
        );

        register_taxonomy('job_location', self::$post_type, $location_args);
    }
}
