<?php
class ControllerErrorNotFound extends Controller {
	public function index() {
		// GET CONTENT OF MORPHEUS
		$language = 'de/';

		$urlMorpheus = $this->request->get['_route_'];
		$urlMorpheus = HTTP_SERVER . 'cms/' . $language . $urlMorpheus;

		$response = file_get_contents($urlMorpheus);
		$response = str_replace(array('</body>', '</html>'), '', $response);

		$response = json_decode($response);

		$contentMorpheus = $response->message;
		$title = $response->title;
		$des = $response->des;
		// END

		$this->load->language('error/not_found');

		$this->document->setTitle($title);
		$this->document->setDescription($des);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['route'])) {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link($route, $url, $this->request->server['HTTPS'])
			);
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['content_morpheus'] = $contentMorpheus;

		//$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

		$this->response->setOutput($this->load->view('common/morpheus', $data));
	}
}