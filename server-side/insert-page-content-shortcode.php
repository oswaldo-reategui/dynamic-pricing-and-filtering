<?php
// Function to create a shortcode that inserts page content based on the provided ID for Wordpress
function insert_page_content_by_id($atts) {
    // Attributes for the shortcode
    // 'id' attribute allows specifying the ID of the page whose content is to be inserted
    $a = shortcode_atts(array(
        'id' => '0', // Default value set to '0' meaning no page by default
    ), $atts);

    // Fetch the content of the page using the provided ID
    // get_post_field is used to retrieve the 'post_content' of the specified page
    $page_content = get_post_field('post_content', $a['id']);

    // Return the content after applying any relevant filters
    // apply_filters('the_content') ensures that the content is processed similarly to the standard page content
    return apply_filters('the_content', $page_content);
}

// Register the shortcode with WordPress
// The shortcode 'insert_page' can be used in posts or pages to insert content from another page
add_shortcode('insert_page', 'insert_page_content_by_id');
