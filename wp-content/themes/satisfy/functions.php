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
       
        <?php 
        // show any error messages after form submission
        medimitra_show_error_messages(); ?>
        <p><?php _e('Please suggest a patient-friendly rational, ethical doctor based on your experience with him/her. By suggesting a doctor with whom you had a good experience, you can help us to expand our network so that more patients benefit.'); ?></p>
        <form id="medimitra_doctor_form" class="medimitra_form" action="" method="POST">
            <fieldset>
                <h4><?php _e('Doctor\'s information'); ?></h4>
                <hr>
                                
                    <!-- First name last name change  -->
                <div class="col-md-12">

                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('First Name'); ?><span>*</span></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_doctor_firstname" placeholder="Enter first name" class="form-control" type="text" id="medimitra_doctor_firstname" required="true">
                    </div>
                    </div>
                    </div>



 
                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('Last Name'); ?><span>*</span></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_doctor_lastname" placeholder="Enter last name" class="form-control" type="text" id="medimitra_doctor_lastname" required="true">
                    </div>
                    </div>
                    </div>

                </div>




                <div class="col-md-12">

                <div class="col-md-6">
                <div class="form-group">
                <label class="col-md-3 control-label"><?php _e('Address'); ?><span>*</span></label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <textarea name="medimitra_doctor_address" placeholder="Enter address" class="form-control" rows="5" id="medimitra_doctor_address" required="true"></textarea>
                </div>
                </div>
                </div>
                </div>

                <div class="col-md-6">
                <div class="form-group">
                <label class="col-md-3 control-label"><?php _e('State'); ?></label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

                <select  name="medimitra_doctor_state" id="medimitra_doctor_state" class="medimitra_doctor_state">
                <option value="maharashtra" selected="selected">Maharashtra</option>
                </select>

                </div>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-3 control-label"><?php _e('City'); ?></label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

                <select name="medimitra_doctor_city" id="medimitra_doctor_city" class="medimitra_doctor_city">
                <option value="pune" selected="selected">Pune</option>
                </select>

                </div>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-3 control-label"><?php _e('Area'); ?><span>*</span></label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

<select name="medimitra_doctor_area" id="medimitra_doctor_area" tabindex="9" required="true">
<option value=""><?php _e('Select area'); ?></option>

<?php
$categories = get_terms('area', array('hide_empty' => 0));
foreach ($categories as $category) {
//print_r($categories);
echo "<option id='medimitra_doctor_area' value='$category->term_id'>$category->name</option>";
}
?>
</select>            
                <?php  //wp_dropdown_categories( 'tab_index=0&hide_empty=0&taxonomy=area&name=medimitra_doctor_area&class=medimitra_doctor_area&required=true&show_option_all=Select a Area' ); ?>
                </div>
                </div>
                </div>
                </div>




                </div>



                <div class="col-md-12">

                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('Email Id'); ?></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_doctor_email" placeholder="Enter email id" class="form-control" type="email" id="medimitra_doctor_email">
                    </div>
                    </div>
                    </div>



 
                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('Contact'); ?></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_doctor_contact" placeholder="Enter Mobile or Landline number" class="form-control" type="text" id="medimitra_doctor_contact">
                    </div>
                    </div>
                    </div>

                </div>



                <div class="col-md-12">
                <div class="form-group col-md-6">
                <label class="col-md-3 control-label"><?php _e('Specialization'); ?></label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <?php wp_dropdown_categories( 'tab_index=0&hide_empty=0&taxonomy=speciality&name=medimitra_doctor_speciality&class=medimitra_doctor_speciality&show_option_all=Select a Speciality' ); ?>
                </div>
                </div>
                </div>

