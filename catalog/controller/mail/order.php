<?php
include "./dompdf/autoload.inc.php";
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;

require "PHPMailer.php";
require "SMTP.php";
require "Exception.php";

class ControllerMailOrder extends Controller {
	public function index(&$route, &$args) {
		if (isset($args[0])) {
			$order_id = $args[0];
		} else {
			$order_id = 0;
		}

		if (isset($args[1])) {
			$order_status_id = $args[1];
		} else {
			$order_status_id = 0;
		}	

		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}
		
		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}
						
		// We need to grab the old order status ID
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			// If order status is 0 then becomes greater than 0 send main html email
			if (!$order_info['order_status_id'] && $order_status_id) {
				$this->add($order_info, $order_status_id, $comment, $notify);
			} 
			
			// If order status is not 0 then send update text email
			if ($order_info['order_status_id'] && $order_status_id && $notify) {
				$this->edit($order_info, $order_status_id, $comment, $notify);
			}		
		}
	}

	public function generateRandomString($length = 10) {
		// Character and number pool
		$characters = '123456789abcdefghjklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
		// Shuffle the characters
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		//echo $randomString; die();
		return $randomString;
	}
		
	public function add($order_info, $order_status_id, $comment, $notify) {
		// Check for any downloadable products
		$download_status = false;

		$order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);
		
		foreach ($order_products as $order_product) {
			// Check if there are any linked downloads
			$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

			if ($product_download_query->row['total']) {
				$download_status = true;
			}
		}
		
		// Load the language for any mails that might be required to be sent out
		$language = new Language($order_info['language_code']);
		$language->load($order_info['language_code']);
		$language->load('mail/order_add');

		// HTML Mail

		$data['text_greeting'] = sprintf($language->get('text_greeting'), $order_info['store_name']);
		$data['text_link'] = $language->get('text_link');
		$data['text_download'] = $language->get('text_download');
		$data['text_order_detail'] = $language->get('text_order_detail');
		$data['text_instruction'] = $language->get('text_instruction');
		$data['text_order_id'] = $language->get('text_order_id');
		$data['text_date_added'] = $language->get('text_date_added');
		$data['text_payment_method'] = $language->get('text_payment_method');
		$data['text_shipping_method'] = $language->get('text_shipping_method');
		$data['text_email'] = $language->get('text_email');
		$data['text_telephone'] = $language->get('text_telephone');
		$data['text_ip'] = $language->get('text_ip');
		$data['text_order_status'] = $language->get('text_order_status');
		$data['text_payment_address'] = $language->get('text_payment_address');
		$data['text_shipping_address'] = $language->get('text_shipping_address');
		$data['text_product'] = $language->get('text_product');
		$data['text_model'] = $language->get('text_model');
		$data['text_quantity'] = $language->get('text_quantity');
		$data['text_price'] = $language->get('text_price');
		$data['text_total'] = $language->get('text_total');
		$data['text_footer'] = $language->get('text_footer');

		$data['logo'] = $order_info['store_url'] . 'image/' . $this->config->get('config_logo');
		$data['store_name'] = $order_info['store_name'];
		$data['store_url'] = $order_info['store_url'];
		$data['customer_id'] = $order_info['customer_id'];
		$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];
		
		$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], '2'.$data['text_order_status'], $order_info['order_id']);

		if ($download_status) {
			$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
		} else {
			$data['download'] = '';
		}

		$data['order_id'] = $order_info['order_id'];
		$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
		$data['payment_method'] = $order_info['payment_method'];
		$data['shipping_method'] = $order_info['shipping_method'];
		$data['email'] = $order_info['email'];
		$data['telephone'] = $order_info['telephone'];
		$data['ip'] = $order_info['ip'];

		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
	
		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = 'Auftrag';
		}

		if(true) {
		//if ($comment && $notify) {
			$data['comment'] = nl2br($comment);
			$data['comment'] .= '<br><br>' . nl2br($order_info['comment']);
		} else {
			$data['comment'] = '';
		}

		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		);

		$replace = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'zone_code' => $order_info['payment_zone_code'],
			'country'   => $order_info['payment_country']
		);
  
		$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$data['first_name'] = $order_info['payment_firstname'];
		$data['last_name'] = $order_info['payment_lastname'];
		
		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		);

		$replace = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'zone_code' => $order_info['shipping_zone_code'],
			'country'   => $order_info['shipping_country']
		);

		$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		$this->load->model('tool/upload');
		$this->load->model('checkout/order');
		
        // Products
		$data['products'] = array();

		$sum_tax_1 = 0; $sum_tax_2 = 0; $totalNormalProduct = 0;

		foreach ($order_products as $order_product) {
			$option_data = array();

			$order_options = $this->model_checkout_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);

			foreach ($order_options as $order_option) {
				if ($order_option['type'] != 'file') {
					$value = $order_option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $order_option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			// VU show tax
			$resultTax_1 = 0; $resultTax_2 = 0;

			$taxs = $this->model_checkout_order->getTaxs();
			$tax_1 = (int) $taxs[0]['rate']; $tax_2 = (int) $taxs[1]['rate'];

			// if normal product
			if($order_product['type'] == 0) {
			   $total_1 = $order_product['price'] * $order_product['quantity'];
			   $totalNormalProduct = $totalNormalProduct + $total_1;

			   $resultTax_1 = round(($total_1) - ($total_1) / (1 + $tax_1/100), 2);

			   $sum_tax_1 = $sum_tax_1 + $resultTax_1;
			} else { // if event
				$total_1 = ($order_product['price'] - $order_product['price_1']) * $order_product['quantity'];
				$resultTax_1 = round(($total_1) - ($total_1) / (1 + $tax_1/100), 2);

				$total_2 = ($order_product['price_1']) * $order_product['quantity'];
				$resultTax_2 = round(($total_2) - ($total_2) / (1 + $tax_2/100), 2);

				$sum_tax_1 = $sum_tax_1 + $resultTax_1;
				$sum_tax_2 = $sum_tax_2 + $resultTax_2;
			}
			 
			// END

			$data['products'][] = array(
				'name'     => $order_product['name'],
				'model'    => $order_product['model'],
				'option'   => $option_data,
				'quantity' => $order_product['quantity'],
				'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'price_number' => $product['price'],
				'price_1'   => $order_product['price_1'],
				'price_2'   => $order_product['price'] - $order_product['price_1'] ,
				'type'      => $order_product['type'],
				'tax_1'     => $resultTax_1,
				'tax_2'     => $resultTax_2,
				'text_tax_1' => $tax_1 . '% of ',//$this->language->get('tax_1'),
				'text_tax_2' => $tax_2 . '% of ', //$this->language->get('tax_2'),
			);
		}

		// Vouchers
		$data['vouchers'] = array();

		$order_vouchers = $this->model_checkout_order->getOrderVouchers($order_info['order_id']);

		foreach ($order_vouchers as $order_voucher) {
			$data['vouchers'][] = array(
				'description' => $order_voucher['description'],
				'amount'      => $this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		// Order Totals
		$data['totals'] = array();

		$order_totals = $this->model_checkout_order->getOrderTotals($order_info['order_id']);

		$this->document->displayOrder($order_totals, $sum_tax_1, $sum_tax_2, $this->session->data['shipping_address']['country_id'], $totalNormalProduct, $this->config->get('config_login_attempts'));

		foreach ($order_totals as $order_total) {
			$data['totals'][] = array(
				'title' => $order_total['title'],
				'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$this->load->model('setting/setting');
		
		$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
		
		if (!$from) {
			$from = $this->config->get('config_email');
		}

		$invoice_prefix = $order_info['invoice_prefix'];
		$invoice_prefix = explode('-', $invoice_prefix);

		$invoice_prefix[2] = '00';

		$order_info['invoice_prefix'] = $invoice_prefix[0] . '-' . $invoice_prefix[1] . '-' . $invoice_prefix[2];

		//$invoiceNumber = $order_info['invoice_prefix'] . $order_info['invoice_no'];

		//$data['order_id'] = $invoiceNumber;
		
        //$type = $this->createPDFInvoice($order_info, $order_status_id, $sum_tax_1, $sum_tax_2, $totalNormalProduct);
		
		$subject = html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $data['order_status'], $order_info['order_id']), ENT_QUOTES, 'UTF-8');
		$fromName = html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8');

		// if status is complete
		if(in_array($order_status_id, $this->config->get('config_complete_status'))) {
		  $generateIDCoupon = $this->generateRandomString(6);

		  $invoice = $this->model_checkout_order->countInvoiceNumber() + 1;

		  $invoiceNumber = $order_info['invoice_prefix'] . $invoice;

		  $data['order_id'] = $invoiceNumber;

		  $subject = html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $data['order_status'], $invoiceNumber), ENT_QUOTES, 'UTF-8');

		  // set invoice
		  $this->model_checkout_order->setInvoiNumber($order_info['order_id'], $invoice);

		  $type = $this->createPDFInvoice($order_info, $order_status_id, $sum_tax_1, $sum_tax_2, $totalNormalProduct);
		  $typeCoupon = $this->createPDFInvoiceCoupon($order_info, $order_status_id, $sum_tax_1, $sum_tax_2, $totalNormalProduct, $generateIDCoupon);

		  $this->model_checkout_order->setCouponNumber($order_info['order_id'], $typeCoupon, $generateIDCoupon);
		  
          $this->sendMailSMTP($order_info['order_id'], $order_info['email'], $subject, '', $fromName, $this->load->view('mail/order_add', $data), 1, $type, $typeCoupon);
		}
		// if status is not complete
		else {
		   $type = $this->createPDFInvoice($order_info, $order_status_id, $sum_tax_1, $sum_tax_2, $totalNormalProduct);
		   $this->sendMailSMTP($order_info['order_id'], $order_info['email'], $subject, '', $fromName, $this->load->view('mail/order_add', $data), 1);
		} 

		$this->sendMailSMTP($order_info['order_id'], $this->config->get('config_email'), $subject, '', $fromName, $this->load->view('mail/order_alert', $data), 1);
		$this->sendMailSMTP($order_info['order_id'], SPECIAL_EMAIL, $subject, '', $fromName, $this->load->view('mail/order_add', $data), 1);
		$this->sendMailSMTP($order_info['order_id'], SPECIAL_EMAIL2, $subject, '', $fromName, $this->load->view('mail/order_add', $data), 1);
    }

	function sendMailSMTP($order_id, $to, $subject, $from, $fromName, $message, $sendMail=1, $type = false, $typeCoupon = false)
   {
		// return;
		
	    $files1 = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']) . "pdf/order_".$order_id.".pdf";
		$files2 = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']) . "pdf/order_event_".$order_id.".pdf";
		$files3 = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']) . "pdf/order_coupon_".$order_id.".pdf";
				
		// $from = "shop@obsthofamsteinberg.de";		
		$from = "webshop@obsthof-am-steinberg.de";
		
		$mail = new PHPMailer();
		
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
		$mail->SMTPAuth = true; // enable SMTP authentication
		// $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Implizite TLS-Verschlüsselung aktivieren
		$mail->SMTPSecure = "ssl";
		$mail->Host = "sxb1plzcpnl504105.prod.sxb1.secureserver.net"; // sets GMAIL as the SMTP server
		// $mail->Host = "smtp.ionos.de"; // sets GMAIL as the SMTP server
		$mail->Port = 465; // set the SMTP port for the GMAIL server
		$mail->Username = 'webshop@obsthof-am-steinberg.de'; // GMAIL username
		$mail->Password = "xUG3Kbj3w4bM3x2";
		// $mail->Password = "!wEr4!hZtvB";
		$mail->CharSet = 'UTF-8';
		// $mail->Encoding = 'base64';
		$mail->AddAddress($to);
		// $mail->addBcc("b@7sc.eu");
		$mail->Subject = $subject;
		$mail->FromName = $fromName;
		$mail->From = $from;
		$mail->IsHTML(true);
		$mail->Body = $message;		
		// $mail->addReplyTo = "webshop@obsthof-am-steinberg.de";
		
		// if order on frontend
		if($sendMail == 1) {
			if($files1) $mail->addAttachment($files1);

			if($type && $files2)
			  $mail->addAttachment($files2);
			
			if($typeCoupon && $files3)
			  $mail->addAttachment($files3);
		}
		    
        if (!$mail->Send()) {
			//echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			//echo "Message sent!";
		}
  }

  public function createPDFInvoice($order_info, $order_status_id, $sum_tax_1 = 0, $sum_tax_2 = 0, $totalNormalProduct = 0) {
	//$this->load->model('checkout/order');

	$order_info = $this->model_checkout_order->getOrder($order_info['order_id']);

	//print_r($order_info); die();  
	$invoice_no = $this->model_checkout_order->countInvoiceNumber() + 1;

	// Check for any downloadable products
	$download_status = false;

	$order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);

	foreach ($order_products as $order_product) {
		// Check if there are any linked downloads
		$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

		if ($product_download_query->row['total']) {
			$download_status = true;
		}
	}
	
	// Load the language for any mails that might be required to be sent out
	$language = new Language($order_info['language_code']);
	$language->load($order_info['language_code']);
	$language->load('mail/order_add');

	// HTML Mail

	$data['text_greeting'] = sprintf($language->get('text_greeting'), $order_info['store_name']);
	$data['text_link'] = $language->get('text_link');
	$data['text_download'] = $language->get('text_download');
	$data['text_order_detail'] = $language->get('text_order_detail');
	$data['text_instruction'] = $language->get('text_instruction');
	$data['text_order_id'] = $language->get('text_order_id');
	$data['text_date_added'] = $language->get('text_date_added');
	$data['text_payment_method'] = $language->get('text_payment_method');
	$data['text_shipping_method'] = $language->get('text_shipping_method');
	$data['text_email'] = $language->get('text_email');
	$data['text_telephone'] = $language->get('text_telephone');
	$data['text_ip'] = $language->get('text_ip');
	$data['text_order_status'] = $language->get('text_order_status');
	$data['text_payment_address'] = $language->get('text_payment_address');
	$data['text_shipping_address'] = $language->get('text_shipping_address');
	$data['text_product'] = $language->get('text_product');
	$data['text_model'] = $language->get('text_model');
	$data['text_quantity'] = $language->get('text_quantity');
	$data['text_price'] = $language->get('text_price');
	$data['text_total'] = $language->get('text_total');
	$data['text_footer'] = $language->get('text_footer');

	$data['logo'] = $order_info['store_url'] . 'image/' . $this->config->get('config_logo');
	$data['store_name'] = $order_info['store_name'];
	$data['store_url'] = $order_info['store_url'];
	$data_1['store_url'] = $order_info['store_url'];
	$data['customer_id'] = $order_info['customer_id'];
	$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];
	
	$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], '5 - '.$data['text_order_status'], $order_info['order_id']);

	if ($download_status) {
		$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
	} else {
		$data['download'] = '';
	}

	$data['order_id'] = $order_info['order_id'];
	$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
	$data['payment_method'] = $order_info['payment_method'];
	$data['shipping_method'] = $order_info['shipping_method'];
	$data['email'] = $order_info['email'];
	$data['telephone'] = $order_info['telephone'];
	$data['ip'] = $order_info['ip'];

	$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

	if ($order_status_query->num_rows) {
		$data['order_status'] = $order_status_query->row['name'];
	} else {
		$data['order_status'] = '';
	}

	if ($comment && $notify) {
		$data['comment'] = nl2br($comment);
	} else {
		$data['comment'] = '';
	}

	if ($order_info['payment_address_format']) {
		$format = $order_info['payment_address_format'];
	} else {
		$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	}

	$find = array(
		'{firstname}',
		'{lastname}',
		'{company}',
		'{address_1}',
		'{address_2}',
		'{city}',
		'{postcode}',
		'{zone}',
		'{zone_code}',
		'{country}'
	);

	$replace = array(
		'firstname' => $order_info['payment_firstname'],
		'lastname'  => $order_info['payment_lastname'],
		'company'   => $order_info['payment_company'],
		'address_1' => $order_info['payment_address_1'],
		'address_2' => $order_info['payment_address_2'],
		'city'      => $order_info['payment_city'],
		'postcode'  => $order_info['payment_postcode'],
		'zone'      => $order_info['payment_zone'],
		'zone_code' => $order_info['payment_zone_code'],
		'country'   => $order_info['payment_country']
	);

	$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

	if ($order_info['shipping_address_format']) {
		$format = $order_info['shipping_address_format'];
	} else {
		$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	}

	$find = array(
		'{firstname}',
		'{lastname}',
		'{company}',
		'{address_1}',
		'{address_2}',
		'{city}',
		'{postcode}',
		'{zone}',
		'{zone_code}',
		'{country}'
	);

	$replace = array(
		'firstname' => $order_info['shipping_firstname'],
		'lastname'  => $order_info['shipping_lastname'],
		'company'   => $order_info['shipping_company'],
		'address_1' => $order_info['shipping_address_1'],
		'address_2' => $order_info['shipping_address_2'],
		'city'      => $order_info['shipping_city'],
		'postcode'  => $order_info['shipping_postcode'],
		'zone'      => $order_info['shipping_zone'],
		'zone_code' => $order_info['shipping_zone_code'],
		'country'   => $order_info['shipping_country']
	);

	$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

	$this->load->model('tool/upload');

	// Products
	$data['products'] = array();

	$data_1 = $data;
	
	foreach ($order_products as $order_product) {
		$option_data = array();

		$order_options = $this->model_checkout_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);

		foreach ($order_options as $order_option) {
			if ($order_option['type'] != 'file') {
				$value = $order_option['value'];
			} else {
				$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

				if ($upload_info) {
					$value = $upload_info['name'];
				} else {
					$value = '';
				}
			}

			$option_data[] = array(
				'name'  => $order_option['name'],
				'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
			);
		}

		// if product is event
		if($order_product['type'] == 1 && $order_product['idOption'] != -1) {
			$data_1['products'][] = array(
				'name'     => $order_product['name'],
				'model'    => $order_product['model'],
				'option'   => $option_data,
				'quantity' => $order_product['quantity'],
				'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			);
		}
		// end

		$data['products'][] = array(
			'name'     => $order_product['name'],
			'model'    => $order_product['model'],
			'option'   => $option_data,
			'quantity' => $order_product['quantity'],
			'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
			'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
		);
	}

	// Vouchers
	$data['vouchers'] = array();

	$order_vouchers = $this->model_checkout_order->getOrderVouchers($order_info['order_id']);

	foreach ($order_vouchers as $order_voucher) {
		$data['vouchers'][] = array(
			'description' => $order_voucher['description'],
			'amount'      => $this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
		);
	}

	// Order Totals
	$data['totals'] = array();
	
	$order_totals = $this->model_checkout_order->getOrderTotals($order_info['order_id']);
	$this->document->displayOrder($order_totals, $sum_tax_1, $sum_tax_2, $this->session->data['shipping_address']['country_id'], $totalNormalProduct, $this->config->get('config_login_attempts'));

	foreach ($order_totals as $order_total) {
		$data['totals'][] = array(
			'title' => $order_total['title'],
			'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
		);
	}

	if($order_status_id == 7) {
		$count = count($data['totals']);
		$data['totals'][$count - 1]['text'] = '-' . $data['totals'][$count - 1]['text'];
	}

	//$data['totals'][$count - 1]['text'] = '-' . $data['totals'][$count - 1]['text'];
	

	$this->load->model('setting/setting');
	
	$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
	
	if (!$from) {
		$from = $this->config->get('config_email');
	}

	if(in_array($order_status_id, $this->config->get('config_processing_status')))
	  $data['status'] = 'AUFTRAG';
	else if(in_array($order_status_id, $this->config->get('config_complete_status')))
	  $data['status'] = 'RECHNUNG';
	else 
	  $data['status'] = 'STORNO';

	$data_1['status'] = $data['status'];

	$invoice_prefix = $order_info['invoice_prefix'];
	$invoice_prefix = explode('-', $invoice_prefix);

	$invoice_prefix[2] = '00';

	$order_info['invoice_prefix'] = $invoice_prefix[0] . '-' . $invoice_prefix[1] . '-' . $invoice_prefix[2];

	if($data['status'] != 'STORNO') {
		$data['order_id'] = ($order_info['invoice_no'] > 0) ? $order_info['invoice_prefix'] . $order_info['invoice_no'] : $order_info['order_id'];
    } else {
		$data['date_added'] = date("Y-m-d");
		$data['order_id'] = ($order_info['invoice_no'] > 0) ? $order_info['invoice_prefix'] . $invoice_no : $order_info['order_id'];
    }
	 
	$data_1['order_id'] = $data['order_id'];

	//create pdf
	$options = new Options();
	$options->set('tempDir', '/tmp');
	$options->set('chroot', __DIR__);    
	$options->set('isRemoteEnabled', TRUE);
	$dompdf = new Dompdf($options);
	// $dompdf->setHtmlFooter($htmlFooter);
	
	$dompdf->loadHtml($this->load->view('mail/order_pdf', $data));
	$dompdf->setPaper('A4', 'Horizontal');
	$dompdf->render();
	$pdf = $dompdf->output();
	$file_location = "./pdf/order_".$order_info['order_id'].".pdf";
	file_put_contents($file_location, $pdf);
	$fileLocal = "./admin/Invoice/".$data['order_id'].".pdf";
	file_put_contents($fileLocal, $pdf); 
	//end

	// if order has event, then create pdf only for event
	if(count($data_1['products'])) {
		$dompdf = new Dompdf($options);
		// $dompdf->setHtmlFooter($htmlFooter);

		$dompdf->loadHtml($this->load->view('mail/order_event_pdf', $data_1));
		$dompdf->setPaper('A4', 'Horizontal');
		$dompdf->render();
		$pdf = $dompdf->output();
		$file_location = "./pdf/order_event_".$order_info['order_id'].".pdf";
		file_put_contents($file_location, $pdf);
	
		return true;
	}

	return false;
}

