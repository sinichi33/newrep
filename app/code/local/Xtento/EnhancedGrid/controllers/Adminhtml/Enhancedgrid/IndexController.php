<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-07-08T14:33:23+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/controllers/Adminhtml/Enhancedgrid/IndexController.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Adminhtml_Enhancedgrid_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function permissionsAction()
    {
        Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('xtento_enhancedgrid')->__('You don\'t have rights to customize grids. Please go to System > Permissions > Roles and assign the "XTENTO Enhanced Grid > Grid Customization" permission to your admin role.'));
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        return true;
    }
}