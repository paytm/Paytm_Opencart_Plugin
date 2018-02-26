<?php
/**
 * @package		OpenCart
 * @author		Meng Wenbin
 * @copyright	Copyright (c) 2010 - 2017, Chengdu Guangda Network Technology Co. Ltd. (https://www.opencart.cn/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.cn
 */

class ControllerExtensionPaymentPaytm extends Controller {
	private $error = array();

	public function index() {
		require_once(DIR_SYSTEM . 'encdec_paytm.php');
		require_once(DIR_SYSTEM . 'paytm_constants.php');
		$this->load->language('extension/payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$arr = array();	
				//echo "<pre>"; print_r($this->config);
		foreach($this->request->post as $key => $value)
		{
			if($key == 'payment_paytm_merchant2')
			{				
				 $arr[$key] = encrypt_e($value, $const1);		
				 continue;
			}
			$arr[$key] = $value;
		} 
		

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paytm', $arr);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_merchant_help'] = $this->language->get('entry_merchant_help');
		$data['entry_merchantkey'] = $this->language->get('entry_merchantkey');
		$data['entry_merchantkey_help'] = $this->language->get('entry_merchantkey_help');
		$data['entry_industry'] = $this->language->get('entry_industry');
		$data['entry_industry_help'] = $this->language->get('entry_industry_help');
		/*$data['entry_environment'] = $this->language->get('entry_environment');
		$data['entry_environment_help'] = $this->language->get('entry_environment_help');*/
		$data['entry_transaction_url'] = $this->language->get('entry_transaction_url');
		$data['entry_transaction_url_help'] = $this->language->get('entry_transaction_url_help');
		$data['entry_transaction_url_status'] = $this->language->get('entry_transaction_url_status');
		$data['entry_transaction_url_status_help'] = $this->language->get('entry_transaction_url_status_help');
		$data['entry_website'] = $this->language->get('entry_website');
		$data['entry_website_help'] = $this->language->get('entry_website_help');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_checkstatus'] = $this->language->get('entry_checkstatus');
		$data['callbackurl_status'] = $this->language->get('callbackurl_status');
		$data['entry_checkstatus_help'] = $this->language->get('entry_checkstatus_help');
		$data['entry_total'] = $this->language->get('entry_total');
        $data['help_total'] = $this->language->get('help_total');                
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}
		
