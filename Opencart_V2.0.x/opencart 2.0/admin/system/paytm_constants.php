<?php
$const1 = 'abcdefghijklmnop';
/*	19751/17Jan2018	*/
	/*$PAYTM_PAYMENT_URL_PROD = "https://secure.paytm.in/oltp-web/processTransaction";
	$STATUS_QUERY_URL_PROD = "https://secure.paytm.in/oltp/HANDLER_INTERNAL/TXNSTATUS";
	$PAYTM_PAYMENT_URL_TEST = "https://pguat.paytm.com/oltp-web/processTransaction";
	$STATUS_QUERY_URL_TEST = "https://pguat.paytm.com/oltp/HANDLER_INTERNAL/TXNSTATUS";*/

	$PAYTM_PAYMENT_URL_PROD = "https://securegw.paytm.in/theia/processTransaction";
	$STATUS_QUERY_URL_PROD = "https://securegw.paytm.in/merchant-status/getTxnStatus";

	$PAYTM_PAYMENT_URL_TEST = "https://securegw-stage.paytm.in/theia/processTransaction";
	$STATUS_QUERY_URL_TEST = "https://securegw-stage.paytm.in/merchant-status/getTxnStatus";
/*	19751/17Jan2018 end	*/

$callbackurl_tail_part ="/index.php?route=payment/paytm/callback";