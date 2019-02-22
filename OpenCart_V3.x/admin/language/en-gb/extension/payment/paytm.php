<?php
// Heading
$_['heading_title']     = 'Paytm Payments';

// Text
$_['text_payment']      = 'Payment';
$_['text_extension']    = 'Extension';
$_['text_payments']     = 'Payments';
$_['text_success']      = 'Success: You have modified paytm account details!';
$_['text_paytm']        = '<img src="view/image/payment/paytm.png" alt="Paytm" title="Paytm" style="border: 1px solid #EEEEEE;" />';
$_['text_opencart_version'] = 'Opencart Version';
$_['text_curl_version'] = 'cURL Version';
$_['text_php_version'] = 'PHP Version';
$_['text_last_updated'] = 'Last Updated';
$_['text_next']         = 'Next';
$_['text_response_success']= 'Updated <b>STATUS</b> has been fetched';
$_['text_response_status_success']= ' and Transaction Status has been updated <b>PENDING</b> to <b>%s</b>';
$_['text_response_error']  = 'Something went wrong. Please again';

// Tab
$_['tab_general']       = 'General';
$_['tab_order_status']  = 'Order Status';
$_['tab_promo_code']    = 'Promo Code';


// Button
$_['button_promo_code_add']      = "Add Promo Code";
$_['button_promo_code_remove']   = "Remove Promo Code";
$_['button_fetch_status']        = "Fetch Status";

// Entry
$_['entry_merchant_id']         = 'Merchant ID';
$_['entry_merchant_id_help']    = 'Enter your Merchant ID provided by Paytm';

$_['entry_merchant_key']        = 'Merchant Key';
$_['entry_merchant_key_help']   = 'Enter your Merchant Key provided by Paytm';

$_['entry_website']             = 'Website Name';
$_['entry_website_help']        = 'Enter your Website Name provded by Paytm';

$_['entry_industry_type']       = 'Industry Type';
$_['entry_industry_type_help']  = 'Eg. Retail, Entertainment etc.';

$_['entry_transaction_url']     = 'Transaction URL';
$_['entry_transaction_url_help']= 'Enter Transaction URL provided by Paytm';

$_['entry_transaction_status_url'] = 'Transaction Status URL';
$_['entry_transaction_status_url_help'] = 'Enter Transaction Status URL provided by Paytm';

$_['entry_callback_url_status'] = 'Custom Callback URL';
$_['entry_callback_url_status_help'] = 'Enable this if you want to modify default callback URL';

$_['entry_callback_url']        = 'Callback URL';

$_['entry_status']              = 'Status';
$_['entry_status_help']         = 'Enable this to accept payment using Paytm Gateway';

$_['entry_sort_order']          = 'Sort Order';

$_['entry_order_success_status'] = 'Order Success Status';
$_['entry_order_success_status_help'] = 'Order status that will set for Successful Payment';

$_['entry_order_failed_status']  = 'Order Failed Status';
$_['entry_order_failed_status_help'] = 'Order status that will set for Failed Payment';

$_['entry_promo_code']          = "Promo Code";
$_['entry_promo_code_help1']    = "These promo codes must be configured with your Paytm MID.";
$_['entry_promo_code_status']   = "Status";
$_['entry_promo_code_start_date'] = "Start Date";
$_['entry_promo_code_end_date'] = "End Date";

$_['entry_promo_code_status']   = "Status";
$_['entry_promo_code_status_help1'] = "Enabling this will show Promo Code field at Checkout.";

$_['entry_promo_code_validation'] = "Local Validation";
$_['entry_promo_code_validation_help1'] = "Validate applied Promo Code before proceeding to Paytm payment page.";
$_['entry_promo_code_validation_help2'] = "Transaction will be failed in case of Promo Code failure at Paytm's end.";


// Error
$_['error_permission']      = 'Warning: You do not have permission to modify Paytm Payment!';
$_['error_merchant_id']     = 'Merchant ID Required!';
$_['error_merchant_key']    = 'Merchant Key Required!';
$_['error_website']         = 'Website Required!';
$_['error_industry_type']   = 'Industry Type Required!';
$_['error_transaction_url'] = 'Transaction URL Required!';
$_['error_transaction_status_url'] = 'Transaction Status URL Required!';
$_['error_callback_url']    = 'Callback URL Required!';
$_['error_promo_code']      = 'Promo Code Required!';
$_['error_start_date']      = 'Start Date Required!';
$_['error_end_date']        = 'End Date Required!';
$_['error_invalid_end_date'] = 'End Date should be greate than Start Date!';
$_['error_valid_callback_url'] = 'Callback URL is invalid. Please enter valid URL and it must be start with http:// or https://';
$_['error_curl_warning'] = 'Please make sure <b>Transaction Status URL</b> is correct and <b>cURL</b> is able to communicate with it';
