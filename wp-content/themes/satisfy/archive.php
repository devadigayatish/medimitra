<?php defined( 'ABSPATH' ) or die;

/*
 * Archives
 */

get_header();

$satisfy_archive_desc = get_the_archive_description();

if ( is_author() && get_theme_mod( 'show_author_pic' ) ) {
    $satisfy_archive_desc = '<div class="satisfy-author">' . get_avatar( get_the_author_meta( 'ID' ) ) . $satisfy_archive_desc . '</div>';
}

satisfy_blog_loop(
    satisfy_get_paged( strip_tags( get_the_archive_title() ) ),
    $satisfy_archive_desc
);

get_footer();
