<?php
// Enqueues custom JavaScript scripts for the WordPress theme
function enqueue_custom_scripts() {
    // Enqueues the custom script for budget filtering
    wp_enqueue_script('dynamic-pricing-interface', get_stylesheet_directory_uri() . '/js/dynamic-pricing-interface.js', ['jquery'], null, true);

    // Localizes the script, passing PHP values to JavaScript
    // This allows for securely passing the AJAX URL and the current page ID to the JavaScript file
    wp_localize_script('dynamic-pricing-interface', 'ajax_object', ['ajax_url' => admin_url('admin-ajax.php'), 'page_id' => get_the_ID()]);
}
// Adds the function to the WordPress enqueue scripts action hook
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Registers AJAX actions for logged-in and non-logged-in users
// These actions are triggered by AJAX requests from the JavaScript file
add_action('wp_ajax_filter_products_by_budget', 'filter_products_by_budget');
add_action('wp_ajax_nopriv_filter_products_by_budget', 'filter_products_by_budget');

// Function to filter products based on the budget
function filter_products_by_budget() {
    // Retrieves the budget value sent via AJAX from the JavaScript file
    $budget = $_POST['budget'];
    $tolerance = $budget * 0.10; // Allows a 10% tolerance on the budget

    // WordPress query to fetch products from the 'actividades' category
    $query = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1, // Fetches all products
        'tax_query' => [['taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'actividades']]
    ]);

    // Array to hold products and their prices
    $products = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Stores product ID and price in the array
            $products[get_the_ID()] = (int) get_post_meta(get_the_ID(), '_price', true);
        }
    }

    // Dynamic programming array to find combinations of products within budget
    $dp = array_fill(0, $budget + $tolerance + 1, null);
    $dp[0] = []; // Base case for the dynamic programming

    // Iterates through each product to find combinations that fit within the budget
    foreach ($products as $id => $price) {
        for ($j = $price; $j <= $budget + $tolerance; $j++) {
            if ($dp[$j - $price] !== null) {
                $new_combination = array_merge($dp[$j - $price], [$id]);
                if ($dp[$j] === null || array_sum(array_intersect_key($products, array_flip($new_combination))) > array_sum(array_intersect_key($products, array_flip($dp[$j])))) {
                    $dp[$j] = $new_combination;
                }
            }
        }
    }

    // Outputs the first combination of products within the budget + tolerance
    for ($j = $budget; $j <= $budget + $tolerance; $j++) {
        if ($dp[$j] !== null) {
            echo wp_json_encode($dp[$j]); // Sends the product IDs as a JSON response
            wp_die(); // Terminates the execution and prevents WordPress from sending a 0
        }
    }

    // If no combinations are found within budget, returns an error message
    echo wp_json_encode(['error' => 'No hay actividades en tu actual presupuesto, trata de incrementarlo un poco más']);
    wp_die(); // Terminates the execution and prevents WordPress from sending a 0
}
