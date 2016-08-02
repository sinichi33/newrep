<?php
require_once(Mage::getModuleDir('controllers','Enterprise_Rma').DS.'Adminhtml'.DS.'RmaController.php');
class Icube_Rma_Adminhtml_RmaController extends Enterprise_Rma_Adminhtml_RmaController
{

    /**
     * Save New RMA
     *
     * @throws Mage_Core_Exception
     */
    public function saveNewAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            if ($this->getRequest()->getParam('back', false)) {
                $this->_redirect('*/*/');
                return;
            }
            try {
                /** @var $model Enterprise_Rma_Model_Rma */
                $model = $this->_initModel();
                $order = Mage::registry('current_order');
                $orderId = $order->getId();
                $rmaData = array(
                    'status'                => Enterprise_Rma_Model_Rma_Source_Status::STATE_PENDING,
                    'date_requested'        => Mage::getSingleton('core/date')->gmtDate(),
                    'order_id'              => $order->getId(),
                    'order_increment_id'    => $order->getIncrementId(),
                    'store_id'              => $order->getStoreId(),
                    'customer_id'           => $order->getCustomerId(),
                    'order_date'            => $order->getCreatedAt(),
                    'customer_name'         => $order->getCustomerName(),
                    'customer_custom_email' => $data['contact_email']
                );
                $model->setData($rmaData);
                $result = $model->saveRmaData($data);

                if ($result && $result->getId()) {
                    if (isset($data['comment'])
                        && isset($data['comment']['comment'])
                        && !empty($data['comment']['comment'])
                    ) {
                        $visible = isset($data['comment']['is_visible_on_front']) ? true : false;

                        Mage::getModel('enterprise_rma/rma_status_history')
                            ->setRmaEntityId($result->getId())
                            ->setComment($data['comment']['comment'])
                            ->setIsVisibleOnFront($visible)
                            ->setStatus($result->getStatus())
                            ->setCreatedAt(Mage::getSingleton('core/date')->gmtDate())
                            ->setIsAdmin(1)
                            ->save();
                    }
                    if (isset($data['rma_confirmation']) && !empty($data['rma_confirmation'])) {
                        $model->sendNewRmaEmail();
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        $this->__('The RMA request has been submitted.')
                    );

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

                    $ticketContent .= "Created from admin page.";

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

                    Mage::log('JSON Data sent: .'.$data_json.' | created from admin page | HTTP CODE RESULT: '.$httpCode,null,'rma-zendesk.log');

                } else {
                    Mage::throwException($this->__('Failed to save RMA.'));
                }
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $errorKeys = Mage::getSingleton('core/session')->getRmaErrorKeys();
                $controllerParams = array('order_id' => Mage::registry('current_order')->getId());
                if (!empty($errorKeys) && isset($errorKeys['tabs']) && ($errorKeys['tabs'] == 'items_section')) {
                    $controllerParams['active_tab'] = 'items_section';
                }
                $this->_redirect('*/*/new', $controllerParams);

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Failed to save RMA.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/');
    }


}
