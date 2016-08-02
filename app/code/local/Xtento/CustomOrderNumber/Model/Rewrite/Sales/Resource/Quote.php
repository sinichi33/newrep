<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-08-12T15:50:17+02:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Model/Rewrite/Sales/Resource/Quote.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Model_Rewrite_Sales_Resource_Quote extends Mage_Sales_Model_Resource_Quote
{
    /**
     * Check is order increment id use in sales/order table
     *
     * Overriding to fix issue with characters in order increment id
     *
     * @param string $orderIncrementId (FIX - class rewrite required because of this - Must be string not int)
     * @return boolean
     */
    public function isOrderIncrementIdUsed($orderIncrementId)
    {
        $adapter = $this->_getReadAdapter();
        $bind = array(':increment_id' => $orderIncrementId); // no type casting to (int)
        $select = $adapter->select();
        $select->from($this->getTable('sales/order'), 'entity_id')
            ->where('increment_id = :increment_id');
        $entity_id = $adapter->fetchOne($select, $bind);
        if ($entity_id > 0) {
            return true;
        }

        return false;
    }
}