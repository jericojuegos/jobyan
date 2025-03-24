<?php
/**
 * Jobyan Shortcodes
 */
class Jobyan_Shortcodes {
    /**
     * Register shortcodes
     */
    public function register() {
        add_shortcode('jobyan_jobs', array($this, 'jobs_shortcode'));
        add_shortcode('jobyan_job_filters', array($this, 'job_filters_shortcode'));
    }
    
    /**
     * [jobyan_jobs] shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function jobs_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 10,
            'featured' => '',
            'category' => '',
            'type' => '',
            'location' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'show_filters' => 'yes',
        ), $atts);
        
        // Extract variables
        $count = intval($atts['count']);
        $featured = $atts['featured'] === 'yes' ? true : false;
        $show_filters = $atts['show_filters'] === 'yes' ? true : false;
        
        // Start output buffering
        ob_start();
        
        // Show filters if enabled
        if ($show_filters) {
            echo do_shortcode('[jobyan_job_filters]');
        }
        
        // Get query args
        $args = array(
            'post_type' => 'job',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );
        
        // Add meta query for featured jobs if specified
        if ($featured) {
            $args['meta_query'] = array(
                array(
                    'key' => '_job_featured',
                    'value' => '1',
                    'compare' => '=',
                ),
            );
        }
        
        // Add taxonomy queries if specified
        $tax_queries = array();
        
        if (!empty($atts['category'])) {
            $tax_queries[] = array(
                'taxonomy' => 'job_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            );
        }
        
        if (!empty($atts['type'])) {
            $tax_queries[] = array(
                'taxonomy' => 'job_type',
                'field' => 'slug',
                'terms' => explode(',', $atts['type']),
            );
        }
        
        if (!empty($atts['location'])) {
            $tax_queries[] = array(
                'taxonomy' => 'job_location',
                'field' => 'slug',
                'terms' => explode(',', $atts['location']),
            );
        }
        
        if (!empty($tax_queries)) {
            $args['tax_query'] = $tax_queries;
            
            if (count($tax_queries) > 1) {
                $args['tax_query']['relation'] = 'AND';
            }
        }
        
        // Apply filters to allow programmatic modification of query
        $args = apply_filters('jobyan_jobs_query_args', $args);
        
        // Get jobs
        $jobs = new WP_Query($args);
        
        // Display jobs
        if ($jobs->have_posts()) {
            // Display job listings
            echo '<div class="jobyan-jobs-list">';
            
            while ($jobs->have_posts()) {
                $jobs->the_post();
                $job_id = get_the_ID();
                
                // Get job data
                $company_name = get_post_meta($job_id, '_job_company_name', true);
                $company_location = get_post_meta($job_id, '_job_company_location', true);
                $salary_min = get_post_meta($job_id, '_job_salary_min', true);
                $salary_max = get_post_meta($job_id, '_job_salary_max', true);
                $salary_currency = get_post_meta($job_id, '_job_salary_currency', true) ?: 'USD';
                $salary_period = get_post_meta($job_id, '_job_salary_period', true) ?: 'year';
                $application_deadline = get_post_meta($job_id, '_job_application_deadline', true);
                $is_featured = get_post_meta($job_id, '_job_featured', true);
                
                // Get job terms
                $job_types = get_the_terms($job_id, 'job_type');
                $job_locations = get_the_terms($job_id, 'job_location');
                $job_categories = get_the_terms($job_id, 'job_category');
                
                // Format salary
                $salary_display = '';
                if (!empty($salary_min) || !empty($salary_max)) {
                    $currency_symbols = array(
                        'USD' => '$',
                        'EUR' => '€',
                        'GBP' => '£',
                        'JPY' => '¥',
                        'AUD' => 'A$',
                        'CAD' => 'C$',
                        'CHF' => 'CHF',
                        'CNY' => '¥',
                        'INR' => '₹',
                    );
                    
                    $currency_symbol = isset($currency_symbols[$salary_currency]) ? $currency_symbols[$salary_currency] : $salary_currency;
                    
                    if (!empty($salary_min) && !empty($salary_max)) {
                        $salary_display = $currency_symbol . number_format($salary_min) . ' - ' . $currency_symbol . number_format($salary_max);
                    } else if (!empty($salary_min)) {
                        $salary_display = $currency_symbol . number_format($salary_min) . '+';
                    } else if (!empty($salary_max)) {
                        $salary_display = 'Up to ' . $currency_symbol . number_format($salary_max);
                    }
                    
                    // Add period
                    $periods = array(
                        'hour' => __('per hour', 'jobyan'),
                        'day' => __('per day', 'jobyan'),
                        'week' => __('per week', 'jobyan'),
                        'month' => __('per month', 'jobyan'),
                        'year' => __('per year', 'jobyan'),
                    );
                    
                    $period_text = isset($periods[$salary_period]) ? $periods[$salary_period] : '';
                    if ($period_text) {
                        $salary_display .= ' ' . $period_text;
                    }
                }
                
                // Prepare deadline display
                $deadline_display = '';
                if (!empty($application_deadline)) {
                    $deadline_date = strtotime($application_deadline);
                    $today = strtotime('today');
                    
                    if ($deadline_date < $today) {
                        $deadline_display = __('Expired', 'jobyan');
                    } else {
                        $deadline_display = date_i18n(get_option('date_format'), $deadline_date);
                    }
                }
                
                // Build classes
                $job_classes = array('jobyan-job-item');
                if ($is_featured) {
                    $job_classes[] = 'featured';
                }
                
                // Output job item
                ?>
                <div id="job-<?php echo esc_attr($job_id); ?>" class="<?php echo esc_attr(implode(' ', $job_classes)); ?>">
                    <?php if ($is_featured) : ?>
                        <span class="jobyan-featured-label"><?php _e('Featured', 'jobyan'); ?></span>
                    <?php endif; ?>
                    
                    <div class="jobyan-job-header">
                        <div class="jobyan-job-title-company">
                            <h3 class="jobyan-job-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php if (!empty($company_name)) : ?>
                                <div class="jobyan-job-company">
                                    <?php echo esc_html($company_name); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="jobyan-job-meta">
                        <?php if (!empty($company_location)) : ?>
                            <div class="jobyan-job-meta-item jobyan-job-location">
                                <i class="dashicons dashicons-location"></i>
                                <?php echo esc_html($company_location); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($job_types)) : ?>
                            <div class="jobyan-job-meta-item jobyan-job-type">
                                <i class="dashicons dashicons-clock"></i>
                                <?php 
                                    $type_names = array();
                                    foreach ($job_types as $type) {
                                        $type_names[] = $type->name;
                                    }
                                    echo esc_html(implode(', ', $type_names));
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($salary_display)) : ?>
                            <div class="jobyan-job-meta-item jobyan-job-salary">
                                <i class="dashicons dashicons-money-alt"></i>
                                <?php echo esc_html($salary_display); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($deadline_display)) : ?>
                            <div class="jobyan-job-meta-item jobyan-job-deadline">
                                <i class="dashicons dashicons-calendar-alt"></i>
                                <?php printf(__('Apply by: %s', 'jobyan'), esc_html($deadline_display)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="jobyan-job-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <div class="jobyan-job-footer">
                        <div class="jobyan-job-tags">
                            <?php if (!empty($job_categories)) : ?>
                                <?php foreach ($job_categories as $category) : ?>
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="jobyan-job-tag">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="jobyan-job-date">
                            <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp'))); ?> <?php _e('ago', 'jobyan'); ?>
                        </div>
                    </div>
                    
                    <div class="jobyan-job-actions">
                        <a href="<?php the_permalink(); ?>" class="jobyan-apply-button">
                            <?php _e('View Job', 'jobyan'); ?>
                        </a>
                    </div>
                </div>
                <?php
            }
            
            echo '</div>';
            
            // Reset post data
            wp_reset_postdata();
            
            // Pagination
            $total_pages = $jobs->max_num_pages;
            
            if ($total_pages > 1) {
                $current_page = max(1, get_query_var('paged'));
                
                echo '<div class="jobyan-pagination">';
                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => 'page/%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text'    => '&laquo;',
                    'next_text'    => '&raquo;',
                ));
                echo '</div>';
            }
            
        } else {
            echo '<div class="jobyan-no-jobs">';
            echo '<p>' . __('No jobs found.', 'jobyan') . '</p>';
            echo '</div>';
        }
        
        // Return buffered output
        return ob_get_clean();
    }
    
    /**
     * [jobyan_job_filters] shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function job_filters_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_search' => 'yes',
            'show_category' => 'yes',
            'show_type' => 'yes',
            'show_location' => 'yes',
        ), $atts);
        
        // Start output buffering
        ob_start();
        
        // Get taxonomies
        $categories = get_terms(array(
            'taxonomy' => 'job_category',
            'hide_empty' => true,
        ));
        
        $types = get_terms(array(
            'taxonomy' => 'job_type',
            'hide_empty' => true,
        ));
        
        $locations = get_terms(array(
            'taxonomy' => 'job_location',
            'hide_empty' => true,
        ));
        
        // Get current filter values
        $search = isset($_GET['jobs_search']) ? sanitize_text_field($_GET['jobs_search']) : '';
        $category = isset($_GET['jobs_category']) ? sanitize_text_field($_GET['jobs_category']) : '';
        $type = isset($_GET['jobs_type']) ? sanitize_text_field($_GET['jobs_type']) : '';
        $location = isset($_GET['jobs_location']) ? sanitize_text_field($_GET['jobs_location']) : '';
        
        // Build form action URL
        $form_action = home_url('/');
        
        // Display filters
        ?>
        <div class="jobyan-job-filters">
            <form method="get" action="<?php echo esc_url($form_action); ?>" class="jobyan-filter-form">
                <div class="jobyan-filters-row">
                    <?php if ($atts['show_search'] === 'yes') : ?>
                        <div class="jobyan-filter-field">
                            <label for="jobs_search"><?php _e('Search', 'jobyan'); ?></label>
                            <input type="text" name="jobs_search" id="jobs_search" value="<?php echo esc_attr($search); ?>" placeholder="<?php _e('Keywords', 'jobyan'); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($atts['show_category'] === 'yes' && !empty($categories)) : ?>
                        <div class="jobyan-filter-field">
                            <label for="jobs_category"><?php _e('Category', 'jobyan'); ?></label>
                            <select name="jobs_category" id="jobs_category">
                                <option value=""><?php _e('All Categories', 'jobyan'); ?></option>
                                <?php foreach ($categories as $cat) : ?>
                                    <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($category, $cat->slug); ?>>
                                        <?php echo esc_html($cat->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="jobyan-filters-row">
                    <?php if ($atts['show_type'] === 'yes' && !empty($types)) : ?>
                        <div class="jobyan-filter-field">
                            <label for="jobs_type"><?php _e('Job Type', 'jobyan'); ?></label>
                            <select name="jobs_type" id="jobs_type">
                                <option value=""><?php _e('All Types', 'jobyan'); ?></option>
                                <?php foreach ($types as $t) : ?>
                                    <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($type, $t->slug); ?>>
                                        <?php echo esc_html($t->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($atts['show_location'] === 'yes' && !empty($locations)) : ?>
                        <div class="jobyan-filter-field">
                            <label for="jobs_location"><?php _e('Location', 'jobyan'); ?></label>
                            <select name="jobs_location" id="jobs_location">
                                <option value=""><?php _e('All Locations', 'jobyan'); ?></option>
                                <?php foreach ($locations as $loc) : ?>
                                    <option value="<?php echo esc_attr($loc->slug); ?>" <?php selected($location, $loc->slug); ?>>
                                        <?php echo esc_html($loc->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="jobyan-filter-buttons">
                    <button type="submit" class="jobyan-filter-button jobyan-filter-submit"><?php _e('Search Jobs', 'jobyan'); ?></button>
                    <button type="button" class="jobyan-filter-button jobyan-filter-reset"><?php _e('Reset', 'jobyan'); ?></button>
                </div>
            </form>
        </div>
        <?php
        
        // Return buffered output
        return ob_get_clean();
    }
}
