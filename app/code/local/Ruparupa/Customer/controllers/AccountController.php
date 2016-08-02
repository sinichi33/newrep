<?php
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
 * @category    Mage
 * @package     Mage_Customer
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Customer account controller
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */

require_once 'Mage/Customer/controllers/AccountController.php';

class Ruparupa_Customer_AccountController extends Mage_Customer_AccountController
{
    const CUSTOMER_ID_SESSION_NAME = "customerId";
    const TOKEN_SESSION_NAME = "token";

    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('loginPost', 'createpost');

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        // a brute-force protection here would be nice

        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = strtolower($this->getRequest()->getActionName());
        $openActions = array(
            'create',
            'login',
            'logoutsuccess',
            'forgotpassword',
            'forgotpasswordpost',
            'changeforgotten',
            'resetpassword',
            'resetpasswordpost',
            'confirm',
            'confirmation'
        );
        $pattern = '/^(' . implode('|', $openActions) . ')/i';

        if (!preg_match($pattern, $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getSession()->setNoReferer(true);
        }
    }

    /**
     * Action postdispatch
     *
     * Remove No-referer flag from customer session after each action
     */
    public function postDispatch()
    {
        parent::postDispatch();
        $this->_getSession()->unsNoReferer(false);
    }

    /**
     * Default customer account page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }

    /**
     * Customer login form page
     */
    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    /**
     * Login post action
     */
    public function loginPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }

    /**
     * Define target URL and redirect customer after logging in
     */
    protected function _loginPostRedirect()
    {
        $session = $this->_getSession();

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {
            // Set default URL to redirect customer to
            $session->setBeforeAuthUrl($this->_getHelper('customer')->getAccountUrl());
            // Redirect customer to the last page visited after logging in
            if ($session->isLoggedIn()) {
                if (!Mage::getStoreConfigFlag(
                    Mage_Customer_Helper_Data::XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD
                )) {
                    $referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME);
                    if ($referer) {
                        // Rebuild referer URL to handle the case when SID was changed
                        $referer = $this->_getModel('core/url')
                            ->getRebuiltUrl( $this->_getHelper('core')->urlDecodeAndEscape($referer));
                        if ($this->_isUrlInternal($referer)) {
                            $session->setBeforeAuthUrl($referer);
                        }
                    }
                } else if ($session->getAfterAuthUrl()) {
                    $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
                }
            } else {
                $session->setBeforeAuthUrl( $this->_getHelper('customer')->getLoginUrl());
            }
        } else if ($session->getBeforeAuthUrl() ==  $this->_getHelper('customer')->getLogoutUrl()) {
            $session->setBeforeAuthUrl( $this->_getHelper('customer')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    /**
     * Customer logout action
     */
    public function logoutAction()
    {
        $session = $this->_getSession();
        $session->logout()->renewSession();

        if (Mage::getStoreConfigFlag(Mage_Customer_Helper_Data::XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD)) {
            $session->setBeforeAuthUrl(Mage::getBaseUrl());
        } else {
            $session->setBeforeAuthUrl($this->_getRefererUrl());
        }
        $this->_redirect('*/*/logoutSuccess');
    }

    /**
     * Logout success page
     */
    public function logoutSuccessAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Customer register form page
     */
    public function createAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));

        //this is coded by Sutrisno 24-3-2016 for token validation - customer can't register if entity_id not null
        //disabled temporarily to allow customer always can register
        /*$session = $this->_getSession();
        $cookieValue = Mage::getModel('core/cookie')->get('tokenRupaRupa328');
        $resource = Mage::getSingleton('core/resource');
        $readCon = $resource->getConnection('core_read');
        $custid = $readCon->fetchOne('SELECT entity_id FROM customer_token WHERE tokenId = "'.$cookieValue.'"');
        if ($custid != NULL || $custid != 0 || $custid != '') {
            $message = $this->__('Completely sorry. You have been registered before.', $errUrl);
            $session->addError($message);
           $this->_redirectError($errUrl);
            return;
        }*/

        if (!$this->_validateFormKey()) {
            $this->_redirectError($errUrl);
            return;
        }

        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();

        //validate member number by Sutrisno 7-april-2016
        $custace = $this->getRequest()->getPost('member_ace');
        $custtk = $this->getRequest()->getPost('member_toyskingdom');
        $custinfor = $this->getRequest()->getPost('member_informa');

        if ($custace != '' || $custtk != '' || $custinfor != '') {
            $custace = strtolower($custace);
            $custtk = strtolower($custtk);
            $custinfor = strtolower($custinfor);

            //check length of member number
            $lengthace = strlen($custace);
            $lengthtk = strlen($custtk);
            $lengthinfor = strlen($custinfor);

            //check 2 digit and 1 digit first member number
            $digace = substr($custace, 0, 2);
            $digtk = substr($custtk, 0, 2);
            $diginfor = substr($custinfor, 0, 2);
            $diginforx = substr($custinfor, 0, 1);

        }
        //validate length
        if ($custace != '' && ($lengthace > 10 || $lengthace < 7)) {
            $errormemno = 'true';
        }
        else if ($custtk != '' && ($lengthtk > 10 || $lengthtk < 7)) {
            $errormemno = 'true';
        }
        else if ($custinfor != '' && ($lengthinfor > 10 || $lengthinfor < 7)) {
            $errormemno = 'true';
        }
        else if (!isset($errormemno)){ $errormemno = 'false'; }

        if ($errormemno == 'false') {
            //validate checking 2 digits

            if($digace != '') {
                if (!in_array($digace, array('ar','ir','ta','ci','av'), true) ) {
                    $errornum = 'true';
                    //mage::log('digace nya '.$digace);
                    //mage::log('errornum '.$errornum);
                }
            }
            if($diginfor != '') {
                if (!in_array($diginfor, array('ti','ir','ci'), true) ) {
                    $errornum = 'true';
                    //mage::log('diginfor nya '.$diginfor);
                    //mage::log('errornum '.$errornum);
                }
            }
            if($digtk != ''){
                if (!in_array($digtk, array('sc','tc'), true) ) {
                    $errornum = 'true';
                    //mage::log('digtk nya '.$digtk);
                    //mage::log('errornum '.$errornum);
                }
            }
            if (!$errornum){ $errornum = 'false'; }

        }

        try {
            $errors = $this->_getCustomerErrors($customer);

            //mage::log('errormemno :'.$errormemno);
            //mage::log('errornum :'.$errornum);

            if (empty($errors) && $errormemno == 'false' && $errornum == 'false') {
                $customer->cleanPasswordsValidationData();
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);
                return;
            }
            else if ($errornum == 'true' || $errormemno == 'true') {
                Mage::getSingleton('core/session')->addError('Maaf. Nomor member yang anda masukkan salah!');
            }
            else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
            } else {
                $message = $this->_escapeHtml($e->getMessage());
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            $session->addException($e, $this->__('Cannot save the customer.'));
        }

        $this->_redirectError($errUrl);
    }

    /**
     * Success Registration
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Customer_AccountController
     */
    protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
    {
        $session = $this->_getSession();
        if ($customer->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store*/
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId()
            );
            $customerHelper = $this->_getHelper('customer');
            $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
                $customerHelper->getEmailConfirmationUrl($customer->getEmail())));
            $url = $this->_getUrl('*/*/index', array('_secure' => true));
        } else {

            $session->setCustomerAsLoggedIn($customer);

            //added by Sutrisno 24-3-2016 for launching member
            $cookie = Mage::getModel('core/cookie')->get('tokenRupaRupa328');
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $custid = $customerData->getId();
            $custace = $customerData->getMemberAce();
            $custtk = $customerData->getMemberToyskingdom();
            $custinfor = $customerData->getMemberInforma();
            $resource = Mage::getSingleton('core/resource');

            $expire = date('Y-m-d', strtotime("+2 week"));
            $today = date('Y-m-d');
            //Mage::log('cookie: '.$cookie);
            //Mage::log('customer infor: '.$custinfor);

            //buat assigning group
            $readCon = $resource->getConnection('core_read');
            $writeCon = $resource->getConnection('core_write');
            $cgroup = $readCon->fetchOne('SELECT customer_group_id FROM customer_token WHERE tokenId = "'.$cookie.'"');
            if ($custace != '') {
                $cgroup = 4;
            }else if ($custinfor != '') {
                $cgroup = 5;
            }else if ($custtk != '') {
                $cgroup = 6;
            }
            $customer->setGroupId($cgroup);
            $customer->save();

            //create gift card from pool and check if entity_id is null

            $entid = $readCon->fetchOne('SELECT entity_id FROM customer_token WHERE tokenId = "'.$cookie.'"');

            $tokentype = $readCon->fetchOne('SELECT tokenType FROM customer_token WHERE tokenId = "'.$cookie.'"');

            /* if (($entid == NULL || $entid == 0 || $entid == '') && $tokentype == '1') {

            //value for giftcard
            $gcpool = $readCon->fetchOne('SELECT code FROM enterprise_giftcardaccount_pool WHERE status = 0 AND campaign_name = "memberlaunch50"');

            $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
            $gift_card
                ->setCode($gcpool)
                ->setStatus($gift_card::STATUS_ENABLED)
                ->setDateExpires($expire)
                ->setCustomerId($custid)
                ->setWebsiteId(1)
                ->setState($gift_card::STATE_AVAILABLE)
                ->setIsRedeemable(0)
                ->setCategoryIdsExclusion(5312)
                ->setMinPurchaseValue(250000)
                ->setValidFromDate($today)
                ->setValidToDate($expire)
                ->setRestrictCombine(1)
                ->setCampaignName(launchmember50)
                ->setBalance(50000);

            $gift_card->save();

            $writeCon->query('UPDATE enterprise_giftcardaccount_pool SET status = 1 WHERE code = "'.$gcpool.'"');
            //update entity id di table token kalau dia unique
            $writeCon->query('UPDATE customer_token SET entity_id = "'.$custid.'" WHERE tokenId = "'.$cookie.'" AND tokenType = 1');
            }

            else */ if ( $cookie == 'bnitoken'  ) {
                //this is for promo BNI by sutrisno 4-4-2016

                //value for giftcard
                $gcpool = $readCon->fetchOne('SELECT code FROM enterprise_giftcardaccount_pool WHERE status = 0 AND campaign_name = "promobni"');

                $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
                $gift_card
                    ->setCode($gcpool)
                    ->setStatus($gift_card::STATUS_ENABLED)
                    ->setDateExpires($expire)
                    ->setCustomerId($custid)
                    ->setWebsiteId(1)
                    ->setState($gift_card::STATE_AVAILABLE)
                    ->setIsRedeemable(0)
                    ->setCategoryIdsExclusion(5312)
                    ->setMinPurchaseValue(250000)
                    ->setValidFromDate($today)
                    ->setValidToDate($expire)
                    ->setRestrictCombine(1)
                    ->setCampaignName(promobni)
                    ->setBalance(50000)
                    ->setBinType(BNI);

                $gift_card->save();

                $writeCon->query('UPDATE enterprise_giftcardaccount_pool SET status = 1 WHERE code = "'.$gcpool.'"');

            }

            else if ($custace != '' || $custtk != '' || $custinfor != '') {
                //for setting up member who input membercard will get giftcard

                //value for giftcard
                $gcpool = $readCon->fetchOne('SELECT code FROM enterprise_giftcardaccount_pool WHERE status = 0 AND campaign_name = "memberlaunch50"');

                $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
                $gift_card
                    ->setCode($gcpool)
                    ->setStatus($gift_card::STATUS_ENABLED)
                    ->setDateExpires($expire)
                    ->setCustomerId($custid)
                    ->setWebsiteId(1)
                    ->setState($gift_card::STATE_AVAILABLE)
                    ->setIsRedeemable(0)
                    ->setCategoryIdsExclusion(5312)
                    ->setMinPurchaseValue(250000)
                    ->setValidFromDate($today)
                    ->setValidToDate($expire)
                    ->setRestrictCombine(1)
                    ->setCampaignName('promomember')
                    ->setBalance(50000);

                $gift_card->save();

                $writeCon->query('UPDATE enterprise_giftcardaccount_pool SET status = 1 WHERE code = "'.$gcpool.'"');

            }

            else {
                //for setting up public promo by Sutrisno 22-4-2016

                //value for giftcard
                /*
                $gcpool = $readCon->fetchOne('SELECT code FROM enterprise_giftcardaccount_pool WHERE status = 0 AND campaign_name = "promopublic"');

                $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
                $gift_card
                    ->setCode($gcpool)
                    ->setStatus($gift_card::STATUS_ENABLED)
                    ->setDateExpires($expire)
                    ->setCustomerId($custid)
                    ->setWebsiteId(1)
                    ->setState($gift_card::STATE_AVAILABLE)
                    ->setIsRedeemable(0)
                    ->setCategoryIdsExclusion(5312)
                    ->setMinPurchaseValue(300000)
                    ->setValidFromDate($today)
                    ->setValidToDate($expire)
                    ->setRestrictCombine(1)
                    ->setCampaignName('promopublic')
                    ->setBalance(50000);

                $gift_card->save();

                $writeCon->query('UPDATE enterprise_giftcardaccount_pool SET status = 1 WHERE code = "'.$gcpool.'"');
                */
            }

            $url = $this->_welcomeCustomer($customer);
        }
        $this->_redirectSuccess($url);
        return $this;
    }

    /**
     * Get Customer Model
     *
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        $customer = $this->_getFromRegistry('current_customer');
        if (!$customer) {
            $customer = $this->_getModel('customer/customer')->setId(null);
        }
        if ($this->getRequest()->getParam('is_subscribed', false)) {
            $customer->setIsSubscribed(1);
        }
        /**
         * Initialize customer group id
         */
        $customer->getGroupId();

        return $customer;
    }

    /**
     * Add session error method
     *
     * @param string|array $errors
     */
    protected function _addSessionError($errors)
    {
        $session = $this->_getSession();
        $session->setCustomerFormData($this->getRequest()->getPost());
        if (is_array($errors)) {
            foreach ($errors as $errorMessage) {
                $session->addError($this->_escapeHtml($errorMessage));
            }
        } else {
            $session->addError($this->__('Invalid customer data'));
        }
    }

    /**
     * Escape message text HTML.
     *
     * @param string $text
     * @return string
     */
    protected function _escapeHtml($text)
    {
        return Mage::helper('core')->escapeHtml($text);
    }

    /**
     * Validate customer data and return errors if they are
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return array|string
     */
    protected function _getCustomerErrors($customer)
    {
        $errors = array();
        $request = $this->getRequest();
        if ($request->getPost('create_address')) {
            $errors = $this->_getErrorsOnCustomerAddress($customer);
        }
        $customerForm = $this->_getCustomerForm($customer);
        $customerData = $customerForm->extractData($request);
        $customerErrors = $customerForm->validateData($customerData);
        if ($customerErrors !== true) {
            $errors = array_merge($customerErrors, $errors);
        } else {
            $customerForm->compactData($customerData);
            $customer->setPassword($request->getPost('password'));
            $customer->setPasswordConfirmation($request->getPost('confirmation'));
            $customerErrors = $customer->validate();
            if (is_array($customerErrors)) {
                $errors = array_merge($customerErrors, $errors);
            }
        }
        return $errors;
    }

    /**
     * Get Customer Form Initalized Model
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Customer_Model_Form
     */
    protected function _getCustomerForm($customer)
    {
        /* @var $customerForm Mage_Customer_Model_Form */
        $customerForm = $this->_getModel('customer/form');
        $customerForm->setFormCode('customer_account_create');
        $customerForm->setEntity($customer);
        return $customerForm;
    }

    /**
     * Get Helper
     *
     * @param string $path
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getHelper($path)
    {
        return Mage::helper($path);
    }

    /**
     * Get App
     *
     * @return Mage_Core_Model_App
     */
    protected function _getApp()
    {
        return Mage::app();
    }

    /**
     * Dispatch Event
     *
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _dispatchRegisterSuccess($customer)
    {
        Mage::dispatchEvent('customer_register_success',
            array('account_controller' => $this, 'customer' => $customer)
        );
    }

    /**
     * Gets customer address
     *
     * @param $customer
     * @return array $errors
     */
    protected function _getErrorsOnCustomerAddress($customer)
    {
        $errors = array();
        /* @var $address Mage_Customer_Model_Address */
        $address = $this->_getModel('customer/address');
        /* @var $addressForm Mage_Customer_Model_Form */
        $addressForm = $this->_getModel('customer/form');
        $addressForm->setFormCode('customer_register_address')
            ->setEntity($address);

        $addressData = $addressForm->extractData($this->getRequest(), 'address', false);
        $addressErrors = $addressForm->validateData($addressData);
        if (is_array($addressErrors)) {
            $errors = array_merge($errors, $addressErrors);
        }
        $address->setId(null)
            ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
            ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
        $addressForm->compactData($addressData);
        $customer->addAddress($address);

        $addressErrors = $address->validate();
        if (is_array($addressErrors)) {
            $errors = array_merge($errors, $addressErrors);
        }
        return $errors;
    }

    /**
     * Get model by path
     *
     * @param string $path
     * @param array|null $arguments
     * @return false|Mage_Core_Model_Abstract
     */
    public function _getModel($path, $arguments = array())
    {
        return Mage::getModel($path, $arguments);
    }

    /**
     * Get model from registry by path
     *
     * @param string $path
     * @return mixed
     */
    protected function _getFromRegistry($path)
    {
        return Mage::registry($path);
    }

    /**
     * Add welcome message and send new account email.
     * Returns success URL
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param bool $isJustConfirmed
     * @return string
     */
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );
        if ($this->_isVatValidationEnabled()) {
            // Show corresponding VAT message to customer
            $configAddressType =  $this->_getHelper('customer/address')->getTaxCalculationAddressType();
            $userPrompt = '';
            switch ($configAddressType) {
                case Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING:
                    $userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you shipping address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit'));
                    break;
                default:
                    $userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you billing address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit'));
            }
            $this->_getSession()->addSuccess($userPrompt);
        }

        //added by Sutrisno 24-3-2016 to send gift card email to customer
        $cookie = Mage::getModel('core/cookie')->get('tokenRupaRupa328');
        $resource = Mage::getSingleton('core/resource');
        $readCon = $resource->getConnection('core_read');
        $writeCon = $resource->getConnection('core_write');
        $sentmail = $readCon->fetchOne('SELECT email_sent FROM customer_token WHERE tokenId = "'.$cookie.'" AND tokenType = 1');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $custid = $customerData->getId();
        $custace = $customerData->getMemberAce();
        $custtk = $customerData->getMemberToyskingdom();
        $custinfor = $customerData->getMemberInforma();

        $gcpool_code = $readCon->fetchOne('SELECT code FROM enterprise_giftcardaccount WHERE customer_id = "'.$custid.'"');

        $gcpool_campaign = $readCon->fetchOne('SELECT campaign_name FROM enterprise_giftcardaccount WHERE customer_id = "'.$custid.'"');

        //mage::log('sent mailnya '.$sentmail);
        //mage::log('gcpoolnya '.$gcpool);

        if (($sentmail == 0 || $custace != '' || $custtk != '' || $custinfor != '') && $gcpool_campaign != 'promobni'){

            $emailcust = $customer->getEmail();
            $namecust = $customer->getName();
            $firstname = $customer->getFirstname();
            $expire = date('d-m-Y', strtotime("+2 week"));

            $storeId = Mage::app()->getStore()->getStoreId();
            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($emailcust, $firstname);
            $mailer->addEmailInfo($emailInfo);
            $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId('ruparupa_customer_account_new_giftcard_template');
            $mailer->setTemplateParams(array(
                    'voucher'      => $gcpool_code,
                    'fullname'     => $namecust,
                    'expire'       => $expire,
                    'name'         => $firstname
                )
            );
            $mailer->send();

            //mage::log('alamat email '.$emailcust);
            //mage::log('nama '.$namecust);
            if ($sentmail == 0){
                $writeCon->query('UPDATE customer_token SET email_sent = 1 WHERE tokenId = "'.$cookie.'" AND tokenType = 1');
            }
        }

        else if ($gcpool_campaign == 'promobni'){

            $emailcust = $customer->getEmail();
            $namecust = $customer->getName();
            $firstname = $customer->getFirstname();
            $expire = date('d-m-Y', strtotime("+2 week"));

            $storeId = Mage::app()->getStore()->getStoreId();
            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($emailcust, $firstname);
            $mailer->addEmailInfo($emailInfo);
            $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId('ruparupa_customer_account_new_giftcard_bni_template');
            $mailer->setTemplateParams(array(
                    'voucher'      => $gcpool_code,
                    'fullname'     => $namecust,
                    'expire'       => $expire,
                    'name'         => $firstname
                )
            );
            $mailer->send();
        }


        else if ($gcpool_campaign == 'promopublic'){

            $emailcust = $customer->getEmail();
            $namecust = $customer->getName();
            $firstname = $customer->getFirstname();
            $expire = date('d-m-Y', strtotime("+2 week"));

            $storeId = Mage::app()->getStore()->getStoreId();
            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($emailcust, $firstname);
            $mailer->addEmailInfo($emailInfo);
            $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId('ruparupa_customer_account_new_giftcard_template');
            $mailer->setTemplateParams(array(
                    'voucher'      => $gcpool_code,
                    'fullname'     => $namecust,
                    'expire'       => $expire,
                    'name'         => $firstname
                )
            );
            $mailer->send();
        }

        else {
            $customer->sendNewAccountEmail(
                $isJustConfirmed ? 'confirmed' : 'registered',
                '',
                Mage::app()->getStore()->getId()
            );
        }

        $successUrl = $this->_getUrl('*/*/index', array('_secure' => true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }

    /**
     * Confirm customer account by id and confirmation key
     */
    public function confirmAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_getSession()->logout()->regenerateSessionId();
        }
        try {
            $id      = $this->getRequest()->getParam('id', false);
            $key     = $this->getRequest()->getParam('key', false);
            $backUrl = $this->getRequest()->getParam('back_url', false);
            if (empty($id) || empty($key)) {
                throw new Exception($this->__('Bad request.'));
            }

            // load customer by id (try/catch in case if it throws exceptions)
            try {
                $customer = $this->_getModel('customer/customer')->load($id);
                if ((!$customer) || (!$customer->getId())) {
                    throw new Exception('Failed to load customer by id.');
                }
            }
            catch (Exception $e) {
                throw new Exception($this->__('Wrong customer account specified.'));
            }

            // check if it is inactive
            if ($customer->getConfirmation()) {
                if ($customer->getConfirmation() !== $key) {
                    throw new Exception($this->__('Wrong confirmation key.'));
                }

                // activate customer
                try {
                    $customer->setConfirmation(null);
                    $customer->save();
                }
                catch (Exception $e) {
                    throw new Exception($this->__('Failed to confirm customer account.'));
                }

                // log in and send greeting email, then die happy
                $session->setCustomerAsLoggedIn($customer);
                $successUrl = $this->_welcomeCustomer($customer, true);
                $this->_redirectSuccess($backUrl ? $backUrl : $successUrl);
                return;
            }

            // die happy
            $this->_redirectSuccess($this->_getUrl('*/*/index', array('_secure' => true)));
            return;
        }
        catch (Exception $e) {
            // die unhappy
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError($this->_getUrl('*/*/index', array('_secure' => true)));
            return;
        }
    }

    /**
     * Send confirmation link to specified email
     */
    public function confirmationAction()
    {
        $customer = $this->_getModel('customer/customer');
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        // try to confirm by email
        $email = $this->getRequest()->getPost('email');
        if ($email) {
            try {
                $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
                if (!$customer->getId()) {
                    throw new Exception('');
                }
                if ($customer->getConfirmation()) {
                    $customer->sendNewAccountEmail('confirmation', '', Mage::app()->getStore()->getId());
                    $this->_getSession()->addSuccess($this->__('Please, check your email for confirmation key.'));
                } else {
                    $this->_getSession()->addSuccess($this->__('This email does not require confirmation.'));
                }
                $this->_getSession()->setUsername($email);
                $this->_redirectSuccess($this->_getUrl('*/*/index', array('_secure' => true)));
            } catch (Exception $e) {
                $this->_getSession()->addException($e, $this->__('Wrong email.'));
                $this->_redirectError($this->_getUrl('*/*/*', array('email' => $email, '_secure' => true)));
            }
            return;
        }

        // output form
        $this->loadLayout();

        $this->getLayout()->getBlock('accountConfirmation')
            ->setEmail($this->getRequest()->getParam('email', $email));

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Get Url method
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    protected function _getUrl($url, $params = array())
    {
        return Mage::getUrl($url, $params);
    }

    /**
     * Forgot customer password page
     */
    public function forgotPasswordAction()
    {
        $this->loadLayout();

        $this->getLayout()->getBlock('forgotPassword')->setEmailValue(
            $this->_getSession()->getForgottenEmail()
        );
        $this->_getSession()->unsForgottenEmail();

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Forgot customer password action
     */
    public function forgotPasswordPostAction()
    {
        $email = (string) $this->getRequest()->getPost('email');
        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->_getSession()->setForgottenEmail($email);
                $this->_getSession()->addError($this->__('Invalid email address.'));
                $this->_redirect('*/*/forgotpassword');
                return;
            }

            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken =  $this->_getHelper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->sendPasswordResetConfirmationEmail();
                } catch (Exception $exception) {
                    $this->_getSession()->addError($exception->getMessage());
                    $this->_redirect('*/*/forgotpassword');
                    return;
                }
            }
            $this->_getSession()
                ->addSuccess( $this->_getHelper('customer')
                    ->__('If there is an account associated with %s you will receive an email with a link to reset your password.',
                        $this->_getHelper('customer')->escapeHtml($email)));
            $this->_redirect('*/*/');
            return;
        } else {
            $this->_getSession()->addError($this->__('Please enter your email.'));
            $this->_redirect('*/*/forgotpassword');
            return;
        }
    }

    /**
     * Display reset forgotten password form
     *
     */
    public function changeForgottenAction()
    {
        try {
            list($customerId, $resetPasswordLinkToken) = $this->_getRestorePasswordParameters($this->_getSession());
            $this->_validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken);
            $this->loadLayout();
            $this->renderLayout();

        } catch (Exception $exception) {
            $this->_getSession()->addError($this->_getHelper('customer')->__('Your password reset link has expired.'));
            $this->_redirect('*/*/forgotpassword');
        }
    }

    /**
     * Checks reset forgotten password token
     *
     * User is redirected on this action when he clicks on the corresponding link in password reset confirmation email.
     *
     */
    public function resetPasswordAction()
    {
        try {
            $customerId = (int)$this->getRequest()->getQuery("id");
            $resetPasswordLinkToken = (string)$this->getRequest()->getQuery('token');

            $this->_validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken);
            $this->_saveRestorePasswordParameters($customerId, $resetPasswordLinkToken)
                ->_redirect('*/*/changeforgotten');

        } catch (Exception $exception) {
            $this->_getSession()->addError($this->_getHelper('customer')->__('Your password reset link has expired.'));
            $this->_redirect('*/*/forgotpassword');
        }
    }

    /**
     * Reset forgotten password
     * Used to handle data recieved from reset forgotten password form
     */
    public function resetPasswordPostAction()
    {
        list($customerId, $resetPasswordLinkToken) = $this->_getRestorePasswordParameters($this->_getSession());
        $password = (string)$this->getRequest()->getPost('password');
        $passwordConfirmation = (string)$this->getRequest()->getPost('confirmation');

        try {
            $this->_validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken);
        } catch (Exception $exception) {
            $this->_getSession()->addError($this->_getHelper('customer')->__('Your password reset link has expired.'));
            $this->_redirect('*/*/');
            return;
        }

        $errorMessages = array();
        if (iconv_strlen($password) <= 0) {
            array_push($errorMessages, $this->_getHelper('customer')->__('New password field cannot be empty.'));
        }
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $this->_getModel('customer/customer')->load($customerId);

        $customer->setPassword($password);
        $customer->setPasswordConfirmation($passwordConfirmation);
        $validationErrorMessages = $customer->validate();
        if (is_array($validationErrorMessages)) {
            $errorMessages = array_merge($errorMessages, $validationErrorMessages);
        }

        if (!empty($errorMessages)) {
            $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
            foreach ($errorMessages as $errorMessage) {
                $this->_getSession()->addError($errorMessage);
            }
            $this->_redirect('*/*/changeforgotten');
            return;
        }

        try {
            // Empty current reset password token i.e. invalidate it
            $customer->setRpToken(null);
            $customer->setRpTokenCreatedAt(null);
            $customer->cleanPasswordsValidationData();
            $customer->save();

            $this->_getSession()->unsetData(self::TOKEN_SESSION_NAME);
            $this->_getSession()->unsetData(self::CUSTOMER_ID_SESSION_NAME);

            $this->_getSession()->addSuccess($this->_getHelper('customer')->__('Your password has been updated.'));
            $this->_redirect('*/*/login');
        } catch (Exception $exception) {
            $this->_getSession()->addException($exception, $this->__('Cannot save a new password.'));
            $this->_redirect('*/*/changeforgotten');
            return;
        }
    }

    /**
     * Check if password reset token is valid
     *
     * @param int $customerId
     * @param string $resetPasswordLinkToken
     * @throws Mage_Core_Exception
     */
    protected function _validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken)
    {
        if (!is_int($customerId)
            || !is_string($resetPasswordLinkToken)
            || empty($resetPasswordLinkToken)
            || empty($customerId)
            || $customerId < 0
        ) {
            throw Mage::exception('Mage_Core', $this->_getHelper('customer')->__('Invalid password reset token.'));
        }

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $this->_getModel('customer/customer')->load($customerId);
        if (!$customer || !$customer->getId()) {
            throw Mage::exception('Mage_Core', $this->_getHelper('customer')->__('Wrong customer account specified.'));
        }

        $customerToken = $customer->getRpToken();
        if (strcmp($customerToken, $resetPasswordLinkToken) != 0 || $customer->isResetPasswordLinkTokenExpired()) {
            throw Mage::exception('Mage_Core', $this->_getHelper('customer')->__('Your password reset link has expired.'));
        }
    }

    /**
     * Forgot customer account information page
     */
    public function editAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $block = $this->getLayout()->getBlock('customer_edit');
        if ($block) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $data = $this->_getSession()->getCustomerFormData(true);
        $customer = $this->_getSession()->getCustomer();
        if (!empty($data)) {
            $customer->addData($data);
        }
        if ($this->getRequest()->getParam('changepass') == 1) {
            $customer->setChangePassword(1);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Account Information'));
        $this->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        $this->renderLayout();
    }

    /**
     * Change customer password action
     */
    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getSession()->getCustomer();

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = $this->_getModel('customer/form');
            $customerForm->setFormCode('customer_account_edit')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            $errors = array();
            $customerErrors = $customerForm->validateData($customerData);
            if ($customerErrors !== true) {
                $errors = array_merge($customerErrors, $errors);
            } else {
                $customerForm->compactData($customerData);
                $errors = array();

                // If password change was requested then add it to common validation scheme
                if ($this->getRequest()->getParam('change_password')) {
                    $currPass   = $this->getRequest()->getPost('current_password');
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                    if ( $this->_getHelper('core/string')->strpos($oldPass, ':')) {
                        list($_salt, $salt) = explode(':', $oldPass);
                    } else {
                        $salt = false;
                    }

                    if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                        if (strlen($newPass)) {
                            /**
                             * Set entered password and its confirmation - they
                             * will be validated later to match each other and be of right length
                             */
                            $customer->setPassword($newPass);
                            $customer->setPasswordConfirmation($confPass);
                        } else {
                            $errors[] = $this->__('New password field cannot be empty.');
                        }
                    } else {
                        $errors[] = $this->__('Invalid current password');
                    }
                }

                // Validate account and compose list of errors if any
                $customerErrors = $customer->validate();
                if (is_array($customerErrors)) {
                    $errors = array_merge($errors, $customerErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $customer->cleanPasswordsValidationData();
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('The account information has been saved.'));

                $this->_redirect('customer/account');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('*/*/edit');
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('dob'));
        return $data;
    }

    /**
     * Check whether VAT ID validation is enabled
     *
     * @param Mage_Core_Model_Store|string|int $store
     * @return bool
     */
    protected function _isVatValidationEnabled($store = null)
    {
        return  $this->_getHelper('customer/address')->isVatValidationEnabled($store);
    }

    /**
     * Get restore password params.
     *
     * @param Mage_Customer_Model_Session $session
     * @return array array ($customerId, $resetPasswordToken)
     */
    protected function _getRestorePasswordParameters(Mage_Customer_Model_Session $session)
    {
        return array(
            (int) $session->getData(self::CUSTOMER_ID_SESSION_NAME),
            (string) $session->getData(self::TOKEN_SESSION_NAME)
        );
    }

    /**
     * Save restore password params to session.
     *
     * @param int $customerId
     * @param  string $resetPasswordLinkToken
     * @return $this
     */
    protected function _saveRestorePasswordParameters($customerId, $resetPasswordLinkToken)
    {
        $this->_getSession()
            ->setData(self::CUSTOMER_ID_SESSION_NAME, $customerId)
            ->setData(self::TOKEN_SESSION_NAME, $resetPasswordLinkToken);

        return $this;
    }
}