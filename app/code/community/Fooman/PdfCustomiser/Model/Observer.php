<?php

/**
 * @author     Kristof Ringleff
 * @package    Fooman_PdfCustomiser
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Fooman_PdfCustomiser_Model_Observer
{

    /**
     * test to combine Zend_Pdf content with tcpdf pdfs
     *
     * @param $observer
     */
    public function adjustPdf($observer)
    {

        $extractor = new Zend_Pdf_Resource_Extractor();

        $pdf = $observer->getEvent()->getPdf();
        $counter = false;
        foreach ($pdf->pages as $key => &$page) {
            if ($page instanceof Fooman_PdfCustomiser_Model_Abstract) {
                $counter = 1;
                $instance = $page;
                $firstKey = $key;
                unset ($pdf->pages[$key]);
            } elseif ($counter == 1) {
                $objectArray = $page;
                $counter++;
                unset ($pdf->pages[$key]);
            } elseif ($counter == 2) {
                $orderIds = $page;
                $tcpdf = Zend_Pdf::parse($instance->renderPdf($objectArray, $orderIds, null, true));
                foreach ($tcpdf->pages as $p) {
                    $pdf->pages[$firstKey] = $extractor->clonePage($p);
                }
                $counter = 0;
            }
        }
    }

    /**
     * observe core_block_abstract_prepare_layout_after to change URLS in
     * massaction dropdown menus to Pdf Customiser controller
     * and also to adjust Print Buttons
     *
     * @param $observer
     */
    public function addbutton($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction
            || $block instanceof
                Enterprise_SalesArchive_Block_Adminhtml_Sales_Order_Grid_Massaction
        ) {
            $replaceLinks = array(
                'pdfinvoices_order'      => 'adminhtml/pdfCustomiser_sales_invoice/pdfinvoices',
                'pdfcreditmemos_order'   => 'adminhtml/pdfCustomiser_sales_creditmemo/pdfcreditmemos',
                'pdfshipments_order'     => 'adminhtml/pdfCustomiser_sales_shipment/pdfshipments',
                'pdfdocs_order'          => 'adminhtml/pdfCustomiser_sales_order/pdfdocs',
                'fooman_pdforders_order' => 'adminhtml/pdfCustomiser_sales_order/pdforders',
            );
            foreach ($replaceLinks as $blockName => $link) {
                $printLink = $block->getItem($blockName);
                if ($printLink) {
                    $printLink->setUrl($block->getUrl($link));
                }
            }

            //add button to dropdown
            if ($block->getRequest()->getControllerName() == 'sales_order'
                || $block->getRequest()->getControllerName() == 'adminhtml_sales_order'
                || $block->getRequest()->getControllerName() == 'sales_archive'

            ) {

                $block->addItem(
                    'pdforders_invoiceshipments', array(
                        'label'=> Mage::helper('pdfcustomiser')->__('Print Shipments and Invoices'),
                        'url'  => Mage::helper('adminhtml')->getUrl(
                            'adminhtml/pdfCustomiser_sales_order/pdfshipmentsinvoices',
                            Mage::app()->getStore()->isCurrentlySecure() ? array('_secure'=> 1) : array()
                        ),
                    )
                );
            }
        }

        //adjust button on single views - order is already done via EmailAttachments rewrite
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Invoice_View) {
            //updateButton does not seem to work here
            $block->removeButton('print');
            $block->addButton(
                'fooman_print',
                array(
                    'label'     => Mage::helper('sales')->__('Print'),
                    'class'     => 'save',
                    'onclick' =>
                    'setLocation(\'' . $this->getInvoicePrintUrl($block) . '\')'
                )
            );
            if (Mage::getStoreConfig('sales_pdf/all/allprintaltstore') != 0) {
                $block->addButton(
                    'fooman_print_altstore', array(
                        'label'   => Mage::helper('sales')->__('Print from '.Mage::app()->getStore(Mage::getStoreConfig('sales_pdf/all/allprintaltstore'))->getName()),
                        'class'   => 'save',
                        'onclick' => 'setLocation(\'' . $this->getInvoicePrintUrl($block).'force_store_id/'.Mage::getStoreConfig('sales_pdf/all/allprintaltstore').'/'  . '\')'
                    )
                );
            }
        }
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Shipment_View) {
            $block->removeButton('print');
            $block->addButton(
                'fooman_print',
                array(
                    'label'     => Mage::helper('sales')->__('Print'),
                    'class'     => 'save',
                    'onclick' =>
                    'setLocation(\'' . $this->getShipmentPrintUrl($block) . '\')'
                )
            );
            if (Mage::getStoreConfig('sales_pdf/all/allprintaltstore') != 0) {
                $block->addButton(
                    'fooman_print_altstore', array(
                        'label'   => Mage::helper('sales')->__('Print from '.Mage::app()->getStore(Mage::getStoreConfig('sales_pdf/all/allprintaltstore'))->getName()),
                        'class'   => 'save',
                        'onclick' => 'setLocation(\'' . $this->getShipmentPrintUrl($block).'force_store_id/'.Mage::getStoreConfig('sales_pdf/all/allprintaltstore').'/'  . '\')'
                    )
                );
            }
        }
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Creditmemo_View) {
            $block->removeButton('print');
            $block->addButton(
                'fooman_print',
                array(
                    'label'     => Mage::helper('sales')->__('Print'),
                    'class'     => 'save',
                    'onclick' =>
                    'setLocation(\'' . $this->getCreditmemoPrintUrl($block) . '\')'
                )
            );
            if (Mage::getStoreConfig('sales_pdf/all/allprintaltstore') != 0) {
                $block->addButton(
                    'fooman_print_altstore', array(
                        'label'   => Mage::helper('sales')->__('Print from '.Mage::app()->getStore(Mage::getStoreConfig('sales_pdf/all/allprintaltstore'))->getName()),
                        'class'   => 'save',
                        'onclick' => 'setLocation(\'' . $this->getCreditmemoPrintUrl($block).'force_store_id/'.Mage::getStoreConfig('sales_pdf/all/allprintaltstore').'/'  . '\')'
                    )
                );
            }
        }
    }

    public function getShipmentPrintUrl($block)
    {
        return $block->getUrl(
            'adminhtml/pdfCustomiser_sales_shipment/pdfshipment',
            array(
                'shipment_id' => $block->getShipment()->getId()
            )
        );
    }

    public function getInvoicePrintUrl($block)
    {
        return $block->getUrl(
            'adminhtml/pdfCustomiser_sales_invoice/pdfinvoice',
            array(
                'invoice_id' => $block->getInvoice()->getId()
            )
        );
    }

    public function getCreditmemoPrintUrl($block)
    {
        return $block->getUrl(
            'adminhtml/pdfCustomiser_sales_creditmemo/pdfcreditmemo',
            array(
                'creditmemo_id' => $block->getCreditmemo()->getId()
            )
        );
    }
}
