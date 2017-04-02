/*
 * Satisfy theme script
 */

jQuery(function ( $ ) {
    'use strict';

    var has_scrolled = false,
        resized = false,
        offset = 100,
        speed = 300,
        pad = 150,

        nav = $( '.site-nav' ),
        logo = $( '#site-logo' ),
        res_div = $( '#site-hero .vertical-table' ),
        scroll_btn = $( '.satisfy-to-top' ),
        header = $( '#site-header' ),
        search = $( '#nav-search-bar' );

    if ( ! res_div.length ) {
        res_div = $( 'body' );
        pad = 55;
    }

    $( 'table' ).wrap( '<div class="table-scroll-div">' );


    // Returns if mobile screen size
    function is_mobile () {
        return innerWidth <= 991;
    };

    // Animates scroll
    function anim_scroll ( y ) {
        $( 'html, body' ).animate( { scrollTop: y } );
    };

    // Adds some padding if nav menu collapses
    function check_height () {
        clearTimeout( resized );

        resized = setTimeout(function () {
            res_div.css( 'padding-top', (! is_mobile() && nav.height() > offset ? pad : pad - 55) + 'px' );
            nav.css( 'max-width', 'calc(100% - ' + (logo.width() + 10) + 'px)' );
        }, offset );
    };


    // Nav search button
    nav.find( '.nav-search-icon a' ).on( 'click', function ( e ) {
        e.preventDefault();

        if ( search.is( ':hidden' ) ) {
            search.fadeIn( speed, function () {
                $( this ).find( 'input' ).focus();
            });
        } else {
            $( this ).blur();
        }
    });

    // Nav search input blur fadeout
    search.find( 'input' ).on( 'blur', function () {
        if ( ! is_mobile() ) {
            search.fadeOut( speed );
        }
    });

    // Resize event, trigger directly
    $( window ).on( 'resize', check_height ).trigger( 'resize' );

    // Mobile button
    $( '#mobile-menu-btn a' ).on( 'click', function ( e ) {
        e.preventDefault();

        header.toggleClass( '-active' );
        nav.toggleClass( '-active' );
        $( this ).blur().find( 'span' ).toggleClass( 'fa-bars fa-times' );
    });

    // Hero arrow
    $( '#site-hero .fa-chevron-down' ).on( 'click', function () {
        anim_scroll( $( '.cover-img' ).height() - ('absolute' === header.css( 'position' ) ? 0 : header.height()) );
    });

    // Scroll to top button
    if ( scroll_btn.length ) {
        $( window ).on( 'scroll', function () {
            if ( pageYOffset > offset ) {
                if ( ! has_scrolled ) {
                    scroll_btn.fadeIn( speed );
                    has_scrolled = true;
                }
            } else if ( has_scrolled ) {
                scroll_btn.fadeOut( speed );
                has_scrolled = false;
            }
        });

        scroll_btn.on( 'click', function () {
            anim_scroll( 0 );
        });
    }
});
