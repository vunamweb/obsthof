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
		$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], $order_info['order_id']);

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

		//create pdf
		$options = new Options();
		$options->set('tempDir', '/tmp');
		$options->set('chroot', __DIR__);    
		$options->set('isRemoteEnabled', TRUE);

		$dompdf = new Dompdf($options);
		
		//$dompdf = new Dompdf();
		//$dompdf->set_option('enable_html5_parser', TRUE);
		//$dompdf->set_option('isRemoteEnabled', TRUE);
		
		//$dompdf->loadHtml($this->load->view('mail/order_pdf', $data));
		$dompdf->loadHtml($this->load->view('mail/order_pdf', $data));
		$dompdf->setPaper('A4', 'Horizontal');
		$dompdf->render();
		$pdf = $dompdf->output();
		$file_location = "./pdf/order.pdf";
		file_put_contents($file_location, $pdf);
		//end
		
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($order_info['email']);
		$mail->setFrom($from);
		$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($this->load->view('mail/order_add', $data));
		//$mail->send();
		$subject = html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $order_info['order_id']), ENT_QUOTES, 'UTF-8');
		$fromName = html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8');
		$this->sendMailSMTP($order_info['email'], $subject, 'test@7sc.eu', $fromName, $this->load->view('mail/order_add', $data));
		//$this->sendMailSMTP("vukynamkhtn@gmail.com", $subject, 'test@7sc.eu', $fromName, $this->load->view('mail/order_add', $data));
		//$this->sendMailSMTP($this->config->get('config_email'), $subject, 'test@7sc.eu', $fromName, $this->load->view('mail/order_add', $data));
	}

	function sendMailSMTP($to, $subject, $from, $fromName, $message, $senMail=true)
   {
	    $files1 = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']) . "pdf/order.pdf";
			
	    $mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
		// $mail->SMTPSecure = "STARTTLS";
		$mail->Host = "w0118b8d.kasserver.com"; // sets GMAIL as the SMTP server
		$mail->Port = 465; // set the SMTP port for the GMAIL server
		$mail->Username = "test@7sc.eu"; // GMAIL username
		$mail->Password = "FKEUcsvv2HtgtWvJ";
		$mail->CharSet = 'UTF-8';
		// $mail->Encoding = 'base64';
		$mail->AddAddress($to);
		// $mail->addBcc("b@7sc.eu");
		$mail->Subject = $subject;
		$mail->FromName = $fromName;
		$mail->From = $from;
		$mail->IsHTML(true);
		$mail->Body = $message;
		if($senMail)
		  $mail->addAttachment($files1);

		if (!$mail->Send()) {
			//echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			//echo "Message sent!";
		}
  }

  public function createPDFInvoice($order_info, $order_status_id) {
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
	$data['title'] = sprintf($language->get('text_subject'), $order_info['store_name'], $order_info['order_id']);

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

	//create pdf
	$dompdf = new Dompdf();
	$dompdf->set_option('enable_html5_parser', TRUE);
	$dompdf->loadHtml($this->load->view('mail/order_pdf', $data));
	$dompdf->setPaper('A4', 'Horizontal');
	$dompdf->render();
	$pdf = $dompdf->output();
	$file_location = "./pdf/order.pdf";
	file_put_contents($file_location, $pdf);
	//end
}
	
	public function resend() {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);

		$this->add($order_info, '', '', '');
	}

   public function edit($order_info, $order_status_id, $comment) {
		$language = new Language($order_info['language_code']);
		$language->load($order_info['language_code']);
		$language->load('mail/order_edit');

		$data['text_order_id'] = $language->get('text_order_id');
		$data['text_date_added'] = $language->get('text_date_added');
		$data['text_order_status'] = $language->get('text_order_status');
		$data['text_link'] = $language->get('text_link');
		$data['text_comment'] = $language->get('text_comment');
		$data['text_footer'] = $language->get('text_footer');

		$data['order_id'] = $order_info['order_id'];
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

		$data['comment'] = strip_tags($comment);

		$this->load->model('setting/setting');
		
		$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
		
		if (!$from) {
			$from = $this->config->get('config_email');
		}

		//create pdf
		$this->createPDFInvoice($order_info, $order_status_id);
		//end
		
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$subject = html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $order_info['order_id']), ENT_QUOTES, 'UTF-8');
		
		$mail->setTo($order_info['email']);
		$mail->setFrom($from);
		$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode(sprintf($language->get('text_subject'), $order_info['store_name'], $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
		$mail->setText($this->load->view('mail/order_edit', $data));
		//$mail->send();
		$this->sendMailSMTP($order_info['email'], $subject, 'test@7sc.eu', $from, $this->load->view('mail/order_edit', $data), false);
		$this->sendMailSMTP($this->config->get('config_email'), $subject, 'test@7sc.eu', $from, $this->load->view('mail/order_edit', $data));
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

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$subject = 'mail';

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('mail/order_alert', $data));
			//$mail->send();
			$this->sendMailSMTP($order_info['email'], $subject, 'test@7sc.eu', $fromName, $this->load->view('mail/order_alert', $data));
	

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
}
