<?php
class ControllerProductCategory extends Controller {
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
		  

		return $data;	
	}

	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = ''; //'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = ''; //'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->post['filter_event'])) {
			$page = 1;
		}


		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => './' //$this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$categoryType = $category_info['type'];

			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));

				$data['thumb_width']  = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width');
				$data['thumb_height'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height');
				
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);


				$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
				
				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),

				'thumb'        => $image,
				
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}


				$data['label_sale']     = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_sale');
				$data['label_discount'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_discount');
				$data['label_new']      = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new');
				if ($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new')) {
				$product_new = $this->model_catalog_product->getLatestProducts($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new_limit'));
				}
				
			$data['products'] = array();

			if(!$categoryType)
				$filter_data = array(
					'filter_category_id' => $category_id,
					'filter_filter'      => $filter,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);
			else 
				$filter_data = array(
					'filter_category_id' => $category_id,
					'filter_filter'      => $filter,
					'sort'               => $sort,
					'order'              => $order,
				);
				 

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
			
			$results = $this->model_catalog_product->getProducts($filter_data);

			$startDate = 0; $endDate = 0;

			$count = 1;

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
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}


				$additional_image = $this->model_catalog_product->getProductImages($result['product_id']);
				if ($additional_image) {
				$additional_image = $this->model_tool_image->resize($additional_image[0]['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
				} else {
				$additional_image = false;
				}
				
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$price = str_replace(',00', '', $price);
					
					$price_lit =  $result['price_lit'];//$this->currency->format($this->tax->calculate($result['price_lit'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if(!$categoryType && $result['price_lit'] != 0) { 
					if($result['special']) 
					$price_per_lit = round($result['special']/ $result['price_lit'] * 1000, 2);
				  else 
					$price_per_lit = round($result['price']/ $result['price_lit'] * 1000, 2);
					
				  $price_per_lit = $this->currency->format($this->tax->calculate($price_per_lit, $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			      $price_per_lit = str_replace(',00', '', $price_per_lit); 			
                }      

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$special = str_replace(',00', '', $special);

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
					$rating = (int)$result['rating'];
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
				
				// VU if is event
				if($categoryType) {
					$optionProduct = $this->model_catalog_product->getProductOptions($result['product_id']);

					foreach($optionProduct as $item) {
						$date = $item['value'];
						$date = explode(';', $date);

						//echo time() . '//';
						//echo strtotime('2023-08-31') . '//';

						$date1 = explode('-', $date[0]);

						$date1_new = $date1[1] . '-' . $date1[2];

						$date1 = $date1[2] . '-' . $date1[1] . '-' . $date1[0];
						$short_date = $date1 . ' ' . explode(':', $date[1])[0] . ':' . explode(':', $date[2])[0];
						
						//echo $date1 . '//';
						$format = "day month date";
						
						$date1_ = $this->document->formatDate($date1, $format);
	
						$dateEvent = ($date[3] > 0 ) ? $date1_ . ', ' . $date[1] . '-' .$date[2] . ' ' . $this->language->get('time_unit') : 
						$date1_ . ', ' . $date[1] . '-' .$date[2] . ' ' . $this->language->get('time_unit');
						$dateEvent = '<p class=date_event_text>' . $dateEvent . '</p>';

                        $optionID = $item['product_option_id']; 

	
						//echo time() - strtotime($date1) . '//';

						if(time() - strtotime($date1) <=0) {
							if($startDate == 0 && $endDate == 0) {
	                           $startDate = $date1;
							   $endDate = $date1;
							   //echo $startDate . '//';
							} elseif(strtotime($startDate) > 0 && strtotime($startDate) > strtotime($date1))
							  $startDate = $date1;
							elseif(strtotime($endDate) > 0 && strtotime($endDate) < strtotime($date1))
							  $endDate = $date1;

							if(true) {
							//if($count >= (($page - 1) * $limit) && $count <= (($page - 1) * $limit) + $limit ) {
								$data['products'][] = array(
									'product_id'  => $result['product_id'],
									'optionID'  => $optionID,
									'thumb'       => $image,
									'short_date' => $short_date,
									
				
								'additional_thumb' => $additional_image,
								
				
								'img-width'  => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'),
								'img-height' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'),
								
									'name'        => html_entity_decode($result['name']) . '<br>' . $dateEvent,
									'date'  => $date1_new,
								'reviews' => sprintf($this->language->get('text_reviews'), (int)$result['reviews']), 
									'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
									'price'       => $price,
									'class_event' => ($result['price'] > 0) ? ' ' : ' hide',
									'special'     => $special,
				
								'label_discount' => $label_discount,
								'label_new'      => $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new') ? $label_new : 0,
								
									'tax'         => $tax,
									'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
									'rating'      => $result['rating'],
									'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&option='.$optionID.'' . '&product_id=' . $result['product_id'] . $url)
				
								,
								'options'     => $options
								
								);
							}
							$count++; 
						}

						/*$data['products'][] = array(
							'product_id'  => $result['product_id'],
							'thumb'       => $image,
							'name'        => $result['name'],
							'description' => $dateEvent,
							'price'       => $price,
							'special'     => $special,
							'tax'         => $tax,
							'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
							'rating'      => $result['rating'],
							'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&option='.$optionID.'' . '&product_id=' . $result['product_id'] . $url)
						); */
					} 
				// if not is event
				} else {
					$data['products'][] = array(
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
	
					'additional_thumb' => $additional_image,
					
	
					'img-width'  => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'),
					'img-height' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'),
					
						'name'        => $result['name'],
	 'reviews' => sprintf($this->language->get('text_reviews'), (int)$result['reviews']), 
						'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'price_lit'       => $price_lit,
						'price_per_lit'       => $price_per_lit,
						'special'     => $special,
	
					'label_discount' => $label_discount,
					'label_new'      => $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new') ? $label_new : 0,
					
						'tax'         => $tax,
						'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
						'rating'      => $result['rating'],
						'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
	
					,
					'options'     => $options
					
					);
				}
			}

			//echo $sort . '//' . $order; die();
				
			if($categoryType == 1 && $sort == '' && $order == '') {
				//echo $sort . '//' . $order; die();
				$data['products'] = $this->shortByDate($data['products']);
				//print_r(($data['products'])); die();
			
				$product_total = count($data['products']);
	
				$dataFilter = array();
	
				$count = 0;

				//echo $this->request->post['filter_date']; die();

				for($i = 0; $i < count($data['products']) ; $i++) {
					 if(($count >= (($page - 1) * $limit) && $count < (($page - 1) * $limit) + $limit) || $this->request->post['filter_date']) {
						if($this->request->post['filter_date']) {
						//echo 'dd'; die(); 
						//echo $data['products'][$i]['date'] . '//' . $this->request->post['filter_date']. '  ';  
						if(strlen($data['products'][$i]['date']) == 6)
						$data['products'][$i]['date'] = '0' . $data['products'][$i]['date'];

						if($data['products'][$i]['date'] == $this->request->post['filter_date'])
							 $dataFilter[] = $data['products'][$i];
						} else {
							$dataFilter[] = $data['products'][$i];
						}
					}
					
				$count++; 	   
				}
				
				if($this->request->post['filter_date'] != '0-0')
				  $data['products'] = $dataFilter;
				//print_r(($data['products'])); die();

				if($this->request->post['filter_date'])
				  $product_total = count($data['products']);
			}
			
			$data['start_event'] = $this->document->formatDate($startDate, $format);
			$data['end_event'] = $this->document->formatDate($endDate, $format);

			$startDate = explode('-', $startDate);
			$data['value_start_event'] = $startDate[1] . '-' . $startDate[0];

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				/*$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);*/
			}

			/*$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);*/

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['theme_path'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_directory');
				
            $data['optionID'] = $optionID;

			if($this->request->post['filter_event']) 
			  $this->response->setOutput($this->load->view('product/category_event_filter', $data));
			else if($categoryType == 1)
			  $this->response->setOutput($this->load->view('product/category_event', $data));
			else 
			$this->response->setOutput($this->load->view('product/category', $data));  
  } else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function event() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => './' //$this->url->link('common/home')
		);

		if (true) {
			$data['label_sale']     = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_sale');
				$data['label_discount'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_discount');
				$data['label_new']      = $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new');
				if ($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new')) {
				$product_new = $this->model_catalog_product->getLatestProducts($this->config->get('theme_' . $this->config->get('config_theme') . '_label_new_limit'));
				}
				
			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => '', //$category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_product->getTotalEvents($filter_data);
			$results = $this->model_catalog_product->getEvents($filter_data);

			$startDate = 0; $endDate = 0;

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
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}


				$additional_image = $this->model_catalog_product->getProductImages($result['product_id']);
				if ($additional_image) {
				$additional_image = $this->model_tool_image->resize($additional_image[0]['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
				} else {
				$additional_image = false;
				}
				
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				    $price_lit =  $result['price_lit'];//$this->currency->format($this->tax->calculate($result['price_lit'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
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
					$rating = (int)$result['rating'];
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
				
				// VU if is event
				if(true) {
					$optionProduct = $this->model_catalog_product->getProductOptions($result['product_id']);

					foreach($optionProduct as $item) {
						$date = $item['value'];
						$date = explode(';', $date);

						//echo time() . '//';
						//echo strtotime('2023-08-31') . '//';

						$date1 = explode('-', $date[0]);

						$date1 = $date1[2] . '-' . $date1[1] . '-' . $date1[0];
						$short_date = $date1 . ' ' . explode(':', $date[1])[0] . ':' . explode(':', $date[2])[0];
						//echo $date1 . '//';
						$format = "day month date";
						
						$date1_ = $this->document->formatDate($date1, $format);
	
						$dateEvent = ($date[3] > 0 ) ? $date1_ . '   ' . $date[1] . '-' .$date[2] . $this->language->get('time_unit') . '<br>' . $date[3] . ' ' . $this->language->get('event_in_stock') : 
						$date1_ . '   ' . $date[1] . '-' .$date[2] . $this->language->get('time_unit');

                        $optionID = $item['product_option_id']; 

	
						//echo time() - strtotime($date1) . '//';

						if(time() - strtotime($date1) <=0) {
							if($startDate == 0 && $endDate == 0) {
	                           $startDate = $date1;
							   $endDate = $date1;
							   //echo $startDate . '//';
							} elseif(strtotime($startDate) > 0 && strtotime($startDate) > strtotime($date1))
							  $startDate = $date1;
							elseif(strtotime($endDate) > 0 && strtotime($endDate) < strtotime($date1))
							  $endDate = $date1;

							$data['products'][] = array(
								'product_id'  => $result['product_id'],
								'optionID'  => $optionID,
								'thumb'       => $image,
								'short_date' => $short_date,
			
							'additional_thumb' => $additional_image,
							
			
							'img-width'  => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'),
							'img-height' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'),
							
								'name'        => $result['name'] . '<br>' . $dateEvent,
			                'reviews' => sprintf($this->language->get('text_reviews'), (int)$result['reviews']), 
								'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
								'price'       => $price,
								'special'     => $special,
			
							'label_discount' => $label_discount,
							'label_new'      => $this->config->get('theme_' . $this->config->get('config_theme') . '_label_new') ? $label_new : 0,
							
								'tax'         => $tax,
								'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
								'rating'      => $result['rating'],
								'href'        => $this->url->link('product/product/print', 'path=' . $this->request->get['path'] . '&option='.$optionID.'' . '&product_id=' . $result['product_id'] . $url)
			
							,
							'options'     => $options
							
							);
						}
} 
				// if not is event
				}
			}

			$data['start_event'] = $this->document->formatDate($startDate, $format);
			$data['end_event'] = $this->document->formatDate($endDate, $format);

			$startDate = explode('-', $startDate);
			$data['value_start_event'] = $startDate[1] . '-' . $startDate[2] . '-' . $startDate[0];

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$data['optionID'] = $optionID;

			$this->response->setOutput($this->load->view('product/list_event', $data));
	}
}
}