<!-- replace education with qualification -->
                <div class="form-group col-md-6">
                <label class="col-md-3 control-label"><?php _e('Qualification'); ?></label>  
                <div class="col-md-9 inputGroupContainer">
                <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="medimitra_doctor_qualification" placeholder="Enter qualification" class="form-control" type="text" id="medimitra_doctor_qualification">
                </div>
                </div>
                </div>

                </div>

                </fieldset>

                <fieldset>
                <h4><?php _e('Your information'); ?></h4>
                (Please note that this information will be used only for PCDF’s internal purpose.)
                <hr>


                <div class="col-md-12">

                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('First name'); ?><span>*</span></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_user_firstname" placeholder="Enter your first name" class="form-control" type="text" id="medimitra_user_firstname" required="true">
                    </div>
                    </div>
                    </div>



 
                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('Last Name'); ?><span>*</span></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_user_lastname" placeholder="Enter your last name" class="form-control" type="text" id="medimitra_user_lastname" required="true">
                    </div>
                    </div>
                    </div>

                </div>

                <div class="col-md-12">

                    <!-- <p class="col-md-12 small smallnote"><span>*</span>Please provide either contact number/email</p></div> -->
                        
                    
                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('Email Id'); ?><span>*</span></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_user_email" placeholder="Enter your email id" class="form-control" type="email" id="medimitra_user_email" required="true">
                    </div>
                    </div>
                    </div>


 
                    <div class="form-group col-md-6">
                    <label class="col-md-3 control-label"><?php _e('Contact'); ?><span>*</span></label>  
                    <div class="col-md-9 inputGroupContainer">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input name="medimitra_user_contact" placeholder="Enter your contact number" class="form-control" type="text" id="medimitra_user_contact" required="true">
                    </div>
                    </div>
                    </div>

                </div>

                <div class="col-md-12">

                    <!-- <p class="col-md-12 small smallnote"><span>*</span>Please provide either contact number/email</p></div> -->
                        
                    
                    <div class="form-group col-md-6">
                    <label class="col-md-10 control-label">
                        <input name="medimitra_news_update" type="checkbox" value="1">
                        <?php _e('Would you like to receive news/updates from the forum?'); ?>
                    </label>  
                    </div>


 
                    <div class="form-group col-md-6">
                    </div>

                </div>
</fieldset>
<fieldset>
<h4><?php _e('Feedback'); ?></h4>

                <p>Please share the experience you have had with the doctor by selecting one of the options provided -</p>
                <hr>



<div class="form-group">
<label class="col-md-12 control-label">A) About overall level of satisfaction</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="overall_level_of_satisfaction" required>Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="overall_level_of_satisfaction" required>Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="overall_level_of_satisfaction" required>Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="overall_level_of_satisfaction" required>Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="overall_level_of_satisfaction" required>Very happy</label>

   </div>
  </div>
</div>


<div class="form-group">
<label class="col-md-12 control-label">B) About time given by the doctor</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="time_given_by_doctor" required>Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="time_given_by_doctor" required>Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="time_given_by_doctor" required>Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="time_given_by_doctor" required>Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="time_given_by_doctor" required>Very happy</label>

   </div>
  </div>
</div>




<div class="form-group">
<label class="col-md-12 control-label">C) About doctor listening to you and answering your queries</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="doctor_listening_answering" required>Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="doctor_listening_answering">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="doctor_listening_answering">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="doctor_listening_answering">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="doctor_listening_answering">Very happy</label>

   </div>
  </div>
</div>



<div class="form-group">
<label class="col-md-12 control-label">D) About information received from the doctor about illness and treatment</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="information_received_from_doctor" required>Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="information_received_from_doctor">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="information_received_from_doctor">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="information_received_from_doctor">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="information_received_from_doctor">Very happy</label>

   </div>
  </div>
</div>




<div class="form-group">
<label class="col-md-12 control-label">E) Did the doctor insist on purchase of drugs from a particular store?</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    <label class="radio-inline"><input type="radio" value="1" name="doctor_insist_purchase_from_store" required>Yes</label>
    <label class="radio-inline"><input type="radio" value="5" name="doctor_insist_purchase_from_store">No</label>
    </div>
  </div>
</div>


<div class="form-group">
<label class="col-md-12 control-label">F) Was any procedure done on you or were you admitted??</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    <label class="radio-inline"><input type="radio" value="yes" name="procedure_done_on_you" id="yesprocedureadmissiondone" required>Yes</label>
    <label class="radio-inline"><input type="radio" value="no" name="procedure_done_on_you" id="noprocedureadmissiondone">No</label>
    </div>
  </div>
</div>


<div class="procedureadmissiondone">
If yes, please give feedback on the following :

