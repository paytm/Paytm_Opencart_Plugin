<?php

require_once(DIR_SYSTEM . 'paytm/PaytmHelper.php');
require_once(DIR_SYSTEM . 'paytm/PaytmChecksum.php');

class ControllerExtensionPaymentPaytm extends Controller {

	private $error 					= array();
	
	/**
	* create `paytm_order_data` table and install this module.
	*/
	public function install() {
		$this->load->model('extension/payment/paytm');
		$this->model_extension_payment_paytm->install();
	}

	/**
	* drop `paytm_order_data` table and uninstall this module.
	*/
	public function uninstall() {
		$this->load->model('extension/payment/paytm');
		$this->model_extension_payment_paytm->uninstall();
	}

	public function index() {

		// load all language variables
		$data = $this->load->language('extension/payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));
			
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if(!PaytmHelper::validateCurl(PaytmHelper::getPaytmURL(PaytmConstants::ORDER_STATUS_URL, $this->request->post['paytm_environment']))){
				$this->session->data['warning'] = $this->language->get('error_curl_warning');
				$this->response->redirect($this->url->link('extension/payment/paytm', 'token=' . $this->session->data['token'], true));
			}

			//webhook
			if (isset($this->request->post['paytm_is_webhook_triggered'])) {
				if($this->request->post['paytm_is_webhook_triggered']==1){
					if($this->request->post['paytm_webhook']==1){
						$webhookUrl = $this->language->get('base_url_for_paytm_webhook');
					}else{
						$webhookUrl = "https://www.dummyUrl.com";
					}
					if($this->request->post['paytm_environment']==1){
						$url = $this->language->get('WEBHOOK_PRODUCTION_URL');
						
					}else{
						$url = $this->language->get('WEBHOOK_STAGING_URL');
					}
	                $paytmParams = array(
	                            "mid"       => $this->request->post['paytm_merchant_id'],
	                            "queryParam" => "notificationUrls",
	                            "paymentNotificationUrl" => $webhookUrl
	                          );
	                $paytmParamsJson = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
	                $generateSignature = PaytmChecksum::generateSignature($paytmParamsJson, $this->config->get('paytm_merchant_key'));

	                $curl = curl_init();

	                curl_setopt_array($curl, array(
	                CURLOPT_URL => $url.'api/v1/external/putMerchantInfo', 
	                CURLOPT_RETURNTRANSFER => true,
	                CURLOPT_ENCODING => '',
	                CURLOPT_MAXREDIRS => 10,
	                CURLOPT_TIMEOUT => 0,
	                CURLOPT_FOLLOWLOCATION => true,
	                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	                CURLOPT_CUSTOMREQUEST => 'PUT',
	                CURLOPT_POSTFIELDS =>$paytmParamsJson,
	                CURLOPT_HTTPHEADER => array(
	                        'Content-Type: application/json',
	                        'x-checksum: '.$generateSignature.''
	                    ),
	                ));

	                $response = curl_exec($curl);               
                 	$res = (array)json_decode($response); 
	                if (!empty($res ) && isset($res['success'])) {
					      $this->session->data['warning'] ='';
				    } elseif (isset($res['E_400'])) {
			              $this->session->data['warning'] ='';
				    }else if(isset($res['BO_411'])){
				    	  $this->session->data['warning'] = "Something went wrong while configuring webhook. Please login to Paytm Dashboard to configure.";
			    	}else {
					      $this->session->data['warning'] = "Something went wrong while configuring webhook. Please login to Paytm Dashboard to configure.";
				    }   
				}
			}

			if($this->session->data['warning'] !=''){
				$this->response->redirect($this->url->link('extension/payment/paytm', 'token=' . $this->session->data['token'], true));
			}

			$this->request->post = array_map('trim', $this->request->post);
			$this->model_setting_setting->editSetting('paytm', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}
		
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

		if (isset($this->error['environment'])) {
			$data['error_environment'] = $this->error['environment'];
		} else {
			$data['error_environment'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_home'),
			'href'	  => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_extension'),
			'href'	  => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_payments'),
			'href'	  => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true),
		);

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('heading_title'),
			'href'	  => $this->url->link('extension/payment/paytm', 'token=' . $this->session->data['token'], true),
		);

		$data['action'] = $this->url->link('extension/payment/paytm', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);


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

		if (isset($this->request->post['paytm_environment'])) {
			$data['paytm_environment'] = $this->request->post['paytm_environment'];
		} else if ($this->config->get('paytm_environment')) {
			$data['paytm_environment'] = $this->config->get('paytm_environment');
		}else{
			$data['paytm_environment'] = 0;
		}

		if (isset($this->request->post['paytm_order_success_status_id'])) {
			$data['paytm_order_success_status_id'] = $this->request->post['paytm_order_success_status_id'];
		} else {
			$data['paytm_order_success_status_id'] = $this->config->get('paytm_order_success_status_id');
		}

		if (isset($this->request->post['paytm_order_pending_status_id'])) {
			$data['paytm_order_pending_status_id'] = $this->request->post['paytm_order_pending_status_id'];
		} else {
			$data['paytm_order_pending_status_id'] = $this->config->get('paytm_order_pending_status_id');
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

		
		//webhook
		if (isset($this->request->post['paytm_webhook'])) {
			$data['paytm_webhook'] = $this->request->post['paytm_webhook'];
		} else {
			$data['paytm_webhook'] = (int)$this->config->get('paytm_webhook');
		}	

		// Check cUrl is enabled or not
		$data['curl_version'] = PaytmHelper::getcURLversion();

		if(empty($data['curl_version'])){
			$data['error_warning'] = $this->language->get('text_curl_disabled');
		}

		$data['last_updated'] 		= date("d F Y", strtotime(PaytmConstants::LAST_UPDATED)) .' - '.PaytmConstants::PLUGIN_VERSION;
		$data['opencart_version'] 	= VERSION;
		$data['php_version'] 		= PHP_VERSION;
		
		$data['header'] 			= $this->load->controller('common/header');
		$data['column_left'] 		= $this->load->controller('common/column_left');
		$data['footer'] 			= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paytm', $data));
	}

	/**
	* create tab with paytm response at Order Detail page
	*/
	public function order() {
		if ($this->config->get('paytm_status')) {
			$this->load->model('extension/payment/paytm');
			$this->load->language('extension/payment/paytm');

			if(!empty($this->request->get['order_id'])){			
				$paytm_order_data = $this->model_extension_payment_paytm->getPaytmOrderData($this->request->get['order_id']);
				$data = array();
				$data['token'] = $this->session->data['token'];
				$data['savePaytmResponse'] = PaytmConstants::SAVE_PAYTM_RESPONSE;
				$data['button_fetch_status'] = $this->language->get('button_fetch_status');
				if($paytm_order_data){
					$data['transaction_id']			= $paytm_order_data['transaction_id'];
					$data['paytm_order_id']			= $paytm_order_data['paytm_order_id'];
					$data['order_data_id']			= $paytm_order_data['id'];
					$data['paytm_response'] 		= json_decode($paytm_order_data['paytm_response'],true);
					return $this->load->view('extension/payment/paytm_order', $data);
				}
			}
		}
	}

	/**
	* ajax - fetch and save transaction status in db
	*/
	public function savetxnstatus() {

		$this->load->model('extension/payment/paytm');
		$this->load->language('extension/payment/paytm');

		$json = array("success" => false, "response" => '', 'message' => $this->language->get('text_response_error'));

		if(!empty($this->request->post['paytm_order_id']) && PaytmConstants::SAVE_PAYTM_RESPONSE){
				$reqParams = array(
					"MID" 		=> $this->config->get('paytm_merchant_id'),
					"ORDERID" 	=> $this->request->post['paytm_order_id']
				);

				$reqParams['CHECKSUMHASH'] = PaytmChecksum::generateSignature($reqParams, $this->config->get("paytm_merchant_key"));
					
				$retry = 1;
				do{
					$resParams = PaytmHelper::executecUrl(PaytmHelper::getPaytmURL(PaytmConstants::ORDER_STATUS_URL, $this->request->post['paytm_environment']), $reqParams);
					$retry++;
				} while(!$resParams['STATUS'] && $retry < PaytmConstants::MAX_RETRY_COUNT);

				if(!empty($resParams['STATUS'])){
					$response	=	$this->model_extension_payment_paytm->saveTxnResponse($resParams, $this->request->post['order_data_id']); 
					if($response){
						$message = $this->language->get('text_response_success');					
						$json = array("success" => true, "response" => $response, 'message' => $message);
					}
				}
		}		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {

		$this->request->post = array_map('trim', $this->request->post);
		
		if (!$this->user->hasPermission('modify', 'extension/payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
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

		/*if (!$this->request->post['paytm_industry_type']) {
			$this->error['industry_type'] = $this->language->get('error_industry_type');
		}*/

		if (!in_array($this->request->post['paytm_environment'], array("1","0"))) {
			$this->error['environment'] = $this->language->get('error_environment');
		}

		if(PaytmHelper::getcURLversion() == false){
			$this->error['warning'] = $this->language->get('text_curl_disabled');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}