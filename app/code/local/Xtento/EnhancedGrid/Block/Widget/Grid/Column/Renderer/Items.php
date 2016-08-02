<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-12-16T22:26:37+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Renderer/Items.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Items extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $attributesToShow = $this->getColumn()->getAttributesToShow();
        if (is_array($attributesToShow)) {
            foreach ($attributesToShow as $attrId => $attrCode) {
                if ($attrCode === '') {
                    unset($attributesToShow[$attrId]);
                }
            }
        }
        if ($this->getColumn()->getIsOrderColumn() === false) {
            $orderItems = $row->getAllItems();
        } else {
            $orderItems = $row->getAllVisibleItems();
        }
        if (Mage::helper('xtento_enhancedgrid')->isMageExport()) {
            // Export reduced version when exporting orders using the built-in CSV/Excel XML export functionality of Magento
            $productInfo = "";
            foreach ($orderItems as $orderItem) {
                if ($this->getColumn()->getIsOrderColumn() === false) {
                    $productInfo .= sprintf("%dx %s - ", round($orderItem->getQty()), $orderItem->getName());
                } else {
                    $productInfo .= sprintf("%dx %s - ", round($orderItem->getQtyOrdered()), $orderItem->getName());
                }
            }
            $productInfo = substr($productInfo, 0, -3);
        } else {
            $hasCustomOptions = false;
            if ($this->getColumn()->getShowCustomOptions()) {
                foreach ($orderItems as $orderItem) {
                    $productOptions = $orderItem->getProductOptions();
                    if (isset($productOptions['options']) || isset($productOptions['attributes_info'])) {
                        $hasCustomOptions = true;
                        break;
                    }
                }
            }
            $productInfo = '<div><table class="xtento-item-table"><thead><tr class="xtento-item-tr">';
            if ($this->getColumn()->getShowThumbnail()) {
                $productInfo .= '<th>' . Mage::helper('xtento_enhancedgrid')->__('Image') . '</th>';
            }
            $productInfo .= '<th>' . Mage::helper('xtento_enhancedgrid')->__('Name') . '</th>';
            if ($hasCustomOptions || count($attributesToShow) > 0) {
                $productInfo .= '<th>' . Mage::helper('xtento_enhancedgrid')->__('Product Options') . '</th>';
            }
            $productInfo .= '<th>' . Mage::helper('xtento_enhancedgrid')->__('SKU') . '</th>';
            if ($this->getColumn()->getIsOrderColumn() !== false) {
                $productInfo .= '<th>' . Mage::helper('xtento_enhancedgrid')->__('Total') . '</th>';
            }
            $productInfo .= '</tr></thead><tbody>';
            $lineCount = 0;
            foreach ($orderItems as $orderItem) {
                $lineCount++;
                $trClass = "";
                $trStyle = "";
                if ($this->getColumn()->getData('truncate_items') !== null && $lineCount > $this->getColumn()->getData('truncate_items')) {
                    $trClass = ' xtento-item-tr-hidden-' . $row->getId();
                    $trStyle = ' style="display:none;"';
                }
                $productInfo .= '<tr class="xtento-item-tr' . $trClass . '"' . $trStyle . '>';
                if ($this->getColumn()->getShowThumbnail()) {
                    try {
                        $pictureUrl = Mage::helper('catalog/image')->init(Mage::getModel('catalog/product')->load($orderItem->getProductId()), 'small_image')->resize(50);
                        if ((stristr($pictureUrl, 'no_image') !== false || stristr($pictureUrl, '/placeholder/') !== false) && $orderItem->getParentItem()) {
                            $pictureUrl = Mage::helper('catalog/image')->init(Mage::getModel('catalog/product')->load($orderItem->getParentItem()->getProductId()), 'small_image')->resize(50);
                        }
                    } catch (Exception $e) {
                        $pictureUrl = '';
                    }
                    $productInfo .= '<td><img alt="' . Mage::helper('xtento_enhancedgrid')->__('No Image') . '" src="' . $pictureUrl . '" style="max-height: 50px" /></td>';
                }
                $backorderedStatus = '';
                $backorderedQty = $orderItem->getQtyBackordered();
                if ($orderItem->getHasChildren()) {
                    $backorderedQty = 0;
                    foreach ($orderItem->getChildrenItems() as $childItem) {
                        $backorderedQty += (float)$childItem->getQtyBackordered();
                    }
                }
                if ($backorderedQty > 0) {
                    $backorderedStatus = sprintf(" <strong>(" . Mage::helper('xtento_enhancedgrid')->__('Backordered') . ": %d)</strong>", $backorderedQty);
                }
                $productInfo .= '<td>' . $orderItem->getName() . $backorderedStatus . '</td>';
                if ($hasCustomOptions || count($attributesToShow) > 0) {
                    $customOptionText = '';
                    $customOptions = '';
                    $productOptions = $orderItem->getProductOptions();
                    if (isset($productOptions['options']))
                        $customOptions = $productOptions['options'];
                    else if (isset($productOptions['attributes_info'])) {
                        $customOptions = $productOptions['attributes_info'];
                    }
                    if ($customOptions) {
                        foreach ($customOptions as $customOption) {
                            $customOptionText .= '<b><i>' . $customOption['label'] . ':</i></b><br /> ';
                            $customOptionText .= $customOption['value'] . '<br />';
                        }
                    }
                    if (count($attributesToShow) > 0) {
                        $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());
                        if ($product->getId()) {
                            foreach ($attributesToShow as $attributeCode) {
                                try {
                                    if (!empty($attributeCode)) {
                                        $attributeValue = $product->getAttributeText($attributeCode); //Mage::helper('catalog/output')->productAttribute($product, $product->getData($attributeCode), $attributeCode);
                                        if (empty($attributeValue)) {
                                            $attributeValue = $product->getData($attributeCode);
                                        }
                                        if (!empty($attributeValue)) {
                                            $attribute = Mage::getModel('catalog/resource_eav_attribute')->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
                                            if ($attribute->getId()) {
                                                $attributeLabel = $attribute->getFrontendLabel();
                                            } else {
                                                $attributeLabel = $attributeCode;
                                            }
                                            $customOptionText .= '<b><i>' . $attributeLabel . ':</i></b><br /> ';
                                            $customOptionText .= $attributeValue . '<br />';
                                        }
                                    }
                                } catch (Exception $e) {
                                }
                            }
                        }
                    }
                    $productInfo .= '<td>' . $customOptionText . '</td>';
                }

                if ($this->getColumn()->getIsOrderColumn() === false) {
                    $productInfo .= '<td>' . round($orderItem->getQty()) . 'x ' . $orderItem->getSku() . '</td>';
                } else {
                    $productInfo .= '<td>' . round($orderItem->getQtyOrdered()) . 'x ' . $orderItem->getSku() . '</td>';
                }

                if ($this->getColumn()->getIsOrderColumn() !== false) {
                    if ($this->getColumn()->getData('show_order_currency')) {
                        $productInfo .= '<td>' . $this->currencyByStore($orderItem->getBaseRowTotalInclTax(), $row->getStore(), true, false) . '</td>';
                    } else {
                        $productInfo .= '<td>' . Mage::helper('core')->currency($orderItem->getBaseRowTotalInclTax(), true, false) . '</td>';
                    }
                }

                $productInfo .= '</tr>';

            }
            $productInfo .= '</tbody></table>';
            if ($this->getColumn()->getData('truncate_items') !== null && $lineCount > $this->getColumn()->getData('truncate_items')) {
                $productInfo .= '<a href="#" onclick="showMoreItems(' . $row->getId() . '); this.hide(); return false;">' . Mage::helper('xtento_enhancedgrid')->__('Show more items') . '</a>';
            }
            $productInfo .= '</div>';
        }
        return $productInfo;
    }

    protected function currencyByStore($value, $store = null, $format = true, $includeContainer = true)
    {
        try {
            if (!($store instanceof Mage_Core_Model_Store)) {
                $store = Mage::app()->getStore($store);
            }

            $value = $store->convertPrice($value, $format, $includeContainer);
        }
        catch (Exception $e){
            $value = $e->getMessage();
        }

        return $value;
    }
}

?>