<div class="form-group">
<label class="col-md-12 control-label">1. About accessibility of the main doctor</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="accessibility_of_main_doctor">Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="accessibility_of_main_doctor">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="accessibility_of_main_doctor">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="accessibility_of_main_doctor">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="accessibility_of_main_doctor">Very happy</label>

   </div>
  </div>
</div>



<div class="form-group">
<label class="col-md-12 control-label">2. Experience about the assistant doctors</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="experience_about_assistant">Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="experience_about_assistant">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="experience_about_assistant">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="experience_about_assistant">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="experience_about_assistant">Very happy</label>

   </div>
  </div>
</div>





<div class="form-group">
<label class="col-md-12 control-label">3. Experience about the nurses and other staff</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="experience_about_nurses_and_other">Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="experience_about_nurses_and_other">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="experience_about_nurses_and_other">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="experience_about_nurses_and_other">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="experience_about_nurses_and_other">Very happy</label>

   </div>
  </div>
</div>



<div class="form-group">
<label class="col-md-12 control-label">4. About overall cleanliness</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="overall_cleanliness">Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="overall_cleanliness">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="overall_cleanliness">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="overall_cleanliness">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="overall_cleanliness">Very happy</label>

   </div>
  </div>
</div>


<div class="form-group">
<label class="col-md-12 control-label">5. About hospital charges</label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    
    <label class="radio-inline"><input type="radio" value="1" name="hospital_charges">Very dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="2" name="hospital_charges">Dissatisfied</label>
    <label class="radio-inline"><input type="radio" value="3" name="hospital_charges">Less satisfied</label>
    <label class="radio-inline"><input type="radio" value="4" name="hospital_charges">Satisfied</label>
    <label class="radio-inline"><input type="radio" value="5" name="hospital_charges">Very happy</label>

   </div>
  </div>
</div>
</div>


<div class="form-group">
<label class="col-md-12 control-label"><?php _e('Any other comments?'); ?></label>
    <div class="col-md-12 inputGroupContainer" >
    <div class="input-group">
    <textarea name="medimitra_user_comment" placeholder="Give your comments in 1000 characters" class="form-control" rows="4" cols="70" maxlength="1000" id="medimitra_user_comment"></textarea>
    </div>
  </div>
