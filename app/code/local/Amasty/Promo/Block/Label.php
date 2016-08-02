<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */

    class Amasty_Promo_Block_Label extends Amasty_Promo_Block_Banner
    {
        function getImage()
        {
            $validRule = $this->_getValidRule();
            return Mage::helper("ampromo/image")->getLink($validRule->getData('ampromo_label_img'));
        }
        
        function getAlt()
        {
            $validRule = $this->_getValidRule();
            return $validRule->getData('ampromo_label_alt');
        }
    }
?>