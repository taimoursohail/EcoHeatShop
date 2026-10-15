<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function eco_heat_shop_style() {
    // 1. Enqueue the parent Porto theme's main style
    wp_enqueue_style( 'porto-style', get_template_directory_uri() . '/style.css' );

    // 2. Define the path and URL for your compiled child theme CSS
    $child_css_path = get_stylesheet_directory() . '/style.css';
    $child_css_uri  = get_stylesheet_directory_uri() . '/style.css';

    // 3. Enqueue your child theme's style
    wp_enqueue_style( 'eco-heat-shop-child-style', 
        $child_css_uri, 
        array( 'porto-style' ), 
       // it updates the version number automatically whenever Lightning CSS runs
        file_exists( $child_css_path ) ? filemtime( $child_css_path ) : '1.0.0' 
    );
}
add_action( 'wp_enqueue_scripts', 'eco_heat_shop_style', 99 );

require get_stylesheet_directory() . '/inc/woo-emails.php';

// Fees Rule Application
add_action('woocommerce_cart_calculate_fees', 'add_gateway_handling_fee', 20, 1);
function add_gateway_handling_fee($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Cart total calculate karein (Items + Shipping + Shipping Tax)
    $cart_total_before_fee = $cart->get_cart_contents_total() + $cart->get_shipping_total() + $cart->get_shipping_tax();

    // Agar cart khali hai toh fee add na karein
    if ($cart_total_before_fee <= 0) {
        return;
    }

    // 1. Percentage Fee (2.9%)
    $percentage_fee = $cart_total_before_fee * (2.95 / 100);

    // 2. Fixed Fee (0.30 Pounds)
    $fixed_fee = 0.30; 

    // 3. Total Fee (Percentage + Fixed)
    $total_handling_fee = $percentage_fee + $fixed_fee;

    // Agar tax apply karna hai toh 'false' ko 'true' kar dein
    $cart->add_fee(__('Handling Fees', 'woocommerce'), $total_handling_fee, false);
}


// Tawk.to chat widget script
add_action('wp_footer', 'add_tawkto_chat_script');
function add_tawkto_chat_script() {
    ?>
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        // Use your verified direct chat IDs here
        s1.src='https://embed.tawk.to/6834412a12e92a190ef9eef6/1is61t3g1';
        s1.charset='UTF-8';
        s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <?php
}

// Category Remover
add_action('woocommerce_product_bulk_edit_start', 'custom_bulk_edit_remove_multiple_product_categories');
function custom_bulk_edit_remove_multiple_product_categories() {
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);
    ?>
    <div class="inline-edit-group">
        <label class="alignleft">
            <span class="title">Delete Categories</span>
            <span class="input-text-wrap">
                <select name="remove_product_cats[]" multiple="multiple" class="remove_product_cats" style="height: 100px;">
                    <option value="">Select categories to remove</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category->term_id); ?>">
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </span>
        </label>
    </div>
    <?php
}

add_action('woocommerce_product_bulk_edit_save', 'custom_bulk_edit_remove_multiple_product_categories_save', 9999);
function custom_bulk_edit_remove_multiple_product_categories_save($product) {
    $post_id = $product->get_id();
    if (isset($_REQUEST['remove_product_cats']) && is_array($_REQUEST['remove_product_cats'])) {
        $cats_to_remove = array_map('intval', $_REQUEST['remove_product_cats']); // Sanitize input
        $categories = $product->get_category_ids();
        $updated_categories = array_diff($categories, $cats_to_remove); // Remove selected categories
        if (empty($updated_categories)) {
            $updated_categories = [wc_get_default_product_category()]; // Assign default if empty
        }
        $product->set_category_ids($updated_categories);
        $product->save();
    }
}

// Added bulk actions for setting display type on product categories admin screen
add_filter('bulk_actions-edit-product_cat', 'add_bulk_action_set_display_type');
function add_bulk_action_set_display_type($actions) {
    $actions['set_display_type_default'] = __('Set Display Type: Default', 'woocommerce');
    $actions['set_display_type_products'] = __('Set Display Type: Products', 'woocommerce');
    $actions['set_display_type_subcategories'] = __('Set Display Type: Subcategories', 'woocommerce');
    $actions['set_display_type_both'] = __('Set Display Type: Both', 'woocommerce');
    return $actions;
}

// Handle the bulk action for setting display type
add_action('admin_action_set_display_type_default', 'handle_bulk_display_type_change');
add_action('admin_action_set_display_type_products', 'handle_bulk_display_type_change');
add_action('admin_action_set_display_type_subcategories', 'handle_bulk_display_type_change');
add_action('admin_action_set_display_type_both', 'handle_bulk_display_type_change');

