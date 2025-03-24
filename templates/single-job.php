<?php
/**
 * Template for displaying single job posts
 *
 * @package Jobyan
 */

get_header();

// Start the loop
while (have_posts()) : the_post();

    // Get job data
    $job_id = get_the_ID();
    $company_name = get_post_meta($job_id, '_job_company_name', true);
    $company_website = get_post_meta($job_id, '_job_company_website', true);
    $company_location = get_post_meta($job_id, '_job_company_location', true);
    $salary_min = get_post_meta($job_id, '_job_salary_min', true);
    $salary_max = get_post_meta($job_id, '_job_salary_max', true);
    $salary_currency = get_post_meta($job_id, '_job_salary_currency', true) ?: 'USD';
    $salary_period = get_post_meta($job_id, '_job_salary_period', true) ?: 'year';
    $application_deadline = get_post_meta($job_id, '_job_application_deadline', true);
    $experience_required = get_post_meta($job_id, '_job_experience_required', true);
    $qualification = get_post_meta($job_id, '_job_qualification', true);
    $application_url = get_post_meta($job_id, '_job_application_url', true);
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
    
    // Check if job is expired
    $is_expired = false;
    if (!empty($application_deadline)) {
        $deadline_date = strtotime($application_deadline);
        $today = strtotime('today');
        
        if ($deadline_date < $today) {
            $is_expired = true;
        }
    }
?>

<div class="jobyan-job-container">
    <div class="jobyan-job-detail-header">
        <?php if ($is_featured) : ?>
            <span class="jobyan-featured-label"><?php _e('Featured', 'jobyan'); ?></span>
        <?php endif; ?>
        
        <?php if ($is_expired) : ?>
            <div class="jobyan-expired-notice">
                <p><?php _e('This job listing has expired', 'jobyan'); ?></p>
            </div>
        <?php endif; ?>
        
        <h1 class="jobyan-job-detail-title"><?php the_title(); ?></h1>
        
        <?php if (!empty($company_name)) : ?>
            <div class="jobyan-job-detail-company">
                <?php echo esc_html($company_name); ?>
                <?php if (!empty($company_website)) : ?>
                    <a href="<?php echo esc_url($company_website); ?>" target="_blank" rel="nofollow">
                        <?php _e('Visit Website', 'jobyan'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="jobyan-job-detail-meta">
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
            
            <div class="jobyan-job-meta-item jobyan-job-date">
                <i class="dashicons dashicons-calendar"></i>
                <?php printf(__('Posted: %s ago', 'jobyan'), human_time_diff(get_the_time('U'), current_time('timestamp'))); ?>
            </div>
        </div>
    </div>
    
    <?php if (!empty($job_categories)) : ?>
        <div class="jobyan-job-categories">
            <?php foreach ($job_categories as $category) : ?>
                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="jobyan-job-tag">
                    <?php echo esc_html($category->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="jobyan-job-detail-section">
        <h2 class="jobyan-job-detail-section-title"><?php _e('Job Description', 'jobyan'); ?></h2>
        <div class="jobyan-job-description">
            <?php the_content(); ?>
        </div>
    </div>
    
    <?php if (!empty($experience_required) || !empty($qualification)) : ?>
        <div class="jobyan-job-detail-section">
            <h2 class="jobyan-job-detail-section-title"><?php _e('Requirements', 'jobyan'); ?></h2>
            
            <?php if (!empty($experience_required)) : ?>
                <div class="jobyan-job-experience">
                    <h3><?php _e('Experience', 'jobyan'); ?></h3>
                    <p><?php echo esc_html($experience_required); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($qualification)) : ?>
                <div class="jobyan-job-qualification">
                    <h3><?php _e('Qualifications', 'jobyan'); ?></h3>
                    <?php echo wpautop(wp_kses_post($qualification)); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($company_name)) : ?>
        <div class="jobyan-job-detail-section">
            <h2 class="jobyan-job-detail-section-title"><?php _e('About the Company', 'jobyan'); ?></h2>
            <div class="jobyan-company-info">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="jobyan-company-logo">
                        <?php the_post_thumbnail('medium'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="jobyan-company-details">
                    <h3><?php echo esc_html($company_name); ?></h3>
                    
                    <?php if (!empty($company_location)) : ?>
                        <p><strong><?php _e('Location:', 'jobyan'); ?></strong> <?php echo esc_html($company_location); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($company_website)) : ?>
                        <p><strong><?php _e('Website:', 'jobyan'); ?></strong> <a href="<?php echo esc_url($company_website); ?>" target="_blank" rel="nofollow"><?php echo esc_url($company_website); ?></a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (!$is_expired) : ?>
        <div class="jobyan-job-apply-section">
            <?php if (!empty($application_url)) : ?>
                <a href="<?php echo esc_url($application_url); ?>" target="_blank" rel="nofollow" class="jobyan-application-button">
                    <?php _e('Apply for this job', 'jobyan'); ?>
                </a>
            <?php else : ?>
                <a href="#jobyan-application-form" class="jobyan-application-button">
                    <?php _e('Apply for this job', 'jobyan'); ?>
                </a>
                
                <!-- Application form will be added in future updates -->
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="jobyan-job-navigation">
        <div class="jobyan-job-nav-previous">
            <?php previous_post_link('%link', '&laquo; %title', true, '', 'job_category'); ?>
        </div>
        <div class="jobyan-job-nav-next">
            <?php next_post_link('%link', '%title &raquo;', true, '', 'job_category'); ?>
        </div>
    </div>
</div>

<?php
endwhile;

get_footer();
