/**
 * Jobyan Frontend JavaScript
 */
jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Job filters functionality
     */
    if ($('.jobyan-job-filters').length) {
        // Handle filter form submission
        $('.jobyan-filter-form').on('submit', function(e) {
            // This will submit normally, but we can add AJAX functionality here later
        });
        
        // Handle filter reset
        $('.jobyan-filter-reset').on('click', function(e) {
            e.preventDefault();
            $('.jobyan-filter-form')[0].reset();
        });
    }
    
    /**
     * Job application form validation
     */
    if ($('.jobyan-application-form').length) {
        $('.jobyan-application-form').on('submit', function(e) {
            const $form = $(this);
            const $requiredFields = $form.find('[required]');
            let isValid = true;
            
            // Reset previous validation
            $form.find('.field-error').remove();
            
            // Check each required field
            $requiredFields.each(function() {
                const $field = $(this);
                
                if (!$field.val().trim()) {
                    isValid = false;
                    $field.addClass('error');
                    $field.after('<span class="field-error">This field is required</span>');
                } else {
                    $field.removeClass('error');
                }
                
                // Email validation for email fields
                if ($field.attr('type') === 'email' && $field.val().trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test($field.val().trim())) {
                        isValid = false;
                        $field.addClass('error');
                        $field.after('<span class="field-error">Please enter a valid email address</span>');
                    }
                }
            });
            
            // Resume file validation
            const $resumeFile = $form.find('#application_resume');
            if ($resumeFile.length && $resumeFile[0].files.length > 0) {
                const file = $resumeFile[0].files[0];
                const fileSize = file.size / 1024 / 1024; // in MB
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                if (fileSize > 5) {
                    isValid = false;
                    $resumeFile.addClass('error');
                    $resumeFile.after('<span class="field-error">File size must be less than 5MB</span>');
                }
                
                if (!allowedTypes.includes(file.type)) {
                    isValid = false;
                    $resumeFile.addClass('error');
                    $resumeFile.after('<span class="field-error">Only PDF or Word documents are allowed</span>');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                $form.find('.error').first().focus();
                $('.jobyan-form-errors').html('<div class="error-message">Please correct the errors in the form.</div>').show();
            }
        });
    }
    
    /**
     * Job search functionality
     */
    $('.jobyan-search-form').on('submit', function(e) {
        // We can add AJAX search functionality here in the future
    });
});
