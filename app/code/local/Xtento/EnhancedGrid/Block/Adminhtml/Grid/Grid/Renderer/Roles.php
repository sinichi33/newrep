<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-19T14:53:48+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Grid/Renderer/Roles.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Grid_Renderer_Roles extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $allRoles = Mage::getModel('xtento_enhancedgrid/system_config_source_admin_roles')->toOptionArray();
        $roleIds = $row->getRoleIds();
        $gridRoles = array();
        foreach ($allRoles as $role) {
            if (in_array($role['value'], explode(",", $roleIds))) {
                $gridRoles[] = $role['label'];
            }
        }
        return implode(", ", $gridRoles);
    }
}