<?phpadd_filter( 'the_title', 'custom_posts_page_title', 10, 2 );
/**
 * Sets custom title for the Posts page.
 *
 * @param  string $title Current title.
 * @param  int $id The post ID.
 *
 * @return string Modified title.
 */
function custom_posts_page_title( $title, $id ) {
    // get the id of Posts page.
    $posts_page = get_option( 'video-library' );

    // if we are not on an inner Posts page, abort.
    if ( ! is_home() || is_front_page() ) {
        return $title;
    }

    // if the current entry's ID matches with that of the Posts page..
    if ( $id == $posts_page ) {
        // set your new title here.
        $title = 'WetStone Video Library';
    }

    return $title;
}
?>