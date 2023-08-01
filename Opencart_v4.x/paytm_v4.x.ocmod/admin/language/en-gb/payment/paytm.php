<?php
// Heading
$_['heading_title']         = 'Paytm Payment Gateway';

// Text
$_['text_description']      = '';
$_['text_extension']        = 'Extensions';
$_['text_success']							= 'Success: You have modified paytm account details!';
$_['text_edit']             = 'Edit Payment Gateway';
$_['text_general']          = 'General';
$_['text_developer']        = 'Developer';
$_['text_enable']          = 'Enable';
$_['text_disable']             = 'Disable';
$_['text_report']           = 'Report';
$_['text_staging']             = 'Test/Staging';
$_['text_production']           = 'Production';
$_['text_webstaging']             = 'WEBSTAGING';
$_['text_default']           = 'DEFAULT';

// Column
$_['column_order']          = 'Order ID';
$_['column_paytm_id']           = 'Paytm Id';
$_['column_transaction_id']         = 'Transaction Id';
$_['column_response']       = 'Response';
$_['column_status']         = 'Order Status';
$_['column_date_added']     = 'Date Added';
$_['column_action']         = 'Action';

// Entry
$_['entry_response']        = 'Webhook';
$_['entry_bank_offer']        = 'Enable Bank Offer';
$_['entry_emi_subvention']        = 'Enable EMI Subvention';
$_['entry_dc_emi']        = 'Enable DC EMI';
$_['entry_environment']        = 'Environment';
$_['entry_mid']        = 'Test/Production MID';
$_['entry_mkey']        = 'Test/Production Secret Key';
$_['entry_website']        = 'Website (Provided by Paytm)';
$_['entry_envert_logo']        = 'Invert Logo';
$_['entry_approved_status'] = 'Order Success Status';
$_['entry_failed_status']   = 'Order Failed Status';
$_['entry_order_status']    = 'Order Pending Status';
$_['entry_order_total']    = 'Total';
$_['entry_geo_zone']        = 'Geo Zone';
$_['entry_status']          = 'Status';
$_['entry_sort_order']      = 'Sort Order';

// Help
$_['help_response']         = 'Choose if the credit card should return approved or denied making test orders';

// Error
$_['error_permission']      = 'Warning: You do not have permission to modify Paytm Payment!';
$_['error_merchant_id'] 					= 'Merchant ID Required!';
$_['error_merchant_key']					= 'Merchant Key Required!';
$_['error_website'] 						= 'Website Required!';
$_['error_industry_type'] 					= 'Industry Type Required!';
$_['error_environment'] 					= 'Environment Required!';
$_['error_curl_warning'] 					= 'Your server is not getting to connect with us. Please contact to Paytm Support.';

$_['base_url_for_paytm_webhook']			= HTTP_CATALOG.'index.php?route=extension/paytm_payment_gateway/payment/paytm.webhook';
$_['WEBHOOK_STAGING_URL']					=  "https://boss-stage-ext.paytm.com/";
$_['WEBHOOK_PRODUCTION_URL']				= "https://boss-ext.paytm.in/";