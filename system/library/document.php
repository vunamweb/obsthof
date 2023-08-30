<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Document class
*/
class Document {
	private $title;
	private $description;
	private $keywords;

	private $links = array();
	private $styles = array();
	private $scripts = array();

	/**
     * 
     *
     * @param	string	$title
     */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
     * 
	 * 
	 * @return	string
     */
	public function getTitle() {
		return $this->title;
	}

	/**
     * 
     *
     * @param	string	$description
     */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
     * 
     *
     * @param	string	$description
	 * 
	 * @return	string
     */
	public function getDescription() {
		return $this->description;
	}

	/**
     * 
     *
     * @param	string	$keywords
     */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
     *
	 * 
	 * @return	string
     */
	public function getKeywords() {
		return $this->keywords;
	}
	
	/**
     * 
     *
     * @param	string	$href
	 * @param	string	$rel
     */
	public function addLink($href, $rel) {
		$this->links[$href] = array(
			'href' => $href,
			'rel'  => $rel
		);
	}

	/**
     * 
	 * 
	 * @return	array
     */
	public function getLinks() {
		return $this->links;
	}

	/**
     * 
     *
     * @param	string	$href
	 * @param	string	$rel
	 * @param	string	$media
     */
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen', $position = 'header') {
		$this->styles[$position][$href] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}

	/**
     * 
	 * 
	 * @return	array
     */
	public function getStyles($position = 'header') {
		if (isset($this->styles[$position])) {
			return $this->styles[$position];
		} else {
			return array();
		}
	}

	/**
     * 
     *
     * @param	string	$href
	 * @param	string	$position
     */
	public function addScript($href, $position = 'header') {
		$this->scripts[$position][$href] = $href;
	}

	/**
     * 
     *
     * @param	string	$position
	 * 
	 * @return	array
     */
	public function getScripts($position = 'header') {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return array();
		}
	}

	public function displayOrder(&$totals, $sum_tax_1, $sum_tax_2, $country_id = null, $totalNormalProduct = 0)
     {
          $this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate");
  
          $taxObj = $query->rows;
          //print_r($taxObj); die();
          $tax_1 = (int) $taxObj[1]['rate'];
          $tax_2 = (int) $taxObj[0]['rate'];
            
          //echo count($totals); die();
            //print_r($country_id);
          if (count($totals) == 2) {
               //echo count($totals);
               $totals[2]['title'] = 'enthaltene MwSt. ('.$tax_1.'%)';
               $totals[2]['value'] = $sum_tax_2;

               $totals[3] = $totals[1];
               

               $totals[1]['title'] = 'enthaltene MwSt. ('.$tax_2.'%)';
               $totals[1]['value'] = $sum_tax_1;

               
               //print_r($totals);
          } else if (count($totals) == 3) {
               // set shipping free or not free
               // if germany
               /*if ($country_id == 258) {
                    if ((int) $totals[0]['value'] >= MIN_SHIP_GERMANY) {
                         $totals[1]['title'] = 'Versand';
                         $totals[1]['value'] = 0;

                         $totals[2]['value'] = $totals[0]['value'] + $totals[1]['value'];
                    }
               } else {
                    if ((int) $totals[0]['value'] >= MIN_SHIP_SWITZERLAND) {
                         $totals[1]['title'] = 'Versand';
                         $totals[1]['value'] = 0;

                         $totals[2]['value'] = $totals[0]['value'] + $totals[1]['value'];
                    }
               }*/
               //end set shipping
               //print_r($totals);die();
               $totals[3]['title'] = 'enthaltene MwSt. ('.$tax_1.'%)';
               $totals[3]['value'] = $sum_tax_2;

               $totals[4] = $totals[2];
               // if total normal product >=100
               if($totalNormalProduct >= 100) {
                    $totals[4]['value'] = $totals[0]['value'];
                    $totals[1]['value'] = 0;   
               }
                 
               $totals[2]['title'] = 'enthaltene MwSt. ('.$tax_2.'%)';
               $totals[2]['value'] = $sum_tax_1 + round( $totals[1]['value'] - $totals[1]['value']/1.19 ,2);

               //print_r($totals);
          } else {
               // set shipping free or not free
               // if germany
               /*if ($country_id == 258) {
                    if ((int) $totals[0]['value'] >= MIN_SHIP_GERMANY) {
                         $totals[1]['title'] = 'Versand';
                         $totals[1]['value'] = 0;

                         $totals[2]['value'] = $totals[0]['value'] + $totals[1]['value'];
                    }
               } else {
                    if ((int) $totals[0]['value'] >= MIN_SHIP_SWITZERLAND) {
                         $totals[1]['title'] = 'Versand';
                         $totals[1]['value'] = 0;

                         $totals[2]['value'] = $totals[0]['value'] + $totals[1]['value'];
                    }
               }*/
               //end set shipping
               //print_r($totals);die();
               
               $totals[4] = $totals[3];

               $value1 = $totals[0]['value'];
               $value2 = $totals[1]['value'];
               $value3 = $totals[2]['value'];

               $value_ = ($value1 + $value2 + $value3) - ($value1 + $value2 + $value3) / (1 + $tax1/100);
               //echo $value1 + $value2 + $value3;
               $totals[3]['title'] = 'enthaltene MwSt. ('.$tax1.'%)';
               $totals[3]['value'] = $value_;
          }
     }
}