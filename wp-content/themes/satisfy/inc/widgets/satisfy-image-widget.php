<?php defined( 'ABSPATH' ) or die;

/*
 * Satisfy Image Widget
 */

class Satisfy_image_widget extends WP_Widget {

    public function __construct () {
        parent::__construct(
            'satisfy_image_widget',
            __( 'Satisfy Image Widget', 'satisfy' ),
            array(
                'description' => __( 'Displays an image from url', 'satisfy' ),
                'classname'   => 'satisfy_image_widget'
            )
        );
    }

    // Makes sure all options are set in array
    public function prep_instance ( $ins ) {
        foreach ( array( 'title', 'url', 'alt', 'link' ) as $option ) {
            $ins[ $option ] = satisfy_not_empty( $option, $ins ) ? $ins[ $option ] : '';
        }
        return $ins;
    }

    // Display on site
    public function widget ( $args, $ins ) {
        $ins = $this->prep_instance( $ins );

        if ( $ins['url'] ) {
            echo $args['before_widget']; ?>

            <div class="satisfy-widget-div-image">
                <?php if ( $ins['title'] ) {
                    echo $args['before_title'], esc_html( apply_filters( 'widget_title', $ins['title'], $ins, $this->id_base ) ), $args['after_title'];
                }
                if ( $ins['link'] ) {
                    echo '<a href="', esc_url( $ins['link'] ), '">';
                }
                echo '<img src="', esc_url( $ins['url'] ), '" alt="', esc_attr( $ins['alt'] ), '">';
                echo $ins['link'] ? '</a>' : ''; ?>
            </div>

            <?php echo $args['after_widget'];
        }
    }

    // Admin form
    public function form ( $ins ) {
        $ins = $this->prep_instance( $ins ); ?>

        <div>
            <p>
                <label for="<?php echo $this->get_field_name( 'url' ); ?>"><?php _e( 'Image Url', 'satisfy' ); ?>:</label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_name( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo esc_url( $ins['url'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title', 'satisfy' ); ?>: <em>(<?php _e( 'optional', 'satisfy' ); ?>)</em></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_name( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $ins['title'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_name( 'alt' ); ?>"><?php _e( 'Alt text', 'satisfy' ); ?>: <em>(<?php _e( 'optional', 'satisfy' ); ?>)</em></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_name( 'alt' ); ?>" name="<?php echo $this->get_field_name( 'alt' ); ?>" value="<?php echo esc_attr( $ins['alt'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_name( 'link' ); ?>"><?php _e( 'Link to', 'satisfy' ); ?>: <em>(<?php _e( 'optional', 'satisfy' ); ?>)</em></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_name( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo esc_url( $ins['link'] ); ?>">
            </p>
        </div>
    <?php
    }

    // Update values
    public function update ( $ins, $old ) {
        $ins = $this->prep_instance( $ins );

        $ins['title'] = sanitize_text_field( $ins['title'] );
        $ins['url']   = esc_url_raw( $ins['url'] );
        $ins['alt']   = sanitize_text_field( $ins['alt'] );
        $ins['link']  = esc_url_raw( $ins['link'] );

        return $ins;
    }
}
