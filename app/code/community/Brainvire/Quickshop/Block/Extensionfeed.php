<?php

/** 

 * Quickshop block 

 * 

 * @category Brainvire 

 * @package Brainvire_Quickshop

* @copyright Copyright (c) 2015 Brainvire Infotech Pvt Ltd
 
 * 
 */
?>
<?php

class Brainvire_Quickshop_Block_Extensionfeed extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    protected $_url = null;
    protected $_maxFeedCount = 10;

    public function __construct($param) {
        $this->_url = 'http://www.ecomextension.com/media/productsfeed/extensionfeed.xml';

        parent::__construct();
    }

    public function render(Varien_Data_Form_Element_Abstract $element) {

        $html = $this->_getBrainvireExtensions();
        return $html;
    }

    protected function _getBrainvireExtensions() {

        $modules = array_keys((array) Mage::getConfig()->getNode('modules')->children());

        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: feed=bvfeed\r\n"
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($this->_url, true, $context);



        if (!$response) {
            return Mage::helper('core')->__('Could not get extensions suggestion for you. visit %s', "<a href='http://ecomextensions.com/'>Ecomextension</a>");
        }



        $xmlObj = new Varien_Simplexml_Config($response);

        $xmlData = $xmlObj->getNode();


        $html = '';
        $logopath = 'http://www.brainvire.com/wp-content/themes/brainvire/images/brainvire-logo.png';
        $html = <<<HTML
<div style="background:url('$logopath') no-repeat scroll 14px 14px #EAF0EE;border:1px solid #CCCCCC;margin-bottom:20px;padding:10px 5px 5px 264px;">
     <p>
        <strong>PREMIUM and FREE MAGENTO EXTENSIONS</strong><br />
        <a href="http://www.magentocommerce.com/magento-connect/developer/brainvire#extensions" target="_blank">Brainvire</a> offers a wide choice of nice-looking and easily editable free and premium Magento extensions. You can find free and paid extensions for the extremely popular Magento eCommerce platform.<br />       
    </p>
    <p>
        If you have any questions please email us at <a href="mailto:info@brainvire.com">info@brainvire.com</a>
        <br />
    </p>
</div>
HTML;
        $html.='<div style="border: 1px solid rgb(204, 204, 204); background-color: rgb(234, 240, 238); margin-bottom: 20px; padding: 2px 14px;"><strong>MOST POPULAR EXTENSIONS BY BRAINVIRE </strong></div>';
        $_maxFeedCount = 0;
        foreach ($xmlData->extension as $_extension) {          
            if (!in_array($_extension->package_name, $modules) && strlen($_extension->package_name) > 0) {
                $_maxFeedCount++;
                if ($_maxFeedCount <= $this->_maxFeedCount) {
                    $html.=$this->_getExtensionHtml($_extension);
                }
            }
        }
        return $html;
    }

    protected function _getExtensionHtml($extension) {

        $extension->price = Mage::helper('core')->currency($extension->price, true, false);
        $html = '<div class="extension-list" style="float:left; text-align:center; width:20%;">
                    <a style="clear:both;display:block;" target="_blank" href="' . $extension->url . '" alt ="' . $extension->name . '"/>
                    <img src="' . $extension->image . '" alt = "' . $extension->name . '" height="160" width="160"/></a>
                    <strong  class="extension-name">' . $extension->name . '<br><b style="color:#b90100">' . $extension->price . '</b></strong>
                    <a style="clear:both;display:block;text-decoration:none; background:#16a085; color:#fff; padding: 5px 0px; margin:10px 28px;" target="_blank" href="' . $extension->url . '" alt ="' . $extension->name . '"/>' . $this->__('Buy Now') . '</a>
                </div>';
        return $html;
    }

}
