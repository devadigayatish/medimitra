<?php defined( 'ABSPATH' ) or die;

/*
 * Admin settings
 */

class Satisfy_admin {

    // Loads scripts and styles for admin settings page
    public static function scripts_and_styles ( $page ) {
        if ( 'appearance_page_' . SATISFY_THEME_ADMIN === $page ) {
            wp_enqueue_script( 'jquery-ui-tabs' );
            wp_enqueue_script( 'satisfy-theme-admin', SATISFY_THEME_URL . 'js/satisfy-theme-admin.js', array( 'jquery' ), SATISFY_THEME_VERSION, true );
            wp_enqueue_style( 'satisfy-theme-admin-css', SATISFY_THEME_URL . 'css/satisfy-theme-admin.css', array(), SATISFY_THEME_VERSION );
        }
    }

    // Admin settings page
    public static function settings_page () { ?>
        <div class="wrap">

            <div id="<?php echo SATISFY_THEME_ADMIN . '-form' ?>">
                <h1><?php _e( 'Satisfy Info', 'satisfy' ); ?></h1>

                <div id="tabs">

                    <ul class="tabs-ul">
                        <li><a href="#tabs-1"><?php _e( 'General', 'satisfy' ); ?></a></li>
                        <li><a href="#tabs-2"><?php _e( 'Translations', 'satisfy' ); ?></a></li>
                        <li><a href="#tabs-3"><?php _e( 'Premium', 'satisfy' ); ?></a></li>
                    </ul>

                    <div id="tabs-1" class="tab-item">
                        <h2><?php _e( 'General', 'satisfy' ); ?></h2>
                        <?php printf( __( 'Your theme settings are at Appearance &gt; <a href="%s">Customize</a>.', 'satisfy' ), esc_url( admin_url( 'customize.php' ) ) ); ?>

                        <h2 class="top-30"><?php _e( 'Widgets', 'satisfy' ); ?></h2>
                        <p><?php _e( 'Satisfy adds two widgets that you can use', 'satisfy' ); ?></p>

                        <ul class="default-ul">
                            <li><?php _e( 'Satisfy Latest Posts - Latest posts with thumbnail images (uses posts "featured image")', 'satisfy' ); ?></li>
                            <li><?php _e( 'Satisfy Image - Displays an image of your choice', 'satisfy' ); ?></li>
                        </ul>

                        <h2 class="top-30"><?php _e( 'About the footer', 'satisfy' ); ?></h2>
                        <p>
                            <?php _e( 'The theme adds three sections in the footer for widgets (Footer 1, Footer 2, and Footer 3). If you only add widgets in one section they will have 100% width in the footer, two sections will have 50% each and three sections 33%. Tip: If you only want to have a small widget in the middle add it to the Footer 2 (middle) widget and add an empty "Text widget" in Footer 1 (left) and one in Footer 3 (right).', 'satisfy' ); ?>
                        </p>

                        <h2 class="top-30"><?php _e( 'Like the theme?', 'satisfy' ); ?></h2>
                        <p><?php _e( 'Show your support by rating the theme at wordpress.org!', 'satisfy' ); ?></p>
                    </div>

                    <div id="tabs-2" class="tab-item">
                        <h2><?php _e( 'Satisfy is translation ready!', 'satisfy' ); ?></h2>
                        <p>
                            <?php _e( 'On your site the theme displays some sentences like "posted on", "updated" and "Oops! That page can\'t be found" etc. These sentences can be translated into your own language if you want. (If they already aren\'t)', 'satisfy' ); ?>
                        </p>
                        <p>
                            <?php _e( 'Either you can help contributing to translating the theme at satifys theme page at wordpress.org so others also can use your translations. Or you can use a plugin like Loco Translate or some other to translate the sentences you want. Programs like Poedit also does the trick.', 'satisfy' ); ?>
                        </p>
                    </div>

                    <div id="tabs-3" class="tab-item">
                        <h2><?php _e( 'Satisfy Premium', 'satisfy' ); ?></h2>
                        <p><?php _e( 'There\'s a premium version of Satisfy out there which includes:', 'satisfy' ); ?></p>

                        <ul class="default-ul">
                            <li><?php _e( 'Home page header slider', 'satisfy' ); ?></li>
                            <li><?php _e( 'Home page video header', 'satisfy' ); ?></li>
                            <li><?php _e( 'Customizable headers on Posts and Pages', 'satisfy' ); ?></li>
                            <li><?php _e( 'Google fonts', 'satisfy' ); ?></li>
                            <li><?php _e( 'And much more!', 'satisfy' ); ?></li>
                        </ul>

                        <p><?php printf( __( 'Available at: <a href="%s" target="_blank">webbjocke.com/satisfy</a>', 'satisfy' ), esc_url( 'https://www.webbjocke.com/satisfy/' ) ); ?></p>
                    </div>

                </div>

            </div>
        </div><!-- wrap -->
    <?php
    }
}
