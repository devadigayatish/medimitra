<?php defined( 'ABSPATH' ) or die;

/*
 * Satisfy Latest posts Widget
 */

class Satisfy_latest_posts_widget extends WP_Widget {

    public function __construct () {
        parent::__construct(
            'satisfy_latest_posts_widget',
            __( 'Satisfy Latest posts Widget', 'satisfy' ),
            array(
                'description' => __( 'Latest posts with their featured images as thumbnail images', 'satisfy' ),
                'classname'   => 'satisfy_latest_posts_widget'
            )
        );
    }

    // Makes sure all options are set in array
    public function prep_instance ( $ins ) {
        $ins['title'] = satisfy_not_empty( 'title', $ins ) ? $ins['title'] : '';
        $ins['num']   = satisfy_not_empty( 'num', $ins ) ? $ins['num'] : 5;

        return $ins;
    }

    // Display on site
    public function widget ( $args, $ins ) {
        $ins = $this->prep_instance( $ins );

        echo $args['before_widget']; ?>

        <div class="satisfy-widget-div-latest-posts">

            <?php if ( $ins['title'] ) {
                echo $args['before_title'], esc_html( apply_filters( 'widget_title', $ins['title'], $ins, $this->id_base ) ), $args['after_title'];
            } ?>

            <ul>
                <?php $num = absint( $ins['num'] );

                if ( ! $num ) {
                    $num = 5;
                }

                $q = new WP_Query( array(
                    'post_status'         => 'publish',
                    'showposts'           => $num,
                    'has_password'        => false,
                    'order'               => 'DESC',
                    'ignore_sticky_posts' => true
                ) );

                while ( $q->have_posts() ) {
                    $q->the_post();
                    $clName = 'satisfy-latest-posts-text'; ?>

                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'thumbnail' );
                                $clName .= ' -sfy-wid';
                            } ?>
                            <span class="<?php echo $clName; ?>">
                                <span class="satisfy-latest-posts-title"><?php the_title(); ?></span>
                                <span class="small-text"><?php the_time( get_option( 'date_format' ) ); ?></span>
                            </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?php echo $args['after_widget'];

        wp_reset_postdata();
    }

    // Admin form
    public function form ( $ins ) {
        $ins = $this->prep_instance( $ins ); ?>

        <div>
            <p>
                <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title', 'satisfy' ); ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_name( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $ins['title'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_name( 'num' ); ?>"><?php _e( 'Amount of posts', 'satisfy' ); ?>:</label>
                <input type="number" class="tiny-text" size="3" id="<?php echo $this->get_field_name( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" value="<?php echo absint( $ins['num'] ); ?>">
            </p>
        </div>
    <?php
    }

    // Update values
    public function update ( $ins, $old ) {
        $ins = $this->prep_instance( $ins );

        $ins['title'] = sanitize_text_field( $ins['title'] );
        $ins['num']   = absint( $ins['num'] );

        return $ins;
    }
}