public function createPDFInvoiceCoupon($order_info, $order_status_id, $sum_tax_1 = 0, $sum_tax_2 = 0, $totalNormalProduct = 0, $generateIDCoupon='A55FR8') {
	//$this->load->model('checkout/order');

	$order_info = $this->model_checkout_order->getOrder($order_info['order_id']);

	//print_r($order_info); die();  
	$invoice_no = $this->model_checkout_order->countInvoiceNumber() + 1;

	// Check for any downloadable products
	$download_status = false;

	$order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);

	foreach ($order_products as $order_product) {
		// Check if there are any linked downloads
		$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

		if ($product_download_query->row['total']) {
			$download_status = true;
		}
	}
	
	// Load the language for any mails that might be required to be sent out
	$language = new Language($order_info['language_code']);
	$language->load($order_info['language_code']);
	$language->load('mail/order_add');

	// HTML Mail

	$data['text_greeting'] = sprintf($language->get('text_greeting'), $order_info['store_name']);
	$data['text_link'] = $language->get('text_link');
	$data['text_download'] = $language->get('text_download');
	$data['text_order_detail'] = $language->get('text_order_detail');
	$data['text_instruction'] = $language->get('text_instruction');
	$data['text_order_id'] = $language->get('text_order_id');
	$data['text_date_added'] = $language->get('text_date_added');
	$data['text_payment_method'] = $language->get('text_payment_method');
	$data['text_shipping_method'] = $language->get('text_shipping_method');
	$data['text_email'] = $language->get('text_email');
	$data['text_telephone'] = $language->get('text_telephone');
	$data['text_ip'] = $language->get('text_ip');
	$data['text_order_status'] = $language->get('text_order_status');
	$data['text_payment_address'] = $language->get('text_payment_address');
	$data['text_shipping_address'] = $language->get('text_shipping_address');
	$data['text_product'] = $language->get('text_product');
	$data['text_model'] = $language->get('text_model');
	$data['text_quantity'] = $language->get('text_quantity');
	$data['text_price'] = $language->get('text_price');
	$data['text_total'] = $language->get('text_total');
	$data['text_footer'] = $language->get('text_footer');

	$data['logo'] = $order_info['store_url'] . 'image/' . $this->config->get('config_logo');
	$data['store_name'] = $order_info['store_name'];
	$data['store_url'] = $order_info['store_url'];
	$data_1['store_url'] = $order_info['store_url'];
	$data['customer_id'] = $order_info['customer_id'];
	$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];
	
	$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], '5 - '.$data['text_order_status'], $order_info['order_id']);

	if ($download_status) {
		$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
	} else {
		$data['download'] = '';
	}

	$data['order_id'] = $order_info['order_id'];
	$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
	$data['payment_method'] = $order_info['payment_method'];
	$data['shipping_method'] = $order_info['shipping_method'];
	$data['email'] = $order_info['email'];
	$data['telephone'] = $order_info['telephone'];
	$data['ip'] = $order_info['ip'];

	$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

	if ($order_status_query->num_rows) {
		$data['order_status'] = $order_status_query->row['name'];
	} else {
		$data['order_status'] = '';
	}

	if ($comment && $notify) {
		$data['comment'] = nl2br($comment);
	} else {
		$data['comment'] = '';
	}

	if ($order_info['payment_address_format']) {
		$format = $order_info['payment_address_format'];
	} else {
		$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	}

	$find = array(
		'{firstname}',
		'{lastname}',
		'{company}',
		'{address_1}',
		'{address_2}',
		'{city}',
		'{postcode}',
		'{zone}',
		'{zone_code}',
		'{country}'
	);

	$replace = array(
		'firstname' => $order_info['payment_firstname'],
		'lastname'  => $order_info['payment_lastname'],
		'company'   => $order_info['payment_company'],
		'address_1' => $order_info['payment_address_1'],
		'address_2' => $order_info['payment_address_2'],
		'city'      => $order_info['payment_city'],
		'postcode'  => $order_info['payment_postcode'],
		'zone'      => $order_info['payment_zone'],
		'zone_code' => $order_info['payment_zone_code'],
		'country'   => $order_info['payment_country']
	);

	$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

	if ($order_info['shipping_address_format']) {
		$format = $order_info['shipping_address_format'];
	} else {
		$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	}

	$find = array(
		'{firstname}',
		'{lastname}',
		'{company}',
		'{address_1}',
		'{address_2}',
		'{city}',
		'{postcode}',
		'{zone}',
		'{zone_code}',
		'{country}'
	);

	$replace = array(
		'firstname' => $order_info['shipping_firstname'],
		'lastname'  => $order_info['shipping_lastname'],
		'company'   => $order_info['shipping_company'],
		'address_1' => $order_info['shipping_address_1'],
		'address_2' => $order_info['shipping_address_2'],
		'city'      => $order_info['shipping_city'],
		'postcode'  => $order_info['shipping_postcode'],
		'zone'      => $order_info['shipping_zone'],
		'zone_code' => $order_info['shipping_zone_code'],
		'country'   => $order_info['shipping_country']
	);

	$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

	$this->load->model('tool/upload');

	// Products
	$data['products'] = array();

	$data_2 = $data;
	
	foreach ($order_products as $order_product) {
		$option_data = array();

		$order_options = $this->model_checkout_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);

		foreach ($order_options as $order_option) {
			if ($order_option['type'] != 'file') {
				$value = $order_option['value'];
			} else {
				$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

				if ($upload_info) {
					$value = $upload_info['name'];
				} else {
					$value = '';
				}
			}

			$option_data[] = array(
				'name'  => $order_option['name'],
				'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
			);
		}

		if($order_product['idOption'] == -1) { // if is coupon
			$data_2['products'][] = array(
				'name'     => $order_product['name'],
				'model'    => $order_product['model'],
				'option'   => $option_data,
				'quantity' => $order_product['quantity'],
				'price_1' => $order_product['price'],
				'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			);
		}
		// end

		$data['products'][] = array(
			'name'     => $order_product['name'],
			'model'    => $order_product['model'],
			'option'   => $option_data,
			'quantity' => $order_product['quantity'],
			'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
			'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
		);
	}

	// Vouchers
	$data['vouchers'] = array();

	$order_vouchers = $this->model_checkout_order->getOrderVouchers($order_info['order_id']);

	foreach ($order_vouchers as $order_voucher) {
		$data['vouchers'][] = array(
			'description' => $order_voucher['description'],
			'amount'      => $this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
		);
	}

	// Order Totals
	$data['totals'] = array();
	
	$order_totals = $this->model_checkout_order->getOrderTotals($order_info['order_id']);
	$this->document->displayOrder($order_totals, $sum_tax_1, $sum_tax_2, $this->session->data['shipping_address']['country_id'], $totalNormalProduct, $this->config->get('config_login_attempts'));

	foreach ($order_totals as $order_total) {
		$data['totals'][] = array(
			'title' => $order_total['title'],
			'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
		);
	}

	if($order_status_id == 7) {
		$count = count($data['totals']);
		$data['totals'][$count - 1]['text'] = '-' . $data['totals'][$count - 1]['text'];
	}

	//$data['totals'][$count - 1]['text'] = '-' . $data['totals'][$count - 1]['text'];
	

	$this->load->model('setting/setting');
	
	$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
	
	if (!$from) {
		$from = $this->config->get('config_email');
	}

	if(in_array($order_status_id, $this->config->get('config_processing_status')))
	  $data['status'] = 'AUFTRAG';
	else if(in_array($order_status_id, $this->config->get('config_complete_status')))
	  $data['status'] = 'RECHNUNG';
	else 
	  $data['status'] = 'STORNO';

	$data_2['status'] = $data['status'];
	
    $invoice_prefix = $order_info['invoice_prefix'];
	$invoice_prefix = explode('-', $invoice_prefix);

	$invoice_prefix[2] = '00';

	$order_info['invoice_prefix'] = $invoice_prefix[0] . '-' . $invoice_prefix[1] . '-' . $invoice_prefix[2];

	if($data['status'] != 'STORNO') {
		$data['order_id'] = ($order_info['invoice_no'] > 0) ? $order_info['invoice_prefix'] . $order_info['invoice_no'] : $order_info['order_id'];
    } else {
		$data['date_added'] = date("Y-m-d");
		$data['order_id'] = ($order_info['invoice_no'] > 0) ? $order_info['invoice_prefix'] . $invoice_no : $order_info['order_id'];
    }
	 
	$data_2['coupon'] = $generateIDCoupon;

	//create pdf
	$options = new Options();
	$options->set('tempDir', '/tmp');
	$options->set('chroot', __DIR__);    
	$options->set('isRemoteEnabled', TRUE);

	// if order has event, then create pdf only for event
	if(count($data_2['products'])) {
		//print_r($data_2['products']); die();
		$dompdf = new Dompdf($options);
		// $dompdf->setHtmlFooter($htmlFooter);

		$data_2['start_day'] = date('d.m.Y');
		
		$currentDate = new DateTime(); // Get the current date
		$currentDate->modify('+3 years'); // Add 3 years to the current date
		$currentDate->setDate($currentDate->format('Y'), 12, 31); // Set the date to December 31st of the current year
		$futureDate = $currentDate->format('d.m.Y');

		$data_2['end_date'] = $futureDate;

		$data_2['price_coupon'] = intval($data_2['products'][0]['price_1']);
		
        $dompdf->loadHtml($this->load->view('mail/order_coupon_pdf', $data_2));
		$dompdf->setPaper('A4', 'Horizontal');
		$dompdf->render();
		$pdf = $dompdf->output();
		$file_location = "./pdf/order_coupon_".$order_info['order_id'].".pdf";
		file_put_contents($file_location, $pdf);

		$this->model_checkout_order->insertCoupon($generateIDCoupon, $data_2['products'][0]['price_1']);
	
		return true;
	}

	return false;
}
	
	public function resend() {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);

		$this->add($order_info, '', '', '');
	}

   public function edit($order_info, $order_status_id, $comment) {
		if($order_status_id == $order_info['order_status_id'])
		 {
			 return; 
		 } 
		 
	    $language = new Language($order_info['language_code']);
		$language->load($order_info['language_code']);
		$language->load('mail/order_edit');

		$this->load->model('checkout/order');
		
		$data['store_url'] = $order_info['store_url'];
		
		$data['text_order_id'] = $language->get('text_order_id');
		$data['text_date_added'] = $language->get('text_date_added');
		$data['text_order_status'] = $language->get('text_order_status');
		$data['text_link'] = $language->get('text_link');
		$data['text_comment'] = $language->get('text_comment');
		$data['text_footer'] = $language->get('text_footer');

		$order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);
		
		foreach ($order_products as $order_product) {
			// Check if there are any linked downloads
			$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

			if ($product_download_query->row['total']) {
				$download_status = true;
			}
		}
		
		// Load the language for any mails that might be required to be sent out
		$language = new Language($order_info['language_code']);
		$language->load($order_info['language_code']);
		$language->load('mail/order_add');

		// HTML Mail

		$data['text_greeting'] = sprintf($language->get('text_greeting'), $order_info['store_name']);
		$data['text_link'] = $language->get('text_link');
		$data['text_download'] = $language->get('text_download');
		$data['text_order_detail'] = $language->get('text_order_detail');
		$data['text_instruction'] = $language->get('text_instruction');
		$data['text_order_id'] = $language->get('text_order_id');
		$data['text_date_added'] = $language->get('text_date_added');
		$data['text_payment_method'] = $language->get('text_payment_method');
		$data['text_shipping_method'] = $language->get('text_shipping_method');
		$data['text_email'] = $language->get('text_email');
		$data['text_telephone'] = $language->get('text_telephone');
		$data['text_ip'] = $language->get('text_ip');
		$data['text_order_status'] = $language->get('text_order_status');
		$data['text_payment_address'] = $language->get('text_payment_address');
		$data['text_shipping_address'] = $language->get('text_shipping_address');
		$data['text_product'] = $language->get('text_product');
		$data['text_model'] = $language->get('text_model');
		$data['text_quantity'] = $language->get('text_quantity');
		$data['text_price'] = $language->get('text_price');
		$data['text_total'] = $language->get('text_total');
		$data['text_footer'] = $language->get('text_footer');

		$data['logo'] = $order_info['store_url'] . 'image/' . $this->config->get('config_logo');
		$data['store_name'] = $order_info['store_name'];
		$data['store_url'] = $order_info['store_url'];
		$data['customer_id'] = $order_info['customer_id'];
		$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];
		
		$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], '6 - '.$data['text_order_status'], $order_info['order_id']);

		if ($download_status) {
			$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
		} else {
			$data['download'] = '';
		}

		$data['order_id'] = $order_info['order_id'];
		$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
		$data['payment_method'] = $order_info['payment_method'];
		$data['shipping_method'] = $order_info['shipping_method'];
		$data['email'] = $order_info['email'];
		$data['telephone'] = $order_info['telephone'];
		$data['ip'] = $order_info['ip'];

		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
	
		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}

		if ($comment && $notify) {
			$data['comment'] = nl2br($comment);
			$data['comment'] .= '<br><br>' . nl2br($order_info['comment']);
		} else {
			$data['comment'] = '';
		}

		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		);

		$replace = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'zone_code' => $order_info['payment_zone_code'],
			'country'   => $order_info['payment_country']
		);
  
		$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$data['first_name'] = $order_info['payment_firstname'];
		$data['last_name'] = $order_info['payment_lastname'];
		
		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		);

		$replace = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'zone_code' => $order_info['shipping_zone_code'],
			'country'   => $order_info['shipping_country']
		);

		$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		$this->load->model('tool/upload');
		$this->load->model('checkout/order');
		
        // Products
		$data['products'] = array();

		$sum_tax_1 = 0; $sum_tax_2 = 0; $totalNormalProduct = 0;

		foreach ($order_products as $order_product) {
			$option_data = array();

			$order_options = $this->model_checkout_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);

			foreach ($order_options as $order_option) {
				if ($order_option['type'] != 'file') {
					$value = $order_option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $order_option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			// VU show tax
			$resultTax_1 = 0; $resultTax_2 = 0;

			$taxs = $this->model_checkout_order->getTaxs();
			$tax_1 = (int) $taxs[0]['rate']; $tax_2 = (int) $taxs[1]['rate'];

			// if normal product
			if($order_product['type'] == 0) {
			   $total_1 = $order_product['price'] * $order_product['quantity'];
			   $totalNormalProduct = $totalNormalProduct + $total_1;

			   $resultTax_1 = round(($total_1) - ($total_1) / (1 + $tax_1/100), 2);

			   $sum_tax_1 = $sum_tax_1 + $resultTax_1;
			} else { // if event
				$total_1 = ($order_product['price'] - $order_product['price_1']) * $order_product['quantity'];
				$resultTax_1 = round(($total_1) - ($total_1) / (1 + $tax_1/100), 2);

				$total_2 = ($order_product['price_1']) * $order_product['quantity'];
				$resultTax_2 = round(($total_2) - ($total_2) / (1 + $tax_2/100), 2);

				$sum_tax_1 = $sum_tax_1 + $resultTax_1;
				$sum_tax_2 = $sum_tax_2 + $resultTax_2;
			}
			 
			// END

			$data['products'][] = array(
				'name'     => $order_product['name'],
				'model'    => $order_product['model'],
				'option'   => $option_data,
				'quantity' => $order_product['quantity'],
				'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'price_number' => $product['price'],
				'price_1'   => $order_product['price_1'],
				'price_2'   => $order_product['price'] - $order_product['price_1'] ,
				'type'      => $order_product['type'],
				'tax_1'     => $resultTax_1,
				'tax_2'     => $resultTax_2,
				'text_tax_1' => $tax_1 . '% of ',//$this->language->get('tax_1'),
				'text_tax_2' => $tax_2 . '% of ', //$this->language->get('tax_2'),
			);
		}

		// Vouchers
		$data['vouchers'] = array();

		$order_vouchers = $this->model_checkout_order->getOrderVouchers($order_info['order_id']);

		foreach ($order_vouchers as $order_voucher) {
			$data['vouchers'][] = array(
				'description' => $order_voucher['description'],
				'amount'      => $this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		// Order Totals
		$data['totals'] = array();

		$order_totals = $this->model_checkout_order->getOrderTotals($order_info['order_id']);

		$this->document->displayOrder($order_totals, $sum_tax_1, $sum_tax_2, $this->session->data['shipping_address']['country_id'], $totalNormalProduct, $this->config->get('config_login_attempts'));

		foreach ($order_totals as $order_total) {
			$data['totals'][] = array(
				'title' => $order_total['title'],
				'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}

		$this->load->model('setting/setting');
		
		$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
		
		if (!$from) {
			$from = $this->config->get('config_email');
		}


		////////////////// below///////////////////////

		$invoice_prefix = $order_info['invoice_prefix'];
		$invoice_prefix = explode('-', $invoice_prefix);

		$invoice_prefix[2] = '00';

		$order_info['invoice_prefix'] = $invoice_prefix[0] . '-' . $invoice_prefix[1] . '-' . $invoice_prefix[2];

        $data['order_id'] = ($order_info['invoice_no'] == 0) ? $order_info['order_id'] : $order_info['invoice_prefix'] . $order_info['invoice_no'];
		$data['invoice_number'] = ($order_info['invoice_no'] == 0) ? false : true;

		$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
		
		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
	
		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}

		if ($order_info['customer_id']) {
			$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];
		} else {
			$data['link'] = '';
		}

		$data['comment'] = strip_tags(nl2br($comment));

		$this->load->model('setting/setting');
		
		$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
		
		if (!$from) {
			$from = $this->config->get('config_email');
		}

		//create pdf
		$type = $this->createPDFInvoice($order_info, $order_status_id, $sum_tax_1, $sum_tax_2, $totalNormalProduct);
		//end
		
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		// $subject = html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $data['order_status'], $order_info['order_id']), ENT_QUOTES, 'UTF-8');
		$subject = html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $data['order_status'], ''), ENT_QUOTES, 'UTF-8');
		
		$mail->setTo($order_info['email']);
		$mail->setFrom($from);
		$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
		// $mail->setSubject(html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $data['order_status'], $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $data['order_status'], ''), ENT_QUOTES, 'UTF-8'));
		$mail->setText($this->load->view('mail/order_edit', $data));
		//$mail->send();
		$template = $data['invoice_number'] ? $this->load->view('mail/order_edit_invoice', $data) : $this->load->view('mail/order_edit', $data);

		/*if($type) // if order has event, then send mail with attach pdf of event
			$this->sendMailSMTP($order_info['email'], $subject, 'shop@obsthofamsteinberg.de', $from, $template, 2);
		else // if not, just send invoice
			$this->sendMailSMTP($order_info['email'], $subject, 'shop@obsthofamsteinberg.de', $from, $template, 3);*/
		 
		$checkStatus = (!in_array($order_status_id, $this->config->get('config_processing_status')) && !in_array($order_status_id, $this->config->get('config_complete_status'))) ? true : false;

		// if status is complete
		if(in_array($order_status_id, $this->config->get('config_complete_status')))
		  $this->sendMailSMTP($order_info['order_id'], $order_info['email'], $subject, '', $from, $template, 1, $type);
		// if status is in process or cancel
		else if(in_array($order_status_id, $this->config->get('config_processing_status')) || $checkStatus)
		  $this->sendMailSMTP($order_info['order_id'], $order_info['email'], $subject, '', $from, $template, 1);
		// status not in above
		else 
		  $this->sendMailSMTP($order_info['order_id'], $order_info['email'], $subject, '', $from, $template, 2, $type);
		    
		/*if($order_status_id == 18 || $order_status_id == 5 || $checkStatus)
		  $this->sendMailSMTP($order_info['email'], $subject, '', $from, $template, 1, $type);
		else 
		  $this->sendMailSMTP($order_info['email'], $subject, '', $from, $template, 2, $type);*/
		   	  
		// if status is complate or cancel
		if(in_array($order_status_id, $this->config->get('config_complete_status')) || $checkStatus)
		  $this->sendMailSMTP($order_info['order_id'], SPECIAL_EMAIL, $subject, '', $from, $template, 1);
	}
	
	// Admin Alert Mail
	public function alert(&$route, &$args) {
		if (isset($args[0])) {
			$order_id = $args[0];
		} else {
			$order_id = 0;
		}
		
		if (isset($args[1])) {
			$order_status_id = $args[1];
		} else {
			$order_status_id = 0;
		}	
		
		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}
		
		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info && !$order_info['order_status_id'] && $order_status_id && in_array('order', (array)$this->config->get('config_mail_alert'))) {	
			$this->load->language('mail/order_alert');
			
			// HTML Mail
			$data['text_received'] = $this->language->get('text_received');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_order_status'] = $this->language->get('text_order_status');
			$data['text_product'] = $this->language->get('text_product');
			$data['text_total'] = $this->language->get('text_total');
			$data['text_comment'] = $this->language->get('text_comment');
			
			$data['order_id'] = $order_info['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

			if ($order_status_query->num_rows) {
				$data['order_status'] = $order_status_query->row['name'];
			} else {
				$data['order_status'] = '';
			}

			$this->load->model('tool/upload');
			
			$data['products'] = array();

			$order_products = $this->model_checkout_order->getOrderProducts($order_id);

			foreach ($order_products as $order_product) {
				$option_data = array();
				
				$order_options = $this->model_checkout_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);
				
				foreach ($order_options as $order_option) {
					if ($order_option['type'] != 'file') {
						$value = $order_option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);
	
						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $order_option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);					
				}
					
				$data['products'][] = array(
					'name'     => $order_product['name'],
					'model'    => $order_product['model'],
					'quantity' => $order_product['quantity'],
					'option'   => $option_data,
					'total'    => html_entity_decode($this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				);
			}
			
			$data['vouchers'] = array();
			
			$order_vouchers = $this->model_checkout_order->getOrderVouchers($order_id);

			foreach ($order_vouchers as $order_voucher) {
				$data['vouchers'][] = array(
					'description' => $order_voucher['description'],
					'amount'      => html_entity_decode($this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				);					
			}

			$data['totals'] = array();
			
			$order_totals = $this->model_checkout_order->getOrderTotals($order_id);

			foreach ($order_totals as $order_total) {
				$data['totals'][] = array(
					'title' => $order_total['title'],
					'value' => html_entity_decode($this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				);
			}

			$data['comment'] = strip_tags($order_info['comment']);

			/*$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');*/

			$subject = html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), '1 - '.$data['text_order_status'], $order_info['order_id']), ENT_QUOTES, 'UTF-8');

			/*$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('mail/order_alert', $data));
			$mail->send();*/

			//$this->sendMailSMTP($order_info['email'], $subject, 'test@7sc.eu', $fromName, $this->load->view('mail/order_alert', $data));
	

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$this->sendMailSMTP($order_info['order_id'], $email, $subject, 'test@7sc.eu', $fromName, $this->load->view('mail/order_alert', $data));
					//$mail->setTo($email);
					//$mail->send();
				}
			}
		}
	}
}
