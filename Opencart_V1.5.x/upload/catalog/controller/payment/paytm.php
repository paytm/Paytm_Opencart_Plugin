<?php
class ControllerPaymentPaytm extends Controller {
	
	private $append_timestamp 		= true; /* prevent duplicate order id */
	private $save_paytm_response 	= true; /* save paytm response in db */
	private $max_retry_count 		= 3; /* number of retries untill cURL gets success */
	private $request_id				= 'OPENCART_' . VERSION;
	
	protected function index() 
	{
		require_once(DIR_SYSTEM . 'paytm/encdec_paytm.php');
		
		$this->load->language('payment/paytm');
		$this->load->model('payment/paytm');
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

		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		
		$parameters = array(
							"MID" 				=> $this->config->get('paytm_merchant_id'),
							"WEBSITE"			=> $this->config->get('paytm_website'),
							"INDUSTRY_TYPE_ID" 	=> $this->config->get('paytm_industry_type'),
							"CALLBACK_URL" 		=> $this->config->get('paytm_callback_url'),
							"ORDER_ID" 			=> $this->getPaytmOrderId($order_info['order_id']),
							"CHANNEL_ID" 		=> "WEB",
							"CUST_ID" 			=> $cust_id,
							"TXN_AMOUNT" 		=> $amount,
							"MOBILE_NO" 		=> $mobile_no,
							"EMAIL" 			=> $email,
						);

		$parameters["CHECKSUMHASH"] = PaytmPayment::getChecksumFromArray($parameters, $this->config->get('paytm_merchant_key'));
		
		if($this->request_id){
			$path = DIR_SYSTEM . "/paytm/paytm_version.txt";
			if(file_exists($path)){
				$handle = fopen($path, "r");
				if($handle !== false){
					$this->request_id .= '_' . fread($handle, 10); // i.e. DD-MM-YYYY or 25-04-2018
				}
			}			
			$parameters["X-REQUEST-ID"] 	=  $this->request_id;
		}
		
		$this->data['paytm_fields'] 	= $parameters;
		$this->data['action'] 			= $this->config->get('paytm_transaction_url');
		$this->data['button_confirm'] 	= $this->language->get('button_confirm');
	 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/paytm.tpl';
		} else {
			$this->template = 'default/template/payment/paytm.tpl';
		}
		$this->render();
	}
	
	/**
	* paytm sends response to callback
	*/
	public function callback(){

		require_once(DIR_SYSTEM . 'paytm/encdec_paytm.php');

		// load language and model
		$this->load->model('payment/paytm');
		$this->load->language('payment/paytm');
		
		$this->data['title'] 				= sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		$this->data['language']				= $this->language->get('code');
		$this->data['direction'] 			= $this->language->get('direction');
		$this->data['heading_title'] 		= sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		$this->data['text_success'] 		= $this->language->get('text_success');
		$this->data['text_success_wait'] 	= sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
		$this->data['text_failure'] 		= $this->language->get('text_failure');
		$this->data['text_failure_wait'] 	= sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

		if(isset($_POST['RESPMSG']) && !empty($_POST['RESPMSG'])){
			$this->data['text_response'] 	= sprintf($this->language->get('text_response'), $_POST['RESPMSG']);
		} else {
			$this->data['text_response'] 	= sprintf($this->language->get('text_response'), '');
		}
		/* save paytm response in db */
		if(!empty($_POST['STATUS']) && $this->save_paytm_response){
			$order_data_id = $this->model_payment_paytm->saveTxnResponse($_POST, $this->getOrderId($_POST['ORDERID']));
		}
		/* save paytm response in db */

		$isValidChecksum = PaytmPayment::verifychecksum_e($_POST, $this->config->get("paytm_merchant_key"), $_POST['CHECKSUMHASH']);

		if($isValidChecksum === true){

			$order_id = isset($_POST['ORDERID']) && !empty($_POST['ORDERID'])? $this->getOrderId($_POST['ORDERID']) : 0;
			
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);

			if($order_info) {

				if(isset($_POST['STATUS']) && $_POST['STATUS'] == "TXN_SUCCESS") {
				
					$reqParams = array(
										"MID" 		=> $this->config->get('paytm_merchant_id'),
										"ORDERID" 	=> $_POST['ORDERID']
									);
					
					$reqParams['CHECKSUMHASH'] = PaytmPayment::getChecksumFromArray($reqParams, $this->config->get("paytm_merchant_key"));
					
					/* number of retries untill cURL gets success */
					$retry = 1;
					do{
						$resParams = PaytmPayment::executecUrl($this->config->get('paytm_transaction_status_url'), $reqParams);
						$retry++;
					} while(!$resParams && $retry < $this->max_retry_count);
					/* number of retries untill cURL gets success */

					
					/* save paytm response in db */
					if(!empty($resParams['STATUS']) && $this->save_paytm_response){
						$this->model_payment_paytm->saveTxnResponse($resParams, $this->getOrderId($resParams['ORDERID']), $order_data_id);
					}
					/* save paytm response in db */

					// if curl failed to fetch response
					if(!isset($resParams['STATUS'])){

						$this->model_checkout_order->confirm($order_id, $this->config->get('paytm_order_failed_status_id'));

						// unset order id if it is set, so new order id could be generated
						if(isset($this->session->data['order_id']))
							unset($this->session->data['order_id']);

						$this->session->data['success'] = $this->language->get('error_server_communication');
						$this->fireFailure();

					} else {

						if($resParams['STATUS'] == 'TXN_SUCCESS' 
							&& $resParams['TXNAMOUNT'] == $_POST['TXNAMOUNT']) {

							//$comment = sprintf($this->language->get('text_transaction_id'), $resParams['TXNID']) .'<br/>'. sprintf($this->language->get('text_paytm_order_id'), $resParams['ORDERID']);

							$this->model_checkout_order->confirm($order_id, $this->config->get('paytm_order_success_status_id'));
							$this->fireSuccess();
						
						} else {
							
							$this->model_checkout_order->confirm($order_id, $this->config->get('paytm_order_failed_status_id'));

							$this->session->data['success'] = $this->language->get('text_failure');

							if($resParams['TXNAMOUNT'] != $_POST['TXNAMOUNT']) {
								$this->session->data['success'] = $this->language->get('error_amount_mismatch');
							} else if(isset($resParams['RESPMSG']) && !empty($resParams['RESPMSG'])){
								$this->session->data['success'] .= $this->language->get('text_reason').$resParams['RESPMSG'];
							}
							$this->fireFailure();
						}
					}

				} else {

					$this->model_checkout_order->confirm($order_id, $this->config->get('paytm_order_failed_status_id'));

					$this->session->data['success'] = $this->language->get('text_failure');
					if(isset($_POST['RESPMSG']) && !empty($_POST['RESPMSG'])){
						$this->session->data['success'] .= $this->language->get('text_reason').$_POST['RESPMSG'];
					}
					$this->fireFailure();
				}

			} else {
				$this->session->data['success'] = $this->language->get('error_invalid_order');
				$this->fireFailure();
			}

		} else {
			$this->session->data['success'] = $this->language->get('error_checksum_mismatch');
			$this->fireFailure();
		}
	}	
	/**
	* show template while success response 
	*/
	private function fireSuccess(){
		
		$this->data['continue'] = $this->url->link('checkout/success');
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytm_success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/paytm_success.tpl';
		} else {
			$this->template = 'default/template/payment/paytm_success.tpl';
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

	/**
	* show template while failure response 
	*/
	private function fireFailure(){

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

	/**
	* include timestap with order id
	*/
	private function getPaytmOrderId($order_id){
		if($order_id && $this->append_timestamp){
			return $order_id . '_' . date("YmdHis");
		}else{
			return $order_id;
		}
	}
	/**
	* exclude timestap with order id
	*/
	private function getOrderId($order_id){		
		if(($pos = strrpos($order_id, '_')) !== false && $this->append_timestamp) {
			$order_id = substr($order_id, 0, $pos);
		}
		return $order_id;
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