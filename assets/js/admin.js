/**
 * Jobyan Admin JavaScript
 */
jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Initialize date pickers
     */
    if ($.fn.datepicker && $('#job_application_deadline').length) {
        $('#job_application_deadline').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0
        });
    }
    
    /**
     * Validate salary range
     */
    $('#job_salary_min, #job_salary_max').on('change', function() {
        const minSalary = parseFloat($('#job_salary_min').val()) || 0;
        const maxSalary = parseFloat($('#job_salary_max').val()) || 0;
        
        if (maxSalary > 0 && minSalary > maxSalary) {
            alert('Minimum salary cannot be greater than maximum salary.');
            $(this).val('');
        }
    });
    
    /**
     * Job qualifications field enhancement
     */
    if ($('#job_qualification').length) {
        // Add button to help structure qualifications as a list
        const $qualContainer = $('#job_qualification').parent();
        const $helpBtn = $('<button>', {
            type: 'button',
            class: 'button button-secondary',
            text: 'Format as List',
            css: { 'margin-top': '5px' }
        });
        
        $helpBtn.on('click', function(e) {
            e.preventDefault();
            
            const qualification = $('#job_qualification').val();
            const lines = qualification.split('\n').filter(line => line.trim() !== '');
            
            // Format each line with a bullet point if it doesn't already have one
            const formattedLines = lines.map(line => {
                line = line.trim();
                if (!line.startsWith('- ') && !line.startsWith('• ')) {
                    line = '- ' + line;
                }
                return line;
            });
            
            $('#job_qualification').val(formattedLines.join('\n'));
        });
        
        $qualContainer.append($helpBtn);
    }
});
