<?php
namespace Opencart\Catalog\Controller\Extension\PaytmPaymentGateway\Payment;

require_once (DIR_EXTENSION . 'paytm_payment_gateway/system/library/PaytmHelper.php');
require_once (DIR_EXTENSION . 'paytm_payment_gateway/system/library/PaytmChecksum.php');		
use PaytmHelper\PaytmHelper;
use PaytmConstants\PaytmConstants;
use PaytmChecksum\PaytmChecksum;

class Paytm extends \Opencart\System\Engine\Controller {

	/* Index function*/
	public function index(): string {
		$this->load->language('extension/paytm_payment_gateway/payment/paytm');

		if (isset($this->session->data['payment_method'])) {

			$data['language'] = $this->config->get('config_language');
			$this->load->model('checkout/order');
			$order_id = $this->session->data['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$PaytmHelper = new PaytmHelper();
			$PaytmConstants = new PaytmConstants();
			$order_id = $PaytmHelper->getPaytmOrderId($order_info['order_id']);
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
				$logo = $this->config->get('payment_paytm_envert_logo');
				$data['paytm_logo'] = PaytmConstants::COLORED_LOGO_URL;	
				if($logo ==1){
					$data['paytm_logo'] = PaytmConstants::WHITE_LOGO_URL;	
				}

				$paramData = array('amount' => $amount, 'order_id' => $order_id, 'cust_id' => $cust_id, 'email' => $email, 'mobile_no' => $mobile_no);

				$blinkData = $this->blinkCheckoutSend($paramData);
				$data['orderId'] = $blinkData['orderId'];
				$data['amount'] = $blinkData['amount'];
				$data['txnToken'] = $blinkData['txnToken'];	
				$data['jsUrl'] = str_replace('MID',$this->config->get('payment_paytm_mid'), $PaytmHelper->getPaytmURL($PaytmConstants::CHECKOUT_JS_URL, $this->config->get('payment_paytm_environment')));

					return $this->load->view('extension/paytm_payment_gateway/payment/stored', $data);
		}

		return '';
	}


	/* Function for stored data */
	/*public function stored_old(): void {
		$this->load->language('extension/paytm_payment_gateway/payment/paytm');

		$json = [];

		if (isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->session->data['payment_method'])) {
			$payment = explode('.', $this->session->data['payment_method']['code']);
		} else {
			$payment = [];
		}
		if (isset($payment[0])) {
			$payment_method = $payment[0];
		} else {
			$payment_method = '';
		}

		if (isset($payment[1])) {
			$paytm_id = $payment[1];
		} else {
			$paytm_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error']['warning'] = $this->language->get('error_invalid_order');
		}

		if (!$this->customer->isLogged()) {
			$json['error']['warning'] = $this->language->get('error_login');
		}

		if (!$this->config->get('payment_paytm_status') || $payment_method != 'paytm') {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		}

		$this->load->model('extension/paytm_payment_gateway/payment/paytm');

		$paytm_info = $this->model_extension_paytm_payment_gateway_payment_paytm->getPaytm($this->customer->getId(), $paytm_id);
		if (!$paytm_info) {
			$json['error']['warning'] = $this->language->get('error_paytm');
		}

		if (!$json) {
		$PaytmHelper = new PaytmHelper();
		$order_id = $PaytmHelper->getPaytmOrderId($order_info['order_id']);
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
			$response = $this->model_extension_paytm_payment_gateway_payment_paytm->charge($this->customer->getId(), $this->session->data['order_id'], $amount, $paytm_id);


			$paramData = array('amount' => $amount, 'order_id' => $order_id, 'cust_id' => $cust_id, 'email' => $email, 'mobile_no' => $mobile_no);


			$data = $this->blinkCheckoutSend($paramData);
			$json['orderId'] = $data['orderId'];
			$json['amount'] = $data['amount'];
			$json['txnToken'] = $data['txnToken'];
			if ($response) {
				$this->load->model('checkout/order');

				$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_paytm_approved_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
			} else {
				$this->load->model('checkout/order');

				$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_paytm_failed_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}*/

	/* Functiopn For Callback */
	public function callback(): void {
		 /*echo '<pre>';print_r($_POST);die;*/

		$this->load->model('extension/paytm_payment_gateway/payment/paytm');
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
			$isValidChecksum = PaytmChecksum::verifySignature($this->request->post, $this->config->get("payment_paytm_mkey"), $post_checksum);
			if($isValidChecksum === true){
				$order_id = !empty($this->request->post['ORDERID'])? PaytmHelper::getOrderId($this->request->post['ORDERID']) : 0;
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);				
				if($order_info) {
					if(!empty($this->request->post['STATUS'])) {
						$reqParams = array(
											"MID" 		=> $this->config->get('payment_paytm_mid'),
											"ORDERID" 	=> $this->request->post['ORDERID']
										);
						
						$reqParams['CHECKSUMHASH'] = PaytmChecksum::generateSignature($reqParams, $this->config->get("payment_paytm_merchant_key"));
						
						if($data['payment_status'] == 'TXN_SUCCESS' || $data['payment_status'] == 'PENDING'){
							/* number of retries untill cURL gets success */
							$retry = 1;
							do{
								$postData = 'JsonData='.urlencode(json_encode($reqParams));
								$resParams = PaytmHelper::executecUrl(PaytmHelper::getPaytmURL(PaytmConstants::ORDER_STATUS_URL, $this->config->get('payment_paytm_environment')), $postData);
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
							$this->model_extension_paytm_payment_gateway_payment_paytm->saveTxnResponse(PaytmHelper::getOrderId($resParams['ORDERID']),$resParams);
						}

						if(!isset($resParams['STATUS'])){
							$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'), '', true);
							$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true));

						}else{
							if($resParams['STATUS'] == 'TXN_SUCCESS'){
								$comment = sprintf($this->language->get('text_transaction_id'), $resParams['TXNID']) .'<br/>'. sprintf($this->language->get('text_paytm_order_id'), $resParams['ORDERID']);
								$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_approved_status_id'));
								$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true));
							}else if($resParams['STATUS'] == 'PENDING'){
								$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_order_status_id'));
								$this->session->data['error'] = $this->language->get('text_pending');
								if(isset($resParams['RESPMSG']) && !empty($resParams['RESPMSG'])){
									$this->session->data['error'] .= $this->language->get('text_reason').$resParams['RESPMSG'];
								}								
								$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true));
							}else{
								$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'), '', true);
								$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true));								
							}
						}
						/* save paytm response in db */											
					}else{
						//text_failure
						$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'), '', true);
						$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true));							
					}
				}else{
					//invalid order
					$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'), '', true);
					$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true));						
				}
			}else{
				//checksum mismatch
				$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'), '', true);
				$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true));					
			}
		}else{
			//redirect to fail page
			$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'), '', true);
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true));				
		}	
	}

	/* Function for webhook */
	public function webhook(){
		if(!empty($this->request->post['CHECKSUMHASH'])){
			$post_checksum = $this->request->post['CHECKSUMHASH'];
			unset($this->request->post['CHECKSUMHASH']);	
		}else{
			$post_checksum = "";
		}		
		$isValidChecksum = PaytmChecksum::verifySignature($this->request->post, $this->config->get("payment_paytm_mkey"), $post_checksum);
		if($isValidChecksum === true){		
			$order_id = !empty($this->request->post['ORDERID'])? PaytmHelper::getOrderId($this->request->post['ORDERID']) : 0;
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);	
				if($this->request->post['STATUS']=='TXN_SUCCESS'){
					$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_order_status_id'));
				}
				else{
					$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_paytm_failed_status_id'));
				}
				echo 'webhook received';
		} else {
			echo 'Something went wrong';
		}
				
	}

	/* Function for blink checkout*/
	private function blinkCheckoutSend($paramData = array()){

		$PaytmHelper = new PaytmHelper();
		$PaytmConstants = new PaytmConstants();
		$PaytmChecksum = new PaytmChecksum();
		$apiURL =  $PaytmHelper->getPaytmURL($PaytmConstants::INITIATE_TRANSACTION_URL, $this->config->get('payment_paytm_environment')) . '?mid='.$this->config->get('payment_paytm_mid').'&orderId='.$paramData['order_id'];
		$paytmParams = array();

		$paytmParams["body"] = array(
			"requestType"   => "Payment",
			"mid"           => $this->config->get('payment_paytm_mid'),
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
        if($this->config->get('payment_paytm_bank_offer') ==1){
            $paytmParams["body"]["simplifiedPaymentOffers"]["applyAvailablePromo"]= "true";
        }
        // for emi subvention
        if($this->config->get('payment_paytm_emi_subvention') ==1){
            $paytmParams["body"]["simplifiedSubvention"]["customerId"]= $paramData['cust_id'];
            $paytmParams["body"]["simplifiedSubvention"]["subventionAmount"]= $paramData['amount'];
            $paytmParams["body"]["simplifiedSubvention"]["selectPlanOnCashierPage"]= "true";
            //$paytmParams["body"]["simplifiedSubvention"]["offerDetails"]["offerId"]= 1;
        }
        // for dc emi
        if($this->config->get('payment_paytm_dc_emi') ==1){
            $paytmParams["body"]["userInfo"]["mobile"]= $paramData['mobile_no'];
        }
		/*
		* Generate checksum by parameters we have in body
		* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
		*/
		$checksum = $PaytmChecksum->generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $this->config->get('payment_paytm_mkey'));

		$paytmParams["head"] = array(
			"signature"	=> $checksum
		);

		$postData = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		
		$response = $PaytmHelper->executecUrl($apiURL, $postData);



		$data = array('orderId' => $paramData['order_id'], 'amount' => $paramData['amount']);
		$data['response'] = json_encode($response);
		if(!empty($response['body']['txnToken'])){
			$data['txnToken'] = $response['body']['txnToken'];
			$data['message'] = $this->language->get('success_token_generated');
		}else{
			$data['txnToken'] = '';
			$data['message'] = $this->language->get('error_something_went_wrong');
		}
		$data['version']=VERSION.'|'. $PaytmConstants::PLUGIN_VERSION;
		return $data;
	}

	/* Function for get call back URL*/
	private function getCallbackUrl(){
		$PaytmConstants = new PaytmConstants();
		if(!empty($PaytmConstants::CUSTOM_CALLBACK_URL)){
			return $PaytmConstants::CUSTOM_CALLBACK_URL;
		}else{
			return $this->url->link('extension/paytm_payment_gateway/payment/paytm.callback&language='.$this->config->get('config_language'));
		}	
	}

	
}
