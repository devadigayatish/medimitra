<?php defined( 'ABSPATH' ) or die;

/*
 * Class that handles customization options
 */

class Satisfy_customize {

    // Loads sections, settings and controls
    public static function init ( $wp_customize ) {
        self::add_sections( $wp_customize );
        self::add_settings( $wp_customize );
        self::add_controls( $wp_customize );
    }

    // Sections
    public static function add_sections ( $c ) {
        $c->add_section( 'satisfy_fonts' , array(
            'title'       => __( 'Fonts', 'satisfy' ),
            'description' => __( 'Satisfy themes font options.', 'satisfy' )
        ) );

        $c->add_section( 'satisfy_layout', array(
            'title'       => __( 'Layout', 'satisfy' ),
            'description' => __( 'Satisfy themes layout options', 'satisfy' )
        ) );

        $c->add_section( 'satisfy_footer', array(
            'title'       => __( 'Footer', 'satisfy' ),
            'description' => __( 'Satisfy themes footer options', 'satisfy' )
        ) );
    }

    // Settings
    public static function add_settings ( $c ) {
        $c->add_setting( 'satisfy[text_color]', array(
            'default'           => '#444444',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[header_color]', array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[link_color]', array(
            'default'           => '#ba4444',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[link_hover]', array(
            'default'           => '#d16a57',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[border_color]', array(
            'default'           => '#e8e8e8',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[menu_color]', array(
            'default'           => '#fcfcfc',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[menu_background]', array(
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[footer_color]', array(
            'default'           => '#fcfcfc',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[footer_background]', array(
            'default'           => '#ba4444',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy[footer_border]', array(
            'default'           => '#e8e8e8',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );
        $c->add_setting( 'satisfy_banner[size]', array(
            'default'           => 60,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy_banner[page_size]', array(
            'default'           => 60,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy[banner_overlay]', array(
            'default'           => 0.3,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy[footer_overlay]', array(
            'default'           => 0,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy[body_font_size]', array(
            'default'           => 14,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy[headings_font_size]', array(
            'default'           => 1,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy[hero_font_size]', array(
            'default'           => 1.7,
            'sanitize_callback' => array( __CLASS__, 'float_val' )
        ) );
        $c->add_setting( 'satisfy[body_font_family]', array(
            'default'           => '"Open Sans", sans-serif',
            'sanitize_callback' => array( __CLASS__, 'valid_font' )
        ) );
        $c->add_setting( 'satisfy[headings_font_family]', array(
            'default'           => '"Roboto Slab", serif',
            'sanitize_callback' => array( __CLASS__, 'valid_font' )
        ) );

        // Booleans and radio options
        $c->add_setting( 'satisfy_banner[arrow]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy_banner[shadow]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'posts_featured_images', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field'
        ) );
        $c->add_setting( 'featured_images', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field'
        ) );
        $c->add_setting( 'satisfy[footer_image]', array (
            'sanitize_callback' => 'esc_url_raw'
        ) );
        $c->add_setting( 'satisfy[footer_shadow]', array(
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy_show_menu_slogan', array(
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy_tagline', array(
            'sanitize_callback' => 'sanitize_text_field'
        ) );
        $c->add_setting( 'satisfy_new_slogan', array(
            'sanitize_callback' => 'wp_kses_post'
        ) );
        $c->add_setting( 'show_html_tags', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'show_full_posts', array(
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'post_info', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'post_info_pages', array(
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'show_updated', array(
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'show_scroll_btn', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'show_author_pic', array(
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy[fontawesome]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy[stickymenu]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy[search]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy[read_more]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'satisfy[show_nextlinks]', array(
            'default' => true,
            'sanitize_callback' => array( __CLASS__, 'bool_val' )
        ) );
        $c->add_setting( 'sidebar', array(
            'sanitize_callback' => 'sanitize_text_field'
        ) );
        $c->add_setting( 'footer_text', array(
            'sanitize_callback' => 'wp_kses_post'
        ) );
    }

    // Controls
    public static function add_controls ( $c ) {

        // Colors
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_text_color', array(
            'label'    => __( 'Text color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[text_color]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_header_color', array(
            'label'    => __( 'Headings color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[header_color]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_link_color', array(
            'label'    => __( 'Links color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[link_color]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_link_hover', array(
            'label'    => __( 'Links hover color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[link_hover]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_border_color', array(
            'label'    => __( 'Borders color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[border_color]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_menu_color', array(
            'label'    => __( 'Header menu text color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[menu_color]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_menu_background', array(
            'label'    => __( 'Header menu background color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[menu_background]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_footer_color', array(
            'label'    => __( 'Footer text color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[footer_color]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_footer_background', array(
            'label'    => __( 'Footer background color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[footer_background]'
        ) ) );
        $c->add_control( new WP_Customize_Color_Control( $c, 'satisfy_footer_border', array(
            'label'    => __( 'Footer border color', 'satisfy' ),
            'section'  => 'colors',
            'settings' => 'satisfy[footer_border]'
        ) ) );

        // Fonts
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_body_font_size', array(
            'label'       => __( 'Text Font size', 'satisfy' ),
            'description' => sprintf( '(%s: 14px)', __( 'default', 'satisfy' ) ),
            'section'     => 'satisfy_fonts',
            'settings'    => 'satisfy[body_font_size]',
            'type'        => 'select',
            'choices'     => self::get_units( 8, 22, 1, 'px' )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_body_font_family', array(
            'label'    => __( 'Text Font family', 'satisfy' ),
            'section'  => 'satisfy_fonts',
            'settings' => 'satisfy[body_font_family]',
            'type'     => 'select',
            'choices'  => self::get_fonts()
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_headings_font_size', array(
            'label'       => __( 'Headers font size', 'satisfy' ),
            'description' => sprintf( '(%s: 100%%)', __( 'default', 'satisfy' ) ),
            'section'     => 'satisfy_fonts',
            'settings'    => 'satisfy[headings_font_size]',
            'type'        => 'select',
            'choices'     => self::get_units( 0.5, 2.1, 0.1, '%' )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_headings_font_family', array(
            'label'    => __( 'Headers Font family', 'satisfy' ),
            'section'  => 'satisfy_fonts',
            'settings' => 'satisfy[headings_font_family]',
            'type'     => 'select',
            'choices'  => self::get_fonts()
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_hero_font_size', array(
            'label'       => __( 'Big banner font size', 'satisfy' ),
            'description' =>  sprintf( '(%s: 170%%)', __( 'default', 'satisfy' ) ),
            'section'     => 'satisfy_fonts',
            'settings'    => 'satisfy[hero_font_size]',
            'type'        => 'select',
            'choices'     => self::get_units( 1, 2.6, 0.1, '%' )
        ) ) );

        // Identity
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_satisfy_show_menu_slogan', array(
            'label'       => __( 'Display tagline', 'satisfy' ),
            'description' => __( 'Also displays your tagline in header menu if "Display Site Title and Tagline" option is checked', 'satisfy' ),
            'section'     => 'title_tagline',
            'settings'    => 'satisfy_show_menu_slogan',
            'type'        => 'checkbox'
        ) ) );

        // Header image
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_satisfy_tagline', array(
            'label'       => __( 'Home page header title', 'satisfy' ),
            'section'     => 'header_image',
            'settings'    => 'satisfy_tagline',
            'type'        => 'text'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_satisfy_new_slogan', array(
            'label'       => __( 'Home page header text', 'satisfy' ),
            'description' => sprintf( '%s: %s', __( 'Allowed tags', 'satisfy' ), satisfy_get_allowed_html_tags() ),
            'section'     => 'header_image',
            'settings'    => 'satisfy_new_slogan',
            'type'        => 'textarea'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_banner_arrow', array(
            'label'    => __( 'Show header scroll down arrow', 'satisfy' ),
            'section'  => 'header_image',
            'settings' => 'satisfy_banner[arrow]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_banner_shadow', array(
            'label'    => __( 'Text shadow in header', 'satisfy' ),
            'section'  => 'header_image',
            'settings' => 'satisfy_banner[shadow]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_banner_size', array(
            'label'       => __( 'Header height on home page', 'satisfy' ),
            'description' => __( 'Header is always 100% width, here you change its height. Vh units are relative to your screen, 100vh means full screen height', 'satisfy' ),
            'section'     => 'header_image',
            'settings'    => 'satisfy_banner[size]',
            'type'        => 'select',
            'choices'     => self::get_units( 25, 100, 1, 'vh' )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_banner_page_size', array(
            'label'       => __( 'Header height on posts and pages', 'satisfy' ),
            'description' => __( 'If you have set the option to display featured images as header images in layout section', 'satisfy' ),
            'section'     => 'header_image',
            'settings'    => 'satisfy_banner[page_size]',
            'type'        => 'select',
            'choices'     => self::get_units( 25, 100, 1, 'vh' )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_banner_overlay', array(
            'label'       => __( 'Black background overlay', 'satisfy' ),
            'section'     => 'header_image',
            'settings'    => 'satisfy[banner_overlay]',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 1,
                'step' => 0.1
            )
        ) ) );

        // Layout
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_sidebar', array(
            'label'    => __( 'Sidebar', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'sidebar',
            'type'     => 'radio',
            'choices'  => array(
                ''          => __( 'Right sidebar', 'satisfy' ),
                'left'      => __( 'Left sidebar', 'satisfy' ),
                'off'       => __( 'No sidebar', 'satisfy' ),
                'off-small' => __( 'No sidebar and small layout', 'satisfy' )
            )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_posts_featured_images', array(
            'label'       => __( 'Featured images in posts and pages', 'satisfy' ),
            'description' => __( 'How your posts and pages "featured images" should be displayed on those pages', 'satisfy' ),
            'section'     => 'satisfy_layout',
            'settings'    => 'posts_featured_images',
            'type'        => 'radio',
            'choices'     => array(
                ''     => __( 'In content', 'satisfy' ),
                'full' => __( 'As header images', 'satisfy' ),
                'off'  => __( 'Don\'t show in posts or pages', 'satisfy' )
            )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_featured_images', array(
            'label'       => __( 'Featured images in blog loops', 'satisfy' ),
            'description' => __( 'How your posts "featured images" should be displayed in blog loops', 'satisfy' ),
            'section'     => 'satisfy_layout',
            'settings'    => 'featured_images',
            'type'        => 'radio',
            'choices'     => array(
                ''     => __( 'Medium size', 'satisfy' ),
                'full' => __( 'Full size', 'satisfy' ),
                'off'  => __( 'Don\'t show in blog loops', 'satisfy' )
            )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_search', array(
            'label'    => __( 'Show search icon in header menu', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'satisfy[search]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_stickymenu', array(
            'label'    => __( 'Sticky header menu', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'satisfy[stickymenu]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_scroll_btn', array(
            'label'    => __( 'Sticky scroll to top button', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'show_scroll_btn',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_html_tags', array(
            'label'    => __( 'Show "Allowed html tags" text at posts and pages comment form', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'show_html_tags',
            'type'     => 'checkbox'
        ) ) );

        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_full_posts', array(
            'label'    => __( 'Show posts full content in blog loops', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'show_full_posts',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_post_info', array(
            'label'    => __( 'Show "posted on" info', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'post_info',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_post_info_pages', array(
            'label'    => __( 'Show "posted on" info for pages', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'post_info_pages',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_fontawesome', array(
            'label'    => __( 'Show fontawesome icons for post info, categories, tags and read more buttons', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'satisfy[fontawesome]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_updated', array(
            'label'    => __( 'Show posts updated date', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'show_updated',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_read_more', array(
            'label'    => __( 'Show read more buttons', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'satisfy[read_more]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_author_pic', array(
            'label'       => __( 'Show profile picture on author pages', 'satisfy' ),
            'description' => __( 'From "Profile picture" on your Profile page', 'satisfy' ),
            'section'     => 'satisfy_layout',
            'settings'    => 'show_author_pic',
            'type'        => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_show_nextlinks', array(
            'label'    => __( 'Show previous and next post links in posts and pages', 'satisfy' ),
            'section'  => 'satisfy_layout',
            'settings' => 'satisfy[show_nextlinks]',
            'type'     => 'checkbox'
        ) ) );

        // Footer
        $c->add_control( new WP_Customize_Image_Control($c, 'satisfy_footer_image', array(
            'label'    => __( 'Footer background image', 'satisfy' ),
            'section'  => 'satisfy_footer',
            'settings' => 'satisfy[footer_image]'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_footer_overlay', array(
            'label'       => __( 'Black background overlay', 'satisfy' ),
            'section'     => 'satisfy_footer',
            'settings'    => 'satisfy[footer_overlay]',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 1,
                'step' => 0.1
            )
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_footer_shadow', array(
            'label'    => __( 'Text shadow in footer', 'satisfy' ),
            'section'  => 'satisfy_footer',
            'settings' => 'satisfy[footer_shadow]',
            'type'     => 'checkbox'
        ) ) );
        $c->add_control( new WP_Customize_Control( $c, 'satisfy_footer_text', array(
            'label'       => __( 'Footer text', 'satisfy' ),
            'description' => sprintf( '%s<br><br>%s: %s', __( 'Text to be displayed in the footer. If you have active footer widgets they will be shown instead', 'satisfy' ), __( 'Allowed tags', 'satisfy' ), satisfy_get_allowed_html_tags() ),
            'section'     => 'satisfy_footer',
            'settings'    => 'footer_text',
            'type'        => 'textarea'
        ) ) );
    }

    // Displays all styles as inline
    public static function display_styles () {
        $styles = array_merge(
            array(
                'text_color'           => '#444444',
                'body_font_size'       => 14,
                'border_color'         => '#e8e8e8',
                'link_color'           => '#ba4444',
                'link_hover'           => '#d16a57',
                'menu_background'      => '',
                'menu_color'           => '#fcfcfc',
                'footer_color'         => '#fcfcfc',
                'footer_image'         => '',
                'body_font_family'     => '"Open Sans", sans-serif',
                'header_color'         => '#333333',
                'headings_font_family' => '"Roboto Slab", serif',
                'hero_font_size'       => 1.7,
                'banner_overlay'       => 0.3,
                'footer_overlay'       => 0,
                'footer_background'    => '#ba4444',
                'footer_border'        => '#e8e8e8',
                'footer_shadow'        => false,
                'show_nextlinks'       => true,
                'fontawesome'          => true,
                'stickymenu'           => true,
                'banner_color'         => '#' . get_header_textcolor(),
                'read_more'            => true,
                'search'               => true,
                'headings_font_size'   => 1
            ),
            get_theme_mod( 'satisfy', array() )
        );

        $escaped_styles = self::escape_styles( $styles );

        if ( ! $escaped_styles['read_more'] ) {
            remove_filter( 'excerpt_more', 'satisfy_excerpt_more' );
        }

        $content_width = satisfy_temp_option( 'page_width' );
        if ( ! $content_width ) {
            $content_width = '1200px';
        }

        $css = "
        body,
        .pagination li a,
        .comment-list .fn a,
        .comment-list .comment-meta a,
        caption{
            color: {$escaped_styles['text_color']};
            font-size: {$escaped_styles['body_font_size']}px;
            font-family: {$escaped_styles['body_font_family']};
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        h1 a,
        h2 a,
        h3 a,
        h4 a,
        h5 a,
        h6 a,
        legend{
            color: {$escaped_styles['header_color']};
            font-family: {$escaped_styles['headings_font_family']};
        }
        .content-wrapper{
            max-width: " . esc_attr( $content_width ) . ";
        }
        #site-hero{
            font-size: {$escaped_styles['hero_font_size']}em;
        }
        article .post-info,
        .section-line,
        #primary-sidebar li,
        #home-page-widgets li,
        #comments li,
        blockquote,
        #comments #submit,
        #comments .comment-body,
        #comments-below-nav,
        .no-comments,
        pre,
        .btn-default,
        h2.comments-title,
        .form-control,
        .children,
        iframe,
        #wp-calendar thead,
        body.single .category-and-tag-info,
        #comments #reply-title,
        #comments #comments-line,
        input,
        button,
        textarea,
        select,
        table tr,
        article .article-footer #satisfy-prev-and-next,
        #primary-sidebar #inner-content-sidebar,
        .wp-caption,
        legend,
        abbr{
            border-color: {$escaped_styles['border_color']};
        }
        .read-more,
        .search-btn,
        .site-nav .current-menu-item > a,
        .site-nav .current_page_item > a{
            background: {$escaped_styles['link_color']};
            color: #fcfcfc;
            border-color: transparent;
        }
        .meta-wrap a,
        .small-text{
            color: {$escaped_styles['text_color']};
        }
        a,
        .meta-wrap .fa,
        .pagination li a{
            color: {$escaped_styles['link_color']};
            border-color: {$escaped_styles['border_color']};
        }
        a:hover,
        a:focus,
        .meta-wrap a:hover .fa,
        .meta-wrap a:focus .fa,
        #site-hero .fa:hover{
            color: {$escaped_styles['link_hover']};
            border-color: {$escaped_styles['link_hover']};
            background: none;
            cursor: pointer;
        }
        #site-header,
        .site-nav .sub-menu,
        .site-nav .children{
            background: {$escaped_styles['menu_background']};
        }
        .site-nav{
            font-size: " . ($escaped_styles['body_font_size'] >= 16 ? 1 : 1.1) . "em;
            padding-right: " . ($escaped_styles['search'] ? '45px' : '0') . "
        }
        .site-nav a,
        #site-header #site-logo a,
        #mobile-menu-btn a{
            color: {$escaped_styles['menu_color']};
        }
        .site-nav .search-field,
        .satisfy-to-top{
            color: {$escaped_styles['menu_color']};
            background: {$escaped_styles['menu_background']};
        }
        @media screen and (max-width:991px){
            .site-nav{
                background: {$escaped_styles['menu_background']};
            }
            .site-nav .search-field,
            .site-nav .sub-menu,
            .site-nav .children{
                background: transparent;
                border-bottom-color: {$escaped_styles['menu_color']};
                box-shadow: none;
            }
        }
        .site-nav a:hover,
        .site-nav a:focus,
        body .read-more:hover,
        body .read-more:focus,
        .search-btn:hover,
        .search-btn:focus,
        .article-body-inner .read-more:active,
        .input-group-btn .search-btn:active,
        .satisfy-to-top:hover,
        #mobile-menu-btn a:hover,
        #mobile-menu-btn a:focus,
        .pagination li a:hover,
        .pagination li a:focus,
        .pagination li a:active{
            color: #fcfcfc;
            background: {$escaped_styles['link_hover']};
            border-color: {$escaped_styles['link_hover']};
        }
        #site-hero h1,
        #site-hero p,
        #site-hero .hero-arrow{
            color: {$escaped_styles['banner_color']};
        }
        #site-hero .vertical-table{
            background: rgba(0,0,0,{$escaped_styles['banner_overlay']});
        }
        #footer-overlay{
            background: rgba(0,0,0,{$escaped_styles['footer_overlay']});
        }
        .pagination li span.current,
        .pagination li span.dots{
            border-color: {$escaped_styles['border_color']};
            color: {$escaped_styles['text_color']};
        }
        #site-footer li,
        #site-footer input,
        #site-footer select,
        #site-footer tr,
        #site-footer #wp-calendar thead,
        #site-footer .wp-caption,
        #footer-top legend,
        #footer-top textarea,
        #footer-top button,
        #footer-top abbr{
            border-color: {$escaped_styles['footer_border']};
        }
        #site-footer .footer-nav li{
            border-color: {$escaped_styles['footer_border']};
            font-size: {$escaped_styles['body_font_size']}px;
        }
        #footer-top{
            background-color: {$escaped_styles['footer_background']};
            color: {$escaped_styles['footer_color']};" .
            ($escaped_styles['footer_shadow'] ? 'text-shadow: 1px 1px 3px rgba(0,0,0,0.9);' : '') .
            ($escaped_styles['footer_image'] ? 'background-image: url(' . esc_url( $styles['footer_image'] ) . ');' : '') .
        "}
        #footer-top a,
        #footer-top #wp-calendar caption,
        #footer-top .small-text,
        #footer-top caption,
        #footer-top legend{
            color: {$escaped_styles['footer_color']};
        }
        #satisfy-prev-and-next{" .
            ($escaped_styles['show_nextlinks'] ? '' : 'display: none;') .
        "}
        .nav-search-icon{" .
            ($escaped_styles['search'] ? '' : 'display: none;') .
        "}
        .meta-wrap .fa,
        .read-more .fa{" .
            ($escaped_styles['fontawesome'] ? '' : 'display: none;') .
        "}";

        if ( ! $escaped_styles['stickymenu'] ) {
            $css .= '
            #site-header{
                position: absolute;
            }
            @media screen and (max-width: 767px){
                #site-header .content-wrapper{
                    margin: 0;
                }
                #site-logo{
                    margin-left: -15px;
                }
                #mobile-menu-btn{
                    margin-right: -15px;
                }
            }';
        }

        if ( $escaped_styles['body_font_size'] < 16 ) {
            $css .= '
            .satisfy-widget-div-latest-posts img,
            #comments .avatar{
                width: 50px;
            }
            .satisfy-widget-div-latest-posts .satisfy-latest-posts-text.-sfy-wid{
                max-width: calc(100% - 65px);
            }
            #comments .reply{
                font-size :1em;
            }';
        }

        $css .= self::get_header_sizes( $escaped_styles['headings_font_size'] );

        wp_add_inline_style( 'satisfy-theme-style', satisfy_trim( $css ) );
    }

    // Heading styles
    public static function get_header_sizes ( $font_size ) {
        $sizes = array( 2.5, 2, 1.75, 1.5, 1.25, 1.10 );
        $str = '';

        for ( $i = 1, $len = count( $sizes ); $i <= $len; $i++ ) {
            $str .= "h$i{
                font-size: " . ($sizes[ $i - 1 ] * $font_size) . 'em;
            }';
        }
        return $str;
    }

    // Fonts
    public static function get_fonts () {
        return array(
            '"Open Sans", sans-serif'                                  => __( 'Open Sans', 'satisfy' ),
            '"Roboto Slab", serif'                                     => __( 'Roboto Slab', 'satisfy' ),
            'Georgia, serif'                                           => __( 'Georgia', 'satisfy' ),
            '"Palatino Linotype", "Book Antiqua", Palatino, serif'     => __( 'Palatino Linotype', 'satisfy' ),
            '"Times New Roman", Times, serif'                          => __( 'Times New Roman', 'satisfy' ),
            'Arial, Helvetica, sans-serif'                             => __( 'Arial', 'satisfy' ),
            '"Arial Black", Gadget, sans-serif'                        => __( 'Arial Black', 'satisfy' ),
            '"Comic Sans MS", cursive, sans-serif'                     => __( 'Comic Sans', 'satisfy' ),
            'Impact, Charcoal, sans-serif'                             => __( 'Impact', 'satisfy' ),
            'Tahoma, Geneva, sans-serif'                               => __( 'Tahoma', 'satisfy' ),
            '"Helvetica Neue", Helvetica, Arial, sans-serif'           => __( 'Helvetica Neue', 'satisfy' ),
            '"Lucida Sans Unicode", "Lucida Grande", sans-serif'       => __( 'Lucida Sans Unicode', 'satisfy' ),
            '"Trebuchet MS", Helvetica, sans-serif'                    => __( 'Trebuchet', 'satisfy' ),
            'Verdana, Geneva, sans-serif'                              => __( 'Verdana', 'satisfy' ),
            '"Century Gothic", CenturyGothic, AppleGothic, sans-serif' => __( 'Century Gothic', 'satisfy' ),
            '"Courier New", Courier, monospace'                        => __( 'Courier New', 'satisfy' ),
            '"Lucida Console", Monaco, monospace'                      => __( 'Lucida Console', 'satisfy' ),
            'cursive, sans-serif'                                      => __( 'Cursive', 'satisfy' ),
            'sans-serif'                                               => __( 'Sans Serif', 'satisfy' ),
            'serif'                                                    => __( 'Serif', 'satisfy' )
        );
    }

    // Escapes styles before displaying
    public static function escape_styles ( $styles ) {
        foreach ( $styles as $key => $val ) {
            if ( ! is_numeric( $val ) && ! is_bool( $val ) ) {
                $styles[ $key ] = str_replace( '&quot;', '"', esc_attr( $val ) );
            }
        }
        return $styles;
    }

    // Font sanitize_callback
    public static function valid_font ( $font, $obj = null ) {
        $fonts = self::get_fonts();
        return isset( $fonts[ $font ] ) ? $font : '';
    }

    // Float sanitize_callback
    public static function float_val ( $num, $obj = null ) {
        return floatval( $num );
    }

    // Bool sanitize_callback
    public static function bool_val ( $val, $obj = null ) {
        return (bool) $val;
    }

    // Font units for select menus
    public static function get_units ( $from, $to, $incr, $unit ) {
        $arr = array();

        while ( $from <= $to ) {
            if ( '%' === $unit ) {
                $arr[ $from . '' ] = ($from * 100) . $unit;
            } else {
                $arr[ $from . '' ] = $from . $unit;
            }
            $from += $incr;
        }
        return $arr;
    }
}
