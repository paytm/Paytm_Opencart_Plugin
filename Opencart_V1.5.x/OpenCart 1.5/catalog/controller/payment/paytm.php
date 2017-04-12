<?php
class ControllerPaymentpaytm extends Controller {
	
	
	protected function index() 
	{
		require_once(DIR_SYSTEM . 'encdec_paytm.php');
		require_once(DIR_SYSTEM . 'paytm_constants.php');
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['merchant'] = $this->config->get('paytm_merchant');
		
		$this->data['trans_id'] = $this->session->data['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['channel_id'] = "WEB";
		$this->data['industry_type_id'] = $this->config->get('paytm_industry');;
		$this->data['website'] = $this->config->get('paytm_website');
		if( ! empty($order_info['customer_id'])){
			$this->data['customer_id'] = $order_info['customer_id'];
		}else{
			$this->data['customer_id'] = $order_info['email'];
		}
		
		$this->data['email']     =  '';
		$this->data['mobile_no'] =  '';
		
		if(isset($this->data['email'])){
			$this->data['email'] = $order_info['email'];
		}
		
		if(isset($this->data['mobile_no'])){
			$this->data['mobile_no']= preg_replace('#[^0-9]{0,13}#is','',$order_info['telephone']);
		}
		
		/*$protocol='http://';
	
	  $host='';
	  
	  if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
    	$protocol='https://';
	  }
    
	  if (isset($_SERVER["HTTP_HOST"]) && ! empty($_SERVER["HTTP_HOST"])) {
    	$host=$_SERVER["HTTP_HOST"];
	  }
	
		$this->data['callback_url'] = $protocol . $host .$callbackurl_tail_part; 
		
		*/
		
		if(isset($_SERVER['HTTPS'])){
			$this->data['callback_url'] = HTTPS_SERVER .$callbackurl_tail_part; 
		}else{
			$this->data['callback_url'] = HTTP_SERVER .$callbackurl_tail_part; 
		}
		
		
		if($this->config->get('paytm_environment') == "P") {
			$this->data['action_url'] = $PAYTM_PAYMENT_URL_PROD;
		} else {
			$this->data['action_url'] = $PAYTM_PAYMENT_URL_TEST;
		}
		
		$parameters = array(
			   "MID" => $this->data['merchant'],
               "ORDER_ID"  => $this->data['trans_id'],               
               "CUST_ID" => $this->data['customer_id'],
               "TXN_AMOUNT" => $this->data['amount'],
               "CHANNEL_ID" => $this->data['channel_id'],
               "INDUSTRY_TYPE_ID" => $this->data['industry_type_id'],
               "WEBSITE" => $this->data['website'],
				 "MOBILE_NO" => $this->data['mobile_no'],
							 "EMAIL" => $this->data['email'],
				);
		if($this->config->get('paytm_callbackurl') == '1')
		{
			$parameters["CALLBACK_URL"] = $this->data['callback_url'];
		}
		
		$mer = htmlspecialchars_decode(decrypt_e($this->config->get('paytm_key'),$const1),ENT_NOQUOTES);
		$mer = rtrim($mer);
		$this->data['checkSum'] = getChecksumFromArray($parameters, $mer);
		$this->data['paytm_callbackurl'] = $this->config->get('paytm_callbackurl');
		$this->data['callback'] = $this->url->link('payment/paytm/callback', '', 'SSL');
	 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/paytm.tpl';
		} else {
			$this->template = 'default/template/payment/paytm.tpl';
		}
		$this->render();
	}

