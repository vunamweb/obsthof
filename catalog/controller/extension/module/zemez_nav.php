<?php
class ControllerExtensionModuleZemezNav extends Controller {
	public function index() {
		$this->load->language('common/header');
		$this->load->language('extension/module/zemez_nav');

		$data['text_compare']       = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_checkout']      = $this->language->get('text_checkout');

		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');
			
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['logged']        = $this->customer->isLogged();
		$data['home']          = $this->url->link('common/home');
		$data['account']       = $this->url->link('account/account', '', true);
		$data['order']         = $this->url->link('account/order', '', true);
		$data['transaction']   = $this->url->link('account/transaction', '', true);
		$data['download']      = $this->url->link('account/download', '', true);
		$data['logout']        = $this->url->link('account/logout', '', true);
		$data['register']      = $this->url->link('account/register', '', true);
		$data['login']         = $this->url->link('account/login', '', true);
		$data['compare']       = $this->url->link('product/compare');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout']      = $this->url->link('checkout/checkout', '', true);
		$data['wishlist']      = $this->url->link('account/wishlist', '', true);
		$data['telephone']     = $this->config->get('config_telephone');

		return $this->load->view('extension/module/zemez_nav', $data);
	}
}