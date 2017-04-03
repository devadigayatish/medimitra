<?php defined( 'ABSPATH' ) or die;

/*
 * Functions
 */

define( 'SATISFY_THEME_VERSION', '1.0.6' );
define( 'SATISFY_THEME_DIR', get_template_directory() . '/' );
define( 'SATISFY_THEME_URL', get_template_directory_uri() . '/' );
define( 'SATISFY_THEME_ADMIN', 'satisfy-theme-info' );

require_once SATISFY_THEME_DIR . 'inc/satisfy-blog-loop.php';
require_once SATISFY_THEME_DIR . 'inc/satisfy-customize.php';
require_once SATISFY_THEME_DIR . 'inc/satisfy-utils.php';
require_once SATISFY_THEME_DIR . 'inc/satisfy-post-info.php';
require_once SATISFY_THEME_DIR . 'inc/widgets/satisfy-image-widget.php';
require_once SATISFY_THEME_DIR . 'inc/widgets/satisfy-latest-widget.php';


add_action( 'after_setup_theme', 'satisfy_add_support_to_theme' );
add_action( 'widgets_init', 'satisfy_add_widgets_to_theme' );
add_action( 'wp_enqueue_scripts', 'satisfy_load_early_resources', 1 );
add_action( 'wp_enqueue_scripts', 'satisfy_load_theme_resources' );
add_action( 'customize_register', array( 'Satisfy_customize', 'init' ) );
add_action( 'wp_enqueue_scripts', array( 'Satisfy_customize', 'display_styles' ) );
add_action( 'admin_menu', 'satisfy_admin_init' );

add_action( 'satisfy_print_post_info', array( 'Satisfy_post_info', 'print_post_info' ) );
add_action( 'satisfy_print_categories_and_tags', array( 'Satisfy_post_info', 'print_categories_and_tags') );


add_filter( 'post_class', 'satisfy_post_class' );
add_filter( 'excerpt_more', 'satisfy_excerpt_more' );

add_action( 'woocommerce_before_main_content', 'satisfy_woo_wrapper_start' );
add_action( 'woocommerce_after_main_content', 'satisfy_woo_wrapper_end' );


// Registers nav menu and most theme support
function satisfy_add_support_to_theme () {
    load_theme_textdomain( 'satisfy', SATISFY_THEME_DIR . 'languages' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'satisfy' ),
        'footer'  => __( 'Footer Menu', 'satisfy' )
    ) );

    add_theme_support( 'post-thumbnails' );
    add_image_size( 'satisfy-medium', 720, 445, true );

    add_theme_support( 'html5', array(
        'comment-list',
        'gallery',
        'caption'
    ) );

    add_theme_support( 'custom-background', array(
        'default-color' => '#fcfcfc'
    ) );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'woocommerce' );

    add_theme_support( 'infinite-scroll', array(
        'type'      => 'scroll',
        'container' => 'primary-content',
        'wrapper'   => 'infinite-satisfy',
        'render'    => 'satisfy_blog_loop'
    ) );

    add_theme_support( 'custom-logo', array(
        'height'      => 55,
        'width'       => 55,
        'flex-height' => true,
        'flex-width'  => true
    ) );

    add_theme_support( 'custom-header', array(
        'default-text-color' => 'fcfcfc',
        'flex-height'        => true,
        'flex-width'         => true,
        'width'              => 1920,
        'height'             => 1200,
        'uploads'            => true
    ) );
}

// Loads bootstrap a bit earlier (for child theme dependency etc)
function satisfy_load_early_resources () {
    wp_enqueue_style( 'bootstrap', SATISFY_THEME_URL . 'css/bootstrap/bootstrap.min.css', array(), SATISFY_THEME_VERSION );
}

