<?php
/**
 * Job Meta Boxes
 */
class Jobyan_Meta_Boxes {
    /**
     * Register meta boxes
     */
    public function register() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
    }

    /**
     * Add meta boxes to the job post type
     */
    public function add_meta_boxes() {
        add_meta_box(
            'jobyan_job_details',
            __('Job Details', 'jobyan'),
            array($this, 'job_details_meta_box'),
            'job',
            'normal',
            'high'
        );
    }

    /**
     * Job details meta box
     */
    public function job_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('jobyan_save_job_details', 'jobyan_job_details_nonce');

        // Get saved values
        $salary_min = get_post_meta($post->ID, '_job_salary_min', true);
        $salary_max = get_post_meta($post->ID, '_job_salary_max', true);
        $salary_currency = get_post_meta($post->ID, '_job_salary_currency', true) ?: 'USD';
        $salary_period = get_post_meta($post->ID, '_job_salary_period', true) ?: 'year';
        $application_deadline = get_post_meta($post->ID, '_job_application_deadline', true);
        $company_name = get_post_meta($post->ID, '_job_company_name', true);
        $company_website = get_post_meta($post->ID, '_job_company_website', true);
        $company_location = get_post_meta($post->ID, '_job_company_location', true);
        $experience_required = get_post_meta($post->ID, '_job_experience_required', true);
        $qualification = get_post_meta($post->ID, '_job_qualification', true);
        $application_url = get_post_meta($post->ID, '_job_application_url', true);
        $featured = get_post_meta($post->ID, '_job_featured', true);
        
        // Currency options
        $currency_options = array(
            'USD' => __('US Dollar ($)', 'jobyan'),
            'EUR' => __('Euro (€)', 'jobyan'),
            'GBP' => __('British Pound (£)', 'jobyan'),
            'JPY' => __('Japanese Yen (¥)', 'jobyan'),
            'AUD' => __('Australian Dollar (A$)', 'jobyan'),
            'CAD' => __('Canadian Dollar (C$)', 'jobyan'),
            'CHF' => __('Swiss Franc (CHF)', 'jobyan'),
            'CNY' => __('Chinese Yuan (¥)', 'jobyan'),
            'INR' => __('Indian Rupee (₹)', 'jobyan'),
        );
        
        // Salary period options
        $period_options = array(
            'hour' => __('Per hour', 'jobyan'),
            'day' => __('Per day', 'jobyan'),
            'week' => __('Per week', 'jobyan'),
            'month' => __('Per month', 'jobyan'),
            'year' => __('Per year', 'jobyan'),
        );
        
        ?>
        <div class="jobyan-meta-box-panel">
            <h3><?php _e('Company Information', 'jobyan'); ?></h3>
            <div class="jobyan-meta-field">
                <label for="job_company_name"><?php _e('Company Name', 'jobyan'); ?>:</label>
                <input type="text" id="job_company_name" name="job_company_name" value="<?php echo esc_attr($company_name); ?>" class="widefat">
            </div>
            
            <div class="jobyan-meta-field">
                <label for="job_company_website"><?php _e('Company Website', 'jobyan'); ?>:</label>
                <input type="url" id="job_company_website" name="job_company_website" value="<?php echo esc_url($company_website); ?>" class="widefat">
            </div>
            
            <div class="jobyan-meta-field">
                <label for="job_company_location"><?php _e('Company Location', 'jobyan'); ?>:</label>
                <input type="text" id="job_company_location" name="job_company_location" value="<?php echo esc_attr($company_location); ?>" class="widefat">
                <p class="description"><?php _e('Full address or general location (e.g., "New York, NY" or "Remote")', 'jobyan'); ?></p>
            </div>
            
            <h3><?php _e('Salary Information', 'jobyan'); ?></h3>
            <div class="jobyan-meta-field jobyan-salary-range">
                <label><?php _e('Salary Range', 'jobyan'); ?>:</label>
                <div class="salary-inputs">
                    <span class="salary-min">
                        <input type="number" id="job_salary_min" name="job_salary_min" value="<?php echo esc_attr($salary_min); ?>" min="0" step="0.01" placeholder="<?php _e('Min', 'jobyan'); ?>">
                    </span>
                    <span class="salary-separator">-</span>
                    <span class="salary-max">
                        <input type="number" id="job_salary_max" name="job_salary_max" value="<?php echo esc_attr($salary_max); ?>" min="0" step="0.01" placeholder="<?php _e('Max', 'jobyan'); ?>">
                    </span>
                </div>
            </div>
            
            <div class="jobyan-meta-field">
                <label for="job_salary_currency"><?php _e('Currency', 'jobyan'); ?>:</label>
                <select id="job_salary_currency" name="job_salary_currency" class="widefat">
                    <?php foreach ($currency_options as $code => $name) : ?>
                        <option value="<?php echo esc_attr($code); ?>" <?php selected($salary_currency, $code); ?>><?php echo esc_html($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="jobyan-meta-field">
                <label for="job_salary_period"><?php _e('Period', 'jobyan'); ?>:</label>
                <select id="job_salary_period" name="job_salary_period" class="widefat">
                    <?php foreach ($period_options as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($salary_period, $key); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <h3><?php _e('Job Requirements', 'jobyan'); ?></h3>
            <div class="jobyan-meta-field">
                <label for="job_experience_required"><?php _e('Experience Required', 'jobyan'); ?>:</label>
                <input type="text" id="job_experience_required" name="job_experience_required" value="<?php echo esc_attr($experience_required); ?>" class="widefat" placeholder="<?php _e('e.g., 2+ years', 'jobyan'); ?>">
            </div>
            
            <div class="jobyan-meta-field">
                <label for="job_qualification"><?php _e('Qualifications', 'jobyan'); ?>:</label>
                <textarea id="job_qualification" name="job_qualification" rows="4" class="widefat"><?php echo esc_textarea($qualification); ?></textarea>
                <p class="description"><?php _e('Required degrees, certifications, etc.', 'jobyan'); ?></p>
            </div>
            
            <h3><?php _e('Application Information', 'jobyan'); ?></h3>
            <div class="jobyan-meta-field">
                <label for="job_application_deadline"><?php _e('Application Deadline', 'jobyan'); ?>:</label>
                <input type="date" id="job_application_deadline" name="job_application_deadline" value="<?php echo esc_attr($application_deadline); ?>" class="widefat">
            </div>
            
            <div class="jobyan-meta-field">
                <label for="job_application_url"><?php _e('External Application URL', 'jobyan'); ?>:</label>
                <input type="url" id="job_application_url" name="job_application_url" value="<?php echo esc_url($application_url); ?>" class="widefat">
                <p class="description"><?php _e('If left empty, applicants will apply through this site', 'jobyan'); ?></p>
            </div>
            
            <div class="jobyan-meta-field">
                <label>
                    <input type="checkbox" id="job_featured" name="job_featured" value="1" <?php checked($featured, '1'); ?>>
                    <?php _e('Featured Job', 'jobyan'); ?>
                </label>
                <p class="description"><?php _e('Featured jobs will be highlighted and displayed at the top of the job list', 'jobyan'); ?></p>
            </div>
        </div>
        <style>
            .jobyan-meta-box-panel {
                padding: 10px;
            }
            .jobyan-meta-field {
                margin-bottom: 15px;
            }
            .jobyan-meta-field label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .salary-inputs {
                display: flex;
                align-items: center;
            }
            .salary-min, .salary-max {
                flex: 1;
            }
            .salary-separator {
                margin: 0 10px;
            }
            .jobyan-meta-box-panel h3 {
                margin: 15px 0 10px;
                padding-bottom: 5px;
                border-bottom: 1px solid #eee;
            }
        </style>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id, $post) {
        // Check if our nonce is set
        if (!isset($_POST['jobyan_job_details_nonce'])) {
            return;
        }

        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['jobyan_job_details_nonce'], 'jobyan_save_job_details')) {
            return;
        }

        // If this is an autosave, we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if ('job' !== $post->post_type) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save job details
        $fields = array(
            'job_company_name' => 'sanitize_text_field',
            'job_company_website' => 'esc_url_raw',
            'job_company_location' => 'sanitize_text_field',
            'job_salary_min' => 'sanitize_text_field',
            'job_salary_max' => 'sanitize_text_field',
            'job_salary_currency' => 'sanitize_text_field',
            'job_salary_period' => 'sanitize_text_field',
            'job_experience_required' => 'sanitize_text_field',
            'job_qualification' => 'sanitize_textarea_field',
            'job_application_deadline' => 'sanitize_text_field',
            'job_application_url' => 'esc_url_raw',
        );

        foreach ($fields as $field => $sanitize_callback) {
            if (isset($_POST[$field])) {
                $value = call_user_func($sanitize_callback, $_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }

        // Save checkbox fields
        $checkbox_fields = array('job_featured');
        
        foreach ($checkbox_fields as $field) {
            $value = isset($_POST[$field]) ? '1' : '0';
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
