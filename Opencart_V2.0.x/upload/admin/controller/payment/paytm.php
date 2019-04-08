<?php
class ControllerPaymentPaytm extends Controller {

	private $error 					= array();
	private $save_paytm_response 	= true; /* save paytm response in db */
	private $max_retry_count 		= 3; /* number of retries untill cURL gets success */

	/**
	* create `paytm_order_data` table and install this module.
	*/
	public function install() {
		$this->load->model('payment/paytm');
		$this->model_payment_paytm->install();
	}
	/**
	* drop `paytm_order_data` table and uninstall this module.
	*/
	public function uninstall() {
		$this->load->model('payment/paytm');
		$this->model_payment_paytm->uninstall();
	}
	/**
	* get Default callback url
	*/
	private function getCallbackUrl(){
		$callback_url = "index.php?route=payment/paytm/callback";
		return (!empty($_SERVER['HTTPS']))? HTTPS_CATALOG . $callback_url : HTTP_CATALOG . $callback_url;
	}

	public function index() {
		require_once(DIR_SYSTEM . 'paytm/encdec_paytm.php');
		
		$this->language->load('payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));
			
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paytm', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
		
			if(!$this->validateCurl($this->request->post['paytm_transaction_status_url'])){
				$this->session->data['warning'] = $this->language->get('error_curl_warning');
				$this->response->redirect($this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL'));
			}

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] 					= $this->language->get('heading_title');
		
		$data['tab_general'] 					= $this->language->get('tab_general');
		$data['tab_order_status'] 				= $this->language->get('tab_order_status');
		$data['tab_promo_code'] 				= $this->language->get('tab_promo_code');

		$data['text_all_zones'] 				= $this->language->get('text_all_zones');
		$data['text_enabled'] 					= $this->language->get('text_enabled');
		$data['text_disabled'] 					= $this->language->get('text_disabled');
		$data['text_next'] 						= $this->language->get('text_next');

		$data['text_opencart_version'] 		= $this->language->get('text_opencart_version');
		$data['text_curl_version'] 			= $this->language->get('text_curl_version');
		$data['text_php_version'] 				= $this->language->get('text_php_version');
		$data['text_last_updated'] 			= $this->language->get('text_last_updated');
		$data['text_curl_disabled'] 			= $this->language->get('text_curl_disabled');

		$data['entry_merchant_id'] 			= $this->language->get('entry_merchant_id');
		$data['entry_merchant_id_help'] 		= $this->language->get('entry_merchant_id_help');

		$data['entry_merchant_key'] 			= $this->language->get('entry_merchant_key');
		$data['entry_merchant_key_help'] 	= $this->language->get('entry_merchant_key_help');
		
		$data['entry_website'] 					= $this->language->get('entry_website');
		$data['entry_website_help'] 			= $this->language->get('entry_website_help');
		
		$data['entry_industry_type'] 			= $this->language->get('entry_industry_type');
		$data['entry_industry_type_help'] 	= $this->language->get('entry_industry_type_help');

		$data['entry_transaction_url'] 		= $this->language->get('entry_transaction_url');
		$data['entry_transaction_url_help'] = $this->language->get('entry_transaction_url_help');

		$data['entry_transaction_status_url'] = $this->language->get('entry_transaction_status_url');
		$data['entry_transaction_status_url_help'] = $this->language->get('entry_transaction_status_url_help');

		$data['entry_callback_url_status'] 	= $this->language->get('entry_callback_url_status');
		$data['entry_callback_url_status_help'] = $this->language->get('entry_callback_url_status_help');

		$data['entry_callback_url'] 			= $this->language->get('entry_callback_url');
		$data['entry_callback_url_help'] 	= $this->language->get('entry_callback_url_help');

		$data['entry_order_success_status'] = $this->language->get('entry_order_success_status');
		$data['entry_order_success_status_help'] = $this->language->get('entry_order_success_status_help');
		
		$data['entry_order_failed_status'] 	= $this->language->get('entry_order_failed_status');
		$data['entry_order_failed_status_help'] = $this->language->get('entry_order_failed_status_help');

		$data['entry_total'] 						= $this->language->get('entry_total');	
		$data['entry_total_help'] 					= $this->language->get('entry_total_help');	
		$data['entry_geo_zone'] 					= $this->language->get('entry_geo_zone');
		$data['entry_status'] 						= $this->language->get('entry_status');
		$data['entry_status_help'] 				= $this->language->get('entry_status_help');
		$data['entry_sort_order'] 					= $this->language->get('entry_sort_order');


		$data['entry_promo_code'] 					= $this->language->get('entry_promo_code');
		$data['entry_promo_code_help1'] 			= $this->language->get('entry_promo_code_help1');
		$data['entry_promo_code_status'] 		= $this->language->get('entry_promo_code_status');
		$data['entry_promo_code_status_help1'] = $this->language->get('entry_promo_code_status_help1');
		$data['entry_promo_code_availability'] = $this->language->get('entry_promo_code_availability');
		$data['entry_promo_code_validation'] 	= $this->language->get('entry_promo_code_validation');
		$data['entry_promo_code_validation_help1'] = $this->language->get('entry_promo_code_validation_help1');
		$data['entry_promo_code_validation_help2'] = $this->language->get('entry_promo_code_validation_help2');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['button_promo_code_add'] = $this->language->get('button_promo_code_add');
		$data['button_promo_code_remove'] = $this->language->get('button_promo_code_remove');
		$data['entry_promo_code_start_date'] = $this->language->get('entry_promo_code_start_date');
		$data['entry_promo_code_end_date'] = $this->language->get('entry_promo_code_end_date');

		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant_id'])) {
			$data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}
		if (isset($this->error['merchant_key'])) {
			$data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$data['error_merchant_key'] = '';
		}
		if (isset($this->error['website'])) {
			$data['error_website'] = $this->error['website'];
		} else {
			$data['error_website'] = '';
		}
		
		if (isset($this->error['industry_type'])) {
			$data['error_industry_type'] = $this->error['industry_type'];
		} else {
			$data['error_industry_type'] = '';
		}
		
		if (isset($this->error['transaction_url'])) {
			$data['error_transaction_url'] = $this->error['transaction_url'];
		} else {
			$data['error_transaction_url'] = '';
		}
		
		if (isset($this->error['transaction_status_url'])) {
			$data['error_transaction_status_url'] = $this->error['transaction_status_url'];
		} else {
			$data['error_transaction_status_url'] = '';
		}
		
		if (isset($this->error['callback_url_status'])) {
			$data['error_callback_url_status'] = $this->error['callback_url_status'];
		} else {
			$data['error_callback_url_status'] = '';
		}
		
		if (isset($this->error['callback_url'])) {
			$data['error_callback_url'] = $this->error['callback_url'];
		} else {
			$data['error_callback_url'] = '';
		}

		if (isset($this->error['promo_codes'])) {
			$data['error_promo_codes'] = $this->error['promo_codes'];
		} else {
			$data['error_promo_codes'] = array();
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL'),
   		);

		$data['action'] = $this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');


		if (isset($this->request->post['paytm_merchant_id'])) {
			$data['paytm_merchant_id'] = $this->request->post['paytm_merchant_id'];
		} else {
			$data['paytm_merchant_id'] = $this->config->get('paytm_merchant_id');
		}

		if (isset($this->request->post['paytm_merchant_key'])) {
			$data['paytm_merchant_key'] = $this->request->post['paytm_merchant_key'];
		} else {
			$data['paytm_merchant_key'] = $this->config->get('paytm_merchant_key');
		}
		
		if (isset($this->request->post['paytm_website'])) {
			$data['paytm_website'] = $this->request->post['paytm_website'];
		} else {
			$data['paytm_website'] = $this->config->get('paytm_website');
		}

		if (isset($this->request->post['paytm_industry_type'])) {
			$data['paytm_industry_type'] = $this->request->post['paytm_industry_type'];
		} else {
			$data['paytm_industry_type'] = $this->config->get('paytm_industry_type');
		}
	
		if (isset($this->request->post['paytm_transaction_url'])) {
			$data['paytm_transaction_url'] = $this->request->post['paytm_transaction_url'];
		} else {
			$data['paytm_transaction_url'] = $this->config->get('paytm_transaction_url');
		}
	
		if (isset($this->request->post['paytm_transaction_status_url'])) {
			$data['paytm_transaction_status_url'] = $this->request->post['paytm_transaction_status_url'];
		} else {
			$data['paytm_transaction_status_url'] = $this->config->get('paytm_transaction_status_url');
		}
		
		if (isset($this->request->post['paytm_callback_url_status'])) {
			$data['paytm_callback_url_status'] = $this->request->post['paytm_callback_url_status'];
		} else if($this->config->get('paytm_callback_url_status')){
			$data['paytm_callback_url_status'] = $this->config->get('paytm_callback_url_status');
		} else {
			$data['paytm_callback_url_status'] = "0";
		}

		$data["default_callback_url"] = $this->getCallbackUrl();

		if (isset($this->request->post['paytm_callback_url_status']) && $this->request->post['paytm_callback_url_status'] == 1) {
			$data['paytm_callback_url'] = $this->request->post['paytm_callback_url'];
		} else if($this->config->get('paytm_callback_url')) {
			$data['paytm_callback_url'] = $this->config->get('paytm_callback_url');
		} else {
			$data['paytm_callback_url'] = $data["default_callback_url"];
		}


		if (isset($this->request->post['paytm_order_success_status_id'])) {
			$data['paytm_order_success_status_id'] = $this->request->post['paytm_order_success_status_id'];
		} else {
			$data['paytm_order_success_status_id'] = $this->config->get('paytm_order_success_status_id');
		}

		if (isset($this->request->post['paytm_order_failed_status_id'])) {
			$data['paytm_order_failed_status_id'] = $this->request->post['paytm_order_failed_status_id'];
		} else {
			$data['paytm_order_failed_status_id'] = $this->config->get('paytm_order_failed_status_id');
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paytm_total'])) {
			$data['paytm_total'] = $this->request->post['paytm_total'];
		} else {
			$data['paytm_total'] = $this->config->get('paytm_total');
		}
		if (isset($this->request->post['paytm_geo_zone_id'])) {
			$data['paytm_geo_zone_id'] = $this->request->post['paytm_geo_zone_id'];
		} else {
			$data['paytm_geo_zone_id'] = $this->config->get('paytm_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paytm_status'])) {
			$data['paytm_status'] = $this->request->post['paytm_status'];
		} else {
			$data['paytm_status'] = $this->config->get('paytm_status');
		}

