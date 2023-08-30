<?php
class ControllerExtensionModuleZemezPhones extends Controller {
	public function index() {
		$this->load->language('extension/module/zemez_phones');

		$data['telephone'] = $this->config->get('config_telephone');
		$data['fax']       = $this->config->get('config_fax');

		return $this->load->view('extension/module/zemez_phones', $data);
	}
}