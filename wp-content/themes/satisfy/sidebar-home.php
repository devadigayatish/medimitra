<?php defined( 'ABSPATH' ) or die;

/*
 * Home sidebar
 */

if ( is_active_sidebar( 'home-1' ) && 0 === get_query_var( 'paged' ) ) { ?>

    <div id="home-page-widgets">
        <?php dynamic_sidebar( 'home-1' ); ?>
    </div>

<?php }
