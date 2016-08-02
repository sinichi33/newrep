<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2015-06-29T23:10:48+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Shared/Items.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Shared_Items extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    private $_origWriteArray;
    protected $_totalCost = 0;

    public function getConfiguration()
    {
        // Init cache
        if (!isset($this->_cache['product_attributes'])) {
            $this->_cache['product_attributes'] = array();
        }
        // Return config
        return array(
            'name' => 'Item information',
            'category' => 'Shared',
            'description' => 'Export ordered/invoiced/shipped/refunded items of entity.',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO, Xtento_OrderExport_Model_Export::ENTITY_QUOTE, Xtento_OrderExport_Model_Export::ENTITY_AWRMA, Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['items'];
        // Fetch fields to export
        $object = $collectionItem->getObject();
        #$order = $collectionItem->getOrder();
        $items = $object->getAllItems();
        if (empty($items) || (!$this->fieldLoadingRequired('items') && !$this->fieldLoadingRequired('tax_rates') && !$this->fieldLoadingRequired('packages/') && !$this->fieldLoadingRequired('_total_cost'))) {
            return $returnArray;
        }

        // Export item information
        $taxRates = array();
        $taxBaseAmounts = array();
        $itemCount = 0;
        $totalQty = 0;
        $this->_totalCost = 0;
        foreach ($items as $item) {
            $orderItem = false;
            // Check if this product type should be exported
            if ($this->getProfile() && $item->getProductType() && in_array($item->getProductType(), explode(",", $this->getProfile()->getExportFilterProductType()))) {
                continue; // Product type should be not exported
            }
            if ($this->getProfile() && !$item->getProductType() && $this->getProfile()->getExportFilterProductType() !== '' && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_ORDER && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_QUOTE && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_AWRMA && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA) {
                // We are not exporting orders, but need to check the product type - thus, need to load the order item.
                $orderItem = Mage::getModel('sales/order_item')->load($item->getOrderItemId());
                if ($orderItem->getProductType() && in_array($orderItem->getProductType(), explode(",", $this->getProfile()->getExportFilterProductType()))) {
                    continue; // Product type should be not exported
                }
            }
            // Export general item information
            $this->_writeArray = & $returnArray['items'][];
            $this->_origWriteArray = & $this->_writeArray;
            $itemCount++;
            if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_ORDER || $entityType == Xtento_OrderExport_Model_Export::ENTITY_AWRMA || $entityType == Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA) {
                $itemQty = $item->getQtyOrdered();
            } else {
                $itemQty = $item->getQty();
            }
            $totalQty += $itemQty;
            $this->writeValue('qty_ordered', $itemQty); // Legacy
            $this->writeValue('qty', $itemQty);

            $this->writeValue('item_number', $itemCount);
            $this->writeValue('order_product_number', $itemCount); // Legacy
            foreach ($item->getData() as $key => $value) {
                if ($key == 'qty_ordered' || $key == 'qty') continue;
                $this->writeValue($key, $value);
            }

            // Stock level
            if ($this->fieldLoadingRequired('qty_in_stock')) {
                $stockLevel = 0;
                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($item->getProductId());
                if ($stockItem->getId()) {
                    $stockLevel = $stockItem->getQty();
                }
                $this->writeValue('qty_in_stock', $stockLevel);
            }

            // Enterprise Gift Wrapping information
            if ($this->fieldLoadingRequired('enterprise_giftwrapping') && Mage::helper('xtcore/utils')->getIsPEorEE()) {
                if ($item->getGwId()) {
                    $this->_writeArray['enterprise_giftwrapping'] = array();
                    $this->_writeArray =& $this->_writeArray['enterprise_giftwrapping'];
                    $wrapping = Mage::getModel('enterprise_giftwrapping/wrapping')->load($item->getGwId());
                    if ($wrapping->getId()) {
                        foreach ($wrapping->getData() as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                        $this->writeValue('image_url', $wrapping->getImageUrl());
                    }
                }
            }

            // Repeat SKU by qty ordered, i.e. if "test" is ordered twice output test,test
            if ($this->fieldLoadingRequired('sku_repeated_by_qty')) {
                $this->writeValue('sku_repeated_by_qty', implode(",", array_fill(0, $itemQty, $item->getSku())));
            }

            // Add fields of order item for invoice exports
            $taxItem = false;
            if ($entityType !== Xtento_OrderExport_Model_Export::ENTITY_ORDER && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_QUOTE && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_AWRMA && $entityType !== Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA && ($this->fieldLoadingRequired('order_item') || $this->fieldLoadingRequired('tax_rates') || $this->fieldLoadingRequired('custom_options'))) {
                $this->_writeArray['order_item'] = array();
                $this->_writeArray =& $this->_writeArray['order_item'];
                if ($item->getOrderItemId()) {
                    if (!$orderItem) {
                        $orderItem = Mage::getModel('sales/order_item')->load($item->getOrderItemId());
                    }
                    if ($orderItem->getId()) {
                        $taxItem = $orderItem;
                        foreach ($orderItem->getData() as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                    }
                }
                $this->_writeArray = & $this->_origWriteArray;
                $tempOrigArray = & $this->_writeArray;
                if ($this->fieldLoadingRequired('custom_options') && $options = $orderItem->getProductOptions()) {
                    // Export custom options
                    $this->_writeCustomOptions($options, $this->_origWriteArray, $object, $orderItem->getProductId());
                }
                $this->_writeArray =& $tempOrigArray;
            } else {
                $taxItem = $item;
            }

            // Gift message
            if ($this->fieldLoadingRequired('gift_message')) {
                $giftMessageId = $item->getGiftMessageId();
                if (!$giftMessageId && $orderItem) {
                    $giftMessageId = $orderItem->getGiftMessageId();
                }
                $giftMessageModel = Mage::getModel('giftmessage/message')->load($giftMessageId);
                if ($giftMessageModel->getId()) {
                    $this->writeValue('gift_message_sender', $giftMessageModel->getSender());
                    $this->writeValue('gift_message_recipient', $giftMessageModel->getRecipient());
                    $this->writeValue('gift_message', $giftMessageModel->getMessage());
                } else {
                    $this->writeValue('gift_message_sender', '');
                    $this->writeValue('gift_message_recipient', '');
                    $this->writeValue('gift_message', '');
                }
            }

            // Get parent item
            $parentItem = $item->getParentItem();
            if (!$parentItem && $orderItem) {
                $parentItemId = $orderItem->getParentItemId();
                if ($parentItemId) {
                    $parentItem = Mage::getModel('sales/order_item')->load($parentItemId);
                }
            }

            // Get bundle price
            $productOptions = $item->getProductOptions();
            if ($parentItem && $parentItem->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                if (!isset($productOptions['bundle_selection_attributes']) && $parentItem) {
                    $productOptions = $parentItem->getProductOptions();
                }
                if (isset($productOptions['bundle_selection_attributes'])) {
                    $bundleOptions = unserialize($productOptions['bundle_selection_attributes']);
                    if (isset($bundleOptions['price'])) {
                        $this->writeValue('is_bundle', true);
                        $this->writeValue('bundle_price', $bundleOptions['price']);
                    }
                }
            }

            if ($this->fieldLoadingRequired('product_options_data') && $productOptions && is_array($productOptions)) {
                $this->_writeArray['product_options_data'] = array();
                $this->_writeArray = & $this->_origWriteArray['product_options_data'];
                foreach ($productOptions as $productOptionKey => $productOptionValue) {
                    if (!is_array($productOptionKey) && !is_object($productOptionKey) && !is_object($productOptionValue)) {
                        $this->writeValue($productOptionKey, $productOptionValue);
                    }
                }
                $this->_writeArray = & $this->_origWriteArray;
            }

            /*if ($this->fieldLoadingRequired('info_buyrequest') && $productOptions && isset($productOptions['info_buyRequest']) && is_array($productOptions['info_buyRequest'])) {
                $this->_writeArray['info_buyrequest'] = array();
                $this->_writeArray = & $this->_origWriteArray['info_buyrequest'];
                foreach ($productOptions['info_buyRequest'] as $productOptionKey => $productOptionValue) {
                    if (!is_array($productOptionKey) && !is_object($productOptionKey) && !is_array($productOptionValue) && !is_object($productOptionValue)) {
                        $this->writeValue($productOptionKey, $productOptionValue);
                    }
                }
                $this->_writeArray = & $this->_origWriteArray;
            }*/
            if ($this->fieldLoadingRequired('additional_options') && $productOptions && isset($productOptions['additional_options']) && is_array($productOptions['additional_options'])) {
                $this->_writeArray['additional_options'] = array();
                foreach ($productOptions['additional_options'] as $additionalOption) {
                    $this->_writeArray = & $this->_writeArray['additional_options'][];
                    foreach ($additionalOption as $productOptionKey => $productOptionValue) {
                        if (!is_array($productOptionKey) && !is_object($productOptionKey) && !is_array($productOptionValue) && !is_object($productOptionValue)) {
                            $this->writeValue($productOptionKey, $productOptionValue);
                        }
                    }
                }
                $this->_writeArray = & $this->_origWriteArray;
            }
            /*
            if ($this->fieldLoadingRequired('swatch_data')) {
                // "Swatch Data" export
                if (isset($productOptions['info_buyRequest']['swatchData']) && is_array($productOptions['info_buyRequest']['swatchData'])) {
                    $this->_writeArray['swatch_data'] = array();
                    foreach ($productOptions['info_buyRequest']['swatchData'] as $swatchId => $swatchData) {
                        $this->_writeArray = & $this->_origWriteArray['swatch_data'][];
                        foreach ($swatchData as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                    }
                    $this->_writeArray = & $this->_origWriteArray;
                }
                // End "Swatch Data"
            }*/

            if ($item->getProductType() == Mage_Downloadable_Model_Product_Type::TYPE_DOWNLOADABLE && $this->fieldLoadingRequired('downloadable_links')) {
                $productOptions = $item->getProductOptions();
                if ($productOptions) {
                    if (isset($productOptions['links']) && is_array($productOptions['links'])) {
                        $this->_writeArray['downloadable_links'] = array();
                        $downloadableLinksCollection = Mage::getModel('downloadable/link')->getCollection()
                            ->addTitleToResult()
                            ->addFieldToFilter('`main_table`.link_id', array('in' => $productOptions['links']));
                        foreach ($downloadableLinksCollection as $downloadableLink) {
                            $this->_writeArray = & $this->_origWriteArray['downloadable_links'][];
                            foreach ($downloadableLink->getData() as $downloadableKey => $downloadableValue) {
                                $this->writeValue($downloadableKey, $downloadableValue);
                            }
                        }
                        $this->_writeArray = & $this->_origWriteArray;
                    }
                }
            }

            // Save tax information for order
            if ($taxItem && $item->getBaseTaxAmount() > 0 && $taxItem->getTaxPercent() > 0) {
                $taxPercent = str_replace('.', '_', sprintf('%.4f', $taxItem->getTaxPercent()));
                if (!isset($taxRates[$taxPercent])) {
                    $taxRates[$taxPercent] = $item->getBaseTaxAmount();
                    $taxBaseAmounts[$taxPercent] = $item->getBaseRowTotalInclTax() - $item->getBaseDiscountAmount();
                } else {
                    $taxRates[$taxPercent] += $item->getBaseTaxAmount();
                    $taxBaseAmounts[$taxPercent] += $item->getBaseRowTotalInclTax() - $item->getBaseDiscountAmount();
                }
            }

            // Add fields of parent item
            if (($this->fieldLoadingRequired('parent_item') || $this->fieldLoadingRequired('products_total_cost') || $this->fieldLoadingRequired('product_total_cost')) && $parentItem) {
                $this->_writeArray['parent_item'] = array();
                $this->_writeArray =& $this->_writeArray['parent_item'];
                $tempOrigArray = & $this->_writeArray;
                foreach ($parentItem->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
                // Export parent product options
                if ($this->fieldLoadingRequired('custom_options') && $options = $parentItem->getProductOptions()) {
                    $this->_writeCustomOptions($options, $this->_writeArray, $object, $parentItem->getProductId());
                }
                $this->_writeArray =& $tempOrigArray;
                if ($this->fieldLoadingRequired('product_attributes') || $this->fieldLoadingRequired('products_total_cost') || $this->fieldLoadingRequired('product_total_cost')) {
                    $this->_writeProductAttributes($object, $parentItem, true);
                }
            }
            $this->_writeArray = & $this->_origWriteArray;
            // Export product attributes
            if ($this->fieldLoadingRequired('product_attributes') || $this->fieldLoadingRequired('products_total_cost') || $this->fieldLoadingRequired('product_total_cost')) {
                $this->_writeProductAttributes($object, $item, false);
            }

            $this->_writeArray = & $this->_origWriteArray;
            // Export product options
            if ($this->fieldLoadingRequired('custom_options') && $options = $item->getProductOptions()) {
                // Export custom options
                $this->_writeCustomOptions($options, $this->_origWriteArray, $object, $item->getProductId());
                // Export $options["attributes_info"].. maybe?
            }

            // Sample code to get ugiftcert gift certificate information:
            /*
             $giftCerts = Mage::getModel('ugiftcert/cert')->getCollection()->addItemFilter($item->getId());
             if (count($giftCerts)) {
                foreach ($giftCerts as $giftCert) {
                    if (isset($giftCert['cert_number'])) {
                        ...
                    }
                }
             }
             */
        }

        // Sample code to add specific things/amounts as line items:
        /*if ($object->getGiftMessageId() > 0) {
            $giftMessage = Mage::helper('giftmessage/message')->getGiftMessage($object->getGiftMessageId());
            $returnArray['items'][] = array(
                'sku' => 'MESSAGE',
                'qty_ordered' => 1,
                'qty' => 1,
                'price' => 0,
                'discount_percent' => '0',
                'custom_options' => array('custom_option' => array('value' => $giftMessage->getMessage()))
            );
        }*/

        $this->_writeArray = & $returnArray;
        $this->writeValue('export_total_qty_ordered', $totalQty);
        $this->writeValue('products_total_cost', $this->_totalCost);

        // Add tax amounts of other fees to $taxRates
        // Shipping
        $shippingAmount = 0;
        $shippingTaxAmount = 0;
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_ORDER) {
            $shippingAmount = $object->getData('base_shipping_amount');
            $shippingTaxAmount = $object->getData('base_shipping_tax_amount');
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_INVOICE) {
            $shippingAmount = $object->getData('base_shipping_amount');
            $shippingTaxAmount = $object->getData('base_shipping_tax_amount');
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO) {
            $shippingAmount = $object->getData('base_shipping_amount');
            $shippingTaxAmount = $object->getData('base_shipping_tax_amount');
        }
        if ($shippingAmount > 0 && $shippingTaxAmount > 0) {
            $taxPercent = round($shippingTaxAmount / $shippingAmount * 100);
            $taxPercent = str_replace('.', '_', sprintf('%.4f', $taxPercent));
            if (!isset($taxRates[$taxPercent])) {
                $taxRates[$taxPercent] = $shippingTaxAmount;
                $taxBaseAmounts[$taxPercent] = $shippingAmount + $shippingTaxAmount;
            } else {
                $taxRates[$taxPercent] += $shippingTaxAmount;
                $taxBaseAmounts[$taxPercent] += $shippingAmount + $shippingTaxAmount;
            }
        }
        // Cash on Delivery
        $codFee = 0;
        $codFeeTax = 0;
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_ORDER) {
            $codFee = $object->getBaseCodFee();
            $codFeeTax = $object->getBaseCodTaxAmount();
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_INVOICE) {
            $codFee = $object->getOrder()->getData('base_cod_fee_invoiced');
            $codFeeTax = $object->getOrder()->getData('base_cod_tax_amount_invoiced');
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO) {
            $codFee = $object->getOrder()->getData('base_cod_fee_refunded');
            $codFeeTax = $object->getOrder()->getData('base_cod_tax_amount_refunded');
        }
        if ($codFee > 0 && $codFeeTax > 0) {
            $taxPercent = round($codFeeTax / $codFee * 100);
            $taxPercent = str_replace('.', '_', sprintf('%.4f', $taxPercent));
            if (!isset($taxRates[$taxPercent])) {
                $taxRates[$taxPercent] = $codFeeTax;
                $taxBaseAmounts[$taxPercent] = $codFee + $codFeeTax;
            } else {
                $taxRates[$taxPercent] += $codFeeTax;
                $taxBaseAmounts[$taxPercent] += $codFee + $codFeeTax;
            }
        }

        // At least provide a 0% tax rate if no tax was found, as no tax was charged then
        if (empty($taxRates)) {
            $taxRates = array('0_0000' => '');
        }

        // Special VAT Refund construct in the ls_vatrefund field, reset all VAT in that case
        if ($object->getData('ls_vatrefund') < 0) {
            $taxRates = array('0_0000' => '');
        }

        // Export tax information
        $this->_writeArray['tax_rates'] = array();
        if ($this->fieldLoadingRequired('tax_rates')) {
            $grandTotalInclTax = $object->getGrandTotal();
            foreach ($taxRates as $taxRate => $taxAmount) {
                if ($taxRate == '0_0000') continue;
                $taxBaseAmount = $taxBaseAmounts[$taxRate];
                $taxRate = str_replace('_', '.', $taxRate);
                $this->_writeArray = & $returnArray['tax_rates'][];
                $this->writeValue('rate', $taxRate);
                $this->writeValue('amount', $taxAmount);
                $this->writeValue('base', $taxBaseAmount);
                $grandTotalInclTax -= $taxBaseAmount;
            }
            if (isset($taxRates['0_0000'])) {
                $this->_writeArray = & $returnArray['tax_rates'][];
                $this->writeValue('rate', '0.0000');
                $this->writeValue('amount', '0.0000');
                $this->writeValue('base', $grandTotalInclTax);
            }
        }
        $this->_writeArray['order_tax_rates'] = array();
        if ($this->fieldLoadingRequired('order_tax_rates')) {
            $taxRateCollection = Mage::getModel('tax/sales_order_tax')->getCollection()->loadByOrder($collectionItem->getOrder());
            if ($taxRateCollection->count()) {
                foreach ($taxRateCollection as $taxRate) {
                    $this->_writeArray = & $returnArray['order_tax_rates'][];
                    foreach ($taxRate->getData() as $key => $value) {
                        if ($key == 'percent') $key = 'rate';
                        $this->writeValue($key, $value);
                    }
                    // Write "base_tax_base" - the base the tax_amount was calculated on
                    $this->writeValue('base_tax_base', ($taxRate->getBaseAmount() / ($taxRate->getPercent() / 100)) + $taxRate->getBaseAmount());
                }
            }
        }

        /*
        $packageCollection = Mage::getModel('shipusa/packages')->getCollection()->addQuoteFilter($object->getQuoteId());
        $packageCount = 0;
        $this->_writeArray['packages'] = array();
        foreach ($packageCollection as $package) {
            $packageCount++;
            $this->_writeArray = & $returnArray['packages'][];
            $this->writeValue('weight', $package->getWeight());
            $this->writeValue('counter', $packageCount);
        }
        */

        // Done
        return $returnArray;
    }

    private function _writeCustomOptions($options, &$writeArray, $object, $productId = null)
    {
        if (isset($options['options'])) {
            $this->_writeArray['custom_options'] = array();
            foreach ($options['options'] as $customOption) {
                $optionCount = 0;
                if (isset($customOption['option_value'])) {
                    $optionValues = explode(",", $customOption['option_value']);
                    if (isset($customOption['option_type'])
                        && $customOption['option_type'] !== 'field'
                        && $customOption['option_type'] !== 'area'
                    ) {
                        foreach ($optionValues as $optionValue) {
                            //$values = Mage::getModel('catalog/product_option_value')->load($optionValue);
                            $values = Mage::getModel('catalog/product_option_value')
                                ->getCollection()
                                ->addPriceToResult($object->getStoreId())
                                ->getValuesByOption($optionValue, $object->getStoreId());
                            if ($values->count()) {
                                $value = $values->getFirstItem();
                                if ($value->getOptionId() && $value->getSku()) {
                                    $optionCount++;
                                    $this->_writeArray = & $writeArray['custom_options'][];
                                    $this->writeValue('name', $customOption['label']);
                                    $this->writeValue('value', $customOption['value']);
                                    $this->writeValue('sku', $value->getSku());
                                    $this->writeValue('price', $value->getPrice(true));

                                    if (isset($customOption['option_id'])) {
                                        $this->writeValue('option_id', $customOption['option_id']);
                                        $buyRequestQtyKey = 'options_' . $customOption['option_id'] . '_qty';
                                        if (!is_object($options) && is_array($options['info_buyRequest']) && array_key_exists($buyRequestQtyKey, $options['info_buyRequest'])) {
                                            $this->writeValue('qty', $options['info_buyRequest'][$buyRequestQtyKey]);
                                        } else {
                                            $this->writeValue('qty', 1);
                                        }
                                    } else {
                                        $this->writeValue('qty', 1);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($optionCount === 0) {
                    if (!isset($customOption['sku'])) {
                        $customOption['sku'] = '';
                    }
                    if ($productId != NULL && empty($customOption['sku'])) {
                        $productDetail = Mage::getModel('catalog/product')->load($productId);
                        $options = $productDetail->getProductOptionsCollection();
                        foreach ($options as $option) {
                            if ($option->getOptionId() == $customOption['option_id']) {
                                $customOption['sku'] = $option->getSku();
                            }
                        }
                    }

                    $this->_writeArray = & $writeArray['custom_options'][];
                    $this->writeValue('name', $customOption['label']);
                    $this->writeValue('value', $customOption['value']);
                    $this->writeValue('sku', $customOption['sku']);
                    if (isset($customOption['option_id'])) {
                        $this->writeValue('option_id', $customOption['option_id']);
                    }
                }
                if (isset($customOption['option_value'])) {
                    $unserializedOptionValues = @unserialize($customOption['option_value']);
                    if ($unserializedOptionValues !== false) {
                        foreach ($unserializedOptionValues as $unserializedOptionKey => $unserializedOptionValue) {
                            $this->writeValue($unserializedOptionKey, $unserializedOptionValue);
                        }
                    }
                }
            }
        }
    }

    private function _writeProductAttributes($object, $item, $isParentItem = false)
    {
        $this->_writeArray['product_attributes'] = array();
        $this->_writeArray = & $this->_writeArray['product_attributes'];
        if (isset($this->_cache['product_attributes'][$object->getStoreId()]) && isset($this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()])) {
            // "cached"
            foreach ($this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()] as $attributeCode => $value) {
                if ($attributeCode == 'product_total_cost') continue;
                $this->writeValue($attributeCode, $value);
            }
            if ($this->fieldLoadingRequired('_total_cost') && !$isParentItem) {
                $product = Mage::getModel('catalog/product')->setStoreId($object->getStoreId())->load($item->getProductId());
                if ($product->getId()) {
                    $this->_totalCost += ($product->getCost() * $item->getQtyOrdered());
                    $this->writeValue('product_total_cost', $product->getCost() * $item->getQtyOrdered());
                }
            }
        } else {
            $product = Mage::getModel('catalog/product')->setStoreId($object->getStoreId())->load($item->getProductId());
            if ($product->getId()) {
                foreach ($product->getAttributes(null, true) as $productAttribute) {
                    if ($productAttribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute) {
                        $productAttribute->setStoreId(0); // Admin store
                    }
                    $attributeCode = $productAttribute->getAttributeCode();
                    // Handle attribute set name
                    if ($this->fieldLoadingRequired('attribute_set_name') && $productAttribute->getAttributeCode() == 'attribute_set_id') {
                        $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
                        $attributeSetModel->load($productAttribute->getFrontend()->getValue($product));
                        if ($attributeSetModel->getId()) {
                            $this->writeValue('attribute_set_name', $attributeSetModel->getAttributeSetName());
                            $this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()]['attribute_set_name'] = $attributeSetModel->getAttributeSetName();
                        }
                    }
                    if (!$this->fieldLoadingRequired($attributeCode) || $attributeCode == 'category_ids') {
                        continue;
                    }
                    $value = $productAttribute->getFrontend()->getValue($product);
                    if ($attributeCode == 'image' || $attributeCode == 'small_image' || $attributeCode == 'thumbnail') {
                        $this->writeValue($attributeCode . '_raw', $value);
                        $this->writeValue($attributeCode, Mage::getBaseUrl('media') . 'catalog/product/' . $value);
                        continue;
                    }
                    $this->writeValue($attributeCode, $value);
                    $this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()][$attributeCode] = $value;
                    // Get store value
                    if ($this->fieldLoadingRequired($attributeCode . '_store')) {
                        if ($productAttribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute) {
                            $productAttribute->setStoreId($product->getStoreId());
                        }
                        $value = $productAttribute->getFrontend()->getValue($product);
                        $this->writeValue($attributeCode . '_store', $value);
                        $this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()][$attributeCode . '_store'] = $value;
                    }
                }
                if ($this->fieldLoadingRequired('category_ids')) {
                    $categoryIds = "|" . implode("|", $product->getCategoryIds()) . "|";
                    $this->writeValue('category_ids', $categoryIds);
                    $this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()]['category_ids'] = $categoryIds;
                }
                if ($this->fieldLoadingRequired('category_names')) {
                    if ($product->getCategoryIds()) {
                        $categoryNames = array();
                        foreach ($product->getCategoryIds() as $categoryId) {
                            $category = Mage::getModel('catalog/category')->load($categoryId);
                            if ($category && $category->getId()) {
                                $categoryNames[] = $category->getName();
                            }
                        }
                        $categoryNames = "|" . implode("|", $categoryNames) . "|";
                        $this->writeValue('category_names', $categoryNames);
                        $this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()]['category_names'] = $categoryNames;
                    }
                }
                if ($this->fieldLoadingRequired('product_url')) {
                    $productUrl = $product->getProductUrl(false);
                    /*if (preg_match("/&/", $productUrl)) {
                        $productUrl = preg_replace("/___store=(.*?)&/", "&", $productUrl);
                    } else {
                        $productUrl = preg_replace("/\?___store=(.*)/", "", $productUrl);
                    }*/
                    $this->writeValue('product_url', $productUrl);
                    $this->_cache['product_attributes'][$object->getStoreId()][$item->getProductId()]['product_url'] = $productUrl;
                }
                if (!$isParentItem) {
                    $this->_totalCost += ($product->getCost() * $item->getQtyOrdered());
                }
                $this->writeValue('product_total_cost', $product->getCost() * $item->getQtyOrdered());
            }
        }
    }
}