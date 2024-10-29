<?php
// Heading
$_['heading_title']     		            = 'Paytm Payments';

// Text
$_['text_payment']      		            = 'Payment';
$_['text_extension']    		            = 'Extension';
$_['text_payments']     		            = 'Payments';
$_['text_success']      		            = 'Success: You have modified paytm account details!';
$_['text_paytm']        		            = '<img src="https://staticpg.paytmpayments.com/pg_plugins_logo/paytm_logo_paymodes.svg" alt="Paytm" title="Paytm" style="border: 1px solid #EEEEEE;" />';
$_['text_opencart_version'] 	            = 'Opencart Version';
$_['text_curl_version'] 		            = 'cURL Version';
$_['text_php_version']  		            = 'PHP Version';
$_['text_last_updated'] 		            = 'Last Updated';
$_['text_developer_docs'] 		            = 'Developer Docs';
$_['text_curl_disabled']   	                = 'cURL is not enabled properly. Please verify.';
$_['text_production']						= 'Production';
$_['text_staging']							= 'Staging';


// Entry
$_['entry_merchant_id']                   = 'Merchant ID<br /><span class="help">Enter your Merchant ID provided by Paytm</span>';
$_['entry_merchant_key']                  = 'Merchant Key<br /><span class="help">Enter your Merchant Key provided by Paytm</span>';
$_['entry_website']                       = 'Website Name<br /><span class="help">Enter your Website Name provded by Paytm</span>';
$_['entry_industry_type']                 = 'Industry Type<br /><span class="help">Eg. Retail, Entertainment etc.</span>';
$_['entry_environment']                   = 'Environment<br /><span class="help">Please choose an environment.</span>';
$_['entry_status']                        = 'Status<br /><span class="help">Enable this to accept payment using Paytm Gateway</span>';
$_['entry_order_success_status']          = 'Order Success Status<br /><span class="help">Order status that will set for Successful Payment</span>';
$_['entry_order_failed_status']           = 'Order Failed Status<br /><span class="help">Order status that will set for Failed Payment</span>';

$_['entry_total']        			      = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
$_['entry_geo_zone']     			      = 'Geo Zone:';
$_['entry_status']       			      = "Status";
$_['entry_sort_order']                    = 'Sort Order';


// Error
$_['error_permission']                      = 'Warning: You do not have permission to modify Paytm Payment!';
$_['error_merchant_id']                     = 'Merchant ID Required!';
$_['error_merchant_key']                    = 'Merchant Key Required!';
$_['error_website']                         = 'Website Required!';
$_['error_industry_type']                   = 'Industry Type Required!';
$_['error_transaction_url']                 = 'Transaction URL Required!';
$_['error_transaction_status_url']          = 'Transaction Status URL Required!';
$_['error_callback_url']                    = 'Callback URL Required!';
$_['error_valid_callback_url']              = 'Callback URL is invalid. Please enter valid URL and it must be start with http:// or https://';
$_['error_curl_warning']                    = 'Please make sure <b>Transaction Status URL</b> is correct and <b>cURL</b> is able to communicate with it';