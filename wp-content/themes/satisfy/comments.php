<?php defined( 'ABSPATH' ) or die;

/*
 * Comment template
 */

if ( post_password_required() ) {
    return;
} ?>

<div id="comments" class="the-site-comments content-row">

    <?php if ( have_comments() ) { ?>

        <div id="comments-line">

            <h2 class="comments-title">
                <?php
                    printf(
                        '%d %s &quot;%s&quot;',
                        get_comments_number(),
                        _n( 'comment on', 'comments on', get_comments_number(), 'satisfy' ),
                        get_the_title()
                    );
                ?>
            </h2>

            <ol class="comment-list">
                <?php
                    wp_list_comments( array(
                        'style'      => 'ol',
                        'short_ping' => true,
                        'avatar_size'=> 65
                    ) );
                ?>
            </ol>

            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
                <nav id="comments-below-nav" class="navigation comment-navigation" role="navigation">
                    <div class="comment-prev-next-holder">

                        <?php if ( get_previous_comments_link() ) { ?>
                            <div class="comment-go prev-post">
                                <span class="prev-post-span"><span class="fa fa-chevron-left"></span> </span>
                                <?php previous_comments_link( __( 'Older Comments', 'satisfy' ) ); ?>
                            </div>
                        <?php }

                        if ( get_next_comments_link() ) { ?>
                            <div class="comment-go next-post">
                                <?php next_comments_link( __( 'Newer Comments', 'satisfy' ) ); ?>
                                <span class="prev-post-span"> <span class="fa fa-chevron-right"></span></span>
                            </div>
                        <?php } ?>

                    </div>
                </nav>
            <?php }

            if ( ! comments_open() ) { ?>
                <p class="no-comments"><?php _e( 'Comments are closed', 'satisfy' ); ?>.</p>
            <?php } ?>

        </div><!-- comments-line -->

    <?php }

    comment_form( array(
        'class_form' => 'form-group',
        'class_submit' => 'btn btn-default',
        'comment_notes_after' => get_theme_mod( 'show_html_tags', true ) ?
            sprintf(
                '<p class="form-allowed-tags">%s: <code>%s</code></p>',
                __( 'You may use these HTML tags and attributes', 'satisfy' ),
                satisfy_get_allowed_html_tags()
            ) : ''
    ) ); ?>

</div><!-- comments -->