function handle_bulk_display_type_change() {
    // Verify user permissions and taxonomy
    if (!current_user_can('manage_product_terms') || !isset($_REQUEST['taxonomy']) || $_REQUEST['taxonomy'] !== 'product_cat') {
        wp_die(__('You do not have permission to edit product categories.', 'woocommerce'));
    }

    // Get selected category IDs from delete_tags (WooCommerce uses this for bulk actions)
    $category_ids = isset($_REQUEST['delete_tags']) ? array_map('intval', (array)$_REQUEST['delete_tags']) : [];

    if (empty($category_ids)) {
        wp_die(__('No categories selected.', 'woocommerce'));
    }

    // Determine the display type from the action
    $action = current_action();
    $display_type = str_replace('admin_action_set_display_type_', '', $action);

    // Map action to WooCommerce display type values
    $display_types = [
        'default' => '',
        'products' => 'products',
        'subcategories' => 'subcategories',
        'both' => 'both'
    ];

    $new_display_type = isset($display_types[$display_type]) ? $display_types[$display_type] : '';

    // Update display type for each selected category
    foreach ($category_ids as $category_id) {
        update_term_meta($category_id, 'display_type', $new_display_type);
    }

    // Redirect back to the product categories page with a success message
    $redirect_url = add_query_arg([
        'taxonomy' => 'product_cat',
        'post_type' => 'product',
        'message' => 'display_type_updated',
    ], admin_url('edit-tags.php'));

    wp_safe_redirect($redirect_url);
    exit;
}

// Add admin notice for successful bulk action
add_action('admin_notices', 'bulk_display_type_admin_notice');
function bulk_display_type_admin_notice() {
    if (isset($_GET['message']) && $_GET['message'] === 'display_type_updated' && isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'product_cat') {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Category display types updated successfully.', 'woocommerce') . '</p></div>';
    }
}


/* Add a search field to product categories in WooCommerce quick edit and product edit screens.
 */
function add_product_category_search() {
    global $pagenow, $post_type;

    // Only run on relevant admin pages for products
    if ( ( $pagenow === 'edit.php' || $pagenow === 'post.php' || $pagenow === 'post-new.php' ) && $post_type === 'product' ) {
        // Internationalized strings for accessibility and translation
        $search_placeholder = esc_attr__( 'Search categories...', 'your-text-domain' );
        $clear_search_text = esc_html__( 'Clear Search', 'your-text-domain' );

        // Enqueue custom admin styles for better maintainability
        wp_enqueue_style(
            'product-cat-search-style',
            get_stylesheet_directory_uri() . '/admin-product-cat-search.css',
            [],
            '1.0.0'
        );

        ?>
        <script type="text/javascript">
            (function($) {
                // Debug to confirm script is running
                console.log('Product category search script loaded for <?php echo $pagenow; ?> (WooCommerce version: <?php echo WC()->version; ?>)');

                // Function to add and initialize search functionality
                function initializeCategorySearch(containerSelector, context) {
                    var categoryContainer = $(containerSelector);

                    // Check if the container exists
                    if (categoryContainer.length === 0) {
                        console.warn('Category checklist not found for selector: ' + containerSelector + ' in ' + context);
                        // Log nearby DOM for debugging
                        var parentContainer = context === 'quick-edit' ? $('.inline-edit-row') : $('#product_catdiv');
                        if (parentContainer.length) {
                            console.log(context + ' container HTML:', parentContainer.html());
                        } else {
                            console.warn(context + ' parent container not found');
                        }
                        return false;
                    }

                    // Prevent duplicate search fields
                    if (categoryContainer.siblings('.product-cat-search').length === 0) {
                        categoryContainer.before(`
                            <input type="text" class="product-cat-search" placeholder="<?php echo $search_placeholder; ?>" aria-label="<?php echo $search_placeholder; ?>" />
                            <button type="button" class="button clear-product-cat-search" aria-label="<?php echo $clear_search_text; ?>"><?php echo $clear_search_text; ?></button>
                        `);
                        console.log('Search field added for selector: ' + containerSelector + ' in ' + context);
                    }

                    var debounceTimeout;

                    // Live search with debounce
                    categoryContainer.siblings('.product-cat-search').off('keyup.search').on('keyup.search', function() {
                        var searchTerm = $(this).val().toLowerCase();
                        clearTimeout(debounceTimeout);
                        debounceTimeout = setTimeout(function() {
                            categoryContainer.find('li').each(function() {
                                var categoryName = $(this).text().toLowerCase();
                                var isChecked = $(this).find('input[type="checkbox"]').is(':checked');
                                if (categoryName.indexOf(searchTerm) !== -1 || isChecked) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }
                            });
                        }, 200); // Reduced debounce for responsiveness
                    });

                    // Clear search functionality
                    categoryContainer.siblings('.clear-product-cat-search').off('click.search').on('click.search', function() {
                        $(this).siblings('.product-cat-search').val('');
                        categoryContainer.find('li').show();
                    });

                    return true;
                }

                // Initialize for quick edit
                if ('<?php echo $pagenow; ?>' === 'edit.php') {
                    // Comprehensive list of possible selectors
                    var selectors = [
                        '.inline-edit-taxonomy-product_cat-wrap .categorychecklist',
                        'ul.product_catchecklist',
                        'ul.cat-checklist',
                        'ul.cat-checklist.product_catchecklist',
                        '.inline-edit-taxonomy-product_cat-wrap ul',
                        'ul.product_cat-checklist',
                        'ul.taxonomy-product_cat-checklist',
                        '.inline-edit-col-right ul.categorychecklist' // Fallback for custom layouts
                    ];

                    // Function to try initializing with available selectors
                    function tryInitializeQuickEdit() {
                        for (var i = 0; i < selectors.length; i++) {
                            if (initializeCategorySearch(selectors[i], 'quick-edit')) {
                                return true;
                            }
                        }
                        console.warn('No quick edit category checklist found. Tried selectors:', selectors);
                        return false;
                    }

                    // Run on quick edit click
                    $(document).on('click', '.editinline', function() {
                        var quickEditRow = $('.inline-edit-row');
                        if (quickEditRow.length === 0) {
                            console.warn('Quick edit row (.inline-edit-row) not found');
                            return;
                        }

                        // Try initializing immediately
                        if (tryInitializeQuickEdit()) {
                            return;
                        }

                        // Use MutationObserver for dynamic loading
                        var observer = new MutationObserver(function(mutations, obs) {
                            if (tryInitializeQuickEdit()) {
                                obs.disconnect(); // Stop observing once found
                            }
                        });
                        observer.observe(document.body, { childList: true, subtree: true });
                    });
                }

                // Initialize for product edit screen
                if ('<?php echo $pagenow; ?>' === 'post.php' || '<?php echo $pagenow; ?>' === 'post-new.php') {
                    $(document).ready(function() {
                        initializeCategorySearch('#product_catdiv .categorychecklist', 'product-edit');
                    });
                }
            })(jQuery);
        </script>
        <?php
    }
}