	public function callback() 
	{
		require_once(DIR_SYSTEM . 'encdec_paytm.php');
		require_once(DIR_SYSTEM . 'paytm_constants.php');
		$param = array();
		foreach($_POST as $key=>$value)
		{
		   	if($key != "route") {
				$param[$key] = $_REQUEST[$key];
		  	}
		}
		$isValidChecksum = false;
		$txnstatus = false;
		$authStatus = false;
		$mer = htmlspecialchars_decode(decrypt_e($this->config->get('paytm_key'),$const1),ENT_NOQUOTES);
		$mer = rtrim($mer);
		if(isset($_REQUEST['CHECKSUMHASH']))
		{
			$checksum = htmlspecialchars_decode($_REQUEST['CHECKSUMHASH']);
			$return = verifychecksum_e($param, $mer, $_REQUEST['CHECKSUMHASH']);
			if($return == "TRUE")
			$isValidChecksum = true;
		}
		$order_id = $_REQUEST['ORDERID'];	
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if( $param['STATUS'] == "TXN_SUCCESS") {
			$txnstatus = true;
		}
		if ($order_info) 
		{

			$this->language->load('payment/paytm');
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$this->data['text_failure'] = $this->language->get('text_failure');
			$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
			if ($txnstatus && $isValidChecksum) {
				$authStatus = true;
				
				$this->load->model('checkout/order');

				if ($authStatus == false) {
// 					$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'),$this->language->get('auth_query_mismatch'));
// 					$this->model_checkout_order->update($order_id, 10,$this->language->get('auth_query_mismatch'),false);
					
					$this->data['continue'] = $this->url->link('checkout/cart');
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm_failure.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/payment/paytm_failure.tpl';
					} else {
						$this->template = 'default/template/payment/paytm_failure.tpl';
					}

				} 
				else {
					// Create an array having all required parameters for status query.
					$requestParamList = array("MID" => $this->config->get('paytm_merchant') , "ORDERID" => $order_id);
					
					$mer = htmlspecialchars_decode(decrypt_e($this->config->get('paytm_key'),$const1),ENT_NOQUOTES);
					
					$mer = rtrim($mer);
					
					$StatusCheckSum = getChecksumFromArray($requestParamList, $mer);
							
					$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;
					
					// Call the PG's getTxnStatus() function for verifying the transaction status.
					
					if($this->config->get('paytm_environment') == "P") {
						$check_status_url = 'https://secure.paytm.in/oltp/HANDLER_INTERNAL/getTxnStatus';
					} else {
						$check_status_url = 'https://pguat.paytm.com/oltp/HANDLER_INTERNAL/getTxnStatus';
					}
					$responseParamList = callNewAPI($check_status_url, $requestParamList);
					if($responseParamList['STATUS']=='TXN_SUCCESS' && $responseParamList['TXNAMOUNT']==$_POST['TXNAMOUNT'])
					{
						$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
						$this->model_checkout_order->update($order_id, $this->config->get('paytm_order_status_id'),'',false);
						
						$this->data['continue'] = $this->url->link('checkout/success');
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm_success.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/payment/paytm_success.tpl';
						} else {
							$this->template = 'default/template/payment/paytm_success.tpl';
						}
					}
					else
					{
						$this->data['continue'] = $this->url->link('checkout/cart');
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm_failure.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/payment/paytm_failure.tpl';
						} else {
							$this->template = 'default/template/payment/paytm_failure.tpl';
						}
					}
				}
	
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
				$this->response->setOutput($this->render());
				
			} else {
				$this->load->model('checkout/order');
// 				if ($isValidChecksum == false) {
// 					$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), $this->language->get('checksum_mismatch'));
// 					$this->model_checkout_order->update($order_id, 1,$this->language->get('checksum_mismatch'),false);
// 				}
// 				else if ($param['STATUS'] == "TXN_FAILURE") {
// 					$message = 'Txn Failed';
// 					$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'),$messge);
// 					$this->model_checkout_order->update($order_id, 10,$message,false);
// 				}
				$this->data['continue'] = $this->url->link('checkout/cart');
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/paytm_failure.tpl';
				} else {
					$this->template = 'default/template/payment/paytm_failure.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
	
				$this->response->setOutput($this->render());
			}
		}
	}
}
?>