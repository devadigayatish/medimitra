<?php defined( 'ABSPATH' ) or die;

/*
 * Footer sidebar
 */

function satisfy_sidebar_foot () {
    $widget_numbers = array();

    for ( $i = 1; $i <= 3; $i++ ) {
        if ( is_active_sidebar( "footer-$i" ) ) {
            $widget_numbers[] = $i;
        }
    }

    if ( $widget_numbers ) {
        $cols = 12 / count( $widget_numbers ); ?>

        <div class="footer-widgets">
            <?php foreach ( $widget_numbers as $num ) { ?>
                <div class="foot-widgets col-sm-<?php echo $cols; ?>">
                    <?php dynamic_sidebar( "footer-$num" ); ?>
                </div>
            <?php } ?>
        </div>

    <?php
    } else { ?>
        <div class="col-xs-12">
            <?php satisfy_footer_top_info(); ?>
        </div>
    <?php
    }
}

satisfy_sidebar_foot();