// Hook into admin_print_footer_scripts for reliable script injection
add_action( 'admin_print_footer_scripts', 'add_product_category_search', 20 );

// Enqueue admin styles separately for maintainability
function enqueue_product_category_search_styles() {
    global $pagenow, $post_type;
    if ( ( $pagenow === 'edit.php' || $pagenow === 'post.php' || $pagenow === 'post-new.php' ) && $post_type === 'product' ) {
        $css = '
            .product-cat-search {
                width: 100%;
                border: 1px solid #ddd;
                padding: 8px;
                font-size: 14px;
                margin-bottom: 10px;
                box-sizing: border-box;
            }
            .clear-product-cat-search {
                background: #f0f0f0;
                border: 1px solid #ddd;
                margin-bottom: 10px;
            }
        ';
        wp_add_inline_style( 'wp-admin', $css );
    }
}
add_action( 'admin_enqueue_scripts', 'enqueue_product_category_search_styles' );

/**
 * Disable WordPress image compression and scaling
 */

// Force max quality for JPEG/WebP
add_filter( 'jpeg_quality', function() { return 100; } );
add_filter( 'wp_editor_set_quality', function() { return 100; } );

// Disable big image size threshold (no auto-scaling to 2560px)
add_filter( 'big_image_size_threshold', '__return_false' );

// Enqueue Microsoft Clarity tracking script
function child_enqueue_clarity_script() {
    wp_enqueue_script(
        'clarity-tracking', 
        get_stylesheet_directory_uri() . '/js/clarity.js', 
        array(), 
        null, 
        false // load in header, not footer
    );
}
add_action('wp_enqueue_scripts', 'child_enqueue_clarity_script');

// Disabling the Shipping for Class: Pickup Only 
add_filter( 'woocommerce_package_rates', 'restrict_shipping_for_pickup_only', 10, 2 );
function restrict_shipping_for_pickup_only( $rates, $package ) {
    $pickup_only = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product_id = $cart_item['product_id'];
        $product = wc_get_product( $product_id );

        // Check if product has shipping class "pickup-only"
        if ( $product->get_shipping_class() === 'pickup-only' ) {
            $pickup_only = true;
            break;
        }
    }

    // If pickup-only item is in cart, remove all methods except Local Pickup
    if ( $pickup_only ) {
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'local_pickup' !== $rate->method_id ) {
                unset( $rates[ $rate_id ] );
            }
        }
    }

    return $rates;
}

