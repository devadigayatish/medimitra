<?php defined( 'ABSPATH' ) or die;

/*
 * Footer
 */

?>
                    </div>
                </div>
            </div><!-- container-fluid -->

            <footer id="site-footer">
                <div id="footer-top" class="cover-img">
                    <div id="footer-overlay">
                        <div class="container-fluid">
                            <div class="content-wrapper">

                                <?php get_sidebar( 'foot' ); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="footer-bottom" class="content-row">

                    <?php satisfy_footer_bottom_info(); ?>

                </div>
            </footer>
        </div>

        <?php
        if ( get_theme_mod( 'show_scroll_btn', true ) ) { ?>
            <div class="satisfy-to-top">
                <span class="fa fa-chevron-up"></span>
            </div>
        <?php } ?>

    </div><!-- site-wrapper -->

    <?php wp_footer(); ?>

    </body>
</html>
