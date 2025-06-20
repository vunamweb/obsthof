<?php
class ControllerExtensionModuleCategoryHierarchy extends Controller {
	public function index() {
        $this->load->language('extension/module/category_hierarchy');
        $this->load->language('product/category');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

                //$data["cat_id"] = (isset($parts[1]))?$parts[1]:((isset($parts[0]))?$parts[0]:0);
                $data["cat_id"] = $this->model_catalog_category->getRootCategory();//$parts[0];
                $data["current"] = $parts[1];
                
		$data['categories'] = array();
                if($data["cat_id"]!=0){

                    $currrent_category = $this->model_catalog_category->getCategory($data["cat_id"]);
                    $categories = $this->model_catalog_category->getCategories($data["cat_id"]);
                    //echo $data["cat_id"] . '/' . count($categories);
                    $children_data = array();
                    $sub_child = array();

                    foreach ($categories as $category) {
                        $sub_child = array();

                        //print_r($category);        
                        $children = $this->model_catalog_category->getCategories($category['category_id']);
                            if(count($children)>0){
                                        foreach($children as $child) {

                                              $filter_data = array();
                                            $filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

                                            $category_info = $this->model_catalog_category->getCategory($child['category_id']);
                                            $categoryType = $category_info['type'];

                                            if($categoryType == 1) {
                                                $sub_child_child = array();

                                                // get children of subcategory
                                                $childrenOfChildren = $this->model_catalog_category->getCategories($child['category_id']);
                                                if(count($childrenOfChildren)>0){
                                                    foreach($childrenOfChildren as $childOfChild) {
                                                        $filter_data_1 = array();
                                                        $filter_data_1 = array('filter_category_id' => $childOfChild['category_id'], 'filter_sub_category' => true);
            
                                                        if($this->model_catalog_product->getTotalEvents($filter_data_1) > 0) {
                                                            $sub_child_child[] = array(
                                                                'category_id' => $childOfChild['category_id'], 
                                                                'name' => $childOfChild['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalEvents($filter_data_1) . ')' : ''), 
                                                                'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $childOfChild['category_id'])
                                                            );
                                                        }
                                                    }
                                                }
                                                // end 
                                                if($this->model_catalog_product->getTotalEvents($filter_data)) {
                                                    $sub_child[] = array(
                                                        'category_id' => $child['category_id'], 
                                                        'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalEvents($filter_data) . ')' : ''), 
                                                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
                                                        'children'=>$sub_child_child
                                                    );
                                                }
                                            }
                                            else 
                                                $sub_child[] = array(
                                                    'category_id' => $child['category_id'], 
                                                    'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''), 
                                                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                                                );
                                    }
                                }else{
                                    $sub_child = array();
                                }
//                            echo '<pre>';
//                            var_dump($sub_child); // $currrent_category["parent_id"];
                            $filter_data = array();
                            $filter_data = array('filter_category_id' => $category['category_id'], 'filter_sub_category' => true);

                            $category_info = $this->model_catalog_category->getCategory($category['category_id']);
                            $categoryType = $category_info['type'];

                            if($categoryType == 1)
                                $children_data[] = array(
                                        'category_id' => $category['category_id'], 
                                        'name' => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalEvents($filter_data) . ')' : ''), 
                                        'href' => $this->url->link('product/category', 'path=' . $currrent_category['category_id'] . '_' . $category['category_id']),
                                        'children'=>$sub_child
                                );
                            else 
                                $children_data[] = array(
                                    'category_id' => $category['category_id'], 
                                    'name' => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''), 
                                    'href' => $this->url->link('product/category', 'path=' . $currrent_category['category_id'] . '_' . $category['category_id']),
                                    'children'=>$sub_child
                                );
}
                    
//                echo '<pre>';
//                var_dump($children_data);
//                die();
                    $zeroLevel = array(
                            'category_id' => $currrent_category["category_id"],
                            'name'        => $currrent_category['name'],
                            'href'        => $this->url->link('product/category', 'path=' . $currrent_category['category_id']),
                            'children'    => $children_data
                        );
                        /*$zeroLevel[1] = array(
                            'category_id' => $currrent_category["category_id"],
                            'name'        => $currrent_category['name'],
                            'href'        => $this->url->link('product/category', 'path=' . $currrent_category['category_id']),
                            'children'    => $children_data
                        );*/
                    //print_r($zeroLevel);    
                        
                    $parentLevel1 = array();
                    $parentLevel2 = array();
                    $parent1 = array();
                    $parent2 = array();
                    if($currrent_category["parent_id"]==0){
                        $zeroLevel['sub_cat']= $children_data;
                        $cat_data = $zeroLevel;
                    }else{
                        if(empty($children_data)){
//                            die('here');
//print_r($currrent_category);                            
$siblings_cats = $this->model_catalog_category->getCategories($currrent_category["parent_id"]);
//print_r($siblings_cats);
                            foreach($siblings_cats as $siblings_cat) {
                                    $filter_data = array();
                                    $filter_data = array(
                                        'filter_category_id'  => $siblings_cat['category_id'],
                                        'filter_sub_category' => true
                                    );
                                    $siblings[] = array(
                                    'category_id' => $siblings_cat['category_id'], 
                                    'name' => $siblings_cat['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''), 
                                    'href' => $this->url->link('product/category', 'path=' . $currrent_category["parent_id"] . '_' . $siblings_cat['category_id'])
                                );
                            }
                            $zeroLevel['siblings']= $siblings;
                        }
                        $parentLevel1 = $this->model_catalog_category->getCategory($currrent_category["parent_id"]);
                        $zeroLevel['href'] = $this->url->link('product/category', 'path='.$parentLevel1["category_id"].'_' . $currrent_category['category_id']);
                        $parent1 = array(
                            'category_id' => $parentLevel1["category_id"],
                            'name'        => $parentLevel1['name'],
                            'href'        => $this->url->link('product/category', 'path=' . $parentLevel1['category_id']),
                            'sub_cat'    => $zeroLevel
                        );
                        if($parentLevel1['parent_id']==0){
                            $cat_data = $parent1;
                        }else{
    //                        DIE($parentLevel1['parent_id']);
                            $parentLevel2 = $this->model_catalog_category->getCategory($parentLevel1["parent_id"]);
                            $parent1['href'] = $this->url->link('product/category', 'path='.$parentLevel2["category_id"].'_' . $parentLevel1['category_id']);
                            $parent2 = array(
                                'category_id' => $parentLevel2["category_id"],
                                'name'        => $parentLevel2['name'],
                                'href'        => $this->url->link('product/category', 'path=' . $parentLevel2['category_id']),
                                'sub_cat'    => $parent1
                            );  
                            $cat_data = $parent2;
                        }

                    }
                    $data['categories'][] = $cat_data;   
                }

		return $this->load->view('extension/module/category_hierarchy', $data);

	}
}