// TRACKING CODE ENQUEUE SCRIPTS STARTS FROM HERE:

function tracking_enqueue_scripts(){
    
    // Enqueue GTM HEADER TAG JS
    wp_enqueue_script(
        'eco-heat-shop',
        get_stylesheet_directory_uri() . '/js/gtm-header-tag.js',
        array(),
        '1.0.0',
        false
    );
    
}

add_action('wp_enqueue_scripts', 'tracking_enqueue_scripts');

function gtm_footer_enqueue_scripts() {
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WJK7252P"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}

add_action('wp_footer', 'gtm_footer_enqueue_scripts');

function ecoheat_enqueue_gtm_events_js() {
    wp_enqueue_script(
        'ecoheat-gtm-events',
        get_stylesheet_directory_uri() . '/js/gtm-events.js',
        array('jquery'),
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'ecoheat_enqueue_gtm_events_js');

// Removing Default Caches

function remove_script_version($src) {
    return remove_query_arg('ver', $src);
}
add_filter('style_loader_src', 'remove_script_version', 15, 1);
add_filter('script_loader_src', 'remove_script_version', 15, 1);

// SHOP MANAGER ACCESS SETTIGNS:
add_action('admin_menu', function() {
    $user = wp_get_current_user();

    // Only run for Shop Manager
    if ( ! in_array('shop_manager', (array) $user->roles) ) {
        return;
    }

    // ───── Your existing restrictions (unchanged) ─────
    remove_menu_page('edit.php?post_type=page');                    // Pages
    remove_menu_page('themes.php');                                 // Appearance
    remove_menu_page('edit.php?post_type=elementor_library');      // Elementor Templates
    remove_menu_page('tools.php');                                  // Tools
    remove_menu_page('users.php');                                  // Users
    remove_menu_page('profile.php');                                // Their profile

    // ───── NEW: Hide ONLY WooCommerce Settings ─────
    remove_submenu_page( 'woocommerce', 'wc-settings' );           // Main Settings tab
    remove_submenu_page( 'woocommerce', 'wc-status' );             // Status
    remove_submenu_page( 'woocommerce', 'wc-addons' );             // Extensions

    // Hide every single Settings sub-tab (General, Products, Tax, Shipping, etc.)
    remove_submenu_page( 'wc-settings', 'wc-settings-general' );
    remove_submenu_page( 'wc-settings', 'wc-settings-products' );
    remove_submenu_page( 'wc-settings', 'wc-settings-tax' );
    remove_submenu_page( 'wc-settings', 'wc-settings-shipping' );
    remove_submenu_page( 'wc-settings', 'wc-settings-checkout' );
    remove_submenu_page( 'wc-settings', 'wc-settings-account' );
    remove_submenu_page( 'wc-settings', 'wc-settings-email' );
    remove_submenu_page( 'wc-settings', 'wc-settings-advanced' );
}, 999 );

// Checkout VAT text Manupulation:
add_filter( 'woocommerce_cart_totals_order_total_html', function ( $html ) {
    return preg_replace(
        '/<small class="includes_tax">\s*\(includes\s*(.*?)\s*VAT\)\s*<\/small>/',
        '<small class="includes_tax">($1 VAT)</small>',
        $html
    );
});


// reCAPTCHA v2 script
add_action('wp_enqueue_scripts', function () {
    if (is_product()) {
        wp_enqueue_script(
            'google-recaptcha-v2',
            'https://www.google.com/recaptcha/api.js',
            [],
            null,
            true
        );
    }
});

add_action('comment_form_after_fields', 'wc_review_recaptcha_v2');
add_action('comment_form_logged_in_after', 'wc_review_recaptcha_v2');

// Output reCAPTCHA
function wc_review_recaptcha_v2() {
    if (!is_product()) {
        return;
    }

    ?>
    <div class="wc-review-captcha">
        <div
            class="g-recaptcha"
            data-sitekey="<?php echo esc_attr(RECAPTCHA_V2_SITE_KEY); ?>"
            data-callback="wcCaptchaVerified"
            data-expired-callback="wcCaptchaExpired">
        </div>
    </div>
    <?php
}


// Disable submit button until captcha is verified
add_action('wp_footer', function () {
    if (!is_product()) {
        return;
    }
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('commentform');
            if (!form) return;

            const submitBtn = form.querySelector('#submit');
            if (!submitBtn) return;

            // Disable submit initially
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';

            window.wcCaptchaVerified = function () {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            };

            window.wcCaptchaExpired = function () {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            };
        });
    </script>
    <?php
});

