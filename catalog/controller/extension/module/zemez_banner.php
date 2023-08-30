<?php
class ControllerExtensionModuleZemezBanner extends Controller {
	public function index($setting) {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['zemez_banner'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
			
			if ($setting['link']) {
				$data['link'] =$setting['link'];
			} else {
				$data['link'] = '';
			}
			
			if ($setting['link1']) {
				$data['link1'] =$setting['link1'];
			} else {
				$data['link1'] = '';
			}
			
			if ($setting['link2']) {
				$data['link2'] =$setting['link2'];
			} else {
				$data['link2'] = '';
			}
			
			if ($setting['image']) {
				$data['image'] = $this->model_tool_image->resize($setting['image'], $setting['width'], $setting['height']);
			} else {
				$data['image'] = '';
			}
			
			if ($setting['image1']) {
				$data['image1'] = $this->model_tool_image->resize($setting['image1'], $setting['width'], $setting['height']);
			} else {
				$data['image1'] = '';
			}
			
			if ($setting['image2']) {
				$data['image2'] = $this->model_tool_image->resize($setting['image2'], $setting['width'], $setting['height']);
			} else {
				$data['image2'] = '';
			}
			
			if ($setting['width']) {
				$data['width'] = $setting['width'];
			} else {
				$data['width'] = '0';
			}
			if ($setting['height']) {
				$data['height'] = $setting['height'];
			} else {
				$data['height'] = '0';
			}
			
			
			
			return $this->load->view('extension/module/zemez_banner', $data);
		}
	}
}