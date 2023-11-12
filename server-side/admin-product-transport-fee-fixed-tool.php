<?php
// Adding custom fields to WooCommerce Product Admin for additional product options
// Hooks into WooCommerce to add custom fields in the General product data section
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_fields');

// Hooks into WooCommerce to save custom fields when a product is saved or updated
add_action('woocommerce_process_product_meta', 'save_custom_product_fields');

// Function to add custom fields in the WooCommerce product admin
function add_custom_product_fields() {
    // Add a text input for the transport fee
    // This allows the admin to set a transport fee for each product
    woocommerce_wp_text_input([
        'id' => 'transport_fee', // Field ID
        'label' => 'Transport Fee', // Field label displayed in the admin
        'type' => 'number', // Input type is number
        'custom_attributes' => ['step' => 'any', 'min' => '0'] // Additional attributes like step and minimum value
    ]);

    // Add a checkbox for the "Is Fixed Price?" option
    // This allows the admin to specify if a product has a fixed price, independent of the number of people
    woocommerce_wp_checkbox([
        'id' => 'is_fixed_price', // Field ID
        'label' => 'Is Fixed Price?', // Field label
        'desc_tip' => true, // Show description tooltip
        'description' => 'Check this if the product has a fixed price and does not require selecting the number of people.', // Description for the checkbox
    ]);
}

// Function to save the custom fields when a product is saved or updated
function save_custom_product_fields($post_id) {
    // Save the transport fee field
    $transport_fee = $_POST['transport_fee'] ?? ''; // Retrieve the transport fee from the submitted form
    update_post_meta($post_id, 'transport_fee', sanitize_text_field($transport_fee)); // Sanitize and save it as post meta

    // Save the "Is Fixed Price?" checkbox
    $is_fixed_price = isset($_POST['is_fixed_price']) ? 'yes' : 'no'; // Check if the checkbox is set and save accordingly
    update_post_meta($post_id, 'is_fixed_price', $is_fixed_price); // Save it as post meta
}
