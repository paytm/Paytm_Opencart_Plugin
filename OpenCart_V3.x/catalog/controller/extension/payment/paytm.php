<?php
class ControllerExtensionPaymentPaytm extends Controller {
	
	public function index() {
	
		require_once(DIR_SYSTEM . 'encdec_paytm.php');
		
		$this->load->language('extension/payment/paytm');
		$this->load->model('extension/payment/paytm');
		$this->load->model('checkout/order');
    
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$mobile_no = "";
		if(isset($order_info['telephone'])){
			$mobile_no = preg_replace('/\D/', '', $order_info['telephone']);
		}

		$cust_id = "";
		$email = "";
		if(isset($order_info['email']) && trim($order_info['email']) != ""){
			$cust_id = $email = $order_info['email'];
		} else if(isset($order_info['customer_id']) && trim($order_info['customer_id']) != ""){
			$cust_id = $order_info['customer_id'];
		}


		// if customer's selected currency is not INR, then notify amount to customer before charging
	 	$data["conversion_text"] = "";
	 	
	 	// amount without currency code
		$org_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		
		// amount with currency code prefix
		$display_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], true);

		if (strtoupper($this->session->data['currency']) !== "INR") {

		 	$data["conversion_text"] = sprintf($this->language->get('conversion_text'), $org_amount, $display_amount);

		 	// if INR is available and paytm multi currency support is set to conversion
		 	if($this->currency->getId("INR") && $this->config->get('payment_paytm_multi_currency_support') == "1"){

				// order total will always be in default currency
				$amount = $this->currency->convert($order_info['total'], $this->config->get('config_currency'), "INR");
			
				$data["conversion_text"] = sprintf($this->language->get('conversion_text'), number_format($amount, '2', '.', ''), $display_amount);

				// amount is already converted to INR, just need to format this in INR
				$amount = $this->currency->format($amount, "INR", "1", false);
			
			} else {

				$amount = $org_amount;
			}

	 	} else {

	 		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
	 	}


	 	/*
	 	** in case INR is not added in currency from admin, then $amount will be zero
	 	** to handle such case, use no conversion flow here
	 	*/
	 	if($amount <= 0){
		 	$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		 	$data["conversion_text"] = sprintf($this->language->get('conversion_text'), $amount, $display_amount);
	 	}


		$parameters = array(
							"MID" => $this->config->get('payment_paytm_merchant_id'),
							"WEBSITE" => $this->config->get('payment_paytm_website'),
							"INDUSTRY_TYPE_ID" => $this->config->get('payment_paytm_industry_type'),
							"CALLBACK_URL" => $this->config->get('payment_paytm_callback_url'),
							"ORDER_ID"  => $this->session->data['order_id'],
							"CHANNEL_ID" => "WEB",
							"CUST_ID" => $cust_id,
							"TXN_AMOUNT" => $amount,
							"MOBILE_NO" => $mobile_no,
							"EMAIL" => $email,
						);

		// $parameters["ORDER_ID"] = "Test_".date("Ymd").'_'.$parameters["ORDER_ID"]; // just for testing
		
		$parameters["CHECKSUMHASH"] = getChecksumFromArray($parameters, $this->config->get('payment_paytm_merchant_key'));

		$data['paytm_fields'] = $parameters;
		$data['action'] = $this->config->get('payment_paytm_transaction_url');
		$data['button_confirm'] = $this->language->get('button_confirm');
		
		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/paytm', $data);
		} else {
			return $this->load->view('extension/payment/paytm', $data);
		}
	}
	
	public function callback(){
	
		require_once(DIR_SYSTEM . 'encdec_paytm.php');

		$isValidChecksum = false;
		$txnstatus = false;
		$authStatus = false;

		if(isset($_POST['CHECKSUMHASH'])) {
			$checksum = htmlspecialchars_decode($_POST['CHECKSUMHASH']);
			$return = verifychecksum_e($_POST, $this->config->get("payment_paytm_merchant_key"), $checksum);
			if($return == "TRUE")
				$isValidChecksum = true;
		}

		$order_id = isset($_POST['ORDERID']) && !empty($_POST['ORDERID'])? $_POST['ORDERID'] : 0;
		
		// $order_id = str_replace("Test_".date("Ymd")."_", "", $order_id); // just for testing

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if(isset($_POST['STATUS']) && $_POST['STATUS'] == "TXN_SUCCESS") {
			$txnstatus = true;
		}

		if ($order_info){

			$this->language->load('extension/payment/paytm');
			$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$data['text_response'] = $this->language->get('text_response');
			$data['text_success'] = $this->language->get('text_success');
			$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$data['text_failure'] = $this->language->get('text_failure');
			$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

			if ($txnstatus && $isValidChecksum) {
				$reqParams = array(
									"MID" => $this->config->get('payment_paytm_merchant_id'),
									"ORDERID" => $order_id
								);
				
				// $reqParams["ORDERID"] = "Test_".date("Ymd")."_".$reqParams["ORDERID"]; // just for testing

				$reqParams['CHECKSUMHASH'] = getChecksumFromArray($reqParams, $this->config->get("payment_paytm_merchant_key"));
						
				$resParams = callNewAPI($this->config->get('payment_paytm_transaction_status_url'), $reqParams);

				if($resParams['STATUS'] == 'TXN_SUCCESS' && $resParams['TXNAMOUNT'] == $_POST['TXNAMOUNT']) {
					
					$authStatus = true;
									
					$this->load->model('checkout/order');
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_paytm_order_status_id'));
					
					$data['continue'] = $this->url->link('checkout/success');

					if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm_success')) {
						$this->template = $this->config->get('config_template') . '/template/extension/payment/paytm_success';
					} else {
						$this->template = 'extension/payment/paytm_success';
					}
						
					$this->children = array(
						'common/column_left',
						'common/column_right',
						'common/content_top',
						'common/content_bottom',
						'common/footer',
						'common/header'
					);
					
					$this->response->setOutput($this->load->view($this->template, $data));
				
				} else {
					
					$data['continue'] = $this->url->link('checkout/cart');

					if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm_failure')) {
						$this->template = $this->config->get('config_template') . '/template/extension/payment/paytm_failure';
					} else {
						$this->template = 'extension/payment/paytm_failure';
					}

					// unset order id if it is set, so new order id could be generated by paytm for next txns
					if(isset($this->session->data['order_id']))
						unset($this->session->data['order_id']);

					$this->children = array(
						'common/column_left',
						'common/column_right',
						'common/content_top',
						'common/content_bottom',
						'common/footer',
						'common/header'
					);
		
					$this->response->setOutput($this->load->view($this->template, $data));
				}
				
			} else {

				// unset order id if it is set, so new order id could be generated by paytm for next txns
				if(isset($this->session->data['order_id']))
					unset($this->session->data['order_id']);

				$data['continue'] = $this->url->link('checkout/cart');

				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm_failure')) {
					$this->template = $this->config->get('config_template') . '/template/extension/payment/paytm_failure';
				} else {
					$this->template = 'extension/payment/paytm_failure';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
	
				$this->response->setOutput($this->load->view($this->template, $data));
			}
		}
	}
}
?>
