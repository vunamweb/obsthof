<?php
class ControllerExtensionModuleZemezBanner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/zemez_banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('zemez_banner', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['link'])) {
			$data['error_link'] = $this->error['link'];
		} else {
			$data['error_link'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/zemez_banner', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/zemez_banner', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/zemez_banner', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/zemez_banner', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['link'])) {
			$data['link'] = $this->request->post['link'];
		} elseif (!empty($module_info)) {
			$data['link'] = $module_info['link'];
		} else {
			$data['link'] = '';
		}
		
		if (isset($this->request->post['link1'])) {
			$data['link1'] = $this->request->post['link1'];
		} elseif (!empty($module_info)) {
			$data['link1'] = $module_info['link1'];
		} else {
			$data['link1'] = '';
		}
		
		if (isset($this->request->post['link2'])) {
			$data['link2'] = $this->request->post['link2'];
		} elseif (!empty($module_info)) {
			$data['link2'] = $module_info['link2'];
		} else {
			$data['link2'] = '';
		}

		if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		} elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		} else {
			$data['module_description'] = array();
		}

		$this->load->model('localisation/language');
		$this->load->model('tool/image');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($module_info)) {
			$data['image'] = $module_info['image'];
		} else {
			$data['image'] = '';
		}
		
		if (isset($this->request->post['image1'])) {
			$data['image1'] = $this->request->post['image1'];
		} elseif (!empty($module_info)) {
			$data['image1'] = $module_info['image1'];
		} else {
			$data['image1'] = '';
		}
		
		if (isset($this->request->post['image2'])) {
			$data['image2'] = $this->request->post['image2'];
		} elseif (!empty($module_info)) {
			$data['image2'] = $module_info['image2'];
		} else {
			$data['image2'] = '';
		}
		
		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($module_info) && isset($module_info['image']) && is_file(DIR_IMAGE . $module_info['image'])) {
			$data['image_thumb'] = $this->model_tool_image->resize($module_info['image'], 100, 100);
		} else {
			$data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['image1']) && is_file(DIR_IMAGE . $this->request->post['image1'])) {
			$data['image_thumb1'] = $this->model_tool_image->resize($this->request->post['image1'], 100, 100);
		} elseif (!empty($module_info) && isset($module_info['image1']) && is_file(DIR_IMAGE . $module_info['image1'])) {
			$data['image_thumb1'] = $this->model_tool_image->resize($module_info['image1'], 100, 100);
		} else {
			$data['image_thumb1'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['image2']) && is_file(DIR_IMAGE . $this->request->post['image2'])) {
			$data['image_thumb2'] = $this->model_tool_image->resize($this->request->post['image2'], 100, 100);
		} elseif (!empty($module_info) && isset($module_info['image1']) && is_file(DIR_IMAGE . $module_info['image2'])) {
			$data['image_thumb2'] = $this->model_tool_image->resize($module_info['image2'], 100, 100);
		} else {
			$data['image_thumb2'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}		
		
		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->response->setOutput($this->load->view('extension/module/zemez_banner', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/zemez_banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}