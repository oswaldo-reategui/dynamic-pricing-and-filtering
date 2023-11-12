<?php
// Add custom functionality to WooCommerce's cart calculation process
// Hooks into the WooCommerce action to calculate additional fees (transport fee in this case)
add_action('woocommerce_cart_calculate_fees', 'add_transport_fee_as_cart_item');

// Hooks into the WooCommerce filter to modify cart item data, particularly for overriding cart quantity
add_filter('woocommerce_add_cart_item_data', 'override_cart_quantity_based_on_people', 20, 3);

// Function to calculate and add transport fee as a cart item
function add_transport_fee_as_cart_item($cart) {
    // Check and return early if in admin area and not processing an AJAX request
    if (is_admin() && !defined('DOING_AJAX')) return;

    $transport_fee_total = 0; // Initialize transport fee total

    // Loop through each item in the cart
    foreach ($cart->get_cart() as $cart_item) {
        // Retrieve the transport fee for each product
        $transport_fee = get_post_meta($cart_item['product_id'], 'transport_fee', true);
        if (!$transport_fee) continue; // Skip if no transport fee is set

        // Retrieve or default to 1 the number of people from cart item data
        $people_count = $cart_item['number_of_people'] ?? 1;
        // Calculate the transport fee for this cart item and add it to the total
        $transport_fee_total += ceil($people_count / 3) * $transport_fee;
    }

    // If there is a transport fee total, add it as a fee to the cart
    if ($transport_fee_total > 0) {
        $cart->add_fee('Transporte', $transport_fee_total, false);
    }
}

// Function to override the cart quantity based on the number of people selected
function override_cart_quantity_based_on_people($cart_item_data, $product_id, $variation_id) {
    // Check if 'number_of_people' is set in POST data and use it to override quantity
    if (isset($_POST['number_of_people'])) {
        $cart_item_data['quantity'] = intval($_POST['number_of_people']);
    }
    return $cart_item_data; // Return the modified cart item data
}