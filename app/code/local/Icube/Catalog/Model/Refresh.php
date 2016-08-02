<?php

class Icube_Catalog_Model_Refresh extends Enterprise_Catalog_Model_Index_Action_Category_Flat_Refresh {

    /**
     * Return attribute values for given entities and store
     *
     * @param array $entityIds
     * @param integer $storeId
     * @return array
     */
    protected function _getAttributeValues($entityIds, $storeId)
    {
        if (!is_array($entityIds)) {
            $entityIds = array($entityIds);
        }
        $values = array();

        foreach ($entityIds as $entityId) {
            $values[$entityId] = array();
        }
        $attributes = $this->_getAttributes();
        $attributesType = array(
            'varchar',
            'int',
            'decimal',
            'text',
            'datetime',
            'url_key'
        );
        foreach ($attributesType as $type) {
            foreach ($this->_getAttributeTypeValues($type, $entityIds, $storeId) as $row) {
                if (isset($row['entity_id']) && isset($row['attribute_id'])) {
                    $attributeId   = $row['attribute_id'];
                    if (isset($attributes[$attributeId])) {
                        $attributeCode = $attributes[$attributeId]['attribute_code'];
                        $values[$row['entity_id']][$attributeCode] = $row['value'];
                    }
                }
            }
        }
        return $values;
    }

}