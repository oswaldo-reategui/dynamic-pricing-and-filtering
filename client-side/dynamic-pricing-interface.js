jQuery(document).ready(function($) {
    // Initialize a debounce timer variable for managing rapid-fire events like scrolling
    let debounceTimer;

    // Debounce function to limit the rate at which a function can fire
    function debounce(func, delay) {
        clearTimeout(debounceTimer); // Clear the current debounce timer
        debounceTimer = setTimeout(func, delay); // Set a new debounce timer
    }

    // Function to get URL parameters, useful for reading values passed in the query string such as 'budget'
    function getUrlParameter(name) {
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Function to fetch and display products within a specified budget
    // This function is connected to the PHP AJAX action 'filter_products_by_budget'
    function fetchProducts(budget) {
        $.ajax({
            url: ajax_object.ajax_url, // URL from 'ajax_object', localized in PHP using 'wp_localize_script'
            type: 'POST', // Method type for the AJAX request
            data: { action: 'filter_products_by_budget', budget: budget }, // Data sent to the server
            success: function(response) {
                const productIDs = JSON.parse(response); // Parse the response containing product IDs
                const message = productIDs.error ? productIDs.error : `Estas son las actividades que se ajustan a tu presupuesto de S/. ${budget}`;
                $('#budget-message').html(message); // Display the appropriate message based on the response
                $('#us_grid_x9d6').toggleClass('hide-initially', !!productIDs.error); // Show or hide product grid

                // Animate the showing of products based on the received product IDs
                $('article.w-grid-item').each(function() {
                    if (productIDs.includes($(this).data('id'))) {
                        $(this).hide().fadeIn(700); // Hide first, then show with a fade-in effect
                    } else {
                        $(this).hide(); // Hide products not within the budget
                    }
                });

                $('#no-results-message').toggle(!!productIDs.error); // Show a message if no products match the budget
            }
        });
    }

    // Check for 'budget' URL parameter and fetch products accordingly
    const budget = getUrlParameter('budget');
    if (budget && ajax_object.page_id == 178) { // Ensure we're on the correct page to execute this script
        fetchProducts(budget); // Fetch products without reloading the page if budget parameter exists
    }

    // Event handler for the budget search button click
    $('#search_budget_button').on('click', function() {
        const budget = $('#budget_input').val(); // Retrieve the budget value from the input field
        if (budget) {
            if (ajax_object.page_id == 178) { // Check if we're on the correct page
                fetchProducts(budget); // Fetch products without reloading the page
            } else {
                // Redirect to the correct page ID with the budget parameter
                window.location.href = `/?page_id=178&budget=${budget}`;
            }
        }
    });

    // Show the 'people-selector' dropdown directly on the product page
    // This ties into the 'woocommerce_before_add_to_cart_button' action in PHP
    $('#people-selector').show();

    // Function to handle enabling/disabling of the cart button and quantity input
    // Based on the fixed price flag and number of people, which are set via PHP functions
    function handleEnableDisable() {
        const peopleCount = parseInt($('#number_of_people').val(), 10); // Get the number of people selected
        const isFixedPrice = $('#is_fixed_price_value').val() === 'yes'; // Check if the product has a fixed price

        // Enable or disable the cart button and quantity input based on the conditions
        if (isFixedPrice || peopleCount > 0) {
            $('.single_add_to_cart_button').prop('disabled', false);
            $('input.input-text.qty.text').prop('disabled', false).prop('readonly', true);
        } else {
            $('.single_add_to_cart_button').prop('disabled', true);
            $('input.input-text.qty.text').prop('disabled', true).prop('readonly', false);
        }
    }

    // Initial call to set the correct state for cart button and quantity input
    handleEnableDisable();

    // Event handler for changes in the "Number of People" dropdown
    // It updates the quantity box and calculates the total price based on people count and transport fee
    $('#number_of_people').change(function() {
        const peopleCount = parseInt($(this).val(), 10); // Get the selected number of people
        const transportFee = parseFloat($('#transport_fee_value').val()); // Get the transport fee set by PHP
        const basePrice = parseFloat($('#base_product_price').text()); // Get the base product price set by PHP

        const additionalPrice = Math.ceil(peopleCount / 3) * transportFee; // Calculate the additional price
        const totalPrice = (peopleCount * basePrice) + additionalPrice; // Calculate the total price

        // Update the UI with the calculated prices
        if (peopleCount > 0) {
            $('.single_add_to_cart_button').prop('disabled', false);
            $('input.input-text.qty.text').prop('disabled', false).prop('readonly', true);
            $('#additional_price').text(`Transporte: S/. ${additionalPrice.toFixed(2)}`);
            $('#total_product_price').html(`<strong>TOTAL: S/. ${totalPrice.toFixed(2)}</strong>`);
        } else {
            $('.single_add_to_cart_button').prop('disabled', true);
            $('input.input-text.qty.text').prop('disabled', true).prop('readonly', false);
            $('#additional_price').text(`Transporte: S/. 0.00`);
            $('#total_product_price').html(`<strong>TOTAL: S/. 0.00</strong>`);
        }

        // Set the quantity input to the number of people selected
        $('input.input-text.qty.text').val(peopleCount);
        // Call the function to handle enabling/disabling the cart button and quantity input
        handleEnableDisable();
    });
});
