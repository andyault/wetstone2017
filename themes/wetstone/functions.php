<?php
function wpse62415_filter_wp_title( $title ) {
    if ( is_page_template( 'video-library-page.php' ) ) {
        return 'WetStone Video Library';
    }
    return $title;
}
add_filter( 'wp_title', 'wpse62415_filter_wp_title' );
?>