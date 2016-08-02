<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-14T21:17:45+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Entity/Boostrma.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Entity_Boostrma extends Xtento_OrderExport_Model_Export_Entity_Abstract
{
    protected $_entityType = Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA;

    protected function _construct()
    {
        $this->_collection = Mage::getResourceModel('ProductReturn/rma_collection');
        parent::_construct();
    }

    public function setCollectionFilters($filters)
    {
        foreach ($filters as $filter) {
            foreach ($filter as $attribute => $filterArray) {
                if ($attribute == 'increment_id') {
                    $attribute = 'rma_id';
                }
                if ($attribute == 'entity_id') {
                    $attribute = 'rma_id';
                }
                $this->_collection->addFieldToFilter($attribute, $filterArray);
            }
        }
        return $this->_collection;
    }
}