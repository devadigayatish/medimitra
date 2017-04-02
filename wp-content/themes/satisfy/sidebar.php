<?php defined( 'ABSPATH' ) or die;

/*
 * Sidebar
 */

if ( is_active_sidebar( 'sidebar-1' ) && false === strpos( get_theme_mod( 'sidebar' ), 'off' ) ) { ?>

    <div id="primary-sidebar" class="col-md-4 col-sm-12">
        <div class="sfy-pad-<?php echo 'left' === get_theme_mod( 'sidebar' ) ? 'right' : 'left'; ?>">
            <aside id="inner-content-sidebar">
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
            </aside>
        </div>
    </div><!-- primary sidebar -->

<?php }