// Server-side validation
add_filter('preprocess_comment', 'wc_validate_review_recaptcha_v2');

function wc_validate_review_recaptcha_v2($commentdata) {

    if (!is_product()) {
        return $commentdata;
    }

    if (empty($_POST['g-recaptcha-response'])) {
        wp_die('Captcha verification is required.');
    }

    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'body' => [
                'secret'   => RECAPTCHA_V2_SECRET_KEY,
                'response' => sanitize_text_field($_POST['g-recaptcha-response']),
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            ],
        ]
    );

    $result = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($result['success'])) {
        wp_die('Captcha verification failed.');
    }

    return $commentdata;
}

// ReOrder the Form Elements 
add_action('wp_footer', function () {
    if (!is_product()) {
        return;
    }
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('#commentform');
            if (!form) return;

            const rating  = form.querySelector('.comment-form-rating');
            const comment = form.querySelector('.comment-form-comment');
            const captcha = form.querySelector('.wc-review-captcha');
            const submit  = form.querySelector('.form-submit');

            if (rating)  form.appendChild(rating);
            if (comment) form.appendChild(comment);
            if (captcha) form.appendChild(captcha);
            if (submit)  form.appendChild(submit);
        });
    </script>
    <?php
});

// Current year logic
function currentYear() {
    return date('Y');
}
add_shortcode('year', 'currentYear');


// /* Reorder WooCommerce Product Tabs */

// add_filter( 'woocommerce_product_tabs', 'reorder_reviews_tab_first', 98 );

// function reorder_reviews_tab_first( $tabs ) {
//     // Set Reviews priority to 5 (First)
//     if ( isset( $tabs['reviews'] ) ) {
//         $tabs['reviews']['priority'] = 5;
//     }

//     // Ensure Description is set to a higher number
//     if ( isset( $tabs['description'] ) ) {
//         $tabs['description']['priority'] = 10;
//     }

//     return $tabs;
// }

// Google Rating Conifuration:
add_action( 'woocommerce_thankyou', 'porto_child_google_reviews_optin', 20 );

function porto_child_google_reviews_optin( $order_id ) {
    if ( ! $order_id ) return;

    $order = wc_get_order( $order_id );

    // 1. DELIVERY DATE 
    $delivery_date = date( 'Y-m-d', strtotime( '+5 days' ) );

    // 2. GTIN FIELD NAME 
    $gtin_field_name = '_global_unique_id'; 
    
    $gtin_array = array();

    foreach ( $order->get_items() as $item_id => $item ) {
        $product = $item->get_product();
        
        if ( $product ) {
            $gtin_value = get_post_meta( $product->get_id(), $gtin_field_name, true );
            
            if ( ! empty( $gtin_value ) ) {
                $gtin_array[] = '{"gtin":"' . esc_js( $gtin_value ) . '"}';
            }
        }
    }
    
    // List string 
    $gtin_string = implode( ',', $gtin_array );

    ?>
    <script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>
    <script>
      window.renderOptIn = function() {
        window.gapi.load('surveyoptin', function() {
          window.gapi.surveyoptin.render(
            {
              "merchant_id": 5313509415,
              "order_id": "<?php echo esc_js( $order->get_order_number() ); ?>",
              "email": "<?php echo esc_js( $order->get_billing_email() ); ?>",
              "delivery_country": "<?php echo esc_js( $order->get_shipping_country() ); ?>",
              "estimated_delivery_date": "<?php echo $delivery_date; ?>",
              
              <?php if ( ! empty( $gtin_string ) ) : ?>
              "products": [<?php echo $gtin_string; ?>]
              <?php endif; ?>
            });
        });
      }
    </script>
    <?php
}

// Google review Badge
add_action( 'wp_footer', 'eco_heat_google_badge_final', 99 );

function eco_heat_google_badge_final() {
    ?>
    <script id='merchantWidgetScript' src="https://www.gstatic.com/shopping/merchant/merchantwidget.js" defer></script>

    <script>
      merchantWidgetScript.addEventListener('load', function () {
        merchantwidget.start({
          
             merchant_id: 5313509415,
             
          
             position: 'RIGHT_BOTTOM',
             region: 'GB',
             
          
             bottomMargin: 80,
             mobileBottomMargin: 85,
             
             sideMargin: 10,
             mobileSideMargin: 35
        });
      });
    </script>
    <?php
}