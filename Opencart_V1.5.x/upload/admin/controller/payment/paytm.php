<?php
require_once(DIR_SYSTEM . 'paytm/PaytmHelper.php');
require_once(DIR_SYSTEM . 'paytm/PaytmChecksum.php');
class ControllerPaymentPaytm extends Controller {
	
	private $error 					= array();

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

	public function index() {

		$this->language->load('payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post = array_map('trim', $this->request->post);
			$this->model_setting_setting->editSetting('paytm', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			if(!PaytmHelper::validateCurl(PaytmHelper::getPaytmURL(PaytmConstants::ORDER_STATUS_URL, $this->request->post['paytm_environment']))){
				$this->session->data['warning'] = $this->language->get('error_curl_warning');
				$this->redirect($this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL'));
			}

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		// load all language variables
		$this->data = $this->load->language('payment/paytm');
		
		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['merchant_id'])) {
			$this->data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$this->data['error_merchant_id'] = '';
		}

		if (isset($this->error['merchant_key'])) {
			$this->data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$this->data['error_merchant_key'] = '';
		}

		if (isset($this->error['website'])) {
			$this->data['error_website'] = $this->error['website'];
		} else {
			$this->data['error_website'] = '';
		}
		
		if (isset($this->error['industry_type'])) {
			$this->data['error_industry_type'] = $this->error['industry_type'];
		} else {
			$this->data['error_industry_type'] = '';
		}

		if (isset($this->error['environment'])) {
			$this->data['error_environment'] = $this->error['environment'];
		} else {
			$this->data['error_environment'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['paytm_merchant_id'])) {
			$this->data['paytm_merchant_id'] = $this->request->post['paytm_merchant_id'];
		} else {
			$this->data['paytm_merchant_id'] = $this->config->get('paytm_merchant_id');
		}

		if (isset($this->request->post['paytm_merchant_key'])) {
			$this->data['paytm_merchant_key'] = $this->request->post['paytm_merchant_key'];
		} else {
			$this->data['paytm_merchant_key'] = $this->config->get('paytm_merchant_key');
		}
		
		if (isset($this->request->post['paytm_website'])) {
			$this->data['paytm_website'] = $this->request->post['paytm_website'];
		} else {
			$this->data['paytm_website'] = $this->config->get('paytm_website');
		}
		
		if (isset($this->request->post['paytm_industry_type'])) {
			$this->data['paytm_industry_type'] = $this->request->post['paytm_industry_type'];
		} else {
			$this->data['paytm_industry_type'] = $this->config->get('paytm_industry_type');
		}
		
		if (isset($this->request->post['paytm_environment'])) {
			$this->data['paytm_environment'] = $this->request->post['paytm_environment'];
		} else if ($this->config->get('paytm_environment')) {
			$this->data['paytm_environment'] = $this->config->get('paytm_environment');
		}else{
			$this->data['paytm_environment'] = 0;
		}
		
		if (isset($this->request->post['paytm_order_success_status_id'])) {
			$this->data['paytm_order_success_status_id'] = $this->request->post['paytm_order_success_status_id'];
		} else {
			$this->data['paytm_order_success_status_id'] = $this->config->get('paytm_order_success_status_id');
		}

		if (isset($this->request->post['paytm_order_failed_status_id'])) {
			$this->data['paytm_order_failed_status_id'] = $this->request->post['paytm_order_failed_status_id'];
		} else {
			$this->data['paytm_order_failed_status_id'] = $this->config->get('paytm_order_failed_status_id');
		}
		
		if (isset($this->request->post['paytm_order_status_id'])) {
			$this->data['paytm_order_status_id'] = $this->request->post['paytm_order_status_id'];
		} else {
			$this->data['paytm_order_status_id'] = $this->config->get('paytm_order_status_id');
		}
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paytm_total'])) {
			$this->data['paytm_total'] = $this->request->post['paytm_total'];
		} else {
			$this->data['paytm_total'] = $this->config->get('paytm_total'); 
		}
		
		if (isset($this->request->post['paytm_geo_zone_id'])) {
			$this->data['paytm_geo_zone_id'] = $this->request->post['paytm_geo_zone_id'];
		} else {
			$this->data['paytm_geo_zone_id'] = $this->config->get('paytm_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paytm_status'])) {
			$this->data['paytm_status'] = $this->request->post['paytm_status'];
		} else {
			$this->data['paytm_status'] = $this->config->get('paytm_status');
		}

		if (isset($this->request->post['paytm_sort_order'])) {
			$this->data['paytm_sort_order'] = $this->request->post['paytm_sort_order'];
		} else {
			$this->data['paytm_sort_order'] = $this->config->get('paytm_sort_order');
		}

		// Check cUrl is enabled or not
		$this->data['curl_version']		= PaytmHelper::getcURLversion();

		if(empty($this->data['curl_version'])){
			$this->data['error_warning']	= $this->language->get('text_curl_disabled');
		}

		$this->data['last_updated']		= date("d F Y", strtotime(PaytmConstants::LAST_UPDATED)) .' - '.PaytmConstants::PLUGIN_VERSION;
		$this->data['opencart_version']	= VERSION;
		$this->data['php_version']		= PHP_VERSION;
		$this->template = 'payment/paytm.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
       
		$this->response->setOutput($this->render());	
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
	
	//validate function to ensure required fields are filled before proceeding
	protected function validate() {

		$this->request->post = array_map('trim', $this->request->post);

		if (!$this->user->hasPermission('modify', 'payment/paytm')) {
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

		if (!$this->request->post['paytm_industry_type']) {
			$this->error['industry_type'] = $this->language->get('error_industry_type');
		}

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
?>