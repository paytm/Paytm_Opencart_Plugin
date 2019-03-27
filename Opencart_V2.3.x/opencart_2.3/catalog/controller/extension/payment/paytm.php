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

		$data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$parameters = array(
							"MID" => $this->config->get('paytm_merchant_id'),
							"WEBSITE" => $this->config->get('paytm_website'),
							"INDUSTRY_TYPE_ID" => $this->config->get('paytm_industry_type'),
							"CALLBACK_URL" => $this->config->get('paytm_callback_url'),
							"ORDER_ID"  => $this->session->data['order_id'],
							"CHANNEL_ID" => "WEB",
							"CUST_ID" => $cust_id,
							"TXN_AMOUNT" => $data['amount'],
							"MOBILE_NO" => $mobile_no,
							"EMAIL" => $email,
						);

		// $parameters["ORDER_ID"] = "TEST_".date("Ymd").'_'.$parameters["ORDER_ID"]; // just for testing
		
		$parameters["CHECKSUMHASH"] = getChecksumFromArray($parameters, $this->config->get('paytm_merchant_key'));

		/*
		if($order_info['currency_code'] != "INR"){
			$parameters["CURRENCY"] = $order_info['currency_code'];
		}
		*/

		
		$data['paytm_fields'] = $parameters;
		$data['action'] = $this->config->get('paytm_transaction_url');
		$data['button_confirm'] = $this->language->get('button_confirm');

		if($this->config->get('paytm_promo_code_status')) {
			$data["show_promo_code"] = true;
		} else {
			$data["show_promo_code"] = false;
		}
		
		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/paytm.tpl', $data);
		} else {
			return $this->load->view('extension/payment/paytm.tpl', $data);
		}
	}
	
	public function callback(){
	
		require_once(DIR_SYSTEM . 'encdec_paytm.php');

		$isValidChecksum = false;
		$txnstatus = false;
		$authStatus = false;

		if(isset($_POST['CHECKSUMHASH'])) {
			$checksum = htmlspecialchars_decode($_POST['CHECKSUMHASH']);
			$return = verifychecksum_e($_POST, $this->config->get("paytm_merchant_key"), $checksum);
			if($return == "TRUE")
				$isValidChecksum = true;
		}

		$order_id = isset($_POST['ORDERID']) && !empty($_POST['ORDERID'])? $_POST['ORDERID'] : 0;
		
		// $order_id = str_replace("TEST_".date("Ymd")."_", "", $order_id); // just for testing


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
									"MID" => $this->config->get('paytm_merchant_id'),
									"ORDERID" => $order_id
								);
				
				// $reqParams["ORDERID"] = "TEST_".date("Ymd")."_".$reqParams["ORDERID"]; // just for testing

				$reqParams['CHECKSUMHASH'] = getChecksumFromArray($reqParams, $this->config->get("paytm_merchant_key"));
						
				$resParams = callNewAPI($this->config->get('paytm_transaction_status_url'), $reqParams);

				if($resParams['STATUS'] == 'TXN_SUCCESS' && $resParams['TXNAMOUNT'] == $_POST['TXNAMOUNT']) {
					
					$authStatus = true;
									
					$this->load->model('checkout/order');
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paytm_order_success_status_id'));
					
					$data['continue'] = $this->url->link('checkout/success');

					if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm_success.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/extension/payment/paytm_success.tpl';
					} else {
						$this->template = 'extension/payment/paytm_success.tpl';
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
					
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paytm_order_failed_status_id'));

					$data['continue'] = $this->url->link('checkout/cart');

					if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm_failure.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/extension/payment/paytm_failure.tpl';
					} else {
						$this->template = 'extension/payment/paytm_failure.tpl';
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

				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paytm_order_failed_status_id'));
 

				// unset order id if it is set, so new order id could be generated by paytm for next txns
				if(isset($this->session->data['order_id']))
					unset($this->session->data['order_id']);

				$data['continue'] = $this->url->link('checkout/cart');

				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/paytm_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/extension/payment/paytm_failure.tpl';
				} else {
					$this->template = 'extension/payment/paytm_failure.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
	
				$this->response->setOutput($this->load->view($this->template,$data));
			}
		}
	}

	public function apply_promo_code(){
		if(isset($this->request->post["promo_code"]) && trim($this->request->post["promo_code"]) != "") {

			$json = array();

			// if promo code local validation enabled
			if($this->config->get("paytm_promo_code_validation")){

				$promo_code_found = false;

				// get all available promo codes
				if($promo_codes = $this->config->get("paytm_promo_codes")){


					foreach($promo_codes as $key=>$val){
						// entered promo code should matched
						// should be active
						// should be in start and end date range
						// plus 86400 to include entire day of expiry date
						if($val["code"] == $this->request->post["promo_code"] 
							&& $val["status"] == "1" 
							&& strtotime($val["start_date"]) <= strtotime("now") 
							&& (strtotime($val["end_date"]) +86400) >= strtotime("now")){
							$promo_code_found = true;
							break;
						}
					}
				}
			} else {
				$promo_code_found = true;
			}

			if($promo_code_found){
				$json = array("success" => true, "message" => "Applied Successfully");
				
				$reqParams = $this->request->post;

				if(isset($reqParams["promo_code"])){
					// PROMO_CAMP_ID is key for Promo Code at Paytm's end
					$reqParams["PROMO_CAMP_ID"] = $reqParams["promo_code"];
				
					// unset promo code sent in request	
					unset($reqParams["promo_code"]);

					// unset CHECKSUMHASH
					unset($reqParams["CHECKSUMHASH"]);
				}

				// create a new checksum with Param Code included and send it to browser
				require_once(DIR_SYSTEM . 'encdec_paytm.php');
				$json['CHECKSUMHASH'] = getChecksumFromArray($reqParams, $this->config->get("paytm_merchant_key"));
			} else {
				$json = array("success" => false, "message" => "Incorrect Promo Code");
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
	
	/**
	* check cURL working or able to communicate with Paytm 
	*/
	public function curltest(){
		// phpinfo();exit;
		$debug = array();
		if(!function_exists("curl_init")){
			$debug[0]["info"][] = "cURL extension is either not available or disabled. Check phpinfo for more info.";
		// if curl is enable then see if outgoing URLs are blocked or not
		} else {
			// if any specific URL passed to test for
			if(isset($this->request->get["url"]) && $this->request->get["url"] != ""){
				$testing_urls = array(urldecode($this->request->get["url"]));
			} else {
				// this site homepage URL
				$server = $this->request->server['HTTPS']? HTTPS_SERVER : HTTP_SERVER;
				$testing_urls = array(
					$server,
					"https://www.gstatic.com/generate_204",
					$this->config->get('paytm_transaction_status_url'));
			}
			// loop over all URLs, maintain debug log for each response received
			foreach($testing_urls as $key => $val){
				$debug[$key]["info"][] = "Connecting to <b>" . $val . "</b> using cURL";
				$ch = curl_init($val);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$res = curl_exec($ch);
				if (!curl_errno($ch)) {
					$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					$debug[$key]["info"][] = "cURL executed succcessfully.";
					$debug[$key]["info"][] = "HTTP Response Code: <b>". $http_code . "</b>";
				} else {
					$debug[$key]["info"][] = "Connection Failed !!";
					$debug[$key]["info"][] = "Error Code: <b>" . curl_errno($ch) . "</b>";
					$debug[$key]["info"][] = "Error: <b>" . curl_error($ch) . "</b>";
				}
				if(isset($this->request->get["url"]) && $this->request->get["url"] != ""){
					$debug[$key]["info"][] = $res;
				}
				curl_close($ch);
			}
		}
		foreach($debug as $k=>$v){
			echo "<ul>";
			foreach($v["info"] as $info){
				echo "<li>".$info."</li>";
			}
			echo "</ul>";
			echo "<hr/>";
		}
	}
}
?>