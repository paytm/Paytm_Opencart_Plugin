<?php

class PaytmConstants{
	CONST TRANSACTION_URL_PRODUCTION			= "https://securegw.paytm.in/order/process";
	CONST TRANSACTION_STATUS_URL_PRODUCTION		= "https://securegw.paytm.in/order/status";

	CONST TRANSACTION_URL_STAGING				= "https://securegw-stage.paytm.in/order/process";
	CONST TRANSACTION_STATUS_URL_STAGING		= "https://securegw-stage.paytm.in/order/status";

	CONST SAVE_PAYTM_RESPONSE 					= true;
	CONST CHANNEL_ID							= "WEB";
	CONST APPEND_TIMESTAMP						= false;
	CONST ONLY_SUPPORTED_INR					= true;
	CONST X_REQUEST_ID							= "PLUGIN_OPENCART_" . VERSION;

	CONST MAX_RETRY_COUNT						= 3;
	CONST CONNECT_TIMEOUT						= 10;
	CONST TIMEOUT								= 10;

	CONST LAST_UPDATED							= "20200120";
	CONST PLUGIN_VERSION						= "2.0";

	CONST CUSTOM_CALLBACK_URL					= "";
}

?>