<?php
class ControllerExtensionModuleZemezManufacturer extends Controller
{
	public function index($setting)
	{
		static $module = 0;
		$this->load->language('extension/module/zemez_manufacturer');
		$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');

		$data['heading_title'] = $this->language->get('heading_brand_title');

		$data['categories'] = array();

		$manufacturer_filter = array(
			'start' => 0,
			'limit' => $setting['limit']
			);

		$results = $this->model_catalog_manufacturer->getManufacturers($manufacturer_filter);

		foreach ($results as $result) {

			if (!isset($data['categories'])) {
				$data['categories']['name'] = $result['name'];
			}
			$data['categories']['manufacturer'][] = array(
				'name' => $result['name'],
				'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
				'image' => $result['image']
				);
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/zemez_manufacturer', $data);
	}
}