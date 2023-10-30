<?php
namespace Opencart\Admin\Model\Extension\PaytmPaymentGateway\Payment;
class Paytm extends \Opencart\System\Engine\Model {
	
	public function install(): void {

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paytm_report` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,
			`status` varchar(64) NOT NULL,
			`paytm_order_id` varchar(64) NOT NULL,
			`transaction_id` varchar(64) NOT NULL,
			`response` text NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
		");
	}

	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paytm_report`");
	}

	public function getReports(int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytm_report` ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReports(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "paytm_report`");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
