<?php
namespace Opencart\Admin\Controller\Extension\PaytmPaymentGateway\Payment;
require_once (DIR_EXTENSION . 'paytm_payment_gateway/system/library/PaytmChecksum.php');	
require_once (DIR_EXTENSION . 'paytm_payment_gateway/system/library/PaytmHelper.php');	
use PaytmChecksum\PaytmChecksum;
use PaytmHelper\PaytmHelper;

class Paytm extends \Opencart\System\Engine\Controller {
	
	public function index(): void {
		$this->load->language('extension/paytm_payment_gateway/payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paytm_payment_gateway/payment/paytm', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/paytm_payment_gateway/payment/paytm.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		$data['payment_paytm_response'] = $this->config->get('payment_paytm_response');

		$data['payment_paytm_approved_status_id'] = $this->config->get('payment_paytm_approved_status_id');
		$data['payment_paytm_failed_status_id'] = $this->config->get('payment_paytm_failed_status_id');
		$data['payment_paytm_order_status_id'] = $this->config->get('payment_paytm_order_status_id');

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['payment_paytm_geo_zone_id'] = $this->config->get('payment_paytm_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payment_paytm_status'] = $this->config->get('payment_paytm_status');
		$data['payment_paytm_sort_order'] = $this->config->get('payment_paytm_sort_order');
		$data['payment_paytm_environment'] = $this->config->get('payment_paytm_environment');
		$data['payment_paytm_mid'] = $this->config->get('payment_paytm_mid');
		$data['payment_paytm_mkey'] = $this->config->get('payment_paytm_mkey');
		$data['payment_paytm_website'] = $this->config->get('payment_paytm_website');
		$data['payment_paytm_total'] = $this->config->get('payment_paytm_total');
		$data['payment_paytm_bank_offer'] = $this->config->get('payment_paytm_bank_offer');
		$data['payment_paytm_emi_subvention'] = $this->config->get('payment_paytm_emi_subvention');
		$data['payment_paytm_dc_emi'] = $this->config->get('payment_paytm_dc_emi');
		$data['payment_paytm_envert_logo'] = $this->config->get('payment_paytm_envert_logo');
		$data['payment_paytm_webhook'] = $this->config->get('payment_paytm_webhook');

		$data['report'] = $this->getReport();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/paytm_payment_gateway/payment/paytm', $data));
	}

	public function save(): void {
		$json = [];
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			// Code for webhook
			$PaytmChecksum = new PaytmChecksum();
				$this->load->language('extension/paytm_payment_gateway/payment/paytm');
				if (isset($this->request->post['payment_paytm_is_webhook_triggered'])) {
					if($this->request->post['payment_paytm_is_webhook_triggered']==1){
						if($this->request->post['payment_paytm_webhook']==1){
							$webhookUrl = $this->language->get('base_url_for_paytm_webhook');
							$webhookUrl = str_replace('.save/', '/', $webhookUrl);
						}else{
							$webhookUrl = "https://www.dummyUrl.com";
						}
						if($this->request->post['payment_paytm_environment']==1){
							$url = $this->language->get('WEBHOOK_PRODUCTION_URL');
							
						}else{
							$url = $this->language->get('WEBHOOK_STAGING_URL');
						}
		                $paytmParams = array(
		                            "mid"       => $this->request->post['payment_paytm_mid'],
		                            "queryParam" => "notificationUrls",
		                            "paymentNotificationUrl" => $webhookUrl
		                          );
		                $paytmParamsJson = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		               
		                $generateSignature = $PaytmChecksum->generateSignature($paytmParamsJson, $this->config->get('payment_paytm_mkey'));
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
					        $json =[];
					    } elseif (isset($res['E_400'])) {
				             $json =[];
					    }else if(isset($res['BO_411'])){
					    	  $json['error'] = "Something went wrong while configuring webhook. Please login to Paytm Dashboard to configure.";
				    	}else {
					        $json['error'] = "Something went wrong while configuring webhook. Please login to Paytm Dashboard to configure.";
					    }
					    
					    
					}
				}

			$this->load->language('extension/paytm_payment_gateway/payment/paytm');

		

			if (!$this->user->hasPermission('modify', 'extension/paytm_payment_gateway/payment/paytm')) {
				$json['error'] = $this->language->get('error_permission');
			}else if (!$this->request->post['payment_paytm_mid']) {
				$json['error'] = $this->language->get('error_merchant_id');
			}else if (!$this->request->post['payment_paytm_mkey']) {
				$json['error'] = $this->language->get('error_merchant_key');
			}else if (!$this->request->post['payment_paytm_website']) {
				$json['error'] = $this->language->get('error_website');
			}else if (!in_array($this->request->post['payment_paytm_environment'], array("1","0"))) {
				$json['error'] = $this->language->get('error_environment');
			}

			if (empty($json)) {
				$this->load->model('setting/setting');

				$this->model_setting_setting->editSetting('payment_paytm', $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/payment')) {
			$this->load->model('extension/paytm_payment_gateway/payment/paytm');

			$this->model_extension_paytm_payment_gateway_payment_paytm->install();
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/payment')) {
			$this->load->model('extension/paytm_payment_gateway/payment/paytm');

			$this->model_extension_paytm_payment_gateway_payment_paytm->uninstall();
		}
	}

	public function report(): void {
		$this->load->language('extension/paytm_payment_gateway/payment/paytm');

		$this->response->setOutput($this->getReport());
	}

	public function getReport(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
		$data['reports'] = [];

		$this->load->model('extension/paytm_payment_gateway/payment/paytm');

		$results = $this->model_extension_paytm_payment_gateway_payment_paytm->getReports(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['reports'][] = [
				'order_id'   => $result['order_id'],
				'paytm_order_id'       => $result['paytm_order_id'],
				'transaction_id'     => $result['transaction_id'],
				'response'   => $result['response'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'order'      => $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'])
			];
		}

		$report_total = $this->model_extension_paytm_payment_gateway_payment_paytm->getTotalReports();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $report_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('extension/paytm_payment_gateway/payment/paytm.report', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($report_total - 10)) ? $report_total : ((($page - 1) * 10) + 10), $report_total, ceil($report_total / 10));

		return $this->load->view('extension/paytm_payment_gateway/payment/paytm_report', $data);
	}

}
