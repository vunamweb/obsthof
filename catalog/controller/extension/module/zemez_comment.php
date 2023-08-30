<?php
class ControllerExtensionModuleZemezComment extends Controller {
	public function index() {
		$data['comment'] = $this->config->get('config_comment');
		return $this->load->view('extension/module/zemez_comment', $data);
	}
}