<?php defined( 'ABSPATH' ) or die;

/*
 * Template Name: Full Width No Sidebar
 */

satisfy_temp_option( 'page_width', '100%' );
satisfy_temp_option( 'sidebar', 'off' );

get_header();

satisfy_blog_loop();

get_footer();
