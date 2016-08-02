<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-08-25T21:32:21+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/controllers/Adminhtml/Enhancedgrid/GridController.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Adminhtml_Enhancedgrid_GridController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        $this->_initAction()->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('xtento_enhancedgrid/grid');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('This grid configuration no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : Mage::helper('xtento_enhancedgrid')->__('New Customized Grid'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        } else {
            // Handle certain fields
            $fields = array('role_ids', 'hidden_status', 'hidden_stores', 'attributes_to_show');
            foreach ($fields as $field) {
                $value = $model->getData($field);
                if (!is_array($value)) {
                    $model->setData($field, explode(',', $value));
                }
            }
        }

        Mage::unregister('enhanced_grid_current_grid');
        Mage::register('enhanced_grid_current_grid', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? Mage::helper('xtento_enhancedgrid')->__('Edit Grid') : Mage::helper('xtento_enhancedgrid')->__('New Grid'), $id ? Mage::helper('xtento_enhancedgrid')->__('Edit Grid') : Mage::helper('xtento_enhancedgrid')->__('New Grid'))
            ->_addContent($this->getLayout()->createBlock('xtento_enhancedgrid/adminhtml_grid_edit')->setData('action', $this->getUrl('*/*/save')))
            ->_addLeft($this->getLayout()->createBlock('xtento_enhancedgrid/adminhtml_grid_edit_tabs'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(1);
        $this->getLayout()->getBlock('head')->setCanLoadRulesJs(1);

        $this->renderLayout();

        if (Mage::getSingleton('adminhtml/session')->getGridDuplicated()) {
            Mage::getSingleton('adminhtml/session')->setGridDuplicated(0);
        }
    }

    public function saveAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        if ($postData = $this->getRequest()->getPost()) {
            $gridModel = Mage::getModel('xtento_enhancedgrid/grid');
            $gridModel->setData($postData);
            if ($gridModel->getId()) {
                $gridState = Mage::getModel('xtento_enhancedgrid/grid')->load($gridModel->getId());
                Mage::unregister('enhanced_grid_current_grid');
                Mage::register('enhanced_grid_current_grid', $gridState);
            }
            $gridModel->setLastModification(now());

            if (!$gridModel->getId()) {
                $gridModel->setEnabled(1);
            }

            // Handle certain fields
            $fields = array('role_ids', 'hidden_status', 'hidden_stores', 'attributes_to_show');
            foreach ($fields as $field) {
                $value = $gridModel->getData($field);
                $gridModel->setData($field, '');
                if (is_array($value)) {
                    $gridModel->setData($field, implode(',', $value));
                }
                if (empty($value)) {
                    $gridModel->setData($field, '');
                }
            }

            // Handle column configuration
            $newColumns = $gridModel->getColumns(); // From POST
            if (isset($gridState) && is_array($newColumns)) {
                $mergedColumnConfiguration = Mage::getSingleton('xtento_enhancedgrid/columns')->mergeGridColumns($gridModel, $gridState, $newColumns);
                $gridModel->setConfiguration(serialize($mergedColumnConfiguration));
            } else {
                $gridModel->setConfiguration(false);
            }

            try {
                $gridModel->save();

                Mage::getSingleton('adminhtml/session')->setFormData(false);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('xtento_enhancedgrid')->__('The grid configuration has been saved.'));
                if ($this->getRequest()->getParam('continue')) {
                    $this->_redirect('*/*/edit', array('id' => $gridModel->getId(), 'active_tab' => $this->getRequest()->getParam('active_tab')));
                } else {
                    $this->_redirect('*/*');
                }
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('An error occurred while saving this grid: ' . $e->getMessage()));
            }

            Mage::getSingleton('adminhtml/session')->setFormData($postData);
            $this->_redirectReferer();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('Could not find any data to save in the POST request. POST request too long maybe?'));
            $this->_redirect('*/*');
        }
    }

    protected function _sortColumns($a, $b)
    {
        return ($a['sort_order'] < $b['sort_order'] ? -1 : ($a['sort_order'] > $b['sort_order'] ? 1 : strcmp($a['header'], $b['header'])));
    }

    public function deleteAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('xtento_enhancedgrid/grid');
        $model->load($id);

        if ($id && !$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('This grid configuration does not exist anymore.'));
            $this->_redirect('*/*/');
            return;
        }

        try {
            $model->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('xtento_enhancedgrid')->__('Grid configuration has been successfully deleted.'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }

    public function duplicateAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        $id = $this->getRequest()->getParam('id');
        if (!$id) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('Please select a grid to duplicate.'));
            return $this->_redirect('*/*');
        }

        try {
            $model = Mage::getModel('xtento_enhancedgrid/grid');
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('This grid does not exist anymore.'));
                return $this->_redirect('*/*');
            }

            $grid = clone $model;
            $grid->setEnabled(0);
            $grid->setId(null);
            $grid->setLastModification(now());
            $grid->setLastExecution(null);
            $grid->save();

            Mage::getSingleton('adminhtml/session')->setGridDuplicated(1);
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('xtento_enhancedgrid')->__('The grid has been duplicated. Please make sure to enable it.'));
            $this->_redirect('*/*/edit', array('id' => $grid->getId()));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*');
        }
    }

    public function destinationAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function destinationGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function logGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function historyGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function massDeleteAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        $ids = $this->getRequest()->getParam('grid');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('Please select grids to delete.'));
            $this->_redirect('*/*/');
            return;
        }

        try {
            foreach ($ids as $id) {
                $model = Mage::getModel('xtento_enhancedgrid/grid');
                $model->load($id);
                $model->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($ids)));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }

    public function massUpdateStatusAction()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            $this->getResponse()->setRedirect($this->getUrl('*/enhancedgrid_index/permissions'));
            $this->getResponse()->sendResponse();
            $this->getRequest()->setDispatched(true);
            return $this;
        }
        $ids = $this->getRequest()->getParam('grid');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtento_enhancedgrid')->__('Please select grids to modify.'));
            $this->_redirect('*/*/');
            return;
        }

        try {
            foreach ($ids as $id) {
                $model = Mage::getModel('xtento_enhancedgrid/grid');
                $model->load($id);
                $model->setEnabled($this->getRequest()->getParam('enabled'));
                $model->save();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully updated', count($ids)));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/enhancedgrid')
            ->_title(Mage::helper('xtento_enhancedgrid')->__('Sales'))->_title(Mage::helper('xtento_enhancedgrid')->__('Customized Grids'));
        return $this;
    }

    protected function _isAllowed()
    {
        #return Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize');
        return true;
    }
}