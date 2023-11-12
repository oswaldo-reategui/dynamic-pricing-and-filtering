<?php
// Integrating custom functionality into WooCommerce's single product page
// Hooks into the WooCommerce action right before the add to cart button
add_action('woocommerce_before_add_to_cart_button', 'display_transport_and_people_options');

// Hooks into the WooCommerce filter to modify cart item data
// It's used to save custom data (like number of people) to the cart item
add_filter('woocommerce_add_cart_item_data', 'save_transport_and_people_options_to_cart', 10, 3);

// Function to display custom options (transport fee and people selector) on the product page
function display_transport_and_people_options() {
    global $product; // Access the global product object

    // Retrieve custom metadata from the product (transport fee and fixed price flag)
    $transport_fee = get_post_meta($product->get_id(), 'transport_fee', true);
    $is_fixed_price = get_post_meta($product->get_id(), 'is_fixed_price', true);

    // Echo HTML for a hidden input storing the fixed price flag
    // This value is used in the JavaScript file to control the display logic
    echo '<input type="hidden" id="is_fixed_price_value" value="' . esc_attr($is_fixed_price) . '">';

    // Echo HTML for a hidden div storing the base price of the product
    // Used for price calculations in the JavaScript file
    $base_price = $product->get_price();
    echo '<div id="base_product_price" style="display:none;">' . esc_attr($base_price) . '</div>';

    // Check and return early if transport fee is not set or is zero
    if (!$transport_fee || floatval($transport_fee) <= 0) return;

    // Display the custom option container for the transport fee
    echo '<div id="transport-fee-container" class="custom-option-container">';
    echo '<input type="hidden" id="transport_fee_value" value="' . esc_attr($transport_fee) . '">';

    // Display the people selector dropdown, dynamically populated based on a set range
    echo '<div id="people-selector" class="people-selector custom-option-container" style="margin-bottom: 10px;">';
    echo '<label for="number_of_people">Selecciona el número de personas: </label>';
    echo '<select id="number_of_people" name="number_of_people" required>';
    echo "<option value='0'>Número de personas</option>";
    for ($i = 1; $i <= 10; $i++) {
        echo "<option value='{$i}'>{$i}</option>";
    }
    echo '</select></div>';

    // Display additional price and total product price placeholders, to be updated by JavaScript
    echo '<div id="additional_price" style="margin-top: 10px;">Transporte: S/. 0.00</div>';
    echo '<div id="total_product_price" style="margin-top: 10px;"><strong>TOTAL: S/. 0.00</strong></div>';
}

// Function to save the selected number of people to the cart item data
function save_transport_and_people_options_to_cart($cart_item_data, $product_id, $variation_id) {
    // Check if 'number_of_people' is set in POST data and add it to the cart item data
    if (isset($_POST['number_of_people'])) {
        $cart_item_data['number_of_people'] = intval($_POST['number_of_people']);
    }
    return $cart_item_data; // Return the modified cart item data
}