		if (isset($this->request->post['paytm_sort_order'])) {
			$data['paytm_sort_order'] = $this->request->post['paytm_sort_order'];
		} else {
			$data['paytm_sort_order'] = $this->config->get('paytm_sort_order');
		}

		if (isset($this->request->post['paytm_promo_code_status'])) {
			$data['paytm_promo_code_status'] = $this->request->post['paytm_promo_code_status'];
		} else if($this->config->get('paytm_promo_code_status') != null) {
			$data['paytm_promo_code_status'] = $this->config->get('paytm_promo_code_status');
		} else {
			// keep this disable at fresh installation
			$data['paytm_promo_code_status'] = "0";
		}		

		if (isset($this->request->post['paytm_promo_code_validation'])) {
			$data['paytm_promo_code_validation'] = $this->request->post['paytm_promo_code_validation'];
		} else if($this->config->get('paytm_promo_code_validation') != null) {
			$data['paytm_promo_code_validation'] = $this->config->get('paytm_promo_code_validation');
		} else {
			// keep this enable at fresh installation
			$data['paytm_promo_code_validation'] = "1";
		}

		if (isset($this->request->post['paytm_promo_codes'])) {
			$data['paytm_promo_codes'] = $this->request->post['paytm_promo_codes'];
		} else if($this->config->get('paytm_promo_codes')) {
			$data['paytm_promo_codes'] = $this->config->get('paytm_promo_codes');
		} else {
			$data['paytm_promo_codes'] = array();
		}

