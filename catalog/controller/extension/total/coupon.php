<?php
class ControllerExtensionTotalCoupon extends Controller {
	public function index() {
		if ($this->config->get('total_coupon_status')) {
			$this->load->language('extension/total/coupon');

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}

			return $this->load->view('extension/total/coupon', $data);
		}
	}

	public function coupon() {
		session_start();
		
		$this->load->language('extension/total/coupon');

		$json = array();

		$this->load->model('extension/total/coupon');

		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		$coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);

		if (empty($this->request->post['coupon'])) {
			$json['error'] = $this->language->get('error_empty');

			unset($this->session->data['coupon']);
		} elseif ($coupon_info) {
			$this->session->data['coupon'] = $this->request->post['coupon'];
			$_SESSION['coupon_id'] = $coupon_info['coupon_id'];

			//echo $coupon_info['coupon_id'] . '///dd'; die();

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json['error'] = $this->language->get('error_coupon');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
