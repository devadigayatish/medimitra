<?php defined( 'ABSPATH' ) or die;

/*
 * Blog loop function
 */

function satisfy_blog_loop ( $title = null, $custom_content = null ) {
    $sidebar = satisfy_temp_option( 'sidebar' );
    $show_full = get_theme_mod( 'show_full_posts' );
    $img = $show_full ? 'full' : satisfy_get_featured_image();
    $is_post = satisfy_is_post();

    if ( 'left' === $sidebar ) { // sidebar to the left
        get_sidebar();
    } ?>



    <div id="primary-content" class="<?php echo satisfy_get_content_class(); ?>">


<?php if ( is_front_page() ) {?>
<div class="well">
<?php echo do_shortcode( '[searchandfilter fields="search,speciality,area"]' );?>
</div>
<?php }?>



        <div id="main">

            <?php if ( $title && ! satisfy_temp_option( 'has_banner' ) ) { ?>
                <h1 class="article-h1 entry-title site-h1"><?php echo esc_html( $title ); ?></h1>
                <?php if ( is_search() ) {
                    get_search_form();
                }
                if ( ! $custom_content ) { ?>
                    <div class="custom-content"></div>
            <?php }
            }

            if ( $custom_content ) { ?>
                <div class="custom-content">
                    <?php // This variable $custom_content contains html. Its contents are escaped earlier ?>
                    <?php echo $custom_content; ?>
                </div>
            <?php }

            if ( is_front_page() ) {
                get_sidebar( 'home' );
            }

            if ( have_posts() && ! is_404() ) {
                while ( have_posts() ) {
                    the_post();
                    $clName = ''; ?>

                    <article <?php post_class(); ?>>
                        <div class="article-header">

                            <?php if ( ! $is_post || ! satisfy_temp_option( 'has_banner' ) ) {
                                printf( '<h%1$d class="article-h1 entry-title"><a href="%2$s">%3$s</a></h%1$d>', $is_post ? 1 : 2, esc_url( get_permalink() ), get_the_title() );
                            }
                            //do_action( 'satisfy_print_post_info' ); ?>

                        </div><!-- article-header -->
                        <div class="article-body">

                            <?php if ( has_post_thumbnail() && $img ) {
                                if ( 'satisfy-medium' === $img ) {
                                    $clName = 'col-sm-6 article-half-size';
                                } ?>
                                <div class="article-image-div <?php echo $clName; ?>">
                                    <?php if ( $is_post ) {
                                        the_post_thumbnail( $img );
                                    } else { ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $img ); ?></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="article-body-inner <?php echo $clName; ?>">

                                <?php $is_post || $show_full ? the_content() : the_excerpt(); ?>

                                <?php do_action( 'satisfy_print_categories_and_tags' ); ?>
                                

                            </div>

                        </div><!-- article-body -->

                        <div class="clear-row"></div>

                        <div class="article-footer">
                            <?php if ( $is_post && ! is_front_page() ) { ?>
                                <div id="satisfy-prev-and-next" class="content-row">
                                    <?php
                                        wp_link_pages( array(
                                            'before'    => '<div class="page-links content-row">' . __( 'Pages', 'satisfy' ) . ':',
                                            'after'     => '</div>',
                                            'separator' => ', '
                                        ) );

                                        previous_post_link( '<div class="prev-post"><span class="fa fa-chevron-left"></span> %link</div>' );
                                        next_post_link( '<div class="next-post">%link <span class="fa fa-chevron-right"></span></div>' );
                                    ?>
                                </div>
                                <?php comments_template();
                            } ?>
                        </div><!-- acticle-footer -->


<!-- custom fields -->
<style type="text/css">
.type-doctors{
    background: #eee;
       transition: 0.5s all;
    -webkit-transition: 0.5s all;
    -moz-transition: 0.5s all;
    -o-transition: 0.5s all;
    -ms-transition: 0.5s all;
  
}
.type-doctors:hover{
     box-shadow: 0 8px 17px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-box-shadow: 0 8px 17px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -moz-box-shadow: 0 8px 17px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -o-box-shadow: 0 8px 17px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
}

.type-doctors {

        padding: 2em 2em;

}
.borderless td, .borderless th {
    border: none;
}
.medimitra-doctor-post-table.table>tbody>tr>td{
width: 50%;
}</style>
<div class="wthree-about">
<?php 

if( 1 ) { 
     $education = get_post_meta( get_the_ID(), 'Education', true);
     $experience = get_post_meta( get_the_ID(), 'Experience', true);
     $address = get_post_meta( get_the_ID(), 'Address', true);
     $phone = get_post_meta( get_the_ID(), 'Phone', true);
?>
 <table class="table borderless medimitra-doctor-post-table">
    <tbody>
    <tr><td><span class="fa fa-graduation-cap"></span>&nbsp;Education : <span><?php echo $education;?></span></td>
        <td><span class="fa fa-graduation-cap"></span>&nbsp;Experience : <span><?php echo $experience;?></span></td>
        <td></td></tr>
        <tr><td><span class="fa fa-graduation-cap"></span>&nbsp;Address : <span><?php echo $address;?></span></td>
        <td><span class="fa fa-graduation-cap"></span>&nbsp;Phone : <span><?php echo $phone;?></span></td>
    <td></td></tr>
            <!-- <tr><td><span class="fa fa-graduation-cap"></span>&nbsp;Address : <span><?php echo $address;?></span></td>
            <td><span class="fa fa-graduation-cap"></span>&nbsp;Phone : <span><?php echo $phone;?></span></td>
            <td></td></tr> -->
    
           
    </tbody>
  </table>

<!--   <div class="col-sm-6"><span class="fa fa-graduation-cap"></span>&nbsp;Education : <span><?php echo $education;?></span></div>
<div class="col-sm-6"> <span class="fa fa-graduation-cap"></span>&nbsp;Experience : <span><?php echo $experience;?></span></div>
 <div class="col-sm-6" style="float:left"><span class="fa fa-graduation-cap"></span>&nbsp;Address : <span><?php echo $address;?></span></div>
 <div class="col-sm-6"><span class="fa fa-graduation-cap"></span>&nbsp;Phone : <span><?php echo $phone;?></span></div>
  -->
<?php } ?>
</div>
<!-- custom-fields end -->

                    </article><!-- acticle -->

                    <div class="section-line"></div>

                <?php }

                $links = paginate_links( array(
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'type'      => 'array'
                ) );

                if ( $links ) { ?>
                    <ul class="pagination">
                        <li><?php echo implode( '</li><li>', $links ); ?></li>
                    </ul>
                <?php }

                wp_reset_postdata();

            } elseif ( ! $custom_content ) { ?>

                <div class="no-content">
                    <h4 class="section-padding"><?php _e( 'No content found', 'satisfy' ); ?></h4>
                </div>

            <?php } ?>

        </div>
    </div><!-- primary-content -->

    <?php if ( ! $sidebar ) { // sidebar to the right (default)
        get_sidebar();
    }
}
