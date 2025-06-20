<?php
class ControllerCheckoutPaymentMethod extends Controller {
	public function index() {
		session_start();

		$this->load->language('checkout/checkout');

		if (isset($this->session->data['payment_address'])) {
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			//print_r($total_data['total']);
			
			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			// Payment Methods
			$method_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('payment');

			//print_r($results); die();

			 //$results = $results_[0];

			//print_r($_SESSION['total_1']); die();

			//print_r($results); 
			//die();

			//print_r($total_data);

			$check = true;

			$coupon_id = ($_SESSION['coupon_id']) ? $_SESSION['coupon_id'] : 'null';

			if($coupon_id != null) {
				$query = $this->db->query( 'SELECT * FROM ' . DB_PREFIX . 'coupon' . " where coupon_id = ".$coupon_id."" );

				$row = $query->row;

				//print_r($row);
				//die();

				$valueCoupon = $row['discount'];

				$sum = 0;

				foreach($total_data['totals'] as $item)
				  if($item['code'] == 'sub_total' || $item['code'] == 'shipping')
				    $sum = $sum + $item['value'];

				  if($sum <= $valueCoupon)
				  $check = false;

				  //echo $valueCoupon . '/' . $sum;
				  //die();
			}

            $recurring = $this->cart->hasRecurringProducts();

			//print_r($total_data);
			//die();

			foreach ($results as $result) {
				//echo $total_data['total']; 
				//die(); 
				// if not pay with cash deliver
				if($check) {
					//echo 'dd'; 
					//die();
					if($result['code'] != 'cod')
					if ($this->config->get('payment_' . $result['code'] . '_status')) {
						$this->load->model('extension/payment/' . $result['code']);
	
						$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);
	
						if ($method) {
							if ($recurring) {
								if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
									$method_data[$result['code']] = $method;
								}
							} else {
								$method_data[$result['code']] = $method;
							}
						}
					}
				} else {
					if($result['code'] == 'cod')
						if ($this->config->get('payment_' . $result['code'] . '_status')) {
							$this->load->model('extension/payment/' . $result['code']);
		
							$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);
		
							if ($method) {
								if ($recurring) {
									if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
										$method_data[$result['code']] = $method;
									}
								} else {
									$method_data[$result['code']] = $method;
								}
							}
						}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;
		}

		if (empty($this->session->data['payment_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		$data['scripts'] = $this->document->getScripts();

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), true), $information_info['title'], $information_info['title']);
			    $data['text_agree_1'] = $this->language->get('text_agree_1'); 
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}

		//print_r($_SESSION['total_1']); die();
		$data['auto'] = ($_SESSION['total_1'][4]['value'] == 0) ? 1 : 0;

		// SECURITY
		$script_nonce = NONE_SCRIPT;
		$data['none_script'] = $script_nonce;
		
        $this->response->setOutput($this->load->view('checkout/payment_method', $data));
	}

	public function save() {
		session_start();

		$this->load->language('checkout/checkout');


		$json = array();

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		}

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ( $information_info && ( !isset($this->request->post['agree']) || !isset($this->request->post['agree_']) || !isset($this->request->post['agree__']) ) ) {
				//if($_SESSION['total_1'][4]['value'] != 0)
				 $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

			$this->session->data['comment'] = strip_tags($this->request->post['comment']); 

			$this->load->model('catalog/information');

			$this->model_catalog_information->insertQuestion($this->request->post['questions']);
        }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
