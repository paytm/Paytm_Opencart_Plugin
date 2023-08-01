<?php
namespace PaytmConstants;
class PaytmConstants{
	CONST PRODUCTION_HOST						= "https://securegw.paytm.in/";
	CONST STAGING_HOST							= "https://securegw-stage.paytm.in/";

	CONST ORDER_PROCESS_URL						= "order/process";
	CONST ORDER_STATUS_URL						= "order/status";
	CONST INITIATE_TRANSACTION_URL				= "theia/api/v1/initiateTransaction";
	CONST CHECKOUT_JS_URL						= "merchantpgpui/checkoutjs/merchants/MID.js";

	CONST SAVE_PAYTM_RESPONSE 					= true;
	CONST CHANNEL_ID							= "WEB";
	CONST APPEND_TIMESTAMP						= true;
	CONST ONLY_SUPPORTED_INR					= true;
	CONST X_REQUEST_ID							= "PLUGIN_OPENCART_" . VERSION;

	CONST MAX_RETRY_COUNT						= 3;
	CONST CONNECT_TIMEOUT						= 10;
	CONST TIMEOUT								= 10;

	CONST LAST_UPDATED							= "20230626";
	CONST PLUGIN_VERSION						= "1.0.0.";

	CONST CUSTOM_CALLBACK_URL					= "";
	CONST IS_BLINK_SUPPORTED					= true;

	const COLORED_LOGO_URL = "https://staticpg.paytm.in/pg_plugins_logo/paytm_logo_paymodes.svg";
	const WHITE_LOGO_URL = "https://staticpg.paytm.in/pg_plugins_logo/paytm_logo_invert.svg";
}
?>