</div>


                <div class="col-md-12">
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

    if (isset( $_POST["medimitra_doctor_firstname"] ) && wp_verify_nonce($_POST['medimitra_register_nonce'], 'medimitra-register-nonce')) {

        $doctor_firstname       = $_POST["medimitra_doctor_firstname"];
        $doctor_lastname        = $_POST["medimitra_doctor_lastname"];
        $doctor_address         = $_POST["medimitra_doctor_address"];    
        $doctor_state           = $_POST["medimitra_doctor_state"];    
        $doctor_city            = $_POST["medimitra_doctor_city"];
        $doctor_area            = $_POST["medimitra_doctor_area"];
        $doctor_email           = $_POST["medimitra_doctor_email"];    
        $doctor_contact         = $_POST["medimitra_doctor_contact"];    
        $doctor_qualification   = $_POST["medimitra_doctor_qualification"];


        $fb_overall_level_of_satisfaction   = $_POST["overall_level_of_satisfaction"];
        $fb_time_given_by_doctor   = $_POST["time_given_by_doctor"];
        $fb_doctor_listening_answering   = $_POST["doctor_listening_answering"];
        $fb_information_received_from_doctor   = $_POST["information_received_from_doctor"];
        $fb_doctor_insist_purchase_from_store   = $_POST["doctor_insist_purchase_from_store"];
        $fb_procedure_done_on_you   = $_POST["procedure_done_on_you"];
        

if(isset($_POST["accessibility_of_main_doctor"])) 
        $fb_accessibility_of_main_doctor   = $_POST["accessibility_of_main_doctor"];
    else
        $fb_accessibility_of_main_doctor = '';

if(isset($_POST["experience_about_assistant"])) 
        $fb_experience_about_assistant   = $_POST["experience_about_assistant"];
    else
        $fb_experience_about_assistant = '';

if(isset($_POST["experience_about_nurses_and_other"])) 
        $fb_experience_about_nurses_and_other   = $_POST["experience_about_nurses_and_other"];
    else
        $fb_experience_about_nurses_and_other = '';

if(isset($_POST["overall_cleanliness"])) 
        $fb_overall_cleanliness   = $_POST["overall_cleanliness"];
    else
        $fb_overall_cleanliness = '';

if(isset($_POST["hospital_charges"])) 
        $fb_hospital_charges   = $_POST["hospital_charges"];
    else
        $fb_hospital_charges = '';

        $fb_medimitra_user_comment   = $_POST["medimitra_user_comment"];


   

        if(($fb_procedure_done_on_you == 'yes') && ( $fb_accessibility_of_main_doctor == '' || $fb_experience_about_assistant == '' || $fb_experience_about_nurses_and_other == '' || $fb_overall_cleanliness == '' || $fb_overall_cleanliness == '')) {
            medimitra_errors()->add('accessibility_of_main_doctor', __('All the questions for procedure done are compulsory'));
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
                    'post_title'        => $doctor_firstname." ".$doctor_lastname,
                    'post_status'   => 'pending',
                    'post_type' => 'doctors',
                    'post_date' => $_SESSION['cal_startdate']                    
                )
            );

            $speciality_id = intval($_POST['medimitra_doctor_speciality']);
            $specialities = (!empty($speciality_id) ? array($speciality_id) : array());
            wp_set_post_terms( $new_doctor_id, $specialities, 'speciality' );

            $area_id = intval($_POST['medimitra_doctor_area']);
            $areas = (!empty($area_id) ? array($area_id) : array());
            wp_set_post_terms( $new_doctor_id, $areas, 'area' );



            if ( ! add_post_meta( $new_doctor_id, 'doctor_first_name', $doctor_firstname, true ) ) { 
            update_post_meta( $new_doctor_id, 'doctor_first_name', $doctor_firstname );
            }
            if ( ! add_post_meta( $new_doctor_id, 'doctor_last_name', $doctor_lastname, true ) ) { 
            update_post_meta( $new_doctor_id, 'doctor_first_name', $doctor_lastname );
            }
            if ( ! add_post_meta( $new_doctor_id, 'address', $doctor_address, true ) ) { 
            update_post_meta( $new_doctor_id, 'address', $doctor_address );
            }
            if ( ! add_post_meta( $new_doctor_id, 'state', $doctor_state, true ) ) { 
            update_post_meta( $new_doctor_id, 'state', $doctor_state );
            }
            if ( ! add_post_meta( $new_doctor_id, 'city', $doctor_city, true ) ) { 
            update_post_meta( $new_doctor_id, 'city', $doctor_city );
            }
            if ( ! add_post_meta( $new_doctor_id, 'doctor_email', $doctor_email, true ) ) { 
            update_post_meta( $new_doctor_id, 'doctor_email', $doctor_email );
            }
            if ( ! add_post_meta( $new_doctor_id, 'doctor_contact', $doctor_contact, true ) ) { 
            update_post_meta( $new_doctor_id, 'doctor_contact', $doctor_contact );
            }
            if ( ! add_post_meta( $new_doctor_id, 'qualification', $doctor_qualification, true ) ) { 
            update_post_meta( $new_doctor_id, 'qualification', $doctor_qualification );
            }


            $user_firstname      = $_POST["medimitra_user_firstname"];
            $user_lastname       = $_POST["medimitra_user_lastname"];
            $user_email          = $_POST["medimitra_user_email"];
            $user_contact       = $_POST["medimitra_user_contact"];
            $user_news_update   = $_POST["medimitra_news_update"];



            global $wpdb;
            $insert_query=$wpdb->insert( 
                                    'wp_medimitra_user', 
                                    array( 
                                    'medimitra_user_firstname' => $user_firstname, 
                                    'medimitra_user_lastname' => $user_lastname,
                                    'medimitra_user_contact'=>$user_contact,
                                    'medimitra_user_email'=>$user_email,
                                    'medimitra_user_receive_news'=>$user_news_update,
                                    'medimitra_user_ip'=>'test' 
                                    ) 
                                    
            );
            $new_user_id = $wpdb->insert_id;

