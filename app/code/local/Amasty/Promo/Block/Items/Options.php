<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */
class Amasty_Promo_Block_Items_Options extends Mage_Catalog_Block_Product_View_Options
{
    public function __construct(array $args = array())
    {
        parent::__construct();

        if (isset($args['product']))
            $this->_product = $args['product'];

        $this
            ->addOptionRenderer('text', 'catalog/product_view_options_type_text', 'catalog/product/view/options/type/text.phtml')
            ->addOptionRenderer('file', 'catalog/product_view_options_type_file', 'catalog/product/view/options/type/file.phtml')
            ->addOptionRenderer('select', 'catalog/product_view_options_type_select', 'catalog/product/view/options/type/select.phtml')
            ->addOptionRenderer('date', 'catalog/product_view_options_type_date', 'catalog/product/view/options/type/date.phtml')

            ->setTemplate('catalog/product/view/options.phtml')
        ;
    }
}
