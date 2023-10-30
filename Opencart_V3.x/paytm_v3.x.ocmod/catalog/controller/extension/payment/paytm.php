<?php
require_once(DIR_SYSTEM . 'library/paytm/PaytmHelper.php');
require_once(DIR_SYSTEM . 'library/paytm/PaytmChecksum.php');

class ControllerExtensionPaymentPaytm extends Controller {

	public function index() {
	
		$this->load->language('extension/payment/paytm');
		$this->load->model('extension/payment/paytm');
		$this->load->model('checkout/order');
    
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$order_id = PaytmHelper::getPaytmOrderId($order_info['order_id']);
		
		$cust_id = $email = $mobile_no = "";
		if(isset($order_info['telephone'])){
			$mobile_no = preg_replace('/\D/', '', $order_info['telephone']);
		}
		
		if(!empty($order_info['email'])){
			$cust_id = $email = trim($order_info['email']);
		} else if(!empty($order_info['customer_id'])){
			$cust_id = $order_info['customer_id'];
		}else{
			$cust_id = "CUST_".$order_id;
		}

		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$amount = number_format((float)$amount, 2, '.', '');
		$paramData = array('amount' => $amount, 'order_id' => $order_id, 'cust_id' => $cust_id, 'email' => $email, 'mobile_no' => $mobile_no);

		$data = $this->blinkCheckoutSend($paramData);
		$data['srcUrl'] = str_replace('MID',$this->config->get('payment_paytm_merchant_id'), PaytmHelper::getPaytmURL(PaytmConstants::CHECKOUT_JS_URL, $this->config->get('payment_paytm_environment'),$this->config->get('payment_paytm_merchant_id')));
		
		$data['button_confirm']			= $this->language->get('button_confirm');
		
		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/paytm', $data);
		} else {
			return $this->load->view('extension/payment/paytm', $data);
		}
	}

	private function blinkCheckoutSend($paramData = array()){
		$apiURL = PaytmHelper::getPaytmURL(PaytmConstants::INITIATE_TRANSACTION_URL, $this->config->get('payment_paytm_environment'),$this->config->get('payment_paytm_merchant_id')) . '?mid='.$this->config->get('payment_paytm_merchant_id').'&orderId='.$paramData['order_id'];
		$paytmParams = array();

		$paytmParams["body"] = array(
			"requestType"   => "Payment",
			"mid"           => $this->config->get('payment_paytm_merchant_id'),
			"websiteName"   => $this->config->get('payment_paytm_website'),
			"orderId"       => $paramData['order_id'],
			"callbackUrl"   => $this->getCallbackUrl(),
			"txnAmount"     => array(
				"value"     => $paramData['amount'],
				"currency"  => "INR",
			),
			"userInfo"      => array(
				"custId"    => $paramData['cust_id'],
			),
		);
		// for bank offers
        if($this->config->get('payment_paytm_bankoffer') ==1){
            $paytmParams["body"]["simplifiedPaymentOffers"]["applyAvailablePromo"]= "true";
        }
        // for emi subvention
        if($this->config->get('payment_paytm_emisubvention') ==1){
            $paytmParams["body"]["simplifiedSubvention"]["customerId"]= $paramData['cust_id'];
            $paytmParams["body"]["simplifiedSubvention"]["subventionAmount"]= $paramData['amount'];
            $paytmParams["body"]["simplifiedSubvention"]["selectPlanOnCashierPage"]= "true";
            //$paytmParams["body"]["simplifiedSubvention"]["offerDetails"]["offerId"]= 1;
        }
        // for dc emi
        if($this->config->get('payment_paytm_dcemi') ==1){
            $paytmParams["body"]["userInfo"]["mobile"]= $paramData['mobile_no'];
        }
		/*
		* Generate checksum by parameters we have in body
		* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
		*/
		$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $this->config->get('payment_paytm_merchant_key'));

		$paytmParams["head"] = array(
			"signature"	=> $checksum
		);

		$postData = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		
		$response = PaytmHelper::executecUrl($apiURL, $postData);



		$data = array('orderId' => $paramData['order_id'], 'amount' => $paramData['amount']);
		$data['response'] = json_encode($response);
		if(!empty($response['body']['txnToken'])){
			$data['txnToken'] = $response['body']['txnToken'];
			$data['message'] = $this->language->get('success_token_generated');
		}else{
			$data['txnToken'] = '';
			$data['message'] = $this->language->get('error_something_went_wrong');
		}
		$data['version']=VERSION.'|'.PaytmConstants::PLUGIN_VERSION;
		return $data;
	}

	/**
	* get Default callback url
	*/
	private function getCallbackUrl(){
		if(!empty(PaytmConstants::CUSTOM_CALLBACK_URL)){
			return PaytmConstants::CUSTOM_CALLBACK_URL;
		}else{
			return $this->url->link('extension/payment/paytm/callback');
		}	
	}
	
	/**
	* paytm sends response to callback
	*/
	public function callback(){

		// load language and model
		$this->load->model('extension/payment/paytm');
		$this->load->language('extension/payment/paytm');

		$data['title'] 				= sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		$data['language'] 			= $this->language->get('code');
		$data['direction'] 			= $this->language->get('direction');
		$data['heading_title'] 		= sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$data['payment_status'] = (!empty($this->request->post['STATUS']))? $this->request->post['STATUS'] : 'TXN_FAILURE';

		if(!empty($this->request->post['RESPMSG'])){
			$data['text_response'] 	= sprintf($this->language->get('text_response'), $this->request->post['RESPMSG']);
		} else {
			$data['text_response'] 	= sprintf($this->language->get('text_response'), '');
		}			
		if(!empty($this->request->post)){
			
			if(!empty($this->request->post['CHECKSUMHASH'])){
				$post_checksum = $this->request->post['CHECKSUMHASH'];
				unset($this->request->post['CHECKSUMHASH']);	
			}else{
				$post_checksum = "";
			}
		
			$isValidChecksum = PaytmChecksum::verifySignature($this->request->post, $this->config->get("payment_paytm_merchant_key"), $post_checksum);

			if($isValidChecksum === true){

				$order_id = !empty($this->request->post['ORDERID'])? PaytmHelper::getOrderId($this->request->post['ORDERID']) : 0;
				
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);

				if($order_info) {

					if(!empty($this->request->post['STATUS'])) {
					
						$reqParams = array(
											"MID" 		=> $this->config->get('payment_paytm_merchant_id'),
											"ORDERID" 	=> $this->request->post['ORDERID']
										);
						
						$reqParams['CHECKSUMHASH'] = PaytmChecksum::generateSignature($reqParams, $this->config->get("payment_paytm_merchant_key"));
						
						if($data['payment_status'] == 'TXN_SUCCESS' || $data['payment_status'] == 'PENDING'){
							/* number of retries untill cURL gets success */
							$retry = 1;
							do{
								$postData = 'JsonData='.urlencode(json_encode($reqParams));
								$resParams = PaytmHelper::executecUrl(PaytmHelper::getPaytmURL(PaytmConstants::ORDER_STATUS_URL, $this->config->get('payment_paytm_environment'),$this->config->get('payment_paytm_merchant_id')), $postData);
								$retry++;
							} while(!$resParams['STATUS'] && $retry < PaytmConstants::MAX_RETRY_COUNT);
							/* number of retries untill cURL gets success */
						}

						if(!isset($resParams['STATUS'])){
							$resParams = $this->request->post;
						}
						
						$data['payment_status'] = (!empty($resParams['STATUS']))? $resParams['STATUS'] : $data['payment_status'];
			
						/* save paytm response in db */
						if(PaytmConstants::SAVE_PAYTM_RESPONSE && !empty($resParams['STATUS'])){
							$this->model_extension_payment_paytm->saveTxnResponse($resParams, PaytmHelper::getOrderId($resParams['ORDERID']));
						}
						/* save paytm response in db */

						// if curl failed to fetch response
						if(!isset($resParams['STATUS'])){
							$this->addOrderHistory($order_id, $this->config->get('payment_paytm_order_failed_status_id'));

							$this->session->data['error'] = $this->language->get('error_server_communication');
							$this->preRedirect($data);

						} else {
							if($resParams['STATUS'] == 'TXN_SUCCESS'){
								$comment = sprintf($this->language->get('text_transaction_id'), $resParams['TXNID']) .'<br/>'. sprintf($this->language->get('text_paytm_order_id'), $resParams['ORDERID']);

								$this->addOrderHistory($order_id, $this->config->get('payment_paytm_order_success_status_id'), $comment);
								$this->preRedirect($data);

							}else if($resParams['STATUS'] == 'PENDING'){
								$this->addOrderHistory($order_id, $this->config->get('payment_paytm_order_pending_status_id'));

								$this->session->data['error'] = $this->language->get('text_pending');
								if(isset($resParams['RESPMSG']) && !empty($resParams['RESPMSG'])){
									$this->session->data['error'] .= $this->language->get('text_reason').$resParams['RESPMSG'];
								}
								$this->preRedirect($data);

							}else {
								$this->addOrderHistory($order_id, $this->config->get('payment_paytm_order_failed_status_id'));

								$this->session->data['error'] = $this->language->get('text_failure');
								if(isset($resParams['RESPMSG']) && !empty($resParams['RESPMSG'])){
									$this->session->data['error'] .= $this->language->get('text_reason').$resParams['RESPMSG'];
								}
								$this->preRedirect($data);
							}
						}

					} else {
				
						$this->session->data['error'] = $this->language->get('text_failure');
						if(isset($this->request->post['RESPMSG']) && !empty($this->request->post['RESPMSG'])){
							$this->session->data['error'] .= $this->language->get('text_reason').$this->request->post['RESPMSG'];
						}
						$this->preRedirect($data);
					}

				} else {
					$this->session->data['error'] = $this->language->get('error_invalid_order');
					$this->preRedirect($data);
				}

			} else {
				$this->session->data['error'] = $this->language->get('error_checksum_mismatch');
				$this->preRedirect($data);
			}
		}else{
			$this->preRedirect($data);
		}		
	}

	private function addOrderHistory($order_id, $order_status_id, $comment = ''){
		try{
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment);
		} catch(\Exception $e){
		}
	}

	public function webhook(){
			if(!empty($this->request->post['CHECKSUMHASH'])){
				$post_checksum = $this->request->post['CHECKSUMHASH'];
				unset($this->request->post['CHECKSUMHASH']);	
			}else{
				$post_checksum = "";
			}		
		$isValidChecksum = PaytmChecksum::verifySignature($this->request->post, $this->config->get("payment_paytm_merchant_key"), $post_checksum);
		if($isValidChecksum === true){		
		$order_id = !empty($this->request->post['ORDERID']) ? PaytmHelper::getOrderId($this->request->post['ORDERID']) : 0;
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
			if($this->request->post['STATUS']=='TXN_SUCCESS'){
				$this->addOrderHistory($order_id, $this->config->get('payment_paytm_order_success_status_id'));
			}
			else{
				$this->addOrderHistory($order_id, $this->config->get('payment_paytm_order_failed_status_id'));
			}
			echo 'webhook received';
		} else {
			echo 'Something went wrong';
		}
		
			
	}

	/**
	* show template while response 
	*/
	private function preRedirect($data){
		
		$data['continue'] = $this->url->link('checkout/cart');

		if(!empty($data['payment_status'])){
			if($data['payment_status'] == 'TXN_SUCCESS'){
				$data['continue'] 			= $this->url->link('checkout/success');
				$data['text_message'] 		= $this->language->get('text_success');
				$data['text_message_wait'] 	= sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			}else if($data['payment_status'] == 'PENDING'){
				$data['text_message'] 		= $this->language->get('text_pending');
				$data['text_message_wait'] 	= sprintf($this->language->get('text_pending_wait'), $this->url->link('checkout/cart'));
			}else{
				$data['text_message'] 		= $this->language->get('text_failure');
				$data['text_message_wait'] 	= sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
			}
		}

		$this->response->setOutput($this->load->view('extension/payment/paytm_response', $data));
	}
	
	/**
	* check cURL working or able to communicate with Paytm 
	*/
	public function curltest(){

		$debug = array();

		if(!function_exists("curl_init")){
			$debug[0]["info"][] = "cURL extension is either not available or disabled. Check phpinfo for more info.";

		// if curl is enable then see if outgoing URLs are blocked or not
		} else {

			// if any specific URL passed to test for
			if(!empty($this->request->get["url"])){
				$testing_urls = array(urldecode($this->request->get["url"]));
			} else {

				// this site homepage URL
				$server = (!empty($this->request->server['HTTPS'])? HTTPS_SERVER : HTTP_SERVER);

				$testing_urls = array(
									$server,
									"https://www.gstatic.com/generate_204",
									PaytmConstants::PRODUCTION_HOST.PaytmConstants::ORDER_STATUS_URL , PaytmConstants::STAGING_HOST.PaytmConstants::ORDER_STATUS_URL
								);
			}

			// loop over all URLs, maintain debug log for each response received
			foreach($testing_urls as $key => $url){

				$debug[$key]["info"][] = "Connecting to <b>" . $url . "</b> using cURL";

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$res = curl_exec($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if (!curl_errno($ch)) {
					$debug[$key]["info"][] = "cURL executed succcessfully.";
					$debug[$key]["info"][] = "HTTP Response Code: <b>". $http_code . "</b>";
				} else {
					$debug[$key]["info"][] = "Connection Failed !!";
					$debug[$key]["info"][] = "Error Code: <b>" . curl_errno($ch) . "</b>";
					$debug[$key]["info"][] = "Error: <b>" . curl_error($ch) . "</b>";
				}

				if((!empty($this->request->get["url"])) || (in_array($url, array(PaytmConstants::PRODUCTION_HOST.PaytmConstants::ORDER_STATUS_URL , PaytmConstants::STAGING_HOST.PaytmConstants::ORDER_STATUS_URL)))){
					$debug[$key]["info"][] = "Response: <br/><!----- Response Below ----->" . $res;
				}

				curl_close($ch);
			}
		}

		foreach($debug as $k => $v){
			echo "<ul>";
			foreach($v["info"] as $info){
				echo "<li>". $info ."</li>";
			}
			echo "</ul>";
			echo "<hr/>";
		}
	}
}
?>