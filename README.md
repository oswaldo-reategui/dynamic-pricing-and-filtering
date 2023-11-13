# Dynamic Pricing and Filtering Woocommerce

## Overview

This project encompasses a series of innovative customizations for WooCommerce on WordPress. It enhances the product management and shopping experience by introducing dynamic pricing, responsive design elements, and user-friendly interfaces for both site administrators and customers.

## Features

- **Dynamic Pricing & Display**: Adjusts product pricing in real time based on user-defined criteria such as the number of people.
- **Interactive Budget Filtering**: Allows users to filter products based on their budget, improving the shopping experience.
- **Responsive UI Elements**: Implements responsive design to ensure a consistent and professional look across various devices.

## File Descriptions

### PHP Scripts

- `admin-product-transport-fee-fixed-tool.php`: Adds custom fields in the WooCommerce product admin for transport fees and fixed price options, allowing for more detailed product configuration.
  ![gif_01](https://github.com/oswaldo-reategui/dynamic-pricing-and-filtering/assets/59293697/31a04fe3-2f76-421e-b5ee-98c27bc7e7be)


- `checkout-transport-fee-calculation.php`: Implements a calculation tool in the checkout process that dynamically adjusts prices based on transport fees and user selections.
![gif_02](https://github.com/oswaldo-reategui/dynamic-pricing-and-filtering/assets/59293697/b0372d45-ef61-4d99-819f-271add77c49d)


- `insert-page-content-shortcode.php`: Provides a reusable shortcode `[insert_page]` to insert content from other pages, streamlining content management.
![gif_03](https://github.com/oswaldo-reategui/dynamic-pricing-and-filtering/assets/59293697/eff7b7e1-b217-4252-a467-a5e535c86d02)


- `interactive-people-selector-with-transport-fees.php`: A frontend script that introduces an interactive selector for the number of people, integrating it with transport fee calculations.
![gif_04](https://github.com/oswaldo-reategui/dynamic-pricing-and-filtering/assets/59293697/1f6f2277-ae35-4a58-8e74-2f3d5c3be156)


- `budget-conscious-product-filter.php`: Offers a frontend filtering system that allows customers to find products within a specific budget range.
- ![gif_05](https://github.com/oswaldo-reategui/dynamic-pricing-and-filtering/assets/59293697/64efcdd7-71c6-4860-9f74-4e77cef4e898)


- `dynamic-cart-adjustment-for-transport-fees.php`: Adjusts the WooCommerce cart dynamically, accounting for additional transport fees based on product choices and user inputs.


- `per-person-price-display.php`: Modifies the product price display on WooCommerce pages to include a "per person" suffix when applicable.

### CSS Files

- `budget-search-button-styles.css`: Styles the budget search button, enhancing its visual appeal and interaction with smooth transitions and responsive adjustments.

- `custom-checkbox-dropdown-styles.css`: Applies custom styles to checkboxes and dropdowns within WooCommerce, improving the user interface aesthetics.

### JavaScript Files

- `dynamic-pricing-interface.js`: A JavaScript file that manages the interactive pricing interface, updating prices in real-time based on user interactions.

## Usage

To utilize these customizations, simply include the PHP files in your theme's folder and link the CSS files in your theme's stylesheet. The JavaScript file should be enqueued appropriately within your WordPress theme.

## Contributing

Contributions to enhance or extend the functionalities are welcome. Please feel free to fork this repository, and submit pull requests with your improvements.

## License

This project is licensed under MIT license.
