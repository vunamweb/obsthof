<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$this_url = $_SERVER["REQUEST_URI"];
		$this_url = explode('/', $this_url);
		$set_url = '';
		foreach($this_url as $val) {
			if($val != 'shop') $set_url .= $val.' ';			
		}
		$set_url = trim($set_url);
		$data['url'] = $set_url ? $set_url : 'home';
		
		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();

				$data['header_nav'] = $this->load->controller('common/header_nav');
				$data['header_top'] = $this->load->controller('common/header_top');
				$data['navigation'] = $this->load->controller('common/navigation');
				
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');


				$data['page_direction'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_page_direction');
				

				$data['responsive'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_responsive');
				
		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

				$data['theme_path'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_directory');
				

				$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

				$data['fax']     = $this->config->get('config_fax');
				$data['open']    = $this->config->get('config_open');
				$data['comment'] = $this->config->get('config_comment');
				$data['compare'] = $this->url->link('product/compare');
				
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

		// seo1
        $seo1 = new \stdClass;

        $context = '@context';
        $seo1->$context = 'https://schema.org/';

        $type = '@type';
        $seo1->$type = 'CivicStructure';

        $seo1->name = $this->config->get( 'config_name' );
        $seo1->openingHours = $this->config->get( 'config_open' );

        $seo1 = '<script type="application/ld+json">' .json_encode( $seo1 ) . '</script>';
        // end seo1

        // seo2
        $seo2 = new \stdClass;

        $seo2->$context = 'https://schema.org/';
        $seo2->$type = 'Organization';
        $seo2->url = HTTPS_SERVER;

        $seo2->contactPoint = array();
        $seo2->contactPoint[ 0 ] = new \stdClass;
        $seo2->contactPoint[ 0 ]->$type = 'ContactPoint';
        $seo2->contactPoint[ 0 ]->telephone = $this->config->get( 'config_telephone' );
        $seo2->contactPoint[ 0 ]->contactType = 'customer service';

        $seo2 = '<script type="application/ld+json">' .json_encode( $seo2 ) . '</script>';
        // end seo2

        // seo3
        $seo3 = new \stdClass;
        $seo3->$context = 'https://schema.org/';

        $graph = '@graph';
        $seo3->$graph = new \stdClass;
        $seo3->$graph->$type = 'Place';
        $seo3->$graph->address = new \stdClass;
        $seo3->$graph->address->$type = 'PostalAddress';
        $seo3->$graph->address->streetAddress = $this->config->get( 'config_address' );
        //$seo3->$graph->address->geocode = $this->config->get( 'config_geocode' );

        $seo3 = '<script type="application/ld+json">' .json_encode( $seo3 ) . '</script>';
        // end seo3

        $data[ 'seo1' ] = $seo1;
        $data[ 'seo2' ] = $seo2;
        $data[ 'seo3' ] = $seo3;
        $data[ 'seo4' ] = null;
        
        if ( $this->request->get['product_id'] ) {
            $this->load->model( 'catalog/product' );

            $product_id = $_GET[ 'product_id' ];

            $product_info = $this->model_catalog_product->getProduct( $product_id );
            $typeProduct = ( $product_info[ 'type' ] == 0 ) ? 'Product' : 'Event';

            $product_images = $this->model_catalog_product->getProductImages( $product_id );
            $images = array();
            $images[ 0 ] = HTTPS_SERVER . $product_info[ 'image' ];
            foreach ( $product_images as $image ) {
                $url_image = HTTPS_SERVER . $image[ 'image]' ];
                $images[] = $url_image;
            }
            //print_r( $product_info );

            $seo4 = new \stdClass;
            $seo4->$context = 'https://schema.org/';
            $seo4->$type = $typeProduct;
            $seo4->name = $product_info[ 'name' ];

            // if is event
            if ( $product_info[ 'type' ] == 1 ) {
                $product_options = $this->model_catalog_product->getProductOptions( $product_id );
                $option_id = $_GET[ 'option' ];

                foreach ( $product_options as $item ) {
                    if ( $item[ 'product_option_id' ] == $option_id ) {
                        $value = $item[ 'value' ];
                        $value = explode( ';', $value );

                        $startDate = $value[ 0 ] . ' ' . $value[ 1 ];
                        $endDate = $value[ 0 ] . ' ' . $value[ 2 ];

                        $seo4->startDate = $startDate;
                        $seo4->endDate = $endDate;
                    }
                }
                //print_r( $product_options );
            }
            // end
            
            $seo4->description = $product_info[ 'description' ];
            $seo4->sku = $product_info[ 'sku' ];
            $seo4->mpn = $product_info[ 'mpn' ];

            $seo4->review = new \stdClass;
            $seo4->review->$type = 'Review';
            $seo4->review->reviewRating = new \stdClass;
            $seo4->review->reviewRating->$type = 'Rating';
            $seo4->review->reviewRating->ratingValue = $product_info[ 'rating' ];

            $seo4->offers = new \stdClass;
            $seo4->offers->$type = 'AggregateOffer';
            $seo4->offers->price = $product_info[ 'price' ];
            $seo4->offers->priceCurrency = 'Euro';

            $seo4->image = json_encode( $images );

            $seo4 = '<script type="application/ld+json">' .json_encode( $seo4 ) . '</script>';

            $data[ 'seo4' ] = $seo4;
        }

        $uri = $_SERVER['REQUEST_URI'];

        if (str_replace('?', '', $uri) != $uri) {
            $data['noindex'] = true;
        } else {
            $data['noindex'] = false;
        }

        $urlCanonical = HTTP_SERVER . $_GET['_route_'];
        //echo $urlCanonical;
        $data['canonical_product_link'] = '<link rel="canonical" href="'.$urlCanonical.'" />';
           

        return $this->load->view('common/header', $data);
	}
}
