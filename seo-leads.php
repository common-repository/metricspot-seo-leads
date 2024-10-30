<?php
/*
Plugin Name: MetricSpot SEO Leads
Plugin URI: https://wordpress.org/plugins/metricspot-seo-leads/
Description: With MetricSpot's SEO Leads Plugin you will be able to offer free SEO reports on your own website. Automate the process of capturing SEO leads!
Version: 2017.09.25
Author: MetricSpot
Author URI: https://metricspot.com 
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
MetricSpot SEO Leads is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
MetricSpot SEO Leads is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with MetricSpot SEO Leads. If not, see https://www.gnu.org/licenses/gpl-2.0.html
*/

function ms_add_async_forscript($url)
{
    if (strpos($url, '#asyncload')===false){
        return $url;
    } else if (is_admin()){
        return str_replace('#asyncload', '', $url);
    } else{
        return str_replace('#asyncload', '', $url)."' async='async"; 
    }
}
add_filter('clean_url', 'ms_add_async_forscript', 11, 1);

function metricspot_script() {
wp_enqueue_script( 'metricspot-seo-leads', 'https://metricspot.com/apps/audit.min.js#asyncload', array(), '2017.09.25', true);
}
add_action( 'wp_footer', 'metricspot_script' );


/**
 * Add widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function ms_add_dashboard_widgets() {

    wp_add_dashboard_widget(
             'metricspot_dashboard_widget',         // Widget slug.
             'MetricSpot SEO Leads',         // Title.
             'metricspot_dashboard_widget_function' // Display function.
    );	
}
add_action( 'wp_dashboard_setup', 'ms_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function metricspot_dashboard_widget_function() {

    // Display whatever it is you want to show.
    echo '<p>Copy the <a href="https://metricspot.com/seo-leads/">code snippets</a> from your dashboard and paste them in your website.</p>';
    echo '<p>You can start by adding a <a href="https://metricspot.com/seo-leads/">conversion form</a> to your sidebar, your footer or any other widget area of your website.</p>';
    echo '<p>You can also insert forms inside the content of your posts to convert your most engaged visitors.</p>';
}


function metricspot_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'metricspot_dashboard_widget', 'MetricSpot SEO Leads', 'metricspot_dashboard_widget_function' );

    // Globalize the metaboxes array, this holds all the widgets for wp-admin

    global $wp_meta_boxes;

    // Get the regular dashboard widgets array 
    // (which has our new widget already but at the end)

    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    // Backup and delete our new dashboard widget from the end of the array

    $my_widget_backup = array( 'metricspot_dashboard_widget' => $normal_dashboard['metricspot_dashboard_widget'] );
    unset( $normal_dashboard['metricspot_dashboard_widget'] );

    // Merge the two arrays together so our widget is at the beginning

    $sorted_dashboard = array_merge( $my_widget_backup, $normal_dashboard );

    // Save the sorted array back into the original metaboxes 

    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
} 