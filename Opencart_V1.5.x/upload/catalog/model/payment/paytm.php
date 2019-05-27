<?php 
class ModelPaymentPaytm extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/paytm');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paytm_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('paytm_total') > 0 && $this->config->get('paytm_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('paytm_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		// uncomment below line if you do not want customer to show Paytm when currency is not INR
		// $status = ($this->currency->getCode() != 'INR') ? false : $status;

		$method_data = array();
		if ($status) {  
			$method_data = array( 
				'code'       => 'paytm',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('paytm_sort_order')
			);
		}
    	return $method_data;
  	}
	
	/**
	* save response in db
	*/
	public function saveTxnResponse($data  = array(), $order_id, $id = false){		
		if(empty($data['STATUS'])) return false;
		
		$status 				= (!empty($data['STATUS']) && $data['STATUS'] =='TXN_SUCCESS') ? 1 : 0;
		$paytm_order_id 	= (!empty($data['ORDERID'])? $data['ORDERID']:'');
		$transaction_id 	= (!empty($data['TXNID'])? $data['TXNID']:'');
		
		if($id !== false){
			$sql =  "UPDATE " . DB_PREFIX . "paytm_order_data SET order_id = '" . $order_id . "', paytm_order_id = '" . $paytm_order_id . "', transaction_id = '" . $this->db->escape($transaction_id) . "', status = '" . (int)$status . "', paytm_response = '" . $this->db->escape(json_encode($data)) . "', date_modified = NOW() WHERE id = '" . (int)$id . "'";
			$this->db->query($sql);
			return $id;
		}else{
			$sql =  "INSERT INTO " . DB_PREFIX . "paytm_order_data SET order_id = '" . $order_id . "', paytm_order_id = '" . $paytm_order_id . "', transaction_id = '" . $this->db->escape($transaction_id) . "', status = '" . (int)$status . "', paytm_response = '" . $this->db->escape(json_encode($data)) . "', date_added = NOW(), date_modified = NOW()";
			$this->db->query($sql);
			return $this->db->getLastId();
		}
	}
}
?>