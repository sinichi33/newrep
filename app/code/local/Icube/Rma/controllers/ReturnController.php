<?php

require_once(Mage::getModuleDir('controllers','Enterprise_Rma').DS.'ReturnController.php');
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Enterprise
 * @package     Enterprise_Rma
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

class Icube_Rma_ReturnController extends Enterprise_Rma_ReturnController
{
    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        Mage_Core_Controller_Front_Action::preDispatch();
    }

    /**
     * Customer create new return
     */
    public function createAction()
    {
        $orderNumber= $this->getRequest()->getParam('order_number');
        $email      = $this->getRequest()->getParam('email');
        $order      = Mage::getModel('sales/order')->loadByIncrementId($orderNumber);
        $orderId    = $order->getId();

        if (empty($orderId)) {
            $this->_redirect('sales/order/history');
            return;
        }
        Mage::register('current_order', $order);

        if (!$this->_loadOrderItems($orderId)) {
            return;
        }

        if ($this->_canViewOrder($order,$email)) {
            $postData = $this->getRequest()->getPost();
            if (($postData) && !empty($postData['items'])) {
                try {
                    $rmaModel = Mage::getModel('enterprise_rma/rma');
                    $rmaData = array(
                        'status'                => Enterprise_Rma_Model_Rma_Source_Status::STATE_PENDING,
                        'date_requested'        => Mage::getSingleton('core/date')->gmtDate(),
                        'order_id'              => $order->getId(),
                        'order_increment_id'    => $order->getIncrementId(),
                        'store_id'              => $order->getStoreId(),
                        'customer_id'           => $order->getCustomerId(),
                        'order_date'            => $order->getCreatedAt(),
                        'customer_name'         => $order->getCustomerName(),
                        'customer_custom_email' => $postData['customer_custom_email']
                    );

                    //remove unchecked item array
                    foreach ($postData['items'] as $key => $value) {
                        if($value['order_item_id']==NULL){
                            unset($postData['items'][$key]);
                        }
                    }
                    $result = $rmaModel->setData($rmaData)->saveRmaData($postData);
                    if (!$result) {
                        $this->_redirectError(Mage::getUrl('*/*/create', array('order_id'  => $orderId)));
                        return;
                    }
                    $imageUrlArr = array();
                    foreach ($_FILES['images']['name'] as $key => $value) {
                        if (empty($value)) {
                            continue;
                        }
                        try {
                            $uploader = new Varien_File_Uploader(
                                array(
                                    'name' => $_FILES['images']['name'][$key],
                                    'type' => $_FILES['images']['type'][$key],
                                    'tmp_name' => $_FILES['images']['tmp_name'][$key],
                                    'error' => $_FILES['images']['error'][$key],
                                    'size' => $_FILES['images']['size'][$key]
                                )
                            );

                            // Any extention would work
                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                            $uploader->setAllowRenameFiles(true);

                            $uploader->setFilesDispersion(false);

                            $path = Mage::getBaseDir('media') . DS . 'customer_return' . DS  . $orderId. DS;
                            $img = $uploader->save($path, $_FILES['images']['name'][$key]);
                            $returnImgModel = Mage::getModel('icube_rma/images');
                            $returnImgModel->setRmaId($result->getId())->setImages($orderId . DS . str_replace(' ', '_', $_FILES['images']['name'][$key]))->save();
                            $imageUrlArr[] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'customer_return'.DS.$orderId . DS . str_replace(' ', '_', $_FILES['images']['name'][$key]);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                            Mage::log($e->getMessage());
                        }
                    }

                    //create Zendesk Ticket
                    $orderZendesk = Mage::getStoreConfig('rma/zendesk/order_id', Mage::app()->getStore()); //Order ID custom ticket field ID
                    $rmaZendesk = Mage::getStoreConfig('rma/zendesk/rma_id', Mage::app()->getStore()); //RMA ID custom ticket field ID
                    $channelZendesk = Mage::getStoreConfig('rma/zendesk/channel', Mage::app()->getStore()); //Channel Complain custom ticket field ID
                    $ticketContent = "Nama Customer : ".$order->getCustomerFirstname()." ".$order->getCustomerLastname()." \n ".
                                    "Email Customer : ".$order->getCustomerEmail()." \n ".
                                    "Telephone Customer : ".$order->getShippingAddress()->getTelephone(). " \n \n ";
                    
                    $ticketContent .= "Barang yang dikembalikan : \n ";
                    foreach ($result->getItemsCollection() as $key => $item) {
                        $count = $key+1;
                        $ticketContent .= $count.". ". $item->getProductName(). " \n ".
                                        "~ SKU : ".$item->getProductSku()." \n ".
                                        "~ Qty barang yang dikembalikan : ".$item->getQtyRequested()." \n ".
                                        "~ Penjelasan pengembalian : ".$item->getDescription()." \n ";
                        
                        $reasonList = Mage::helper('enterprise_rma/eav')->getAttributeOptionValues('reason', $result->getStoreId(), $useDefaultValue = true);
                        $reasonLabel = $reasonList[$item->getReason()];
                        $ticketContent .= "~ Alasan pengembalian : ".$reasonLabel." \n ";                    
                    }

                    $ticketContent .= "Attached Image : \n ";   
                    foreach ($imageUrlArr as $imgUrl) {
                        $ticketContent .= $imgUrl." \n ";
                    }

                    if(count($imageUrlArr) == 0) {
                        $ticketContent .= " - \n";
                    }

                    $data = array(
                        'ticket' => array(
                            'requester' => array(
                                'name' => $order->getCustomerFirstname().' '.$order->getCustomerLastname(), 
                                'email' => $order->getCustomerEmail()
                                ) , 
                            'subject' => 'Order ID : '.$order->getIncrementId().' - RMA ID : '.$result->getIncrementId(), 
                            'description' => $ticketContent, 
                            'type' => 'task',
                            'priority' => 'normal',
                            'tags' => array('rma'), 
                            'custom_fields' => array(
                                array(
                                    'id' => $orderZendesk, 
                                    'value' => $order->getIncrementId()
                                    ), 
                                array(
                                    'id' => $rmaZendesk, 
                                    'value' => $result->getIncrementId()
                                    ),
                                array(
                                    'id' => $channelZendesk, 
                                    'value' => 'channel_email'
                                    ),
                                ) 
                            ) );
                    $data_json = json_encode($data);
                    $url = Mage::getStoreConfig('rma/zendesk/api', Mage::app()->getStore());
                    $email = Mage::getStoreConfig('rma/zendesk/username', Mage::app()->getStore());
                    $password = Mage::getStoreConfig('rma/zendesk/password', Mage::app()->getStore());
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                    curl_setopt($ch, CURLOPT_USERPWD, $email.":".$password);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    $response = curl_exec($ch);
                    $info = curl_getinfo($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
                    curl_close($ch);

                    Mage::log('JSON Data sent: .'.$data_json.' | HTTP CODE RESULT: '.$httpCode,null,'rma-zendesk.log');


                    $result->sendNewRmaEmail();
                    if (isset($postData['rma_comment']) && !empty($postData['rma_comment'])) {
                        Mage::getModel('enterprise_rma/rma_status_history')
                            ->setRmaEntityId($rmaModel->getId())
                            ->setComment($postData['rma_comment'])
                            ->setIsVisibleOnFront(true)
                            ->setStatus($rmaModel->getStatus())
                            ->setCreatedAt(Mage::getSingleton('core/date')->gmtDate())
                            ->save();
                    }
                    Mage::getSingleton('core/session')->addSuccess(
                        Mage::helper('icube_rma')->__('Return #%s has been submitted successfully', $rmaModel->getIncrementId())
                    );
                    $this->_redirectSuccess(Mage::getUrl('*/*/search'));
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton('core/session')->addError(
                        Mage::helper('icube_rma')->__('Cannot create New Return, try again later')
                    );
                    Mage::logException($e);
                }
            }
            $this->loadLayout();
            $this->_initLayoutMessages('core/session');
            $this->getLayout()->getBlock('head')->setTitle(Mage::helper('icube_rma')->__('Create New Return'));
            if ($block = $this->getLayout()->getBlock('customer.account.link.back')) {
                $block->setRefererUrl($this->_getRefererUrl());
            }
            $this->renderLayout();
        } else {
            $this->_redirect('sales/order/history');
        }
    }

    public function searchAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Check order view availability
     *
     * @param   Enterprise_Rma_Model_Rma | Mage_Sales_Model_Order $item
     * @return  bool
     */
    protected function _canViewOrder($item,$email)
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        if ($item->getId() && $item->getCustomerId() && ($item->getCustomerId() == $customerId)) {
            return true;
        }
        else if ($item->getId() && ($email == $item->getCustomerEmail())){
            return true;
        }
        return false;
    }

    public function historyAction()
    {
        if (!$this->_isEnabledOnFront()) {
            $this->_forward('noRoute');
            return false;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('enterprise_rma')->__('My Returns'));

        if ($block = $this->getLayout()->getBlock('customer.account.link.back')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->renderLayout();
    }

    public function updatestatusAction()
    {
        $rmaId      = $this->getRequest()->getParam('rmaId');
        $status      = $this->getRequest()->getParam('status');
        Mage::log('Update status action triggered.',null,'rma-zendesk.log');
        Mage::log($this->getRequest()->getRequesturi(),null,'rma-zendesk.log');
        try{
            $rma = Mage::getModel('enterprise_rma/rma')->load($rmaId,'increment_id');
            if (!$rma->getId()) {
                Mage::log('No RMA Found! RMA #'.$rmaId, null, 'rma-zendesk.log');
                $this->_fault('not_exists');
                // No invoice found
                return false;
            }

            $rma->setStatus($status)->save();
            $result = 'RMA Updated! RMA #'.$rmaId.', Status : '.$status;
            echo $result;
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $result = $e->getMessage();
        }
        Mage::log($result, null, 'rma-zendesk.log');
        return $result;

    }

    

}
