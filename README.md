# Jobyan - WordPress Job Board Plugin

Jobyan is a comprehensive job board plugin for WordPress that enables you to create a fully functional job listing website. The plugin allows employers to post jobs and job seekers to browse and apply for positions.

## Features

### 1. Job Postings
- Custom post type for job listings
- Detailed job information fields (title, description, requirements, location, salary range, etc.)
- Featured jobs option to highlight important positions
- Job categories, types, and locations taxonomies
- Custom meta boxes for job details

### 2. Frontend Features
- Customizable job listings with shortcodes
- Detailed single job view templates
- Search and filtering capabilities
- Responsive design that works on all devices

## Installation

1. Upload the `jobyan` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start creating job listings via the 'Jobs' menu in your admin dashboard

## Usage

### Creating a Job Listing

1. Go to Jobs → Add New in your WordPress admin
2. Enter the job title and description in the main editor
3. Fill out the Job Details meta box:
   - Company Information: Name, website, location
   - Salary Information: Range, currency, and period
   - Job Requirements: Experience, qualifications
   - Application Information: Deadline, application URL
   - Featured Job: Toggle to highlight the job in listings
4. Add categories, types, and locations using the taxonomy boxes
5. Publish your job listing

### Displaying Job Listings

Use the following shortcodes to display job listings on your site:

**Basic Job Listing**
```
[jobyan_jobs]
```

**Job Listings with Options**
```
[jobyan_jobs count="5" featured="yes" orderby="date" order="DESC" show_filters="yes"]
```

Parameters:
- `count`: Number of jobs to display (default: 10)
- `featured`: Show only featured jobs - "yes" or "no" (default: "")
- `category`: Filter by category slug(s), comma-separated (default: "")
- `type`: Filter by job type slug(s), comma-separated (default: "")
- `location`: Filter by location slug(s), comma-separated (default: "")
- `orderby`: Field to order by - "date", "title", etc. (default: "date")
- `order`: Sort order - "ASC" or "DESC" (default: "DESC")
- `show_filters`: Display filters above job listings - "yes" or "no" (default: "yes")

**Job Filters Only**
```
[jobyan_job_filters]
```

Parameters:
- `show_search`: Show search field - "yes" or "no" (default: "yes")
- `show_category`: Show category filter - "yes" or "no" (default: "yes")
- `show_type`: Show job type filter - "yes" or "no" (default: "yes")
- `show_location`: Show location filter - "yes" or "no" (default: "yes")

### Creating a Job Board Page

1. Create a new page in WordPress (e.g., "Jobs")
2. Add the `[jobyan_jobs]` shortcode to the page content
3. Publish the page
4. Your visitors can now browse job listings on this page

## Customization

The plugin comes with built-in templates that can be overridden by your theme:

1. Copy files from `wp-content/plugins/jobyan/templates/` to your theme directory
2. Customize the templates as needed

Template files that can be overridden:
- `single-job.php` - Single job listing template
- `archive-job.php` - Job archive template
- `taxonomy-job.php` - Job taxonomy template

## Future Features

The following features are planned for future updates:

1. **Applications**: Allow job seekers to apply directly via the plugin
2. **User Profiles**: Separate profiles for job seekers and employers
3. **Advanced Search and Filters**: More customizable filtering options
4. **Email Notifications**: Alerts for new jobs and applications
5. **Resume Management**: Upload and manage resumes
6. **Front-end Submission**: Allow employers to post jobs from the frontend

## Support

For support, feature requests, or bug reporting, please contact the Jobyan team.

## License

This plugin is licensed under the GPL v2 or later.

---

Thank you for using Jobyan! We hope it helps you build a successful job board on your WordPress site.
