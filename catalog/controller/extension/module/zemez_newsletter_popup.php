<?php
class ControllerExtensionModuleZemezNewsletterPopup extends Controller
{
	private $error = array();

	private function install()
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "zemez_newsletter` (
			`zemez_newsletter_id` int(11) NOT NULL AUTO_INCREMENT,
			`zemez_newsletter_email` varchar(255) NOT NULL,
			PRIMARY KEY (`zemez_newsletter_id`)
			)");
	}

	public function index($setting) {
		$this->install();
		$this->load->language('extension/module/zemez_newsletter_popup');
		$data['user_mail'] = '';

		$this->load->model('account/customer');
		if ($this->customer->isLogged() && $this->customer->getNewsletter() == 1){
			$data['show'] = true;
		} elseif ($this->customer->isLogged() && $this->customer->getNewsletter() == 0){
			$data['show'] = '';
			$data['user_mail'] = $this->customer->getEmail();
		} else {
			$data['show'] = '';
		}

		$data['cookie_time'] = $setting['newsletter_popup_cookie'];

		$bgWidth  = $setting['newsletter_popup_bg_width'];
		$bgHeight = $setting['newsletter_popup_bg_height'];
		if (is_file(DIR_IMAGE .  $setting['newsletter_popup_bg'])){
			$this->load->model('tool/image');
			$data['popup_bg'] = $this->model_tool_image->resize($setting['newsletter_popup_bg'], $bgWidth, $bgHeight);
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		$data['name'] = $this->config->get('config_name');		

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}
		if (isset($setting['zemez_newsletter_popup_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['zemez_newsletter_popup_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['html'] = html_entity_decode($setting['zemez_newsletter_popup_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
		}

		$data['action'] = $this->url->link('extension/module/zemez_newsletter_popup', '', true);

		return $this->load->view('extension/module/zemez_newsletter_popup', $data);
	}
	public function addNewsletter() {
		$this->load->model('account/customer');
		$this->load->model('extension/module/zemez_newsletter');
		$this->load->language('extension/module/zemez_newsletter_popup');

		$input_email = $this->request->post['zemez_newsletter_popup_email'];

		if ($this->customer->isLogged() && strcmp($this->customer->getEmail(), $input_email) == 0) {
			if ($this->customer->getNewsletter() == 0) {
				$this->model_account_customer->editNewsletter(1);
			} else {
				$this->error['zemez_newsletter_exist_email'] = $this->language->get('error_exist_email');
			}
		} else {
			if (count($this->model_extension_module_zemez_newsletter->getNewsletterByEmail($input_email)) != 0) {
				$this->error['zemez_newsletter_exist_email'] = $this->language->get('error_exist_email');
			} else if (count($this->model_account_customer->getCustomerByEmail($input_email)) == 0) {
				$this->model_extension_module_zemez_newsletter->addNewsletter($input_email);
			} else {
				$this->error['zemez_newsletter_exist_user'] = $this->language->get('error_exist_user');
			}
		}

		if (count($this->error) > 0) {
			foreach ($this->error as $err) {
				echo $err;
			}
		}
	}
}