// Loads styles and scripts
function satisfy_load_theme_resources () {
    wp_enqueue_style( 'satisfy-theme-style', get_stylesheet_uri(), array( 'bootstrap' ), SATISFY_THEME_VERSION );
    wp_enqueue_style( 'font-awesome', SATISFY_THEME_URL . 'css/font-awesome/css/font-awesome.min.css', array(), SATISFY_THEME_VERSION );

    $styles = get_theme_mod( 'satisfy', array() );

    // Only load google fonts if any of them is used.. TODO: improve when more google fonts will be added
    if ( ! isset( $styles['body_font_family'], $styles['headings_font_family'] ) || preg_grep( '/(Open|Roboto)/', array( $styles['body_font_family'], $styles['headings_font_family'] ) ) ) {
        wp_enqueue_style( 'satisfy-theme-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans%7CRoboto+Slab', array(), null );
    }

    if ( is_singular() ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_enqueue_script( 'satisfy-theme-script', SATISFY_THEME_URL . 'js/satisfy-theme-script.js', array( 'jquery' ), SATISFY_THEME_VERSION, true );
}

// Register sidebars and widgets
function satisfy_add_widgets_to_theme () {
    $sidebars = array(
        'sidebar-1' => __( 'Sidebar', 'satisfy' ),
        'footer-1'  => __( 'Footer 1 (left)', 'satisfy' ),
        'footer-2'  => __( 'Footer 2 (middle)', 'satisfy' ),
        'footer-3'  => __( 'Footer 3 (right)', 'satisfy' ),
        'home-1'    => __( 'Home Page', 'satisfy' )
    );

    foreach ( $sidebars as $id => $name ) {
        register_sidebar( array(
            'id'            => $id,
            'name'          => $name,
            'before_widget' => '<div class="widget-div">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
    }

    register_widget( 'satisfy_image_widget' );
    register_widget( 'satisfy_latest_posts_widget' );
}

// Adds read more buttons - filter "excerpt_more"
function satisfy_excerpt_more ( $more ) {
    return sprintf(
        '..<p><a class="btn btn-default read-more" href="%s">%s <span class="fa fa-angle-right"></span></a></p>',
        esc_url( get_permalink( get_the_ID() ) ),
        __( 'Read more', 'satisfy' )
    );
}

// Removes hentry class if already set - filter "post_class"
function satisfy_post_class ( $classes ) {
    if ( satisfy_is_post() && satisfy_temp_option( 'has_banner' ) ) {
        $classes = array_diff( $classes, array( 'hentry' ) );
    }
    return $classes;
}

// Content class, 12 cols if no sidebar
if ( ! function_exists( 'satisfy_get_content_class' ) ) {
    function satisfy_get_content_class () {
        $sidebar = satisfy_temp_option( 'sidebar' );

        if ( false !== strpos( $sidebar, 'off' ) ) {
            return 'col-xs-12' . ('off-small' === $sidebar ? ' col-md-8 center-div' : '');
        }
        return 'col-md-8 col-sm-12';
    }
}

// Prints logo in header
if ( ! function_exists( 'satisfy_print_logo' ) ) {
    function satisfy_print_logo () {
        if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
        } elseif ( $id = get_theme_mod( 'custom_logo' ) ) { ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="<?php echo esc_url( wp_get_attachment_url( $id ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="custom-logo">
            </a>
        <?php
        }

        if ( display_header_text() ) {
            $show_slogan = get_theme_mod( 'satisfy_show_menu_slogan' ); ?>

            <a id="site-title-wrap" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="vertical-center<?php echo $show_slogan ? ' site-title-slogan' : ''; ?>">
                    <span class="site-title"><?php bloginfo( 'name' ); ?></span>
                    <?php if ( $show_slogan ) { ?>
                        <span class="site-slogan"><?php bloginfo( 'description' ); ?></span>
                    <?php } ?>
                </div>
            </a>
        <?php
        }
    }
}

// Prepares header banner and prints css classes for it
if ( ! function_exists( 'satisfy_prepare_banner' ) ) {
    function satisfy_prepare_banner () {
        $cl_name = '';
        $banner = array(
            'url' => false,
            'h1' => '',
            'slogan' => '',
            'is_page' => false
        );

        if ( is_404() ) {
            return;
        }

        if ( is_front_page() ) {
            $banner['url'] = get_custom_header()->url;
            $banner['slogan'] = get_theme_mod( 'satisfy_new_slogan' );
            $banner['h1'] = get_theme_mod( 'satisfy_tagline' );

        } elseif ( ( satisfy_is_post() || is_home() ) && 'full' === get_theme_mod( 'posts_featured_images' ) ) {
            $id = is_home() ? get_option( 'page_for_posts' ) : get_the_ID();

            if ( has_post_thumbnail( $id ) ) {
                $img_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
                if ( $img_arr ) {
                    $banner['url'] = $img_arr[0];
                    $banner['h1'] = get_the_title( $id );
                    $banner['is_page'] = true;
                    $cl_name = 'hentry';
                }
            }
        }

        if ( $banner['url'] ) {
            satisfy_temp_option( 'has_banner', $banner );
            $cl_name .= ' satisfy-banner';
        }

        echo trim( $cl_name );
    }
}

// Prints header banner
if ( ! function_exists( 'satisfy_print_banner' ) ) {
    function satisfy_print_banner () {
        $banner = satisfy_temp_option( 'has_banner' );

        if ( ! is_array( $banner ) ) {
            return;
        }

        $styles = array_merge(
            array(
                'arrow'     => true,
                'size'      => 60,
                'page_size' => 60,
                'shadow'    => true
            ),
            get_theme_mod( 'satisfy_banner', array() )
        );
        $size = $banner['is_page'] ? $styles['page_size'] : $styles['size']; ?>

        <div class="cover-img" style="min-height:<?php echo absint( $size ); ?>vh;background-image:url(<?php echo esc_url( $banner['url'] ); ?>);<?php
            if ( $styles['shadow'] ) {
                echo 'text-shadow:1px 1px 3px rgba(0,0,0,0.9);';
            } ?>">
            <div class="vertical-table" style="height:<?php echo absint( $size ); ?>vh">
                <div class="vertical-center">
                    <div class="container-fluid">
                        <div class="content-wrapper">
                            <div class="col-xs-12">
                                <h1 class="entry-title"><?php echo satisfy_get_paged( $banner['h1'] ); ?></h1>
                                <p><?php echo satisfy_wp_kses( $banner['slogan'] ); ?></p>
                            </div>
                        </div>
                        <?php if ( $styles['arrow'] ) { ?>
                            <div class="hero-arrow"><span class="fa fa-chevron-down"></span></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}

// Get featured image size for current page
if ( ! function_exists( 'satisfy_get_featured_image' ) ) {
    function satisfy_get_featured_image () {
        if ( satisfy_is_post() ) {
            if ( ! get_theme_mod( 'posts_featured_images' ) ) {
                return 'full';
            }
        } else {
            $img = get_theme_mod( 'featured_images' );

            if ( 'off' !== $img ) {
                return $img ? $img : 'satisfy-medium';
            }
        }
        return false;
    }
}

// Prints icon for posts
if ( ! function_exists( 'satisfy_post_icon' ) ) {
    function satisfy_post_icon () {
        echo 'fa fa-thumb-tack';
    }
}

// If post info for posts or pages should be displayed
if ( ! function_exists( 'satisfy_disp_post_info' ) ) {
    function satisfy_disp_post_info () {
        if ( ! get_theme_mod( 'post_info', true ) || ( is_page() && ! get_theme_mod( 'post_info_pages' ) ) ) {
            echo ' -satisfy-hidden';
        }
    }
}

// Html in footer top if no widgets are added there
if ( ! function_exists( 'satisfy_footer_top_info' ) ) {
    function satisfy_footer_top_info () {
        $text = get_theme_mod( 'footer_text' );

        if ( $text ) { ?>
            <div class="text-center"><p><?php echo satisfy_wp_kses( $text ); ?></p></div>
        <?php } else { ?>
            <div class="text-center">
                <p><?php bloginfo( 'name' ); ?> &copy; <?php echo date_i18n( __( 'Y', 'satisfy' ) ); ?></p>
                <nav class="footer-nav">
                    <?php wp_nav_menu( array(
                        'theme_location' => 'footer'
                    ) ); ?>
                </nav>
            </div>
        <?php
        }
    }
}

// Title text for searches
if ( ! function_exists( 'satisfy_get_search_title' ) ) {
    function satisfy_get_search_title () {
        $search_title = satisfy_get_paged( get_search_query( false ) );

        if ( $search_title ) {
            return sprintf( '%s: %s', __( 'Search results for', 'satisfy' ), $search_title );
        }
        return sprintf( '%s %s', __( 'Search', 'satisfy' ), esc_html( get_bloginfo( 'name' ) ) );
    }
}

// Admin notice first time activating
function satisfy_welcome_message () {
    if ( ! get_theme_mod( 'been_welcomed' ) ) { ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php _e( 'Welcome to Satisfy! Info about the theme can be found at your', 'satisfy' ); ?>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=' . SATISFY_THEME_ADMIN ) ); ?>"><?php _e( 'theme info page', 'satisfy' ); ?></a>.
            </p>
        </div>
        <?php set_theme_mod( 'been_welcomed', true );
    }
}

// Footer bottom credit text
function satisfy_footer_bottom_info () {
    if ( apply_filters( 'satisfy_footer_info', true ) ) {
        printf( '<p>%s Satisfy</p>', __( 'Theme', 'satisfy' ) );
    }
}

// Set up for admin interface
function satisfy_admin_init () {
    $title = __( 'Satisfy Info', 'satisfy' );

    require_once SATISFY_THEME_DIR . 'inc/admin/satisfy-admin.php';

    add_theme_page( $title, $title, 'edit_theme_options', SATISFY_THEME_ADMIN, array( 'Satisfy_admin', 'settings_page' ) );
    add_action( 'admin_enqueue_scripts', array( 'Satisfy_admin', 'scripts_and_styles' ) );
    add_action( 'admin_notices', 'satisfy_welcome_message' );
}

// Woocommerce
function satisfy_woo_wrapper_start () {
    if ( 'left' === satisfy_temp_option( 'sidebar' ) ) {
        get_sidebar();
        satisfy_temp_option( 'sidebar', 'off' );
    }
    printf( '<div id="primary-content" class="%s satisfy-woo-commerce"><div id="main">', satisfy_get_content_class() );
}
function satisfy_woo_wrapper_end () {
    echo '</div></div>';
}

/*custom post type*/
function medimitra_custom_post_type(){

    $labels = array(
        'name' => 'Doctors',
        'singular_name' => 'Doctor',
        'add_new' => 'Add Doctor',
        'all_items' => 'All Doctors',
        'add_new_item' => 'Add Doctor',
        'edit_item' => 'Edit Doctor',
        'new_item' => 'New Doctor',
        'view_item' => 'View Doctor',
        'search_item' => 'Search Doctor',
        'not_found' => 'No doctors found',
        'not_found_in_trash' => 'No doctors found in trash'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'publicly_queryable'=> true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical'    => true,
        'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields', 'post-formats', 'revisions', ),
//      'taxonomies'  => array( 'category', 'post_tag' ),
        'exclude_from_search'   => false,
        'menu_position'         => 5,

    ); 

    register_post_type('doctors', $args);
}
add_action('init','medimitra_custom_post_type');


/*To display custom post on front page*/
add_filter( 'pre_get_posts', 'my_get_posts' );

function my_get_posts( $query ) {

    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'doctors' ) );

    return $query;
}

//custom texanomy
function medimitra_custom_taxonomies() {
    

//Speciality
    //add new taxonomy Not hierarchical
    $labels = array(
        'name' => 'Specialities',
        'singular_name' => 'Speciality',
        'search_items' => 'Search Speciality',
        'all_items' => 'All Specialities',
        //'parent_item' => 'Parent Type',
        //'parent_item_colon' => 'Parent Type:',
        'edit_item' => 'Edit Speciality',
        'update_item' => 'Update Speciality',
        'add_new_item' => 'Add New Speciality',
        'new_item_name' => 'New Speciality Name',
        'menu_name' => 'Specialities'
    );
    
    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'speciality' )
    );
    
    register_taxonomy('speciality', array('doctors'), $args);
    
