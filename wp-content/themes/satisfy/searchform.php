<?php defined( 'ABSPATH' ) or die;

/*
 * Search form
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="input-group">
        <input type="search" required="required" class="search-field form-control" placeholder="<?php esc_attr_e( 'Search', 'satisfy' ); ?>.." value="<?php echo satisfy_trim( get_search_query() ); ?>" name="s">
        <span class="input-group-btn">
            <button type="submit" class="search-btn btn btn-default">
                <span class="fa fa-search" aria-hidden="true"></span>
            </button>
        </span>
    </div>
</form>
