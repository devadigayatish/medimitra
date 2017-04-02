<?php defined( 'ABSPATH' ) or die;

/*
 * 404 not found
 */

get_header();

satisfy_blog_loop(
    __( 'Oops! That page can\'t be found', 'satisfy' ),
    sprintf(
        '<p>%s.</p>
        <p class="section-padding-sm">%s</p>
        <div>%s</div>
        <p class="section-padding">
            <a href="%s"><span class="fa fa-long-arrow-left"></span> %s</a>
        </p>',
        __( 'The page was not found (404)', 'satisfy' ),
        __( 'Maybe a search?', 'satisfy' ),
        get_search_form( false ),
        esc_url( home_url( '/' ) ),
        __( 'Go back to homepage', 'satisfy' )
    )
);

get_footer();