//Areas

    $a_labels = array(
        'name' => 'Areas',
        'singular_name' => 'Area',
        'search_items' => 'Search Area',
        'all_items' => 'All Areas',
        //'parent_item' => 'Parent Type',
        //'parent_item_colon' => 'Parent Type:',
        'edit_item' => 'Edit Area',
        'update_item' => 'Update Area',
        'add_new_item' => 'Add New Area',
        'new_item_name' => 'New Area Name',
        'menu_name' => 'Areas'
    );
    
    $a_args = array(
        'hierarchical' => false,
        'labels' => $a_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'area' )
    );
    
    register_taxonomy('area', array('doctors'), $a_args);  
}
add_action( 'init' , 'medimitra_custom_taxonomies' );





// doctor frontend form
function medimitra_doctor_form() {
 
        $output = medimitra_doctor_form_fields();
        return $output;
}
add_shortcode('doctor_form', 'medimitra_doctor_form');

// registration form fields
function medimitra_doctor_form_fields() {
 
    ob_start(); ?>  
        <!-- <h3 class="medimitra_header"><?php //_e('Suggest A Doctor'); ?></h3> -->
 
        <?php 
        // show any error messages after form submission
        medimitra_show_error_messages(); ?>
        <p><?php _e('Please suggest a patient-friendly rational, ethical doctor based on your experience with him/her. By suggesting a doctor with whom you had a good experience, you can help us to expand our network so that more patients benefit.'); ?></p>
        <form id="medimitra_doctor_form" class="medimitra_form" action="" method="POST">
            <fieldset>
                <h4><?php _e('Doctor\'s information'); ?></h4>
                <hr>
                                
                    <!-- First name last name change  -->
                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('First Name'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_name" placeholder="Name" class="form-control" type="text" id="medimitra_doctor_name">
                </div>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Last Name'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_name" placeholder="Name" class="form-control" type="text" id="medimitra_doctor_name">
                </div>
                </div>
                </div>
                <!-- end -->


                <!-- address change-->
                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Address'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_name" placeholder="Name" class="form-control" type="text" id="medimitra_doctor_name">
                </div>
                </div>
                </div>
                <!-- end -->

                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Area'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <!-- <input name="medimitra_area" placeholder="City" class="form-control" type="text" id="medimitra_area"> -->
                <?php wp_dropdown_categories( 'tab_index=0&hide_empty=0&taxonomy=area&name=medimitra_area&class=medimitra_area&show_option_all=Select a Area' ); ?>
                </div>
                </div>
                </div>


                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Specialization'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <?php wp_dropdown_categories( 'tab_index=0&hide_empty=0&taxonomy=speciality&name=medimitra_speciality&class=medimitra_speciality&show_option_all=Select a Speciality' ); ?>
                </div>
                </div>
                </div>

<!-- replace education with qualification -->
                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Qualification'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_education" placeholder="Qualification" class="form-control" type="text" id="medimitra_doctor_education">
                </div>
                </div>
                </div>

<!-- experience n phone number is not in requirement doc -->
<!--                 <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php // _e('Experience'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_experience" placeholder="Enter experience in year" class="form-control" type="text" id="medimitra_doctor_experience">
                </div>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php //_e('Phone Number'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_phone" placeholder="Enter phone number" class="form-control" type="text" id="medimitra_doctor_phone">
                </div>
                </div>
                </div> -->
<!-- end -->

                <hr>


<h4><?php _e('Your information'); ?><p class="small smallnote"><?php _e('(Please note that this information will be used only for HGF\'s internal purpose)'); ?></p></h4>

<hr>
                

                <!--your First name last name   -->
                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('First Name'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="" placeholder="Name" class="form-control" type="text" id="medimitra_doctor_name">
                </div>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Last Name'); ?></label>  
                <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="" placeholder="Name" class="form-control" type="text" id="medimitra_doctor_name">
                </div>
                </div>
                </div>
                <!-- end -->


<div class="form-group">
  <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Are you doctor ?'); ?></label> 
    <div class="col-md-7 inputGroupContainer">
    <div class="input-group">
    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> -->
    
    <label class="radio-inline">
    <input name="newsletter" value="1" type="radio" id="yesdoctor">
    <?php _e('Yes'); ?>
    </label>

    <label class="radio-inline">
    <input name="newsletter" value="0" checked="checked" type="radio" id="nodoctor">
    <?php _e('No'); ?>
    </label>

    </div>
  </div>
</div>




<div class="form-group ifdoctor" id="degree">
  <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Your degree'); ?></label> 
    <div class="col-md-7 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="udegree" placeholder="Your degree" class="form-control"  type="text">
    </div>
  </div>
</div>

<!-- Text input-->
       <div class="form-group ifdoctor">
  <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Place'); ?></label>  
    <div class="col-md-7 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
  <input name="uplace" placeholder="Place" class="form-control"  type="text">
    </div>
  </div>
</div>



<div class="form-group">
  <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Contact number'); ?></label> 
    <div class="col-md-7 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
  <input name="number" placeholder="Your contact phone/mobile number" class="form-control"  type="text">
    </div>  

    <p class="small smallnote"><?php // _e('Please note that this information will be used only for HGF\'s internal purpose'); ?></p>

  </div>
</div>


<div class="form-group">
  <label class="col-md-5 control-label" style="text-align:right;"><?php _e('Email ID'); ?></label> 
    <div class="col-md-7 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
  <input name="number" placeholder="Your contact email id" class="form-control"  type="text">
    </div>  

    <p class="small smallnote"><?php  _e('Please note that this information will be used only for HGF\'s internal purpose'); ?></p>
    

  </div>
</div>


<div class="user_information">

<h4>Please share the experience you have had with the doctor by selecting one of the options provided</h4> <hr class="big_dark">


<div class="form-group">
<label class="col-md-4 control-label">A) About overall level of satisfaction</label>
    <div class="col-md-8 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="satisfaction" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="satisfaction" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="satisfaction" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="satisfaction" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="satisfaction" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>

<div class="form-group">
<label class="col-md-4 control-label">B) About time given by the doctor</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="timegiven" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="timegiven" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="timegiven" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="timegiven" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="timegiven" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>



<div class="form-group">
<label class="col-md-4 control-label">C) About doctor listening to you and answering your queries</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="listeninganswering" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="listeninganswering" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="listeninganswering" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="listeninganswering" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="listeninganswering" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>


