<?php
class Icube_Vpayment_Adminhtml_PromoController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }
     
    public function newAction()
    {
        $this->editAction();
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('vpayment/program');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Promo has been deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find the Promo to delete.'));
        $this->_redirect('*/*/');
    }
     
    public function editAction()
    {  
        $this->_initAction();

        $id  = $this->getRequest()->getParam('id');
        $model = Mage::getModel('vpayment/program');
     
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This promo no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }  
     
        $this->_title($model->getId() ? $model->getName() : $this->__('New Promo'));

        $data = Mage::getSingleton('adminhtml/session')->getPromoData(true);
        if (!empty($data)) {
            $model->setData($data);
        }  
     
        Mage::register('promo', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Promo') : $this->__('New Promo'), $id ? $this->__('Edit Promo') : $this->__('New Promo'))
            ->_addContent($this->getLayout()->createBlock('vpayment/adminhtml_promo_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }
     
    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $model = Mage::getSingleton('vpayment/program');
            $model->setData($postData);
 
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Promo has been saved.'));
                $this->_redirect('*/*/');
                return;
            }  
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving this Promo.'));
            }
            Mage::getSingleton('adminhtml/session')->setPromoData($postData);
            $this->_redirectReferer();
        }
    }
     
    public function messageAction()
    {
        $data = Mage::getModel('vpayment/program')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/promo')
            ->_title($this->__('Promo'))->_title($this->__('Promo'))
            ->_addBreadcrumb($this->__('Promo'), $this->__('Promotions'))
            ->_addBreadcrumb($this->__('Promo'), $this->__('Manage Promos'));
        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('vpayment/promo_promo');
    }
}