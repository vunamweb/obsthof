<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');

				$data['top']    = $this->load->controller('common/top');
				$data['bottom'] = $this->load->controller('common/bottom');
				
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		// GET MORPHEUS'S CONTENT
		$arrayContent = MORPHEUS_HOMEPAGE;
		$content = '';
        $language = 'de/';

        $content = '<div class="paralax fadeOut"><h2>Warum</h2><h1>Für ein gesundes Heranwachsen Ihres Kindes</h1></div><br><br><br>';

		$count = 0;

		foreach($arrayContent as $item) {
			$urlMorpheus = $item;
			$urlMorpheus = HTTP_SERVER . 'cms/' . $language . $urlMorpheus;
			//echo $urlMorpheus; die();
			
			$objectMorpheus = file_get_contents($urlMorpheus);
			$objectMorpheus = str_replace(array('</body>', '</html>'), '', $objectMorpheus);
			$objectMorpheus = json_decode($objectMorpheus);

			//print_r($objectMorpheus); die();
			$contentMorpheus = $objectMorpheus->message;
			
			if($count == 0)
			  $data['content_morpheus_top'] = '<div class="fadeOut">' . $contentMorpheus . '<br><br></div>';
			else 
			  $data['content_morpheus_bottom'] = '<div class="fadeOut">' . $contentMorpheus . '<br><br></div>';
			
			$count++;  

            //$content .= '<div class="fadeOut">' . $contentMorpheus . '<br><br></div>';
		}

		//$data['content_morpheus'] = $content;
		// END

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