<div class="form-group">
<label class="col-md-4 control-label">D) About information received from the doctor about illness and treatment</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="receivedinformation" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="receivedinformation" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="receivedinformation" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="receivedinformation" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="receivedinformation" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>




<div class="form-group">
<label class="col-md-4 control-label">E) Did the doctor insist on purchase of drugs from a particular store?</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="particularstore" value="0" type="radio">
    Yes
    </label>

    <label class="radio-inline">
    <input name="particularstore" value="1" checked="checked" type="radio">
    No
    </label>

    </div>
  </div>
</div>


<!-- here -->

<div class="form-group">
<label class="col-md-4 control-label">Was any procedure done on you or were you admitted?</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="procedureadmission" value="0" type="radio" id="yesprocedureadmissiondone">
    Yes
    </label>

    <label class="radio-inline">
    <input name="procedureadmission" value="1" checked="checked" type="radio" id="noprocedureadmissiondone">
    No
    </label>

    </div>
  </div>
</div>

<div class="procedureadmissiondone">
<div class="form-group">
<label class="col-md-4 control-label"></label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
 If yes, please give feedback on the following :  
    </div>
  </div>
</div>




<hr class="big_darkgray">


<div class="form-group">
<label class="col-md-4 control-label">1. About accessibility of the main doctor</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="accessibility" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="accessibility" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="accessibility" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="accessibility" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="accessibility" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>


