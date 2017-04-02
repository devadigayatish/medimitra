<?php defined( 'ABSPATH' ) or die;

/*
 * Post info class
 */

class Satisfy_post_info {

    // Prints post date, author and comment info
    public static function print_post_info () {
        $date = get_the_time( 'Y-m-d' );
        $updated = get_the_modified_date( 'Y-m-d' );

        if ( ! $date ) { // for bbpress etc
            return;
        } ?>

        <p class="post-info meta-wrap<?php satisfy_disp_post_info(); ?>">
            
            <span class="<?php satisfy_post_icon(); ?>"></span>
            <?php _e( 'Posted on', 'satisfy' ); ?>
            <a href="<?php the_permalink( get_the_ID() ); ?>" title="<?php the_time( get_option( 'time_format' ) ); ?>" rel="bookmark">
                <span class="fa fa-calendar"></span>
                <time class="entry-date date published<?php echo $date === $updated ? ' updated' : ''; ?>" datetime="<?php echo esc_attr( $date ); ?>">
                    <?php the_time( get_option( 'date_format' ) ); ?>
                </time>
            </a>
            <span class="byline">
                <?php _e( 'by', 'satisfy' ); ?>
                <span class="author vcard">
                    <a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                        <span class="fa fa-user"></span>
                        <?php the_author(); ?>
                    </a>
                </span>
            </span>

            <?php $comments = intval( get_comments_number() );

            if ( $comments > 0 ) { ?>
                - <a href="<?php comments_link() ?>">
                    <span class="fa fa-comment<?php echo $comments > 1 ? 's' : ''; ?>"></span>
                    <?php echo $comments, ' ', _n( 'Comment', 'Comments', $comments, 'satisfy' ); ?>
                </a>
            <?php }

            if ( $date !== $updated ) { ?>
                <span class="<?php echo get_theme_mod( 'show_updated' ) ? '' : 'very-none'; ?>">
                    - <?php _e( 'Updated', 'satisfy' ); ?>
                    <time class="updated" datetime="<?php echo esc_attr( $updated ); ?>">
                        <?php the_modified_date( get_option( 'date_format' ) ); ?>
                    </time>
                </span>
            <?php } ?>
        </p>
    <?php
    }

    // Prints posts categories and tags info
    public static function print_categories_and_tags () { ?>
        <div class="category-and-tag-info">
            <p class="meta-wrap">
                <?php if ( has_category() ) { ?>
                    <span class="fa fa-folder-open-o"></span>
                    <?php the_category( ', ' );
                } ?>
            </p>

            <p class="meta-wrap">
                <?php $tags = get_the_tags();
                if ( $tags ) { ?>
                    <span class="fa fa-tag<?php echo count( $tags ) > 1 ? 's' : ''; ?>"></span>
                    <?php the_tags( '', ', ' );
                } ?>
            </p>
        </div>
    <?php
    }




}