$feedback_score=0;
if($fb_procedure_done_on_you=='yes'){
    $feedback_score=($fb_overall_level_of_satisfaction+$fb_time_given_by_doctor+$fb_doctor_listening_answering+$fb_information_received_from_doctor+$fb_doctor_insist_purchase_from_store+$fb_accessibility_of_main_doctor+$fb_experience_about_assistant+$fb_experience_about_nurses_and_other+$fb_overall_cleanliness+$fb_hospital_charges)*2;
}
else{
    $feedback_score=($fb_overall_level_of_satisfaction+$fb_time_given_by_doctor+$fb_doctor_listening_answering+$fb_information_received_from_doctor+$fb_doctor_insist_purchase_from_store)*4;
}



            $new_feedback_id=$wpdb->insert( 
                                    'wp_medimitra_feedback', 
                                    array( 
                                    'doctor_id' => $new_doctor_id, 
                                    'user_id' => $new_user_id,
                                    'overall_level_of_satisfaction'=>$fb_overall_level_of_satisfaction,
                                    'time_given_by_doctor'=>$fb_time_given_by_doctor,
                                    'doctor_listening_answering'=>$fb_doctor_listening_answering,
                                    'information_received_from_doctor'=>$fb_information_received_from_doctor,
                                    'doctor_insist_purchase_from_store'=>$fb_doctor_insist_purchase_from_store,
                                    'procedure_done_on_you'=>$fb_procedure_done_on_you,
                                    'accessibility_of_main_doctor'=>$fb_accessibility_of_main_doctor,
                                    'experience_about_assistant'=>$fb_experience_about_assistant,
                                    'experience_about_nurses_and_other'=>$fb_experience_about_nurses_and_other,
                                    'overall_cleanliness'=>$fb_overall_cleanliness,
                                    'hospital_charges'=>$fb_hospital_charges,
                                    'medimitra_user_comment'=>$fb_medimitra_user_comment,
                                    'feedback_score'=>$feedback_score
                                    ) 
                                    
            );

 if ( ! add_post_meta( $new_doctor_id, 'average_score', $feedback_score, true ) ) { 
            update_post_meta( $new_doctor_id, 'average_score', $feedback_score );
}

            if($new_doctor_id && $new_feedback_id) {
             $url = get_permalink( '140' );
            // print($url);
             wp_redirect($url);exit();
             
                // send an email to the admin alerting them of the registration
                //wp_new_user_notification($new_user_id);
 
                // log the new user in
                //wp_setcookie($user_login, $user_pass, true);
                //wp_set_current_user($new_user_id, $user_login);   
                //do_action('wp_login', $user_login);
 
                // send the newly created user to the home page after logging them in
             //   wp_redirect(home_url()); exit;
            }
 
        }
 
    }
}
add_action('init', 'medimitra_add_new_member');

// function get_the_user_ip() {
//   if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//     //check ip from share internet
//     $ip = $_SERVER['HTTP_CLIENT_IP'];
//   } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//     //to check ip is pass from proxy
//     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//   } else {
//     $ip = $_SERVER['REMOTE_ADDR'];
//   }
//   return apply_filters( 'wpb_get_ip', $ip );
// }

// function get_ip_address(){
//     foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
//         if (array_key_exists($key, $_SERVER) === true){
//             foreach (explode(',', $_SERVER[$key]) as $ip){
//                 $ip = trim($ip); // just to be safe

//                 if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
//                     return $ip;
//                 }
//             }
//         }
//     }
// }
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
<div class="panel-group">
<div class="panel panel-default">
<div class="panel-heading blink">Hello Doctors !!</div>
<div class="panel-body">“Dear doctors referred by patients, we congratulate you for being referred by patients to others. We request you to visit - <a href="http://www.ethicaldoctors.org/" target="_blank">http://www.ethicaldoctors.org/</a> and get registered yourself approving the declaration on behalf of Alliance of doctors for ethical healthcare, a national network of doctors committed for ethical and rational practice.
<p>
With you getting registered at <a href="http://www.ethicaldoctors.org/" target="_blank">http://www.ethicaldoctors.org/</a>, the search result will display membership status against your name to that effect.”
</p>
</div>
</div>
</div>
<?php 

return ob_get_clean();
}