<div class="form-group">
<label class="col-md-4 control-label">2. About experience about the assistant doctors</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="assistantexperience" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="assistantexperience" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="assistantexperience" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="assistantexperience" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="assistantexperience" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>




<div class="form-group">
<label class="col-md-4 control-label">3. Experience about the nurses and other staff</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="nursesexperience" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="nursesexperience" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="nursesexperience" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="nursesexperience" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="nursesexperience" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>



<div class="form-group">
<label class="col-md-4 control-label">4. About overall cleanliness</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="cleanliness" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="cleanliness" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="cleanliness" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="cleanliness" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="cleanliness" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>



<div class="form-group">
<label class="col-md-4 control-label">5. About hospital charges</label>
    <div class="col-md-8 inputGroupContainer">
    <div class="input-group">
    
    <label class="radio-inline">
    <input name="hospitalcharges" value="0" type="radio">
    Very dissatisfied
    </label>

    <label class="radio-inline">
    <input name="hospitalcharges" value="0" checked="checked" type="radio">
    Dissatisfied
    </label>

    <label class="radio-inline">
    <input name="hospitalcharges" value="0" checked="checked" type="radio">
    Less satisfied
    </label>

    <label class="radio-inline">
    <input name="hospitalcharges" value="0" checked="checked" type="radio">
    Satisfied
    </label>

    <label class="radio-inline">
    <input name="hospitalcharges" value="1" checked="checked" type="radio">
    Very happy
    </label>

    </div>
  </div>
