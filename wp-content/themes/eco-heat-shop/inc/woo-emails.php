<?php

add_action( 'woocommerce_email_after_order_table', 'add_trustpilot_style_review_button', 15, 4 );

function add_trustpilot_style_review_button( $order, $sent_to_admin, $plain_text, $email ) {
    if ( $email->id == 'customer_completed_order' ) {
        
        $review_url = 'https://g.page/r/Cf6s7X-C9ugDEAI/review'; 
        
        echo '<div style="text-align: center; margin: 30px 0; padding: 20px; border-top: 1px solid #eeeeee;">';
        echo '<p style="font-family: Arial, sans-serif; font-size: 16px; color: #333333; margin-bottom: 15px;">How was your experience? We value your feedback!</p>';
        echo '<a href="' . esc_url( $review_url ) . '" style="background-color: #00b67a; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; font-family: Arial, sans-serif; font-size: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">★ Review Us on Google</a>';
        echo '</div>';
    }
}
