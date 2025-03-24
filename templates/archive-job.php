<?php
/**
 * Template for displaying job archives
 *
 * @package Jobyan
 */

get_header();
?>

<div class="jobyan-jobs-archive">
    <h1 class="jobyan-archive-title"><?php post_type_archive_title(); ?></h1>
    
    <?php 
    // Show job filters
    echo do_shortcode('[jobyan_job_filters]');
    
    // Check if we have jobs
    if (have_posts()) :
    ?>
        <div class="jobyan-jobs-list">
            <?php while (have_posts()) : the_post(); 
                // Get job data
                $job_id = get_the_ID();
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
            <?php endwhile; ?>
        </div>
        
        <?php
        // Pagination
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('&laquo; Previous', 'jobyan'),
            'next_text' => __('Next &raquo;', 'jobyan'),
        ));
        ?>
        
    <?php else : ?>
        <div class="jobyan-no-jobs">
            <p><?php _e('No jobs found.', 'jobyan'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();