</div>


</div> 
</div> 


<!-- here -->


<!-- Success message -->
<!-- <div class="alert alert-success" role="alert" id="success_message">Success <i class="glyphicon glyphicon-thumbs-up"></i> Thanks for contacting us, we will get back to you shortly.</div>
 -->
<!-- Button -->
<br>

                    
                <div class="col-md-5">
                </div>
                <div class="col-md-7">
                <input type="hidden" name="medimitra_register_nonce" value="<?php echo wp_create_nonce('medimitra-register-nonce'); ?>"/>
                <input type="submit" value="<?php _e('Submit'); ?>"/>
                </div>


            </fieldset>
        </form>


    <?php
    return ob_get_clean();
}

// add a new doctor
function medimitra_add_new_member() {

    if (isset( $_POST["medimitra_doctor_name"] ) && wp_verify_nonce($_POST['medimitra_register_nonce'], 'medimitra-register-nonce')) {

        $doctor_name        = $_POST["medimitra_doctor_name"];
        $doctor_education   = $_POST["medimitra_doctor_education"];

        $doctor_experience   = $_POST["medimitra_doctor_experience"];
        $doctor_phone   = $_POST["medimitra_doctor_phone"];
        
        // $speciality_id = intval($_POST['medimitra_speciality']);
        // $specialities = (!empty($speciality_id) ? array($speciality_id) : array());


        // $area_id = intval($_POST['medimitra_area']);
        // $areas = (!empty($area_id) ? array($area_id) : array());


 
        if($doctor_name == '') {
            // empty username
            medimitra_errors()->add('doctorname_empty', __('Please enter a doctorname'));
        }
        // if(!is_email($user_email)) {
        //  //invalid email
        //  medimitra_errors()->add('email_invalid', __('Invalid email'));
        // }
        // if(email_exists($user_email)) {
        //  //Email address already registered
        //  medimitra_errors()->add('email_used', __('Email already registered'));
        // }

 
        $errors = medimitra_errors()->get_error_messages();
         // only create the user in if there are no errors

        //custom fields 

        if(empty($errors)) {
 
            $new_doctor_id = wp_insert_post(array(
                    'post_title'        => $doctor_name,
                    'post_status'   => 'pending',
                    'post_type' => 'doctors',
                    'post_date' => $_SESSION['cal_startdate']                    
                )
            );
            //'tax_input' =>  array('speciality'=>$specialities,'area'=>$areas),
//$cat_term = $_POST['medimitra_speciality'];  
      $speciality_id = intval($_POST['medimitra_speciality']);
        $specialities = (!empty($speciality_id) ? array($speciality_id) : array());
        wp_set_post_terms( $new_doctor_id, $specialities, 'speciality' );

        $area_id = intval($_POST['medimitra_area']);
        $areas = (!empty($area_id) ? array($area_id) : array());
        wp_set_post_terms( $new_doctor_id, $areas, 'area' );



if ( ! add_post_meta( $new_doctor_id, 'Education', $doctor_education, true ) ) { 
   update_post_meta( $new_doctor_id, 'Education', $doctor_education );
}
if ( ! add_post_meta( $new_doctor_id, 'Experience', $doctor_experience, true ) ) { 
   update_post_meta( $new_doctor_id, 'Experience', $doctor_experience );
}
if ( ! add_post_meta( $new_doctor_id, 'Phone', $doctor_phone, true ) ) { 
   update_post_meta( $new_doctor_id, 'Phone', $doctor_phone );
}
            
            if($new_doctor_id) {
                // send an email to the admin alerting them of the registration
                //wp_new_user_notification($new_user_id);
 
                // log the new user in
                //wp_setcookie($user_login, $user_pass, true);
                //wp_set_current_user($new_user_id, $user_login);   
                //do_action('wp_login', $user_login);
 
                // send the newly created user to the home page after logging them in
                wp_redirect(home_url()); exit;
            }
 
        }
 
    }
}
add_action('init', 'medimitra_add_new_member');


