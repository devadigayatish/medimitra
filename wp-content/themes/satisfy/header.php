<?php defined( 'ABSPATH' ) or die;

/*
 * Header
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php } ?>

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

    <div id="site-wrapper" class="<?php satisfy_prepare_banner(); ?>">

        <div class="container-fluid">
            <header id="site-header">
                <div class="content-wrapper">
                    <div class="col-xs-12">
                        <div class="content-pad">

                            <div id="site-logo">
                                <?php satisfy_print_logo(); ?>
                            </div>

                            <div id="mobile-menu-btn">
                                <a href="#"><span class="fa fa-bars" aria-hidden="true"></span></a>
                            </div>

                            <nav class="site-nav">

                                <?php wp_nav_menu( array(
                                    'theme_location' => 'primary'
                                ) ); ?>

                                <div class="nav-search-icon<?php echo is_search() ? ' current-menu-item' : ''; ?>">
                                    <a href="#"><span class="fa fa-search" aria-hidden="true"></span></a>
                                </div>

                                <div id="nav-search-bar">
                                    <?php get_search_form(); ?>
                                </div>

                            </nav>

                        </div>
                    </div>
                </div>
            </header><!-- site-header -->
        </div>

        <div id="site-hero" class="content-row">
            <?php satisfy_print_banner(); ?>
        </div>

        <div id="site-main">
            <div class="container-fluid">
                <div class="content-wrapper">
                    <div class="content-table">
