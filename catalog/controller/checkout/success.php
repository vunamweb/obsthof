<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');

		if (isset($this->session->data['order_id'])) {
			$collectorders = array();

			$this->load->model('checkout/order');
			$ts_buyerprotection = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  
				  $collectorders['order_id'] = (int)$ts_buyerprotection['order_id'];
	  
				  if ($this->customer->isLogged()) {
					  $this->load->model('account/customer');
					  $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
					  $collectorders['email'] = $customer_info['email'];
				  } else {
					  $collectorders['email'] = $this->session->data['guest']['email'];
				  }
	  
			$collectorders['email'] = html_entity_decode($ts_buyerprotection['email'], ENT_QUOTES, 'UTF-8');
			$collectorders['total']= $this->currency->format($ts_buyerprotection['total'], $this->session->data['currency'], '', false);
			$collectorders['currency_code'] = html_entity_decode($ts_buyerprotection['currency_code'], ENT_QUOTES, 'UTF-8');
			$collectorders['payment_method'] = html_entity_decode($ts_buyerprotection['payment_method'], ENT_QUOTES, 'UTF-8');
	  
				  $collectorders['order_products'] = array();
				  $this->load->model('tool/image');
				  $this->load->model('catalog/product');
				  foreach ($this->cart->getProducts() as $product) {
	  
					  if ($product['image']) {
						  $image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
					  } else {
						  $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
					  }
	  
	  
	  
					  $product_info = $this->model_catalog_product->getProduct($product['product_id']);
					  $collectorders['order_products'][] = array(
						  'product_id' => $product['product_id'],
						  'name'       => $product['name'],
						  'image'      => $image,
						  'model'      => $product['model'],
						  'sku'      	 => $product_info['sku'],
						  'ean'      	 => $product_info['ean'],
						  'mpn'      	 => $product_info['mpn'],
						  'brand'      => $product_info['manufacturer'],
						  'quantity'   => $product['quantity'],
						  'price'      => $product['price'],//$this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
						  'total'      => $product['total'],//$this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
						  'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					  );
				  }
				  //echo '<pre>'.print_r($collectorders, true).'</pre>';
				  $this->session->data['trustedshop_collectorders'] = $collectorders;

			$this->load->model( 'checkout/order' );
			
			$this->model_checkout_order->updateValueTicket();
			
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}