// function __update_post_meta( $post_id, $field_name, $value = 'true' )
// {
//     if ( empty( $value ) OR ! $value )
//     {
//         delete_post_meta( $post_id, $field_name );
//     }
//     elseif ( ! get_post_meta( $post_id, $field_name ) )
//     {
//         add_post_meta( $post_id, $field_name, $value );
//     }
//     else
//     {
//         update_post_meta( $post_id, $field_name, $value );
//     }
// }



// used for tracking error messages
function medimitra_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function medimitra_show_error_messages() {
    if($codes = medimitra_errors()->get_error_codes()) {
        echo '<div class="medimitra_errors">';
            // Loop error codes and display errors
           foreach($codes as $code){
                $message = medimitra_errors()->get_error_message($code);
                echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
        echo '</div>';
    }   
}



// doctor frontend form
function medimitra_flashing_message() {
 
        $output = medimitra_flashing_message_html();
        return $output;
}
add_shortcode('doctor_flashing_message', 'medimitra_flashing_message');


function medimitra_flashing_message_html(){
ob_start(); ?>

<p>“Dear doctors referred by patients, we congratulate you for being referred by patients to others. We request you to visit - <a href="http://www.ethicaldoctors.org/" target="_blank">http://www.ethicaldoctors.org/</a> and get registered yourself approving the declaration on behalf of Alliance of doctors for ethical healthcare, a national network of doctors committed for ethical and rational practice.

With you getting registered at <a href="http://www.ethicaldoctors.org/" target="_blank">http://www.ethicaldoctors.org/</a>, the search result will display a star against your name to that effect.”</p>

<?php 

return ob_get_clean();
}