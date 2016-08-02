<?php
 
class Icube_Warehouse_Adminhtml_DcdashboardController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Icube'))->_title($this->__('DC Order'));
        $this->loadLayout();
        $this->_setActiveMenu('icube/dcdashboard');
        $this->_addContent($this->getLayout()->createBlock('icube_warehouse/adminhtml_dcdashboard'));
        $this->renderLayout();
    }
 
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('icube_warehouse/adminhtml_dcdashboard_grid')->toHtml()
        );
    }
 
    public function exportIcubeCsvAction()
    {
        $fileName = 'dcdashboard_icube.csv';
        $grid = $this->getLayout()->createBlock('icube_warehouse/adminhtml_dcdashboard_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
 
    public function exportIcubeExcelAction()
    {
        $fileName = 'dcdashboard_icube.xml';
        $grid = $this->getLayout()->createBlock('icube_warehouse/adminhtml_dcdashboard_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('icube/dcdashboard');

    }
}