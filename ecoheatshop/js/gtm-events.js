// gtm-events.js
// currency is GBP
const currency = 'GBP';

// Add to cart
jQuery(document).on('click', '.add_to_cart_button', function() {
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    event: 'add_to_cart'
  });
});

// Begin checkout
jQuery(document).ready(function() {
  if (jQuery('body').hasClass('woocommerce-checkout')) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: 'begin_checkout'
    });
  }
});


// Purchase – fires only on thank-you page
jQuery(document).ready(function() {
  if (jQuery('body').hasClass('woocommerce-order-received')) {

    const currency = 'GBP';

    // Try to find the total amount (graceful)
    const totalText = jQuery('.woocommerce-order-overview__total .woocommerce-Price-amount').first().text() || '';
    const totalValue = parseFloat(totalText.replace(/[^0-9.]/g, '')) || 0;

    // Try multiple selectors to get the order number / transaction id
    let orderId = '';
    orderId = orderId || jQuery('.woocommerce-order-overview__order strong').first().text();
    orderId = orderId || jQuery('.order strong').first().text();
    orderId = orderId || jQuery('.woocommerce-order-overview .order-number').text(); // fallback selector
    // Clean whitespace and prefixes (e.g. "Order #1234" or "Order: 1234")
    if (orderId) {
      orderId = orderId.replace(/[^A-Za-z0-9-_]/g, '').trim();
    } else {
      orderId = ''; // do not send a generated ID here; keep blank if not found
    }

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: 'purchase',
      ecommerce: {
        transaction_id: orderId,
        value: totalValue,
        currency: currency
      }
    });
  }
});
