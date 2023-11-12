<?php
// Adding custom display functionality to WooCommerce's cart table
// Hooks into WooCommerce filter to modify the display of the subtotal line for each cart item
add_filter('woocommerce_cart_item_subtotal', 'display_individual_transport_fee_in_cart_table', 10, 3);

// Function to display the transport fee for each product in the WooCommerce cart table
function display_individual_transport_fee_in_cart_table($subtotal, $cart_item, $cart_item_key) {
    // Check if the number of people is set for the cart item
    if (isset($cart_item['number_of_people'])) {
        // Retrieve the transport fee for the product from post meta
        $transport_fee = get_post_meta($cart_item['product_id'], 'transport_fee', true);

        // If a transport fee is set for the product
        if ($transport_fee) {
            // Calculate the transport fee based on the number of people
            // Default to 1 if the number of people is not explicitly set
            $people_count = $cart_item['number_of_people'] ?? 1;
            $transport_fee_total = ceil($people_count / 3) * $transport_fee;

            // Append the transport fee information to the subtotal line
            // The format is a small text line under the subtotal, displaying the transport fee
            $subtotal .= "<br><small>Transporte: S/. {$transport_fee_total}</small>";
        }
    }

    // Return the modified subtotal, now including the transport fee if applicable
    return $subtotal;
}
