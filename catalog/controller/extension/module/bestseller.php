<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function shortByDate($data) {
		//print_r($data); die();
		for($i = 0; $i < count($data) - 1; $i++) {
			//echo $data[$i]['short_date'] . '////';
			for($j = $i + 1; $j < count($data); $j++)
		    if(strtotime($data[$i]['short_date']) > strtotime($data[$j]['short_date'])) {
				$temp = $data[$i];
				$data[$i] = $data[$j];
				$data[$j] = $temp;
			}
		} 
		  
	   $result = array();
	   
	   $result[] = $data[0];
	   $result[] = $data[1];
	   $result[] = $data[2];
	   $result[] = $data[3];
	   $result[] = $data[4];
	   $result[] = $data[5];
	   
	   return $result;	
	}

	public function index($setting) {

				static $module = 0;
				
		$this->load->language('extension/module/bestseller');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');


				$data['label_sale']     = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_sale');
				$data['label_discount'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_discount');
				$data['label_new']      = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new');
				if ($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new')) {
				$product_new = $this->model_catalog_product->getLatestProducts($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new_limit'));
				}
				

				$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
				$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
				$data['layout_type']  = $setting['layout_type'];
				
		$data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
		$results = $this->shortByDate($results);
		//echo(count($results)); die();

		if ($results) {
			foreach ($results as $result) {

				if ($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new')) {
				$label_new = 0;
				foreach ($product_new as $product_new_id => $product) {
				if ($product_new[$product_new_id]['product_id'] == $result['product_id']) {
				$label_new = 1;
				break;
				}
				}
				}
				
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}


				$additional_image = $this->model_catalog_product->getProductImages($result['product_id']);
				if ($additional_image) {
				$additional_image = $this->model_tool_image->resize($additional_image[0]['image'], $setting['width'], $setting['height']);
				} else {
				$additional_image = false;
				}
				
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				$label_discount = '-' . (int)(100 - ($result['special'] * 100 / $result['price'])) . '%';
				
				} else {
					$special = false;

				$label_discount = false;
				
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}


				$options = array();
				foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
				$product_option_value_data = array();
				foreach ($option['product_option_value'] as $option_value) {
				if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
				if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
				$price_option = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
				} else {
				$price_option = false;
				}
				$product_option_value_data[] = array(
				'product_option_value_id' => $option_value['product_option_value_id'],
				'option_value_id'         => $option_value['option_value_id'],
				'name'                    => $option_value['name'],
				'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
				'price'                   => $price_option,
				'price_prefix'            => $option_value['price_prefix']
				);
				}
				}
				$options[] = array(
				'product_option_id'    => $option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $option['option_id'],
				'name'                 => $option['name'],
				'type'                 => $option['type'],
				'value'                => $option['value'],
				'required'             => $option['required']
				);
				}
				

				$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
				$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
				$data['layout_type']  = $setting['layout_type'];
				
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,

				'additional_thumb' => $additional_image,
				

				'img-width'  => $setting['width'],
				'img-height' => $setting['height'],
				
					'name'        => $result['name'],
					'date_event' =>  $result['date_event'],
 'reviews' => sprintf($this->language->get('text_reviews'), (int)$result['reviews']), 
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,

				'label_discount' => $label_discount,
				'label_new'      => $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new') ? $label_new : 0,
				
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . '&option=' . $result['product_option_id'])
,
				'options'     => $options
				
				);
			}


				$data['module'] = $module++;
				
			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
