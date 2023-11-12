<?php
// Add a checkbox to the General tab in the product data meta box
add_action('woocommerce_product_options_general_product_data', 'add_per_person_price_option');
function add_per_person_price_option() {
    // Use WooCommerce's built-in function to create a checkbox
    woocommerce_wp_checkbox(
        array(
            'id' => 'show_per_person', // ID used to identify the checkbox
            'label' => 'Mostrar "por persona"', // Label for the checkbox
            'description' => 'Check this box to show "por persona" after the price' // Description of the checkbox purpose
        )
    );
}

// Save the checkbox value when the product is saved
add_action('woocommerce_process_product_meta', 'save_per_person_price_option');
function save_per_person_price_option($post_id) {
    // Check if the checkbox was checked and set the meta value accordingly
    $show_per_person = isset($_POST['show_per_person']) ? 'yes' : 'no';
    // Update the product meta with the new setting
    update_post_meta($post_id, 'show_per_person', $show_per_person);
}

// Modify the WooCommerce price display on the frontend
add_filter('woocommerce_get_price_html', 'modify_price_display', 10, 2);
function modify_price_display($price, $product) {
    // Retrieve the meta value to determine if "per person" should be displayed
    $show_per_person = get_post_meta($product->get_id(), 'show_per_person', true);

    // Append "por persona" to the price HTML if enabled
    if ($show_per_person === 'yes') {
        $price = $price . ' <span class="per-person">por persona</span>';
    }

    // Return the modified or unmodified price HTML
    return $price;
}
