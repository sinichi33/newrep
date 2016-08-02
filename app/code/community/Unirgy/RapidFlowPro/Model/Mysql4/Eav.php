<?php 

class Unirgy_RapidFlowPro_Model_Mysql4_Eav extends Unirgy_RapidFlow_Model_Mysql4_Abstract_Fixed
{
    protected $_translateModule = "Unirgy_RapidFlowPro";
    protected $_dataType = "eav_struct";
    protected $_exportRowCallback = array( "EA" => "_exportCleanEntityType", "EAL" => "_exportCleanEntityType", "EAX" => "_exportCleanEntityType", "EAS" => "_exportCleanEntityType", "EASI" => "_exportCleanEntityType", "EAO" => "_exportCleanEntityType", "EAOL" => "_exportCleanEntityType" );

    protected function _construct()
    {
        Unirgy_RapidFlow_Model_Mysql4_Catalog_Product_Abstract::validatelicense("Unirgy_RapidFlowPro");
        parent::_construct();
    }

    protected function _importRowEA($row)
    {
        if( sizeof($row) < 7 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $systemAttributes = $this->_profile->getData("options/import/system_attributes");
        $aTable = $this->_t("eav/attribute");
        $etId = $this->_getEntityType(!empty($row[7]) ? $row[7] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            Mage::throwexception("Invalid entity type");
        }

        $new = array( "attribute_code" => $row[1], "backend_type" => $row[2], "frontend_input" => $row[3], "frontend_label" => $this->_convertEncoding($row[4]), "is_required" => $row[5], "is_unique" => $row[6] );
        $exists = $this->_write->fetchRow("" . "select attribute_id,backend_type,frontend_input,frontend_label,is_required,is_unique,is_user_defined from " . $aTable . " where entity_type_id=" . $etId . " and attribute_code=?", $new["attribute_code"]);
        if( !$exists ) 
        {
            $new["entity_type_id"] = $etId;
            $new["is_user_defined"] = true;
            $this->_write->insert($aTable, $new);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( ($systemAttributes || $exists["is_user_defined"]) && $this->_isChangeRequired($exists, $new) ) 
        {
            $this->_write->update($aTable, $new, "attribute_id=" . $exists["attribute_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEAL($row)
    {
        if( !Mage::helper("urapidflow")->hasMageFeature("table.eav_attribute_label") ) 
        {
            Mage::throwexception($this->__("Invalid Magento version (older than 1.4)"));
        }

        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $alTable = $this->_t("eav/attribute_label");
        $etId = $this->_getEntityType(!empty($row[4]) ? $row[4] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(5);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $aId = $this->_getAttributeId($row[1], $etId);
        if( !$aId ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception($this->__("Invalid attribute"));
        }

        $sId = $this->_getStoreId($row[2]);
        if( $this->_skipStore($sId, 3) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $label = $this->_convertEncoding($row[3]);
        $exists = $this->_write->fetchRow("" . "select attribute_label_id, value from " . $alTable . " where attribute_id=" . $aId . " and store_id=" . $sId);
        if( !$exists ) 
        {
            $this->_write->insert($alTable, array( "attribute_id" => $aId, "store_id" => $sId, "value" => $label ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["value"] != $row[3] ) 
        {
            $this->_write->update($alTable, array( "value" => $label ), "attribute_label_id=" . $exists["attribute_label_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEAX($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $systemAttributes = $this->_profile->getData("options/import/system_attributes");
        $aTable = $this->_t("eav/attribute");
        $etId = $this->_getEntityType(!empty($row[10]) ? $row[10] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(11);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $new = array( "attribute_code" => $row[1], "attribute_model" => !empty($row[2]) ? $row[2] : null, "backend_model" => !empty($row[3]) ? $row[3] : null, "backend_table" => !empty($row[4]) ? $row[4] : null, "frontend_model" => !empty($row[5]) ? $row[5] : null, "frontend_class" => !empty($row[6]) ? $row[6] : null, "source_model" => !empty($row[7]) ? $row[7] : null, "default_value" => !empty($row[8]) ? $this->_convertEncoding($row[8]) : null, "note" => !empty($row[9]) && $row[2] !== "" ? $row[9] : null );
        $exists = $this->_write->fetchRow("" . "select attribute_id,attribute_model,backend_model,backend_table,frontend_model,frontend_class,source_model,default_value,note,is_user_defined from " . $aTable . " where entity_type_id=" . $etId . " and attribute_code=?", $new["attribute_code"]);
        if( !$exists ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception("Invalid attribute");
        }
        else
        {
            if( ($systemAttributes || $exists["is_user_defined"]) && $this->_isChangeRequired($exists, $new) ) 
            {
                $this->_write->update($aTable, $new, "attribute_id=" . $exists["attribute_id"]);
                return self::IMPORT_ROW_RESULT_SUCCESS;
            }

        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEAXP($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $systemAttributes = $this->_profile->getData("options/import/system_attributes");
        $aTable = $this->_t("eav/attribute");
        if( Mage::helper("urapidflow")->hasMageFeature("table.catalog_eav_attribute") ) 
        {
            $caTable = $this->_t("catalog/eav_attribute");
        }

        $new = array( "is_global" => isset($row[2]) && $row[2] !== "" ? (int) $row[2] : false, "is_visible" => isset($row[3]) && $row[3] !== "" ? (int) $row[3] : false, "is_searchable" => isset($row[4]) && $row[4] !== "" ? (int) $row[4] : false, "is_filterable" => isset($row[5]) && $row[5] !== "" ? (int) $row[5] : false, "is_comparable" => isset($row[6]) && $row[6] !== "" ? (int) $row[6] : false, "is_visible_on_front" => isset($row[7]) && $row[7] !== "" ? (int) $row[7] : false, "is_html_allowed_on_front" => isset($row[8]) && $row[8] !== "" ? (int) $row[8] : false, "is_used_for_price_rules" => isset($row[9]) && $row[9] !== "" ? (int) $row[9] : false, "is_filterable_in_search" => isset($row[10]) && $row[10] !== "" ? (int) $row[10] : false, "used_in_product_listing" => isset($row[11]) && $row[11] !== "" ? (int) $row[11] : false, "used_for_sort_by" => isset($row[12]) && $row[12] !== "" ? (int) $row[12] : false, "is_configurable" => isset($row[13]) && $row[13] !== "" ? (int) $row[13] : false, "apply_to" => isset($row[14]) ? $row[14] : false, "is_visible_in_advanced_search" => isset($row[15]) && $row[15] !== "" ? (int) $row[15] : false, "position" => isset($row[16]) && $row[16] !== "" ? (int) $row[16] : false, "frontend_input_renderer" => isset($row[17]) ? $row[17] : false );
        if( Mage::helper("urapidflow")->hasMageFeature("attr.is_wysiwyg_enabled") ) 
        {
            $new["is_wysiwyg_enabled"] = isset($row[18]) && $row[18] !== "" ? (int) $row[18] : false;
        }

        foreach( $new as $k => $v ) 
        {
            if( $v === false ) 
            {
                unset($new[$k]);
            }

        }
        if( !$new ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        try
        {
            $aId = $this->_getAttributeId($row[1], "catalog_product");
        }
        catch( Exception $e ) 
        {
            $aId = $this->_getAttributeId($row[1], "catalog_category");
        }
        if( !$aId ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception($this->__("Invalid attribute"));
        }

        $catEavAttr = Mage::helper("urapidflow")->hasMageFeature("table.catalog_eav_attribute");
        if( $catEavAttr ) 
        {
            $exists = $this->_write->fetchRow("" . "select a.is_user_defined, ca.* from " . $aTable . " a inner join " . $caTable . " ca on ca.attribute_id=a.attribute_id where a.attribute_id=" . $aId);
        }
        else
        {
            $exists = $this->_write->fetchRow("" . "select * from " . $aTable . " where attribute_id=" . $aId);
        }

        if( !$exists ) 
        {
            $new["attribute_id"] = $aId;
            $this->_write->insert($caTable, $new);
        }
        else
        {
            if( ($systemAttributes || $exists["is_user_defined"]) && $this->_isChangeRequired($exists, $new) ) 
            {
                $this->_write->update($catEavAttr ? $caTable : $aTable, $new, "attribute_id=" . $aId);
                return self::IMPORT_ROW_RESULT_SUCCESS;
            }

        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEAS($row)
    {
        if( sizeof($row) < 2 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $asTable = $this->_t("eav/attribute_set");
        $etId = $this->_getEntityType(!empty($row[3]) ? $row[3] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $sortOrder = (int) (isset($row[2]) ? $row[2] : null);
        $exists = $this->_write->fetchRow("" . "select * from " . $asTable . " where entity_type_id=" . $etId . " and attribute_set_name=?", $row[1]);
        if( !$exists ) 
        {
            $this->_write->insert($asTable, array( "entity_type_id" => $etId, "attribute_set_name" => $row[1], "sort_order" => $sortOrder ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $sortOrder != $exists["sort_order"] ) 
        {
            $this->_write->update($asTable, array( "sort_order" => $sortOrder ), "attribute_set_id=" . $exists["attribute_set_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEAG($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $asTable = $this->_t("eav/attribute_set");
        $agTable = $this->_t("eav/attribute_group");
        $setName = $this->_convertEncoding($row[1]);
        $groupName = $this->_convertEncoding($row[2]);
        $etId = $this->_getEntityType(!empty($row[4]) ? $row[4] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(5);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $sortOrder = (int) (isset($row[3]) ? $row[3] : null);
        $asId = $this->_write->fetchOne("" . "select attribute_set_id from " . $asTable . " where entity_type_id=" . $etId . " and attribute_set_name=?", $setName);
        if( !$asId ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception($this->__("Invalid attribute set"));
        }

        $exists = $this->_write->fetchRow("" . "select * from " . $agTable . " where attribute_set_id=" . $asId . " and attribute_group_name=?", $groupName);
        if( !$exists ) 
        {
            $this->_write->insert($agTable, array( "attribute_set_id" => $asId, "attribute_group_name" => $groupName, "sort_order" => $sortOrder ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $sortOrder != $exists["sort_order"] ) 
        {
            $this->_write->update($agTable, array( "sort_order" => $sortOrder ), "attribute_group_id=" . $exists["attribute_group_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEASI($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $asTable = $this->_t("eav/attribute_set");
        $agTable = $this->_t("eav/attribute_group");
        $eaTable = $this->_t("eav/entity_attribute");
        $etId = $this->_getEntityType(!empty($row[5]) ? $row[5] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(6);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $setName = $this->_convertEncoding($row[1]);
        $asId = $this->_write->fetchOne("" . "select attribute_set_id from " . $asTable . " where entity_type_id=" . $etId . " and attribute_set_name=?", $setName);
        if( !$asId ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception($this->__("Invalid attribute set"));
        }

        $groupName = $this->_convertEncoding($row[2]);
        $agId = $this->_write->fetchOne("" . "select attribute_group_id from " . $agTable . " where attribute_set_id=" . $asId . " and attribute_group_name=?", $groupName);
        if( !$agId ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid attribute group"));
        }

        $aId = $this->_getAttributeId($row[3], $etId);
        if( !$aId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid attribute"));
        }

        if( isset($row[4]) ) 
        {
            $sortOrder = (int) $row[4];
        }
        else
        {
            $sortOrder = 1 + $this->_write->fetchOne("" . "select max(sort_order) from " . $eaTable . " where attribute_set_id=" . $asId . " and attribute_group_id=" . $agId);
        }

        $exists = $this->_write->fetchRow("" . "select ea.* from " . $eaTable . " ea inner join " . $asTable . " `as` on as.attribute_set_id=ea.attribute_set_id where ea.attribute_set_id=" . $asId . " and ea.attribute_id=" . $aId);
        if( !$exists ) 
        {
            $this->_write->insert($eaTable, array( "entity_type_id" => $etId, "attribute_set_id" => $asId, "attribute_group_id" => $agId, "attribute_id" => $aId, "sort_order" => $sortOrder ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["attribute_group_id"] != $agId || $exists["sort_order"] != $sortOrder ) 
        {
            $this->_write->update($eaTable, array( "attribute_group_id" => $agId, "sort_order" => $sortOrder ), "entity_attribute_id=" . $exists["entity_attribute_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowEAO($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $oTable = $this->_t("eav/attribute_option");
        $olTable = $this->_t("eav/attribute_option_value");
        $etId = $this->_getEntityType(!empty($row[4]) ? $row[4] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(5);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $aId = $this->_getAttributeId($row[1], $etId);
        if( !$aId ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception($this->__("Invalid attribute"));
        }

        $title = $this->_convertEncoding(trim($row[2]));
        $sortOrder = (int) (isset($row[3]) ? $row[3] : null);
        $duplicates = $this->_profile->getData("options/duplicate_option_values");
        $result = self::IMPORT_ROW_RESULT_NOCHANGE;
        $exists = $this->_write->fetchRow("" . "select o.option_id, ol.value_id, o.sort_order from " . $oTable . " o\n            left join " . $olTable . " ol on ol.option_id=o.option_id and ol.store_id=0 where o.attribute_id=" . $aId . " and ol.value=?", $title);
        if( $duplicates || !$exists ) 
        {
            $this->_write->insert($oTable, array( "attribute_id" => $aId, "sort_order" => $sortOrder ));
            $exists["option_id"] = $this->_write->lastInsertId();
            $result = self::IMPORT_ROW_RESULT_SUCCESS;
        }
        else
        {
            if( $exists["sort_order"] != $sortOrder ) 
            {
                $this->_write->update($oTable, array( "sort_order" => $sortOrder ), "option_id=" . $exists["option_id"]);
                $result = self::IMPORT_ROW_RESULT_SUCCESS;
            }

        }

        if( $duplicates || empty($exists["value_id"]) ) 
        {
            $this->_write->insert($olTable, array( "option_id" => $exists["option_id"], "store_id" => 0, "value" => $title ));
            $result = self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return $result;
    }

    protected function _importRowEAOL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $oTable = $this->_t("eav/attribute_option");
        $olTable = $this->_t("eav/attribute_option_value");
        $etId = $this->_getEntityType(!empty($row[5]) ? $row[5] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(6);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $aId = $this->_getAttributeId($row[1], $etId);
        if( !$aId ) 
        {
            $this->_profile->getLogger()->setColumn(2);
            Mage::throwexception($this->__("Invalid attribute"));
        }

        $defTitle = $this->_convertEncoding(trim($row[2]));
        $sId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($sId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $title = $this->_convertEncoding(trim($row[4]));
        $exists = $this->_write->fetchRow("" . "select o.option_id, ol.value_id, ol.value from " . $oTable . " o\n            inner join " . $olTable . " od on od.option_id=o.option_id and od.store_id=0\n            left join " . $olTable . " ol on ol.option_id=o.option_id and ol.store_id=" . $sId . "\n            where o.attribute_id=" . $aId . " and od.value=" . $this->_write->quote($defTitle));
        if( !$exists ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception("Invalid attribute option");
        }
        else
        {
            if( !$exists["value_id"] ) 
            {
                $this->_write->insert($olTable, array( "option_id" => $exists["option_id"], "store_id" => $sId, "value" => $title ));
                return self::IMPORT_ROW_RESULT_SUCCESS;
            }

            if( $exists["value_id"] && $exists["value"] !== $title ) 
            {
                $this->_write->update($olTable, array( "value" => $title ), array( "option_id=?" => $exists["option_id"], "store_id=?" => $sId ));
                return self::IMPORT_ROW_RESULT_SUCCESS;
            }

        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _renameRowEA($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowEAS($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowEAG($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowEAO($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _deleteRowEA($row)
    {
        if( sizeof($row) < 2 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $aTable = $this->_t("eav/attribute");
        $label = $this->_convertEncoding($row[1]);
        $etId = $this->_getEntityType(!empty($row[2]) ? $row[2] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $existsId = $this->_write->fetchOne("" . "select attribute_id from " . $aTable . " where entity_type_id=" . $etId . " and attribute_code=?", $label);
        if( $existsId ) 
        {
            $this->_write->delete($aTable, "" . "attribute_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowEAL($row)
    {
        if( !Mage::helper("urapidflow")->hasMageFeature("table.eav_attribute_label") ) 
        {
            Mage::throwexception($this->__("Invalid Magento version (older than 1.4)"));
        }

        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $aTable = $this->_t("eav/attribute");
        $alTable = $this->_t("eav/attribute_label");
        $etId = $this->_getEntityType(!empty($row[3]) ? $row[3] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $sId = $this->_getStoreId($row[2]);
        if( $this->_skipStore($sId, 3) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $existsId = $this->_write->fetchOne("" . "select al.attribute_label_id from " . $aTable . " a\n            inner join " . $alTable . " al on a.attribute_id=al.attribute_id\n            where a.entity_type_id=" . $etId . " and al.store_id=" . $sId . " and a.attribute_code=?", $row[1]);
        if( $existsId ) 
        {
            $this->_write->delete($alTable, "" . "attribute_label_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowEAS($row)
    {
        if( sizeof($row) < 2 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $asTable = $this->_t("eav/attribute_set");
        $setName = $this->_convertEncoding($row[1]);
        $etId = $this->_getEntityType(!empty($row[2]) ? $row[2] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $existsId = $this->_write->fetchOne("" . "select attribute_set_id from " . $asTable . " where entity_type_id=" . $etId . " and attribute_set_name=?", $setName);
        if( $existsId ) 
        {
            $this->_write->delete($asTable, "" . "attribute_set_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowEAG($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $asTable = $this->_t("eav/attribute_set");
        $agTable = $this->_t("eav/attribute_group");
        $setName = $this->_convertEncoding($row[1]);
        $groupName = $this->_convertEncoding($row[2]);
        $etId = $this->_getEntityType(!empty($row[3]) ? $row[3] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $existsId = $this->_write->fetchOne("" . "select ag.attribute_group_id from " . $agTable . " ag\n            inner join " . $asTable . " `as` on as.attribute_set_id=ag.attribute_set_id\n            where as.entity_type_id=" . $etId . " and as.attribute_set_name=" . $this->_write->quote($setName) . " and ag.attribute_group_name=" . $this->_write->quote($groupName));
        if( $existsId ) 
        {
            $this->_write->delete($agTable, "" . "attribute_group_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowEASI($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $asTable = $this->_t("eav/attribute_set");
        $eaTable = $this->_t("eav/entity_attribute");
        $aTable = $this->_t("eav/attribute");
        $setName = $this->_convertEncoding($row[1]);
        $groupName = $this->_convertEncoding($row[2]);
        $etId = $this->_getEntityType(!empty($row[3]) ? $row[3] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $existsId = $this->_write->fetchOne("" . "select ea.entity_attribute_id from " . $eaTable . " ea\n            inner join " . $asTable . " `as` on as.attribute_set_id=ea.attribute_set_id\n            inner join " . $aTable . " a on a.attribute_id=ea.attribute_id\n            where as.entity_type_id=" . $etId . " and as.attribute_set_name=" . $this->_write->quote($setName) . " and a.attribute_code=" . $this->_write->quote($groupName));
        if( $existsId ) 
        {
            $this->_write->delete($eaTable, "" . "entity_attribute_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowEAO($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $aTable = $this->_t("eav/attribute");
        $oTable = $this->_t("eav/attribute_option");
        $olTable = $this->_t("eav/attribute_option_value");
        $label = $this->_convertEncoding($row[2]);
        $etId = $this->_getEntityType(!empty($row[3]) ? $row[3] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $existsId = $this->_write->fetchOne("" . "select o.option_id from " . $oTable . " o\n            inner join " . $aTable . " a on a.attribute_id=o.attribute_id\n            inner join " . $olTable . " ol on ol.option_id=o.option_id and ol.store_id=0\n            where a.entity_type_id=" . $etId . " and a.attribute_code=" . $this->_write->quote($row[1]) . " and ol.value=" . $this->_write->quote($label));
        if( $existsId ) 
        {
            $this->_write->delete($oTable, "" . "option_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowEAOL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $aTable = $this->_t("eav/attribute");
        $oTable = $this->_t("eav/attribute_option");
        $olTable = $this->_t("eav/attribute_option_value");
        $label = $this->_convertEncoding($row[2]);
        $etId = $this->_getEntityType(!empty($row[4]) ? $row[4] : "catalog_product", "entity_type_id");
        if( !$etId ) 
        {
            $this->_profile->getLogger()->setColumn(5);
            Mage::throwexception($this->__("Invalid entity type"));
        }

        $sId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($sId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $existsId = $this->_write->fetchOne("" . "select ol.value_id from " . $oTable . " o\n            inner join " . $aTable . " a on a.attribute_id=o.attribute_id\n            inner join " . $olTable . " od on od.option_id=o.option_id and od.store_id=0\n            inner join " . $olTable . " ol on ol.option_id=o.option_id and ol.store_id=" . $sId . "\n            where a.entity_type_id=" . $etId . " and a.attribute_code=" . $this->_write->quote($row[1]) . " and od.value=" . $this->_write->quote($label));
        if( $existsId ) 
        {
            $this->_write->delete($olTable, "" . "value_id=" . $existsId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _exportCleanEntityType(&$row)
    {
        if( !empty($row["entity_type"]) && $row["entity_type"] == "catalog_product" ) 
        {
            $row["entity_type"] = "";
        }

        return true;
    }

    protected function _exportFilterEntityType($tableAlias = "main")
    {
        $etIds = $this->_profile->getData("options/entity_types");
        if( $etIds ) 
        {
            $this->_select->where($tableAlias . ".entity_type_id in (?)", $etIds);
        }

    }

    protected function _exportInitEA()
    {
        $attrTable = $this->_t("eav/attribute");
        $etTable = $this->_t("eav/entity_type");
        $this->_select = $this->_read->select()->from(array( "main" => $attrTable ), array( "attribute_code", "backend_type", "frontend_label", "frontend_input", "is_required", "is_unique" ))->join(array( "et" => $etTable ), "et.entity_type_id=main.entity_type_id", array( "entity_type" => "entity_type_code" ));
        if( !$this->_profile->getData("options/export/system_attributes") ) 
        {
            $this->_select->where("main.is_user_defined=1");
        }

        $this->_exportFilterEntityType();
        $this->_exportConvertFields = array( "frontend_label" );
    }

    protected function _exportInitEAL()
    {
        if( !Mage::helper("urapidflow")->hasMageFeature("table.eav_attribute_label") ) 
        {
            return NULL;
        }

        $attrTable = $this->_t("eav/attribute");
        $alTable = $this->_t("eav/attribute_label");
        $etTable = $this->_t("eav/entity_type");
        $sTable = $this->_t("core/store");
        $this->_select = $this->_read->select()->from(array( "main" => $attrTable ), array( "attribute_code" ))->join(array( "al" => $alTable ), "al.attribute_id=main.attribute_id", array( "label" => "value" ))->join(array( "et" => $etTable ), "et.entity_type_id=main.entity_type_id", array( "entity_type" => "entity_type_code" ))->join(array( "s" => $sTable ), "s.store_id=al.store_id", array( "store" => "code" ))->where("al.store_id in (?)", $this->_getStoreIds());
        if( !$this->_profile->getData("options/export/system_attributes") ) 
        {
            $this->_select->where("main.is_user_defined=1");
        }

        $this->_exportFilterEntityType();
        $this->_exportConvertFields = array( "label" );
    }

    protected function _exportInitEAX()
    {
        $attrTable = $this->_t("eav/attribute");
        $etTable = $this->_t("eav/entity_type");
        $this->_select = $this->_read->select()->from(array( "main" => $attrTable ), array( "attribute_code", "attribute_model", "backend_model", "backend_table", "frontend_model", "frontend_class", "source_model", "default_value", "note" ))->join(array( "et" => $etTable ), "et.entity_type_id=main.entity_type_id", array( "entity_type" => "entity_type_code" ));
        if( !$this->_profile->getData("options/export/system_attributes") ) 
        {
            $this->_select->where("main.is_user_defined=1");
        }

        $this->_exportFilterEntityType();
        $this->_exportConvertFields = array( "default_value", "note" );
    }

    protected function _exportInitEAXP()
    {
        $attrTable = $this->_t("eav/attribute");
        $etTable = $this->_t("eav/entity_type");
        $this->_select = $this->_read->select();
        if( !Mage::helper("urapidflow")->hasMageFeature("table.catalog_eav_attribute") ) 
        {
            $this->_select->from(array( "main" => $attrTable ), array( "*" ));
        }
        else
        {
            $catAttrTable = $this->_t("catalog/eav_attribute");
            $caTable = $this->_t("catalog/eav_attribute");
            $this->_select->from(array( "main" => $attrTable ), array( "attribute_code" ))->join(array( "ca" => $catAttrTable ), "ca.attribute_id=main.attribute_id", array( "*" ));
        }

        $etId = $this->_getEntityType("catalog_product", "entity_type_id");
        $this->_select->where("main.entity_type_id=?", $etId);
        if( !$this->_profile->getData("options/export/system_attributes") ) 
        {
            $this->_select->where("main.is_user_defined=1");
        }

    }

    protected function _exportInitEAS()
    {
        $asTable = $this->_t("eav/attribute_set");
        $etTable = $this->_t("eav/entity_type");
        $this->_select = $this->_read->select()->from(array( "main" => $asTable ), array( "set_name" => "attribute_set_name", "sort_order" ))->join(array( "et" => $etTable ), "et.entity_type_id=main.entity_type_id", array( "entity_type" => "entity_type_code" ));
        $this->_exportFilterEntityType();
        $this->_exportConvertFields = array( "set_name" );
    }

    protected function _exportInitEAG()
    {
        $agTable = $this->_t("eav/attribute_group");
        $asTable = $this->_t("eav/attribute_set");
        $etTable = $this->_t("eav/entity_type");
        $this->_select = $this->_read->select()->from(array( "main" => $agTable ), array( "group_name" => "attribute_group_name", "sort_order" ))->join(array( "as" => $asTable ), "as.attribute_set_id=main.attribute_set_id", array( "set_name" => "attribute_set_name" ))->join(array( "et" => $etTable ), "et.entity_type_id=as.entity_type_id", array( "entity_type" => "entity_type_code" ));
        $this->_exportFilterEntityType("as");
        $this->_exportConvertFields = array( "group_name" );
    }

    protected function _exportInitEASI()
    {
        $attrTable = $this->_t("eav/attribute");
        $eaTable = $this->_t("eav/entity_attribute");
        $asTable = $this->_t("eav/attribute_set");
        $agTable = $this->_t("eav/attribute_group");
        $etTable = $this->_t("eav/entity_type");
        $this->_select = $this->_read->select()->from(array( "main" => $eaTable ), array( "sort_order" ))->join(array( "as" => $asTable ), "as.attribute_set_id=main.attribute_set_id", array( "set_name" => "attribute_set_name" ))->join(array( "ag" => $agTable ), "ag.attribute_group_id=main.attribute_group_id", array( "group_name" => "attribute_group_name" ))->join(array( "a" => $attrTable ), "a.attribute_id=main.attribute_id", array( "attribute_code" ))->join(array( "et" => $etTable ), "et.entity_type_id=main.entity_type_id", array( "entity_type" => "entity_type_code" ))->order(array( "as.sort_order", "as.attribute_set_name", "ag.sort_order", "ag.attribute_group_name", "main.sort_order", "a.attribute_code" ));
        $this->_exportFilterEntityType();
        $this->_exportConvertFields = array( "set_name", "group_name" );
    }

    protected function _exportInitEAO()
    {
        $attrTable = $this->_t("eav/attribute");
        $etTable = $this->_t("eav/entity_type");
        $oTable = $this->_t("eav/attribute_option");
        $olTable = $this->_t("eav/attribute_option_value");
        $this->_select = $this->_read->select()->from(array( "main" => $oTable ), array( "sort_order" ))->join(array( "a" => $attrTable ), "a.attribute_id=main.attribute_id", array( "attribute_code" ))->join(array( "ol" => $olTable ), "ol.option_id=main.option_id and ol.store_id=0", array( "option_name" => "value" ))->join(array( "et" => $etTable ), "et.entity_type_id=a.entity_type_id", array( "entity_type" => "entity_type_code" ));
        $this->_exportFilterEntityType("a");
        $this->_exportConvertFields = array( "option_name" );
    }

    protected function _exportInitEAOL()
    {
        $attrTable = $this->_t("eav/attribute");
        $etTable = $this->_t("eav/entity_type");
        $oTable = $this->_t("eav/attribute_option");
        $olTable = $this->_t("eav/attribute_option_value");
        $sTable = $this->_t("core/store");
        $this->_select = $this->_read->select()->from(array( "main" => $oTable ), array( "sort_order" ))->join(array( "a" => $attrTable ), "a.attribute_id=main.attribute_id", array( "attribute_code" ))->join(array( "od" => $olTable ), "od.option_id=main.option_id and od.store_id=0", array( "option_name" => "value" ))->join(array( "ol" => $olTable ), "ol.option_id=main.option_id and ol.store_id<>0", array( "option_label" => "value" ))->join(array( "s" => $sTable ), "s.store_id=ol.store_id", array( "store" => "code" ))->where("ol.store_id in (?)", $this->_getStoreIds());
        $this->_exportFilterEntityType("a");
        $this->_exportConvertFields = array( "option_name", "option_label" );
    }

}