		$data['last_updated'] = "";
		$path = DIR_SYSTEM . "/paytm/paytm_version.txt";
		if(file_exists($path)){
			$handle = fopen($path, "r");
			if($handle !== false){
				$date = fread($handle, 10); // i.e. DD-MM-YYYY or 25-04-2018
				$data['last_updated'] = date("d F Y", strtotime($date));
			}
		}
		// Check cUrl is enabled or not
		if(function_exists('curl_version')){
			$data['curl_version'] = (!empty($curl_ver_array = curl_version()) && $curl_ver_array['version']) ? $curl_ver_array['version']:'';
		}else{
			$data['curl_version'] = '';
		}		

		$data['opencart_version'] = VERSION;
		$data['php_version'] = PHP_VERSION;


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/paytm.tpl', $data));
	}
	/**
	* create tab with paytm response at Order Detail page
	*/
	public function order() {
		if ($this->config->get('paytm_status')) {
			$this->load->model('payment/paytm');
			$this->load->language('payment/paytm');

			if(!empty($order_id = $this->request->get['order_id'])){			
				$paytm_order_data = $this->model_payment_paytm->getPaytmOrderData($order_id);
				$data = array();
				$data['token'] = $this->session->data['token'];
				$data['button_fetch_status'] = $this->language->get('button_fetch_status');
				if($paytm_order_data){
					$data['transaction_id']			= $paytm_order_data['transaction_id'];
					$data['paytm_order_id']			= $paytm_order_data['paytm_order_id'];
					$data['order_data_id']			= $paytm_order_data['id'];
					$data['paytm_response'] 		= json_decode($paytm_order_data['paytm_response'],true);
					return $this->load->view('payment/paytm_order.tpl', $data);
				}
			}
		}
	}

	/**
	* ajax - fetch and save transaction status in db
	*/
	public function savetxnstatus() {
		require_once(DIR_SYSTEM . 'paytm/encdec_paytm.php');

		$this->load->model('payment/paytm');
		$this->load->language('payment/paytm');

		$json = array("success" => false, "response" => '', 'message' => $this->language->get('text_response_error'));

		if(!empty($this->request->post['paytm_order_id'])){
				$reqParams = array(
					"MID" 		=> $this->config->get('paytm_merchant_id'),
					"ORDERID" 	=> $this->request->post['paytm_order_id']
				);

				$reqParams['CHECKSUMHASH'] = PaytmPayment::getChecksumFromArray($reqParams, $this->config->get("paytm_merchant_key"));
					
				$retry = 1;
				do{
					$resParams = PaytmPayment::executecUrl($this->config->get('paytm_transaction_status_url'), $reqParams);
					$retry++;
				} while(!$resParams && $retry < $this->max_retry_count);

				if($this->save_paytm_response && !empty($resParams['STATUS'])){
					$update_response	=	$this->model_payment_paytm->saveTxnResponse($resParams, $this->request->post['order_data_id']); 
					if($update_response){
						$message = $this->language->get('text_response_success');
						if($resParams['STATUS'] != 'PENDING'){
							$message .= sprintf($this->language->get('text_response_status_success'), $resParams['STATUS']);
						}						
						$json = array("success" => true, "response" => $update_response, 'message' => $message);
					}
				}
		}		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	/**
	* get paytm response by order id from `paytm_order_data`
	*/
	public function getorder(){
		if(!empty($this->request->get['order_id'])){
			$this->load->model('payment/paytm');
			$results = $this->model_payment_paytm->getPaytmOrderData($this->request->get['order_id']);
			$this->response->setOutput(json_encode($results));
		}
	}
	/**
	* check and test cURL is working or able to communicate properly with paytm
	*/
	private function validateCurl($paytm_transaction_status_url = ''){		
		if(!empty($paytm_transaction_status_url) && function_exists("curl_init")){
			$ch 	= curl_init(trim($paytm_transaction_status_url));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$res 	= curl_exec($ch);
			curl_close($ch);
			return $res !== false;
		}
		return false;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// trim all values except for Promo Codes
		foreach($this->request->post as $key=>&$val){
			if($key == "paytm_promo_codes"){
				foreach($val as $k=>&$code) {
					$v["code"] = trim($code["code"]);
					if($code["code"] == ""){
						$this->error['promo_codes'][$k]['promo_code'] = $this->language->get('error_promo_code');
					}

					if($code["start_date"] == ""){
						$this->error['promo_codes'][$k]['start_date'] = $this->language->get('error_start_date');
					}

					if($code["end_date"] == ""){
						$this->error['promo_codes'][$k]['end_date'] = $this->language->get('error_end_date');
					} else if(strtotime($code["end_date"]) < strtotime($code["start_date"])){
						$this->error['promo_codes'][$k]['end_date'] = $this->language->get('error_invalid_end_date');
					}
	
				}
			} else {
				$val = trim($val);
			}
		}

		if (!$this->request->post['paytm_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}
		if (!$this->request->post['paytm_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}
		if (!$this->request->post['paytm_website']) {
			$this->error['website'] = $this->language->get('error_website');
		}
		if (!$this->request->post['paytm_industry_type']) {
			$this->error['industry_type'] = $this->language->get('error_industry_type');
		}
		if (!$this->request->post['paytm_transaction_url']) {
			$this->error['transaction_url'] = $this->language->get('error_transaction_url');
		}
		if (!$this->request->post['paytm_transaction_status_url']) {
			$this->error['transaction_status_url'] = $this->language->get('error_transaction_status_url');
		}
		if (!$this->request->post['paytm_callback_url']) {
			$this->error['callback_url'] = $this->language->get('error_callback_url');
		} else {
			$url_parts = parse_url($this->request->post['paytm_callback_url']);
			if(!isset($url_parts["scheme"]) || (strtolower($url_parts["scheme"]) != "http" && strtolower($url_parts["scheme"]) != "https") || !isset($url_parts["host"]) || $url_parts["host"] == ""){
				$this->error['callback_url'] = $this->language->get('error_valid_callback_url');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}