		if (isset($this->error['merchant2'])) {
			$data['error_merchant'] = $this->error['merchant2'];
		} else {
			$data['error_merchant'] = '';
		}
		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}
		
		if (isset($this->error['industry'])) {
			$data['error_industry'] = $this->error['industry'];
		} else {
			$data['error_industry'] = '';
		}
		
		if (isset($this->error['website'])) {
			$data['error_website'] = $this->error['website'];
		} else {
			$data['error_website'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/paytm', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/paytm', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		
		if (isset($this->request->post['payment_paytm_merchant'])) {
			$data['payment_paytm_merchant'] = $this->request->post['payment_paytm_merchant'];
		} else {
			$data['payment_paytm_merchant'] = $this->config->get('payment_paytm_merchant');
		}
		
		if (isset($this->request->post['payment_paytm_merchant2'])) {
			$data['payment_paytm_merchant2'] = $this->request->post['payment_paytm_merchant2'];
		} else {
			//$data['payment_paytm_merchant2'] = $this->config->get('payment_paytm_merchant2');
			$data['payment_paytm_merchant2'] = htmlspecialchars_decode(decrypt_e($this->config->get('payment_paytm_merchant2'),$const1),ENT_NOQUOTES);
		}
		
		/* if (isset($this->request->post['payment_paytm_key'])) {
		
			$data['payment_paytm_key'] = $this->request->post['payment_paytm_key'];
		} else {
			$data['payment_paytm_key'] = $this->config->get('payment_paytm_key');

			$data['payment_paytm_key'] = "";
			if ($this->config->get('payment_paytm_key') != "") {
				$data['payment_paytm_key'] = htmlspecialchars_decode(decrypt_e($this->config->get('payment_paytm_key'),$const1),ENT_NOQUOTES);
			}
			
		} */
		
		if (isset($this->request->post['payment_paytm_status'])) {
			$data['payment_paytm_status'] = $this->request->post['payment_paytm_status'];
		} else {
			$data['payment_paytm_status'] = $this->config->get('payment_paytm_status');
		}	
		
		if (isset($this->request->post['payment_paytm_industry'])) {
			$data['payment_paytm_industry'] = $this->request->post['payment_paytm_industry'];
		} else {
			$data['payment_paytm_industry'] = $this->config->get('payment_paytm_industry');
		}
		
		if (isset($this->request->post['payment_paytm_order_status_id'])) {
			$data['payment_paytm_order_status_id'] = $this->request->post['payment_paytm_order_status_id'];
		} else {
			$data['payment_paytm_order_status_id'] = $this->config->get('payment_paytm_order_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		
		/*if (isset($this->request->post['payment_paytm_environment'])) {
			$data['payment_paytm_environment'] = $this->request->post['payment_paytm_environment'];
		} else {
			$data['payment_paytm_environment'] = $this->config->get('payment_paytm_environment');
		}*/

		if (isset($this->request->post['payment_paytm_transaction_url'])) {
			$data['payment_paytm_transaction_url'] = $this->request->post['payment_paytm_transaction_url'];
		} else {
			$data['payment_paytm_transaction_url'] = $this->config->get('payment_paytm_transaction_url');
		}

		if (isset($this->request->post['payment_paytm_transaction_status_url'])) {
			$data['payment_paytm_transaction_status_url'] = $this->request->post['payment_paytm_transaction_status_url'];
		} else {
			$data['payment_paytm_transaction_status_url'] = $this->config->get('payment_paytm_transaction_status_url');
		}
		
		if (isset($this->request->post['payment_paytm_website'])) {
			$data['payment_paytm_website'] = $this->request->post['payment_paytm_website'];
		} else {
			$data['payment_paytm_website'] = $this->config->get('payment_paytm_website');
		}
		
		if (isset($this->request->post['payment_paytm_checkstatus'])) {
			$data['payment_paytm_checkstatus'] = $this->request->post['payment_paytm_checkstatus'];
		} else {
			$data['payment_paytm_checkstatus'] = $this->config->get('payment_paytm_checkstatus');
		}
		
		if (isset($this->request->post['payment_paytm_callbackurl'])) {
			$data['payment_paytm_callbackurl'] = $this->request->post['payment_paytm_callbackurl'];
		} else {
			$data['payment_paytm_callbackurl'] = $this->config->get('payment_paytm_callbackurl');
		}
		
		if (isset($this->request->post['payment_paytm_geo_zone_id'])) {
			$data['payment_paytm_geo_zone_id'] = $this->request->post['payment_paytm_geo_zone_id'];
		} else {
			$data['payment_paytm_geo_zone_id'] = $this->config->get('payment_paytm_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
                
                
        if (isset($this->request->post['payment_paytm_sort_order'])) {
			$data['payment_paytm_sort_order'] = $this->request->post['payment_paytm_sort_order'];
		} else {
			$data['payment_paytm_sort_order'] = $this->config->get('payment_paytm_sort_order');
		}
                
                
        if (isset($this->request->post['payment_paytm_total'])) {
			$data['payment_paytm_total'] = $this->request->post['payment_paytm_total'];
		} else {
			$data['payment_paytm_total'] = $this->config->get('payment_paytm_total');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paytm', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payment_paytm_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		if (!$this->request->post['payment_paytm_merchant2']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		/* if (!$this->request->post['payment_paytm_key']) {
			$this->error['key'] = $this->language->get('error_key');
		} */
		
		if (!$this->request->post['payment_paytm_website']) {
			$this->error['website'] = $this->language->get('error_website');
		}
		
		if (!$this->request->post['payment_paytm_industry']) {
			$this->error['industry'] = $this->language->get('error_industry');
		}

		
		

		return !$this->error;
	}
}
