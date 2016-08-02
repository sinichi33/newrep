<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Checkout_Onepage_Progress extends Mage_Checkout_Block_Onepage_Progress
{
	protected function _toHtml()
	{
		$html  = parent::_toHtml();
		if (Mage::getStoreConfig('amorderattr/checkout/progress'))
		{
			$html = preg_replace('@opc-billing(.*?)<\/dd>@s', 'opc-billing$1' 		   . $this->_getOrderAttributesHtml(2), $html);
			$html = preg_replace('@opc-shipping(.*?)<\/dd>@s', 'opc-shipping$1' 	   . $this->_getOrderAttributesHtml(3), $html);
			$html = preg_replace('@opc-shipping_method(.*?)<\/dd>@s', 'opc-shipping_method$1' . $this->_getOrderAttributesHtml(4), $html);
			$html = preg_replace('@opc-payment(.*?)<\/dd>@s', 'opc-payment$1'          . $this->_getOrderAttributesHtml(5), $html);
			
			$html = preg_replace('@billing"(.*?)<\/dd>@s', 'billing"$1' 		        . $this->_getOrderAttributesHtml(2), $html);
			$html = preg_replace('@shipping"(.*?)<\/dd>@s', 'shipping"$1' 	            . $this->_getOrderAttributesHtml(3), $html);
            $html = preg_replace('@shipping_method"(.*?)<\/dd>@s', 'shipping_method"$1' . $this->_getOrderAttributesHtml(4), $html);
            $html = preg_replace('@payment"(.*?)<\/dd>@s', 'payment"$1'                 . $this->_getOrderAttributesHtml(5), $html);
		}
		return $html;
	}
	
	protected function _getOrderAttributesHtml($step)
	{
		$step = Mage::app()->getLayout()->createBlock('amorderattr/checkout_onepage_progress_step', 'amorderattr.progress-step.' . $step, array('step' => $step));
		return $step->toHtml();
	}
}