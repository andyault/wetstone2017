<?php 

function wpse62415_filter_wp_title( $title ) {
    // Return a custom document title for
    // the boat details custom page template
    if ( is_page_template( 'video-page.php' ) ) {
        return 'WetStone Video Library';
    }
    // Otherwise, don't modify the document title
    return $title;
}
add_filter( 'wp_title', 'wpse62415_filter_wp_title' );
?>