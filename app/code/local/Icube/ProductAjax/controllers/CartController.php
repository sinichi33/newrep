<?php
require_once("Mage/Checkout/controllers/CartController.php");

/**
 * Shopping cart controller
 */
class Icube_ProductAjax_CartController extends Mage_Checkout_CartController
{
    
    public function addAction() {
        $storeId = Mage::app()->getStore()->getId();
        $configValue = Mage::getStoreConfig('addtocartajax/option/addtocartajax_enabled', $storeId);
        if($configValue) {
            $response = array();
            $cart   = $this->_getCart();
            $params = $this->getRequest()->getParams();
            try {
                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }
 
                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');
 
                /**
                 * Check product availability
                 */
                if (!$product) {
                    $response['status'] = 'ERROR';
                    $response['message'] = $this->__('Unable to find Product ID');
                }
 
                $cart->addProduct($product, $params);
                if (!empty($related)) {
                    $cart->addProductsByIds(explode(',', $related));
                }
 
                $cart->save();
 
                $this->_getSession()->setCartWasUpdated(true);
 
                /**
                 * @todo remove wishlist observer processAddToCart
                 */
                Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );
 
                if (!$this->_getSession()->getNoCartRedirect(true)) {
                    if (!$cart->getQuote()->getHasError()){
                        $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                        $response['status'] = 'SUCCESS';
                        $response['message'] = $message;
                        
                        $this->loadLayout();

                        $minicart = $this->getLayout()->getBlock('minicart_head')->toHtml();

                        $notification = $this->getLayout()->createBlock('core/template','added_to_cart_result')
                        ->setTemplate('icube/ajaxaddtocart/result.phtml');
                        $notification->setProductName($product->getName());
                        $notificationContent = $notification->toHtml();

                        $response['popup'] = $notificationContent;
                        $response['minicart'] = $minicart;

                    }
                }
            } catch (Mage_Core_Exception $e) {
                $msg = "";
                if ($this->_getSession()->getUseNotice(true)) {
                    $msg = $e->getMessage();
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $msg .= $message.'<br/>';
                    }
                }
 
                $response['status'] = 'ERROR';
                $response['message'] = $msg;
            } catch (Exception $e) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Cannot add the item to shopping cart.');
                Mage::logException($e);
            }

            if(count($response) === 0)
            {
                $response['status'] = 'SUCCESS';
                $response['message'] =  $this->__('You cannot continue to checkout. Please check your shopping cart');

                $this->loadLayout();

                $minicart = $this->getLayout()->getBlock('minicart_head')->toHtml();

                $notification = $this->getLayout()->createBlock('core/template','added_to_cart_result')
                    ->setTemplate('icube/ajaxaddtocart/result.phtml');
                $notification->setProductName($product->getName());
                $notificationContent = $notification->toHtml();

                $response['popup'] = $notificationContent;
                $response['minicart'] = $minicart;
            }

            Mage::register('cartreturn', $response);
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            
            return;
        }
        else {
            return parent::addAction();
        }
    }

}
