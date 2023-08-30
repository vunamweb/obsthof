<?php
class ControllerExtensionModuleZemezOpen extends Controller {
	public function index() {
		$this->load->language('extension/module/zemez_open');

		$data['open'] = $this->config->get('config_open');

		return $this->load->view('extension/module/zemez_open', $data);
	}
}