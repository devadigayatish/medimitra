<?php defined( 'ABSPATH' ) or die;

/*
 * Template Name: No Sidebar
 */

satisfy_temp_option( 'sidebar', 'off' );

get_header();

satisfy_blog_loop();

get_footer();
