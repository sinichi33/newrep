<?php 

class Unirgy_RapidFlowPro_Model_Mysql4_ProductExtra extends Unirgy_RapidFlow_Model_Mysql4_Catalog_Fixed
{
    protected $_translateModule = "Unirgy_RapidFlowPro";
    protected $_dataType = "product_extra";
    protected $_linkTypes = array(  );
    protected $_linkAttrs = array(  );
    protected $_linkAttrIds = array(  );
    protected $_bundleOptions = array(  );
    protected $_customOptions = array(  );
    protected $_customOptionSelections = array(  );
    protected $_imagesBaseDir = NULL;
    protected $_urlPaths = array(  );
    protected $_catIds = array(  );
    protected $_exportRowCallback = array( "CPI" => "_exportCallbackCPI", "CPSAP" => "_exportCallbackLoadAttributeOptions", "CPPT" => "_exportCallbackCPPT", "CCP" => "_exportCallbackCCP" );
    protected $_upPrependRoot = NULL;
    protected $_categoryUrlSuffix = NULL;
    protected $_categoryUrlSuffixLen = NULL;
    protected $_categoryUrlPathAttrId = NULL;
    protected $_categoryNameAttrId = NULL;
    protected $_rootCatPaths = array(  );
    protected $_catEntities = NULL;

    protected function _construct()
    {
        Unirgy_RapidFlow_Model_Mysql4_Catalog_Product_Abstract::validatelicense("Unirgy_RapidFlowPro");
        parent::_construct();
    }

    public function setProfile($profile)
    {
        parent::setprofile($profile);
        $this->_processImageFiles = $profile->getData("options/" . $profile->getProfileType() . "/image_files");
        $this->_imagesMediaDir = Mage::getbasedir("media") . DS . "catalog" . DS . "product";
        $this->_imagesTargetDir = $profile->getImagesBaseDir();
        $this->_remoteImageSubfolderLevel = $profile->getData("options/" . $profile->getProfileType() . "/image_remote_subfolder_level");
        return $this;
    }

    protected function _afterImport($cnt)
    {
    }

    protected function _importLink($row, $linkType)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $linkTable = $this->_t("catalog/product_link");
        $linkAttrTable = $this->_t("catalog/product_link_attribute");
        $linkTypeId = $this->_getLinkTypeId($linkType);
        $linkAttr = $this->_getLinkAttr($linkTypeId);
        $p1 = $this->_getIdBySku($row[1]);
        $p2 = $this->_getIdBySku($row[2]);
        $lt = $this->_getLinkTypeId($linkType);
        $new = array( "position" => isset($row[3]) ? $row[3] : null, "qty" => isset($row[4]) ? $row[4] : null );
        $exists = $this->_write->fetchRow("" . "select * from " . $linkTable . "\n            where product_id=" . $p1 . " and linked_product_id=" . $p2 . " and link_type_id=" . $lt);
        if( $exists ) 
        {
            $changed = false;
            $tables = array(  );
            foreach( $linkAttr as $code => $attr ) 
            {
                $paramTable = $linkAttrTable . "_" . $attr["data_type"];
                $r = $this->_write->fetchRow("" . "select * from " . $paramTable . "\n                    where link_id=" . $exists["link_id"] . " and product_link_attribute_id=" . $attr["id"]);
                $empty = $new[$code] === "" || $new[$code] === null || $new[$code] === false;
                if( !$r ) 
                {
                    if( !$empty ) 
                    {
                        $this->_write->insert($paramTable, array( "product_link_attribute_id" => $attr["id"], "link_id" => $exists["link_id"], "value" => $new[$code] ));
                        $changed = true;
                    }

                }
                else
                {
                    if( $empty ) 
                    {
                        $this->_write->delete($paramTable, "" . "value_id=" . $r["value_id"]);
                        $changed = true;
                    }
                    else
                    {
                        if( $new[$code] != $r["value"] ) 
                        {
                            $this->_write->update($paramTable, array( "value" => $new[$code] ), "" . "value_id=" . $r["value_id"]);
                            $changed = true;
                        }

                    }

                }

            }
            if( !$changed ) 
            {
                return self::IMPORT_ROW_RESULT_NOCHANGE;
            }

        }
        else
        {
            $this->_write->insert($linkTable, array( "product_id" => $p1, "linked_product_id" => $p2, "link_type_id" => $lt ));
            $linkId = $this->_write->lastInsertId($linkTable);
            if( $linkType == "super" && Mage::helper("urapidflow")->hasMageFeature("table.product_relation") ) 
            {
                $relTable = $this->_t("catalog/product_relation");
                if( !$this->_write->fetchOne("" . "select parent_id from " . $relTable . " where parent_id=" . $p1 . " and child_id=" . $p2) ) 
                {
                    $this->_write->insert($relTable, array( "parent_id" => $p1, "child_id" => $p2 ));
                }

            }

            foreach( $new as $code => $value ) 
            {
                if( empty($linkAttr[$code]) || $value === "" || $value === null || $value === false ) 
                {
                    continue;
                }

                $this->_write->insert($linkAttrTable . "_" . $linkAttr[$code]["data_type"], array( "product_link_attribute_id" => $linkAttr[$code]["id"], "link_id" => $linkId, "value" => $value ));
            }
        }

        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _importRowCPRI($row)
    {
        return $this->_importLink($row, "relation");
    }

    protected function _importRowCPUI($row)
    {
        return $this->_importLink($row, "up_sell");
    }

    protected function _importRowCPXI($row)
    {
        return $this->_importLink($row, "cross_sell");
    }

    protected function _importRowCPGI($row)
    {
        return $this->_importLink($row, "super");
    }

    protected function _getBundleOption($pId, $title)
    {
        if( empty($this->_bundleOptions[$pId][$title]) ) 
        {
            $row = $this->_write->fetchRow("" . "select bo.*, bov.value_id, bov.title from " . $this->_t("bundle/option") . " bo\n                inner join " . $this->_t("bundle/option_value") . " bov on bov.option_id=bo.option_id\n                where bo.parent_id=" . $pId . " and bov.store_id=0 and bov.title=?", $title);
            if( !$row ) 
            {
                return false;
            }

            if( $this->_maxCacheItems["bundle_option"] < sizeof($this->_bundleOptions) ) 
            {
                reset($this->_bundleOptions);
                unset($this->_bundleOptions[key($this->_bundleOptions)]);
            }

            $this->_bundleOptions[$pId][$title] = $row;
        }

        return $this->_bundleOptions[$pId][$title];
    }

    protected function _updateBundleOption($pId, $title, $data)
    {
        if( empty($this->_bundleOptions[$pId][$title]) ) 
        {
            $this->_bundleOptions[$pId][$title] = array( "parent_id" => $pId, "title" => $title );
        }

        $this->_bundleOptions[$pId][$title] = array_merge($this->_bundleOptions[$pId][$title], $data);
        return $this->_bundleOptions[$pId][$title];
    }

    protected function _importRowCCP($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/category_product");
        $category = $this->_fetchCategoryRow($row[1]);
        $pId = $this->_getIdBySku($row[2]);
        $position = !empty($row[3]) ? (int) $row[3] : 0;
        if( !$category ) 
        {
            Mage::throwexception($this->__("Invalid Category '%s'", $row[1]));
        }

        if( !$pId ) 
        {
            Mage::throwexception($this->__("Invalid SKU '%s'", $row[2]));
        }

        $exists = $this->_write->fetchRow("" . "select position from " . $t . "\n                where category_id=" . $category["entity_id"] . " and product_id=" . $pId);
        if( $exists ) 
        {
            if( $exists["position"] != $position ) 
            {
                $this->_write->update($t, array( "position" => $position ), "" . "category_id=" . $category["entity_id"] . " and product_id=" . $pId);
            }

            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        $this->_write->insert($t, array( "category_id" => $category["entity_id"], "product_id" => $pId, "position" => $position ));
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _importRowCPBO($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $boTable = $this->_t("bundle/option");
        $bovTable = $this->_t("bundle/option_value");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $pos = isset($row[3]) ? (int) $row[3] : 0;
        $type = isset($row[4]) ? $row[4] : "select";
        $required = isset($row[5]) ? (int) $row[5] : 0;
        $exists = $this->_getBundleOption($pId, $title);
        if( $exists ) 
        {
            if( $exists["position"] == $pos && $exists["type"] == $type && $exists["required"] == $required ) 
            {
                return self::IMPORT_ROW_RESULT_NOCHANGE;
            }

            $new = array( "position" => $pos, "type" => $type, "required" => $required );
            $this->_write->update($boTable, $new, "" . "option_id=" . $exists["option_id"]);
        }
        else
        {
            $new = array( "parent_id" => $pId, "required" => $required, "position" => $pos, "type" => $type );
            $this->_write->insert($boTable, $new);
            $new["option_id"] = $this->_write->lastInsertId($boTable);
            $new["title"] = $title;
            $this->_write->insert($bovTable, array( "option_id" => $new["option_id"], "store_id" => 0, "title" => $title ));
        }

        $this->_updateBundleOption($pId, $title, $new);
        $this->_newRefreshHoRoPids[$pId] = 1;
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _importRowCPBOL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $optTitle = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $title = $row[4];
        $option = $this->_getBundleOption($pId, $optTitle);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $bovTable = $this->_t("bundle/option_value");
        $exists = $this->_write->fetchRow("" . "select * from " . $bovTable . "\n            where option_id=" . $option["option_id"] . " and store_id=" . $storeId);
        if( $exists ) 
        {
            if( $exists["title"] == $title ) 
            {
                return self::IMPORT_ROW_RESULT_NOCHANGE;
            }

            $this->_write->update($bovTable, array( "title" => $title ), "" . "value_id=" . $exists["value_id"]);
        }
        else
        {
            $this->_write->insert($bovTable, array( "option_id" => $option["option_id"], "store_id" => $storeId, "title" => $title ));
        }

        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _importRowCPBOS($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $option = $this->_getBundleOption($pId, $title);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $sId = $this->_getIdBySku($row[3]);
        $new = array( "position" => isset($row[4]) ? (int) $row[4] : 0, "is_default" => isset($row[5]) ? (int) $row[5] : 0, "selection_price_type" => isset($row[6]) ? (int) $row[6] : 0, "selection_price_value" => isset($row[7]) ? (double) $row[7] : 0, "selection_qty" => isset($row[8]) ? (double) $row[8] : 0, "selection_can_change_qty" => isset($row[9]) ? (int) $row[9] : 0 );
        $bosTable = $this->_t("bundle/selection");
        $exists = $this->_write->fetchRow("" . "select * from " . $bosTable . "\n            where option_id='" . $option["option_id"] . "' and product_id='" . $sId . "'");
        if( $exists ) 
        {
            if( !$this->_isChangeRequired($exists, $new) ) 
            {
                return self::IMPORT_ROW_RESULT_NOCHANGE;
            }

            $this->_write->update($bosTable, $new, "" . "selection_id=" . $exists["selection_id"]);
        }
        else
        {
            $new["option_id"] = $option["option_id"];
            $new["parent_product_id"] = $pId;
            $new["product_id"] = $sId;
            $this->_write->insert($bosTable, $new);
        }

        $relTable = $this->_t("catalog/product_relation");
        $exists = $this->_write->fetchRow("" . "select * from " . $relTable . " where parent_id='" . $pId . "' and child_id='" . $sId . "'");
        if( !$exists ) 
        {
            $this->_write->insert($relTable, array( "parent_id" => $pId, "child_id" => $sId ));
        }

        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _getCustomOption($pId, $title)
    {
        if( empty($this->_customOptions[$pId][$title]) ) 
        {
            $row = $this->_write->fetchRow("" . "select co.*, cot.option_title_id, cot.title from " . $this->_t("catalog/product_option") . " co\n                inner join " . $this->_t("catalog/product_option_title") . " cot on cot.option_id=co.option_id\n                where co.product_id=" . $pId . " and cot.store_id=0 and cot.title=?", $title);
            if( !$row ) 
            {
                return false;
            }

            if( $this->_maxCacheItems["custom_option"] < sizeof($this->_customOptions) ) 
            {
                reset($this->_customOptions);
                unset($this->_customOptions[key($this->_customOptions)]);
            }

            $this->_customOptions[$pId][$title] = $row;
        }

        return $this->_customOptions[$pId][$title];
    }

    protected function _updateCustomOption($pId, $title, $data)
    {
        if( empty($this->_customOptions[$pId][$title]) ) 
        {
            $this->_customOptions[$pId][$title] = array( "product_id" => $pId, "title" => $title );
        }

        $this->_customOptions[$pId][$title] = array_merge($this->_customOptions[$pId][$title], $data);
        return $this->_customOptions[$pId][$title];
    }

    protected function _getCustomOptionSelection($oId, $title)
    {
        if( empty($this->_customOptionSelections[$oId][$title]) ) 
        {
            $cosTable = $this->_t("catalog/product_option_type_value");
            $costTable = $this->_t("catalog/product_option_type_title");
            $row = $this->_write->fetchRow("" . "select cos.*, cost.option_type_title_id, cost.title from " . $cosTable . " cos\n                    inner join " . $costTable . " cost on cost.option_type_id=cos.option_type_id\n                    where cos.option_id=" . $oId . " and cost.store_id=0 and cost.title=?", $title);
            if( !$row ) 
            {
                return false;
            }

            if( $this->_maxCacheItems["custom_option_selection"] < sizeof($this->_customOptionSelections) ) 
            {
                reset($this->_customOptionSelections);
                unset($this->_customOptionSelections[key($this->_customOptionSelections)]);
            }

            $this->_customOptionSelections[$oId][$title] = $row;
        }

        return $this->_customOptionSelections[$oId][$title];
    }

    protected function _updateCustomOptionSelection($oId, $title, $data)
    {
        if( empty($this->_customOptionSelections[$oId][$title]) ) 
        {
            $this->_customOptionSelections[$oId][$title] = array( "option_id" => $oId, "title" => $title );
        }

        $this->_customOptionSelections[$oId][$title] = array_merge($this->_customOptionSelections[$oId][$title], $data);
        return $this->_customOptionSelections[$oId][$title];
    }

    protected function _importRowCPCO($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $coTable = $this->_t("catalog/product_option");
        $cotTable = $this->_t("catalog/product_option_title");
        $copTable = $this->_t("catalog/product_option_price");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $price = isset($row[8]) && $row[8] !== "" ? (double) $row[8] : null;
        $priceType = isset($row[9]) && $row[9] !== "" ? $row[9] : "fixed";
        $new = array( "type" => $row[3], "is_require" => isset($row[4]) ? (int) $row[4] : 0, "sku" => isset($row[5]) ? $row[5] : "", "sort_order" => isset($row[6]) ? (int) $row[6] : 0, "max_characters" => isset($row[7]) ? (int) $row[7] : 0, "file_extension" => isset($row[10]) ? $row[10] : "", "image_size_x" => isset($row[11]) ? (int) $row[11] : 0, "image_size_y" => isset($row[12]) ? (int) $row[12] : 0 );
        $changed = false;
        $option = $this->_getCustomOption($pId, $title);
        if( $option ) 
        {
            if( $this->_isChangeRequired($option, $new) ) 
            {
                $this->_write->update($coTable, $new, "" . "option_id=" . $option["option_id"]);
                $changed = true;
                $option = $this->_updateCustomOption($pId, $title, $new);
            }

        }
        else
        {
            $new["product_id"] = $pId;
            $this->_write->insert($coTable, $new);
            $new["option_id"] = $this->_write->lastInsertId($coTable);
            $new["title"] = $title;
            $option = $this->_updateCustomOption($pId, $title, $new);
            $this->_write->insert($cotTable, array( "option_id" => $option["option_id"], "store_id" => 0, "title" => $title ));
            $changed = true;
        }

        $exists = $this->_write->fetchRow("" . "select * from " . $copTable . "\n            where option_id=" . $option["option_id"] . " and store_id=0");
        if( $exists ) 
        {
            if( is_null($price) ) 
            {
                $this->_write->delete($copTable, "" . "option_price_id=" . $exists["option_price_id"]);
                $changed = true;
            }
            else
            {
                if( $exists["price"] != $price || $exists["price_type"] != $priceType ) 
                {
                    $this->_write->update($copTable, array( "price" => $price, "price_type" => $priceType ), "" . "option_price_id=" . $exists["option_price_id"]);
                    $changed = true;
                }

            }

        }
        else
        {
            if( !is_null($price) ) 
            {
                $this->_write->insert($copTable, array( "option_id" => $option["option_id"], "store_id" => 0, "price" => $price, "price_type" => $priceType ));
                $changed = true;
            }

        }

        if( $changed ) 
        {
            $this->_newRefreshHoRoPids[$pId] = 1;
        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPCOL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $optTitle = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $title = $this->_convertEncoding($row[4]);
        $price = isset($row[5]) && $row[5] !== "" ? (double) $row[5] : null;
        $priceType = isset($row[6]) && $row[6] !== "" ? $row[6] : "fixed";
        $option = $this->_getCustomOption($pId, $optTitle);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $changed = false;
        $cotTable = $this->_t("catalog/product_option_title");
        $exists = $this->_write->fetchRow("" . "select * from " . $cotTable . "\n            where option_id=" . $option["option_id"] . " and store_id=" . $storeId);
        if( $exists ) 
        {
            if( $exists["title"] != $title ) 
            {
                $this->_write->update($cotTable, array( "title" => $title ), "" . "option_title_id=" . $exists["option_title_id"]);
                $changed = true;
            }

        }
        else
        {
            $this->_write->insert($cotTable, array( "option_id" => $option["option_id"], "store_id" => $storeId, "title" => $title ));
            $changed = true;
        }

        $copTable = $this->_t("catalog/product_option_price");
        $exists = $this->_write->fetchRow("" . "select * from " . $copTable . "\n            where option_id=" . $option["option_id"] . " and store_id=" . $storeId);
        if( $exists ) 
        {
            if( is_null($price) ) 
            {
                $this->_write->delete($copTable, "" . "option_price_id=" . $exists["option_price_id"]);
                $changed = true;
            }
            else
            {
                if( $exists["price"] != $price || $exists["price_type"] != $priceType ) 
                {
                    $this->_write->update($copTable, array( "price" => $price, "price_type" => $priceType ), "" . "option_price_id=" . $exists["option_price_id"]);
                    $changed = true;
                }

            }

        }
        else
        {
            if( !is_null($price) ) 
            {
                $this->_write->insert($copTable, array( "option_id" => $option["option_id"], "store_id" => $storeId, "price" => $price, "price_type" => $priceType ));
                $changed = true;
            }

        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPCOS($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $cosTable = $this->_t("catalog/product_option_type_value");
        $costTable = $this->_t("catalog/product_option_type_title");
        $cospTable = $this->_t("catalog/product_option_type_price");
        $pId = $this->_getIdBySku($row[1]);
        $optTitle = $this->_convertEncoding($row[2]);
        $selTitle = $this->_convertEncoding($row[3]);
        $new = array( "sku" => isset($row[4]) ? $row[4] : "", "sort_order" => isset($row[5]) ? (int) $row[5] : 0 );
        $price = isset($row[6]) && $row[6] !== "" ? (double) $row[6] : null;
        $priceType = isset($row[7]) && $row[7] !== "" ? $row[7] : "fixed";
        $option = $this->_getCustomOption($pId, $optTitle);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $changed = false;
        $selection = $this->_getCustomOptionSelection($option["option_id"], $selTitle);
        if( $selection ) 
        {
            if( $this->_isChangeRequired($selection, $new) ) 
            {
                $this->_write->update($cosTable, $new, "" . "option_type_id=" . $selection["option_type_id"]);
                $selection = $this->_updateCustomOptionSelection($option["option_id"], $selTitle, $new);
                $changed = true;
            }

        }
        else
        {
            $new["option_id"] = $option["option_id"];
            $this->_write->insert($cosTable, $new);
            $new["option_type_id"] = $this->_write->lastInsertId($cosTable);
            $this->_write->insert($costTable, array( "option_type_id" => $new["option_type_id"], "store_id" => 0, "title" => $selTitle ));
            $selection = $this->_updateCustomOptionSelection($option["option_id"], $selTitle, $new);
            $changed = true;
        }

        $exists = $this->_write->fetchRow("" . "select * from " . $cospTable . "\n            where option_type_id=" . $selection["option_type_id"] . " and store_id=0");
        if( $exists ) 
        {
            if( is_null($price) ) 
            {
                $this->_write->delete($cospTable, "" . "option_type_price_id=" . $exists["option_type_price_id"]);
                $changed = true;
            }
            else
            {
                if( $exists["price"] != $price || $exists["price_type"] != $priceType ) 
                {
                    $this->_write->update($cospTable, array( "price" => $price, "price_type" => $priceType ), "" . "option_type_price_id=" . $exists["option_type_price_id"]);
                    $changed = true;
                }

            }

        }
        else
        {
            if( !is_null($price) ) 
            {
                $this->_write->insert($cospTable, array( "option_type_id" => $selection["option_type_id"], "store_id" => 0, "price" => $price, "price_type" => $priceType ));
                $changed = true;
            }

        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPCOSL($row)
    {
        if( sizeof($row) < 6 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $optTitle = $this->_convertEncoding($row[2]);
        $selTitle = $this->_convertEncoding($row[3]);
        $storeId = $this->_getStoreId($row[4]);
        if( $this->_skipStore($storeId, 5) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $title = $row[5];
        $price = isset($row[6]) && $row[6] !== "" ? (double) $row[6] : null;
        $priceType = isset($row[7]) && $row[7] !== "" ? $row[7] : "fixed";
        $option = $this->_getCustomOption($pId, $optTitle);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $selection = $this->_getCustomOptionSelection($option["option_id"], $selTitle);
        if( !$selection ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $changed = false;
        $costTable = $this->_t("catalog/product_option_type_title");
        $exists = $this->_write->fetchRow("" . "select * from " . $costTable . "\n            where option_type_id=" . $selection["option_type_id"] . " and store_id=" . $storeId);
        if( $exists ) 
        {
            if( $exists["title"] != $title ) 
            {
                $this->_write->update($costTable, array( "title" => $title ), "" . "option_type_title_id=" . $exists["option_type_title_id"]);
                $changed = true;
            }

        }
        else
        {
            $this->_write->insert($costTable, array( "option_type_id" => $selection["option_type_id"], "store_id" => $storeId, "title" => $title ));
            $changed = true;
        }

        $cospTable = $this->_t("catalog/product_option_type_price");
        $exists = $this->_write->fetchRow("" . "select * from " . $cospTable . "\n            where option_type_id=" . $selection["option_type_id"] . " and store_id=" . $storeId);
        if( $exists ) 
        {
            if( is_null($price) ) 
            {
                $this->_write->delete($cospTable, "" . "option_type_price_id=" . $exists["option_type_price_id"]);
                $changed = true;
            }
            else
            {
                if( $exists["price"] != $price || $exists["price_type"] != $priceType ) 
                {
                    $this->_write->update($cospTable, array( "price" => $price, "price_type" => $priceType ), "" . "option_type_price_id=" . $exists["option_type_price_id"]);
                    $changed = true;
                }

            }

        }
        else
        {
            if( !is_null($price) ) 
            {
                $this->_write->insert($cospTable, array( "option_type_id" => $selection["option_type_id"], "store_id" => $storeId, "price" => $price, "price_type" => $priceType ));
                $changed = true;
            }

        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPSA($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $aId = $this->_getAttributeId($row[2]);
        $pos = $row[3];
        $label = $this->_convertEncoding($row[4]);
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superLabelTable = $this->_t("catalog/product_super_attribute_label");
        $changed = false;
        $exists = $this->_write->fetchRow("" . "select sa.*, sal.value_id, sal.value label from " . $superAttrTable . " sa\n            inner join " . $superLabelTable . " sal on sal.product_super_attribute_id=sa.product_super_attribute_id\n            where sa.product_id=" . $pId . " and sa.attribute_id=" . $aId . " and sal.store_id=0");
        if( $exists ) 
        {
            if( $exists["position"] != $pos ) 
            {
                $this->_write->update($superAttrTable, array( "position" => $pos ), "" . "product_super_attribute_id=" . $exists["product_super_attribute_id"]);
                $changed = true;
            }

            if( $exists["label"] != $label ) 
            {
                $this->_write->update($superLabelTable, array( "value" => $label ), "" . "value_id=" . $exists["value_id"]);
                $changed = true;
            }

        }
        else
        {
            $this->_write->insert($superAttrTable, array( "product_id" => $pId, "attribute_id" => $aId, "position" => $pos ));
            $saId = $this->_write->lastInsertId($superAttrTable);
            $this->_write->insert($superLabelTable, array( "product_super_attribute_id" => $saId, "store_id" => 0, "value" => $label ));
            $changed = true;
        }

        if( $changed ) 
        {
            $this->_newRefreshHoRoPids[$pId] = 1;
        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPSAL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $aId = $this->_getAttributeId($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $label = isset($row[4]) && $row[4] !== "" ? $this->_convertEncoding($row[4]) : null;
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superLabelTable = $this->_t("catalog/product_super_attribute_label");
        $exists = $this->_write->fetchRow("" . "select sal.*, sa.product_super_attribute_id from " . $superAttrTable . " sa\n            left join " . $superLabelTable . " sal on sal.product_super_attribute_id=sa.product_super_attribute_id\n                and sal.store_id=" . $storeId . "\n            where sa.product_id=" . $pId . " and sa.attribute_id=" . $aId);
        if( !$exists ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $changed = false;
        if( !is_null($exists["value_id"]) ) 
        {
            if( is_null($label) ) 
            {
                $this->_write->delete($superLabelTable, "" . "value_id=" . $exists["value_id"]);
                $changed = true;
            }
            else
            {
                if( $exists["value"] != $label ) 
                {
                    $this->_write->update($superLabelTable, array( "value" => $label ), "" . "value_id=" . $exists["value_id"]);
                    $changed = true;
                }

            }

        }
        else
        {
            if( !is_null($label) ) 
            {
                $this->_write->insert($superLabelTable, array( "product_super_attribute_id" => $exists["product_super_attribute_id"], "store_id" => $storeId, "value" => $label ));
                $changed = true;
            }

        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPSAP($row)
    {
        if( sizeof($row) < 7 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $aId = $this->_getAttributeId($row[2]);
        $this->_fetchAttributeOptions($aId);
        $valueLabel = $this->_convertEncoding(strtolower($row[3]));
        if( !isset($this->_attrOptionsByLabel[$aId][$valueLabel]) ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception("Invalid attribute option label: " . $row[2] . ", " . $row[3]);
        }

        $valueIndex = $this->_attrOptionsByLabel[$aId][$valueLabel];
        $websiteId = Mage::app()->getWebsite($row[4])->getId();
        $new = array( "pricing_value" => $row[5] !== "" ? (double) $row[5] : null, "is_percent" => (int) $row[6] );
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superPricingTable = $this->_t("catalog/product_super_attribute_pricing");
        $w = Mage::helper("urapidflow")->hasMageFeature("cpsap.website_id");
        $exists = $this->_write->fetchRow("" . "select sap.*, sa.product_super_attribute_id from " . $superAttrTable . " sa\n            left join " . $superPricingTable . " sap on sap.product_super_attribute_id=sa.product_super_attribute_id\n                and sap.value_index=" . $valueIndex . " " . ($w ? "" . "and sap.website_id=" . $websiteId : "") . "" . "\n            where sa.product_id=" . $pId . " and sa.attribute_id=" . $aId);
        if( !$exists ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $changed = false;
        if( !is_null($exists["value_id"]) ) 
        {
            if( is_null($new["pricing_value"]) ) 
            {
                $this->_write->delete($superPricingTable, "" . "value_id=" . $exists["value_id"]);
                $changed = true;
            }
            else
            {
                if( $this->_isChangeRequired($exists, $new) ) 
                {
                    $this->_write->update($superPricingTable, $new, "" . "value_id=" . $exists["value_id"]);
                    $changed = true;
                }

            }

        }
        else
        {
            if( !is_null($new["pricing_value"]) ) 
            {
                $new["product_super_attribute_id"] = $exists["product_super_attribute_id"];
                $new["value_index"] = $valueIndex;
                if( $w ) 
                {
                    $new["website_id"] = $websiteId;
                }

                $this->_write->insert($superPricingTable, $new);
                $changed = true;
            }

        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPSI($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_super_link");
        $p1 = $this->_getIdBySku($row[1]);
        $p2 = $this->_getIdBySku($row[2]);
        $linkId = $this->_write->fetchOne("" . "select link_id from " . $t . " where parent_id='" . $p1 . "' and product_id='" . $p2 . "'");
        if( !$linkId ) 
        {
            $this->_write->insert($t, array( "parent_id" => $p1, "product_id" => $p2 ));
            if( Mage::helper("urapidflow")->hasMageFeature("table.product_relation") ) 
            {
                $relTable = $this->_t("catalog/product_relation");
                if( !$this->_write->fetchOne("" . "select parent_id from " . $relTable . " where parent_id=" . $p1 . " and child_id=" . $p2) ) 
                {
                    $this->_write->insert($relTable, array( "parent_id" => $p1, "child_id" => $p2 ));
                }

            }

            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPI($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_media_gallery");
        $tv = $this->_t("catalog/product_attribute_media_gallery_value");
        $pId = $this->_getIdBySku($row[1]);
        $imgFilename = $this->_convertEncoding($row[2]);
        $label = !empty($row[3]) ? $this->_convertEncoding($row[3]) : "";
        $result = self::IMPORT_ROW_RESULT_NOCHANGE;
        $imgFileChanged = false;
        if( !$this->_processImageFiles ) 
        {
            $this->_validateImageFile($imgFilename, $this->_imagesMediaDir);
            $imgFileChanged = true;
        }
        else
        {
            if( $imgFilename !== "" ) 
            {
                $this->_profile->getLogger()->setColumn(3);
                if( $this->_copyImageFile($this->_imagesTargetDir, $this->_imagesMediaDir, $imgFilename, true) ) 
                {
                    $imgFileChanged = true;
                    $result = self::IMPORT_ROW_RESULT_SUCCESS;
                }

            }

        }

        $imgId = $this->_write->fetchOne("" . "select value_id from " . $t . " where `entity_id`=" . $pId . " and binary `value`=" . $this->_write->quote($imgFilename));
        if( !$imgId && $imgFileChanged ) 
        {
            $img = array( "attribute_id" => $this->_getGalleryAttrId(), "entity_id" => $pId, "value" => $imgFilename );
            $this->_write->insert($t, $img);
            $imgId = $this->_write->lastInsertId();
            $result = self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $imgId ) 
        {
            $lbl = $this->_write->fetchRow("" . "select * from " . $tv . " where `value_id`=" . $imgId . " and store_id=0");
            if( !$lbl ) 
            {
                $lbl = array( "value_id" => $imgId, "store_id" => 0, "label" => !empty($label) ? $this->_convertEncoding($label) : "", "position" => !empty($row[4]) && $row[4] !== "0" ? $row[4] : 1, "disabled" => !empty($row[5]) ? $row[5] : 0 );
                $this->_write->insert($tv, $lbl);
                $result = self::IMPORT_ROW_RESULT_SUCCESS;
            }
            else
            {
                if( isset($label) && $lbl["label"] != $label || isset($row[4]) && $lbl["position"] != $row[4] || isset($row[5]) && $lbl["disabled"] != $row[5] ) 
                {
                    $lbl = array( "label" => !empty($label) ? $label : "", "position" => !empty($row[4]) && $row[4] !== "0" ? $row[4] : 1, "disabled" => !empty($row[5]) ? $row[5] : 0 );
                    $this->_write->update($tv, $lbl, "" . "value_id=" . $imgId . " and store_id=0");
                    $result = self::IMPORT_ROW_RESULT_SUCCESS;
                }

            }

        }

        return $result;
    }

    protected function _importRowCPIL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_media_gallery");
        $tv = $this->_t("catalog/product_attribute_media_gallery_value");
        $pId = $this->_getIdBySku($row[1]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $imgFilename = $this->_convertEncoding($row[2]);
        $label = !empty($row[4]) ? $this->_convertEncoding($row[4]) : "";
        $imgId = $this->_write->fetchOne("" . "select value_id from " . $t . "\n            where `entity_id`=" . $pId . " and binary `value`=" . $this->_write->quote($imgFilename));
        if( !$imgId ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        $result = self::IMPORT_ROW_RESULT_NOCHANGE;
        $lbl = $this->_write->fetchRow("" . "select * from " . $tv . " where `value_id`=" . $imgId . " and store_id=" . $storeId);
        if( !$lbl ) 
        {
            $lbl = array( "value_id" => $imgId, "store_id" => $storeId, "label" => !empty($label) ? $label : "", "position" => !empty($row[5]) && $row[5] !== "0" ? $row[5] : 1, "disabled" => !empty($row[6]) ? $row[6] : 0 );
            $this->_write->insert($tv, $lbl);
            $result = self::IMPORT_ROW_RESULT_SUCCESS;
        }
        else
        {
            if( isset($label) && $lbl["label"] != $label || isset($row[5]) && $lbl["position"] != $row[5] || isset($row[6]) && $lbl["disabled"] != $row[6] ) 
            {
                $lbl = array( "label" => !empty($label) ? $label : "", "position" => !empty($row[5]) && $row[5] !== "0" ? $row[5] : 1, "disabled" => !empty($row[6]) ? $row[6] : 0 );
                $this->_write->update($tv, $lbl, "" . "value_id=" . $imgId . " and store_id=" . $storeId);
                $result = self::IMPORT_ROW_RESULT_SUCCESS;
            }

        }

        return $result;
    }

    protected function _importRowCPPT($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_tier_price");
        $pId = $this->_getIdBySku($row[1]);
        $allGroups = $row[2] === "*" ? 1 : 0;
        $gId = $allGroups ? 0 : (int) $this->_getCustomerGroup($this->_convertEncoding($row[2]), true);
        $qty = (double) $row[3];
        $price = (double) $row[4];
        $wId = !empty($row[5]) ? (int) $this->_getWebsiteId($row[5], true) : 0;
        $exists = $this->_write->fetchRow("" . "select * from " . $t . " where entity_id=" . $pId . " and customer_group_id=" . $gId . " and qty=" . $qty . " and website_id=" . $wId . " and all_groups=" . $allGroups);
        if( !$exists ) 
        {
            $exists = array( "entity_id" => $pId, "all_groups" => $allGroups, "customer_group_id" => $gId, "qty" => $qty, "value" => $price, "website_id" => $wId );
            $this->_write->insert($t, $exists);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["value"] != $price ) 
        {
            $this->_write->update($t, array( "value" => $price ), "" . "value_id=" . $exists["value_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPPG($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_group_price");
        $pId = $this->_getIdBySku($row[1]);
        $gId = (int) $this->_getCustomerGroup($this->_convertEncoding($row[2]), true);
        $price = (double) $row[3];
        $wId = !empty($row[4]) ? (int) $this->_getWebsiteId($row[4], true) : 0;
        $exists = $this->_write->fetchRow("" . "select * from " . $t . " where entity_id=" . $pId . " and customer_group_id=" . $gId . " and website_id=" . $wId);
        if( !$exists ) 
        {
            $exists = array( "entity_id" => $pId, "customer_group_id" => $gId, "all_groups" => 0, "value" => $price, "website_id" => $wId );
            $this->_write->insert($t, $exists);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["value"] != $price ) 
        {
            $this->_write->update($t, array( "value" => $price ), "" . "value_id=" . $exists["value_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPD($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/link");
        $tl = $this->_t("downloadable/link_title");
        $tp = $this->_t("downloadable/link_price");
        $tprod = $this->_t("catalog/product");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $price = isset($row[3]) ? (double) $row[3] : 0;
        $new = array( "number_of_downloads" => isset($row[4]) ? (int) $row[4] : 0, "is_shareable" => !empty($row[5]) && in_array($row[5], array( 0, 1, 2 )) ? $row[5] : 0, "sort_order" => !empty($row[6]) ? $row[6] : 0, "link_url" => !empty($row[7]) ? $row[7] : "", "link_file" => !empty($row[8]) ? $row[8] : "", "link_type" => !empty($row[9]) ? $row[9] : "", "sample_url" => !empty($row[10]) ? $row[10] : "", "sample_file" => !empty($row[11]) ? $row[11] : "", "sample_type" => !empty($row[12]) ? $row[12] : "" );
        $updated = false;
        $exists = $this->_write->fetchRow("" . "select t.*, tl.title_id, tl.title, tp.price_id, tp.price from " . $t . " t\n            left join " . $tl . " tl on tl.link_id=t.link_id and tl.store_id=0\n            left join " . $tp . " tp on tp.link_id=t.link_id and tp.website_id=0\n            where t.product_id=" . $pId . " and tl.title=" . $this->_write->quote($title));
        if( !$exists ) 
        {
            $new["product_id"] = $pId;
            $this->_write->insert($t, $new);
            $exists = array( "link_id" => $this->_write->lastInsertId() );
            $updated = true;
        }
        else
        {
            if( $this->_isChangeRequired($exists, $new) ) 
            {
                $this->_write->update($t, $new, "" . "link_id=" . $exists["link_id"]);
                $updated = true;
            }

        }

        if( empty($exists["title_id"]) ) 
        {
            $this->_write->insert($tl, array( "link_id" => $exists["link_id"], "store_id" => 0, "title" => $title ));
            $updated = true;
        }
        else
        {
            if( $exists["title"] != $title ) 
            {
                $this->_write->update($tl, array( "title" => $title ), "" . "title_id=" . $exists["title_id"]);
                $updated = true;
            }

        }

        if( empty($exists["price_id"]) ) 
        {
            $this->_write->insert($tp, array( "link_id" => $exists["link_id"], "website_id" => 0, "price" => $price ));
            $updated = true;
        }
        else
        {
            if( $exists["price"] != $price ) 
            {
                $this->_write->update($tp, array( "price" => $price ), "" . "price_id=" . $exists["price_id"]);
                $updated = true;
            }

        }

        return $updated ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPDL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/link");
        $tl = $this->_t("downloadable/link_title");
        $pId = $this->_getIdBySku($row[1]);
        $defTitle = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $title = $this->_convertEncoding($row[4]);
        $exists = $this->_write->fetchRow("" . "select t.link_id, tl.title_id, tl.title from " . $t . " t\n            inner join " . $tl . " td on td.link_id=t.link_id and td.store_id=0 and td.title=" . $this->_write->quote($defTitle) . "\n            left join " . $tl . " tl on tl.link_id=t.link_id and tl.store_id=" . $storeId);
        if( !$exists ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        if( !$exists["title_id"] ) 
        {
            $this->_write->insert($tl, array( "link_id" => $exists["link_id"], "store_id" => $storeId, "title" => $title ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["title"] != $title ) 
        {
            $this->_write->update($tl, array( "title" => $title ), "" . "title_id=" . $exists["title_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPDP($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/link");
        $tl = $this->_t("downloadable/link_title");
        $tp = $this->_t("downloadable/link_price");
        $pId = $this->_getIdBySku($row[1]);
        $defTitle = $this->_convertEncoding($row[2]);
        $websiteId = $this->_getWebsiteId($row[3]);
        $price = $row[4];
        if( !$websiteId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid website"));
        }

        $exists = $this->_write->fetchRow("" . "select t.link_id, tl.title_id, tl.title from " . $t . " t\n            inner join " . $tl . " td on td.link_id=t.link_id and td.store_id=0 and td.title=" . $this->_write->quote($title) . "\n            left join " . $tp . " tp on tp.link_id=t.link_id and tp.website_id=" . $websiteId);
        if( !$exists ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        if( !$exists["price_id"] ) 
        {
            $this->_write->insert($tp, array( "link_id" => $exists["link_id"], "website_id" => $websiteId, "price" => $price ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["price"] != $price ) 
        {
            $this->_write->update($tp, array( "price" => $price ), "" . "price_id=" . $exists["price_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPDS($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/sample");
        $tl = $this->_t("downloadable/sample_title");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $new = array( "sort_order" => !empty($row[3]) ? $row[3] : 0, "sample_url" => !empty($row[4]) ? $row[4] : "", "sample_file" => !empty($row[5]) ? $row[5] : "", "sample_type" => !empty($row[6]) ? $row[6] : "" );
        $updated = false;
        $exists = $this->_write->fetchRow("" . "select t.*, tl.title_id, tl.title from " . $t . " t\n            left join " . $tl . " tl on tl.sample_id=t.link_id and tl.store_id=0\n            where t.product_id=" . $pId . " and tl.title=" . $this->_write->quote($title));
        if( !$exists ) 
        {
            $new["product_id"] = $pId;
            $this->_write->insert($t, $new);
            $exists = array( "sample_id" => $this->_write->lastInsertId() );
            $updated = true;
        }
        else
        {
            if( $this->_isChangeRequired($exists, $new) ) 
            {
                $this->_write->update($t, $new, "" . "sample_id=" . $exists["sample_id"]);
                $updated = true;
            }

        }

        if( empty($exists["title_id"]) ) 
        {
            $this->_write->insert($tl, array( "sample_id" => $exists["sample_id"], "store_id" => 0, "title" => $title ));
            $updated = true;
        }
        else
        {
            if( $exists["title"] != $title ) 
            {
                $this->_write->update($tl, array( "title" => $title ), "" . "title_id=" . $exists["title_id"]);
                $updated = true;
            }

        }

        return $updated ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _importRowCPDSL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/sample");
        $tl = $this->_t("downloadable/sample_title");
        $pId = $this->_getIdBySku($row[1]);
        $defTitle = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $title = $this->_convertEncoding($row[4]);
        $exists = $this->_write->fetchRow("" . "select t.sample_id, tl.title_id, tl.title from " . $t . " t\n            inner join " . $tl . " td on td.sample_id=t.sample_id and td.store_id=0 and td.title=" . $this->_write->quote($defTitle) . "\n            left join " . $tl . " tl on tl.sample_id=t.sample_id and tl.store_id=" . $storeId);
        if( !$exists ) 
        {
            return self::IMPORT_ROW_RESULT_DEPENDS;
        }

        if( !$exists["title_id"] ) 
        {
            $this->_write->insert($tl, array( "sample_id" => $exists["sample_id"], "store_id" => $storeId, "title" => $title ));
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        if( $exists["title"] != $title ) 
        {
            $this->_write->update($tl, array( "title" => $title ), "" . "title_id=" . $exists["title_id"]);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _renameRowCP($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $oldSku = $row[1];
        $newSku = $row[2];
        if( $oldSku == $newSku ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $pId = $this->_getIdBySku($oldSku);
        $this->_write->update($this->_t("catalog/product"), array( "sku" => $newSku ), "" . "entity_id=" . $pId);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _renameRowCPCO($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowCPCOS($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowCPSA($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowCPD($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowCPDS($row)
    {
        return self::IMPORT_ROW_RESULT_ERROR;
    }

    protected function _renameRowCPBO($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $oldTitle = $row[2];
        $newTitle = $row[3];
        if( $oldTitle == $newTitle ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $option = $this->_getBundleOption($pId, $oldTitle);
        if( !$option ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception("Bundle option not found (" . $row[1] . ":" . $row[2] . ")");
        }

        $this->_write->update($this->_t("bundle/option_value"), array( "title" => $newTitle ), "" . "value_id=" . $option["value_id"]);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCP($row)
    {
        if( sizeof($row) < 2 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product");
        $entityId = $this->_write->fetchOne("" . "select entity_id from " . $t . " where sku=?", $row[1]);
        if( !$entityId ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $this->_write->delete($t, "" . "entity_id=" . $entityId);
        unset($this->_skus[$row[1]]);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _fetchCategoryRow($urlPath)
    {
        $t = $this->_t("catalog/category");
        if( !$this->_urlPaths ) 
        {
            $noUrlPath = Mage::helper("urapidflow")->hasMageFeature("no_url_path");
            $this->_upPrependRoot = $this->_profile->getData("options/" . $this->_profile->getProfileType() . "/urlpath_prepend_root");
            $storeId = $this->_profile->getStoreId();
            if( is_null($this->_categoryUrlSuffix) ) 
            {
                $this->_categoryUrlSuffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $storeId);
                $this->_categoryUrlSuffixLen = strlen($this->_categoryUrlSuffix);
                $this->_categoryUrlPathAttrId = $this->_getAttributeId("url_path", "catalog_category");
                $this->_categoryNameAttrId = $this->_getAttributeId("name", "catalog_category");
            }

            if( $storeId ) 
            {
                $this->_rootCatId = Mage::app()->getStore($storeId)->getGroup()->getRootCategoryId();
            }
            else
            {
                if( !$this->_upPrependRoot ) 
                {
                    $this->_rootCatId = $this->_read->fetchOne("" . "select g.root_category_id from " . $this->_t("core/website") . " w\n                    inner join " . $this->_t("core/store_group") . " g on g.group_id=w.default_group_id where w.is_default=1");
                }

            }

            $rootPath = $this->_rootCatId ? "1/" . $this->_rootCatId : "1";
            if( $this->_upPrependRoot ) 
            {
                $rootCatPathsSel = $this->_read->select()->from(array( "g" => $this->_t("core/store_group") ), array(  ))->join(array( "e" => $t ), "e.entity_id=g.root_category_id", array( "concat('1/',e.entity_id)" ))->join(array( "name" => $t . "_varchar" ), "" . "name.entity_id=e.entity_id\n                        and name.attribute_id=" . $this->_categoryNameAttrId . "\n                        and name.value<>''\n                        and name.value is not null\n                        and name.store_id=0", array( "value" ))->group("e.entity_id");
                if( $storeId ) 
                {
                    $rootCatPathsSel->where("e.entity_id=?", $this->_rootCatId);
                }

                $this->_rootCatPaths = $this->_read->fetchPairs($rootCatPathsSel);
            }

            $select = $this->_write->select()->from(array( "e" => $t ), array( "entity_id", "path" ))->joinLeft(array( "v" => $t . "_varchar" ), "" . "v.entity_id=e.entity_id\n                                    and v.attribute_id=" . $this->_categoryUrlPathAttrId . "\n                                    and v.store_id in (0, " . $storeId . ")", array( "url_path" => "value" ))->order("v.store_id desc");
            if( $noUrlPath ) 
            {
                $ukAttrId = $this->_getAttributeId("url_key", "catalog_category");
                $select->joinLeft(array( "ups" => $t . "_url_key" ), "" . "ups.entity_id=e.entity_id\n                                    and ups.attribute_id=" . $ukAttrId . "\n                                    and ups.store_id in (0, " . $storeId . ")", array( "url_key" => "value" ))->order("ups.store_id desc");
            }

            if( $this->_upPrependRoot && !empty($this->_rootCatPaths) ) 
            {
                $_rcPaths = array(  );
                foreach( $this->_rootCatPaths as $_rcPath => $_rcName ) 
                {
                    $_rcPaths[] = $this->_read->quoteInto("path=?", $_rcPath);
                    $_rcPaths[] = $this->_read->quoteInto("path like ?", $_rcPath . "/%");
                }
                $select->where(implode(" OR ", $_rcPaths));
            }
            else
            {
                $select->where($this->_read->quoteInto("path=?", $rootPath) . $this->_read->quoteInto(" OR path like ?", $rootPath . "/%"));
            }

            Mage::log((bool) $select, null, "rf.log", true);
            $rows = $this->_write->fetchAll($select);
            $entities = array(  );
            foreach( $rows as $r ) 
            {
                $entities[$r["entity_id"]] = $r;
            }
            foreach( $rows as $r ) 
            {
                if( $noUrlPath ) 
                {
                    $r["url_path"] = $this->catBuildPath($r, $entities);
                }

                if( !empty($this->_urlPaths[$r["url_path"]]) ) 
                {
                    continue;
                }

                $r["url_path"] = $this->_upPrependRoot($r, $r["url_path"]);
                $adjUrlPath = substr($r["url_path"], 0 - $this->_categoryUrlSuffixLen) == $this->_categoryUrlSuffix ? substr($r["url_path"], 0, strlen($r["url_path"]) - $this->_categoryUrlSuffixLen) : $r["url_path"] . $this->_categoryUrlSuffix;
                $data = array( "entity_id" => $r["entity_id"], "path" => $r["path"] );
                $this->_urlPaths[$r["url_path"]] = $data;
                $this->_urlPaths[$adjUrlPath] = $data;
                $this->_catIds[(int) $r["entity_id"]] = $data;
            }
        }

        if( is_numeric($urlPath) ) 
        {
            if( empty($this->_catIds[(int) $urlPath]) ) 
            {
                return false;
            }

            return $this->_catIds[(int) $urlPath];
        }

        return !empty($this->_urlPaths[$urlPath]) ? $this->_urlPaths[$urlPath] : false;
    }

    protected function _deleteRowCC($row)
    {
        if( sizeof($row) < 2 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/category");
        $category = $this->_fetchCategoryRow($row[1]);
        if( $category === false ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $this->_write->delete($t, "" . "entity_id=" . $category["entity_id"] . " or path like '" . $category["path"] . "/%'");
        unset($this->_urlPaths[$row[1]]);
        unset($this->_catIds[$category["entity_id"]]);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCCP($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/category_product");
        $cWild = trim($row[1]) == "*";
        $pWild = trim($row[2]) == "*";
        $category = $this->_fetchCategoryRow($row[1]);
        $pId = !$pWild ? $this->_getIdBySku($row[2]) : null;
        if( !$category && !$pId || !$category && $pId && !$cWild || !$pId && $category && !$pWild ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $dWhere = "1";
        if( !$cWild ) 
        {
            $dWhere .= "" . " AND category_id='" . $category["entity_id"] . "'";
        }

        if( !$pWild ) 
        {
            $dWhere .= "" . " AND product_id='" . $pId . "'";
        }

        $this->_write->delete($t, $dWhere);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteLink($row, $linkType)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_link");
        $p1 = $this->_getIdBySku($row[1]);
        $p2 = $this->_getIdBySku($row[2]);
        $lt = $this->_getLinkTypeId($linkType);
        $linkId = $this->_write->fetchOne("" . "select link_id from " . $t . "\n            where product_id=" . $p1 . " and linked_product_id=" . $p2 . " and link_type_id=" . $lt);
        if( !$linkId ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        if( $linkType == "super" && Mage::helper("urapidflow")->hasMageFeature("table.product_relation") ) 
        {
            $this->_write->query("" . "delete from " . $this->_t("catalog/product_relation") . " where parent_id=" . $p1 . " and child_id=" . $p2);
        }

        $this->_write->delete($t, "" . "link_id=" . $linkId);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCPRI($row)
    {
        return $this->_deleteLink($row, "relation");
    }

    protected function _deleteRowCPUI($row)
    {
        return $this->_deleteLink($row, "up_sell");
    }

    protected function _deleteRowCPXI($row)
    {
        return $this->_deleteLink($row, "cross_sell");
    }

    protected function _deleteRowCPGI($row)
    {
        return $this->_deleteLink($row, "super");
    }

    protected function _deleteRowCPBO($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("bundle/option");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $option = $this->_getBundleOption($pId, $title);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $this->_write->delete($t, "" . "option_id=" . $option["option_id"]);
        unset($this->_bundleOptions[$pId][$title]);
        $this->_newRefreshHoRoPids[$pId] = 1;
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCPBOL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("bundle/option_value");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $option = $this->_getBundleOption($pId, $title);
        if( !$option ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid option (%s: %s)", $row[1], $row[2]));
        }

        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $valueId = $this->_write->fetchOne("" . "select value_id from " . $t . "\n            where option_id=" . $option["option_id"] . " and store_id=" . $storeId);
        if( !$valueId ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $this->_write->delete($t, "" . "value_id=" . $valueId);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCPBOS($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("bundle/selection");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $option = $this->_getBundleOption($pId, $title);
        if( !$option ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid option (%s: %s)", $row[1], $row[2]));
        }

        $sId = $this->_getIdBySku($row[3]);
        $selectionId = $this->_write->fetchOne("" . "select selection_id from " . $t . "\n            where option_id='" . $option["option_id"] . "' and product_id='" . $sId . "'");
        if( !$selectionId ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $this->_write->delete($t, "" . "selection_id=" . $selectionId);
        $this->_write->delete($this->_t("catalog/product_relation"), "" . "parent_id='" . $pId . "' and child_id='" . $sId . "'");
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCPCO($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_option");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $option = $this->_getCustomOption($pId, $title);
        if( !$option ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $this->_write->delete($t, "" . "option_id=" . $option["option_id"]);
        unset($this->_customOptions[$pId][$title]);
        unset($this->_customOptionSelections[$option["option_id"]]);
        $this->_newRefreshHoRoPids[$pId] = 1;
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCPCOL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $option = $this->_getCustomOption($pId, $title);
        if( !$option ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid option (%s: %s)", $row[1], $row[2]));
        }

        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $changed = false;
        $t = $this->_t("catalog/product_option_title");
        $valueId = $this->_write->fetchOne("" . "select value_id from " . $t . "\n            where option_id=" . $option["option_id"] . " and store_id=" . $storeId);
        if( $valueId ) 
        {
            $this->_write->delete($t, "" . "option_title_id=" . $valueId);
            $changed = true;
        }

        $t = $this->_t("catalog/product_option_price");
        $valueId = $this->_write->fetchOne("" . "select value_id from " . $t . "\n            where option_id=" . $option["option_id"] . " and store_id=" . $storeId);
        if( $valueId ) 
        {
            $this->_write->delete($t, "" . "option_price_id=" . $valueId);
            $changed = true;
        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPCOS($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $optTitle = $this->_convertEncoding($row[2]);
        $selTitle = $this->_convertEncoding($row[3]);
        $option = $this->_getCustomOption($pId, $optTitle);
        if( !$option ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid option (%s: %s)", $row[1], $row[2]));
        }

        $selection = $this->_getCustomOptionSelection($option["option_id"], $selTitle);
        if( !$selection ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $t = $this->_t("catalog/product_option_type_value");
        $this->_write->delete($t, "" . "option_type_id=" . $selection["option_type_id"]);
        unset($this->_customOptionSelections[$option["option_id"]][$selTitle]);
        return self::IMPORT_ROW_RESULT_SUCCESS;
    }

    protected function _deleteRowCPCOSL($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $optTitle = $this->_convertEncoding($row[2]);
        $selTitle = $this->_convertEncoding($row[3]);
        $storeId = $this->_getStoreId($row[4]);
        if( $this->_skipStore($storeId, 5) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $option = $this->_getCustomOption($pId, $optTitle);
        if( !$option ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            Mage::throwexception($this->__("Invalid option (%s: %s)", $row[1], $row[2]));
        }

        $selection = $this->_getCustomOptionSelection($option["option_id"], $selTitle);
        if( !$selection ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid selection (%s: %s: %s)", $row[1], $row[2], $row[3]));
        }

        $changed = false;
        $t = $this->_t("catalog/product_option_type_title");
        $valueId = $this->_write->fetchOne("" . "select option_type_title_id from " . $t . "\n            where option_type_id=" . $selection["option_type_id"] . " and store_id=" . $storeId);
        if( $valueId ) 
        {
            $this->_write->delete($t, "" . "option_type_title_id=" . $valueId);
            $changed = true;
        }

        $t = $this->_t("catalog/product_option_type_price");
        $valueId = $this->_write->fetchOne("" . "select option_type_price_id from " . $t . "\n            where option_type_id=" . $selection["option_type_id"] . " and store_id=" . $storeId);
        if( $valueId ) 
        {
            $this->_write->delete($t, "" . "option_type_price_id=" . $valueId);
            $changed = true;
        }

        return $changed ? self::IMPORT_ROW_RESULT_SUCCESS : self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPSA($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        if( !($aWild = trim($row[2]) == "*") ) 
        {
            $aId = $this->_getAttributeId($row[2]);
        }

        $t = $this->_t("catalog/product_super_attribute");
        $saId = $this->_write->fetchCol("" . "select product_super_attribute_id from " . $t . "\n            where product_id=" . $pId . " " . (!$aWild ? "" . "and attribute_id=" . $aId : ""));
        if( !empty($saId) ) 
        {
            $this->_write->delete($t, $this->_write->quoteInto("product_super_attribute_id in (?)", $saId));
            $this->_newRefreshHoRoPids[$pId] = 1;
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPSAL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $aId = $this->_getAttributeId($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superLabelTable = $this->_t("catalog/product_super_attribute_label");
        $valueId = $this->_write->fetchOne("" . "select sal.value_id from " . $superAttrTable . " sa\n            join " . $superLabelTable . " sal on sal.product_super_attribute_id=sa.product_super_attribute_id\n            where sa.product_id=" . $pId . " and sa.attribute_id=" . $aId . " and sal.store_id=" . $storeId);
        if( $valueId ) 
        {
            $this->_write->delete($superLabelTable, "" . "value_id=" . $valueId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPSAP($row)
    {
        if( sizeof($row) < 5 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $pId = $this->_getIdBySku($row[1]);
        $aId = $this->_getAttributeId($row[2]);
        $this->_fetchAttributeOptions($aId);
        $valueLabel = $this->_convertEncoding(strtolower($row[3]));
        if( !isset($this->_attrOptionsByLabel[$aId][$valueLabel]) ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception($this->__("Invalid attribute option label: %s, %s", $row[2], $row[3]));
        }

        $valueIndex = $this->_attrOptionsByLabel[$aId][$valueLabel];
        $websiteId = Mage::app()->getWebsite($row[4])->getId();
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superPricingTable = $this->_t("catalog/product_super_attribute_pricing");
        $w = Mage::helper("urapidflow")->hasMageFeature("cpsap.website_id");
        $valueId = $this->_write->fetchOne("" . "select sap.value_id from " . $superAttrTable . " sa\n            join " . $superPricingTable . " sap on sap.product_super_attribute_id=sa.product_super_attribute_id\n            where sa.product_id=" . $pId . " and sa.attribute_id=" . $aId . "\n                and sap.value_index=" . $valueIndex . " " . ($w ? "" . "and sap.website_id=" . $websiteId : ""));
        if( $valueId ) 
        {
            $this->_write->delete($superPricingTable, "" . "value_id=" . $valueId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPSI($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_super_link");
        $p1 = $this->_getIdBySku($row[1]);
        $pWild = $pWild = trim($row[2]) == "*";
        $p2 = !$pWild ? $this->_getIdBySku($row[2]) : null;
        $linkId = $this->_write->fetchCol("" . "select link_id from " . $t . "\n            where parent_id='" . $p1 . "'" . (!$pWild ? "" . " and product_id='" . $p2 . "'" : ""));
        if( $linkId ) 
        {
            $this->_write->delete($t, $this->_write->quoteInto("link_id in (?)", $linkId));
            if( Mage::helper("urapidflow")->hasMageFeature("table.product_relation") ) 
            {
                $this->_write->delete($this->_t("catalog/product_relation"), "" . "parent_id=" . $p1 . (!$pWild ? "" . " and child_id=" . $p2 : ""));
            }

            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPI($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_media_gallery");
        $pId = $this->_getIdBySku($row[1]);
        $imgFilename = $this->_convertEncoding($row[2]);
        $iWild = trim($row[2]) == "*";
        $imgId = $this->_write->fetchPairs("" . "select value_id, value from " . $t . " where attribute_id=" . $this->_getGalleryAttrId() . " and entity_id=" . $pId . (!$iWild ? "" . " and value=" . $this->_write->quote($imgFilename) : ""));
        if( !empty($imgId) ) 
        {
            $this->_write->delete($t, $this->_write->quoteInto("value_id in (?)", array_keys($imgId)));
            $imgToDel = $imgId;
            if( !$this->_deleteOldImageSkipUsageCheck ) 
            {
                $imgNoToDel = $this->_write->fetchCol("" . "select value from " . $t . " where value in (" . $this->_write->quote($imgToDel) . ")");
                if( !empty($imgNoToDel) ) 
                {
                    $imgToDel = array_diff($imgToDel, $imgNoToDel);
                }

            }

            if( !empty($imgToDel) ) 
            {
                foreach( $imgToDel as $_imgToDel ) 
                {
                    @unlink(@@Mage::getsingleton("catalog/product_media_config")->getMediaPath($_imgToDel));
                }
            }

            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPIL($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_media_gallery");
        $tl = $this->_t("catalog/product_attribute_media_gallery_value");
        $pId = $this->_getIdBySku($row[1]);
        $img = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $imgId = $this->_write->fetchOne("" . "select t.value_id from " . $t . " t\n            inner join " . $tl . " tl on tl.value_id=t.value_id\n            where t.attribute_id=" . $this->_getGalleryAttrId() . " and t.entity_id=" . $pId . "\n                and tl.store_id=" . $storeId . " and t.value=" . $this->_write->quote($row[2]));
        if( $imgId ) 
        {
            $this->_write->delete($tl, "" . "value_id=" . $imgId . " and store_id=" . $storeId);
            $count = $this->_write->fetchOne("" . "SELECT count(`value_id`) FROM " . $tl . " WHERE value_id=" . $imgId);
            if( $count == 0 ) 
            {
                $this->_write->delete($t, "" . "value_id=" . $imgId);
            }

            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPPT($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_tier_price");
        $pId = $this->_getIdBySku($row[1]);
        $allGroups = $row[2] === "*" ? 1 : 0;
        $gId = $allGroups ? 0 : $this->_getCustomerGroup($row[2], true);
        $qty = (double) $row[3];
        $wId = isset($row[4]) ? $this->_getWebsiteId($row[4], true) : 0;
        $priceId = $this->_write->fetchOne("" . "select value_id from " . $t . " where entity_id='" . $pId . "' and customer_group_id='" . $gId . "' and qty='" . $qty . "' and website_id='" . $wId . "' and all_groups=" . $allGroups);
        if( $priceId ) 
        {
            $this->_write->delete($t, "" . "value_id='" . $priceId . "'");
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPPG($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("catalog/product_attribute_group_price");
        $pId = $this->_getIdBySku($row[1]);
        $gId = $this->_getCustomerGroup($row[2], true);
        $wId = isset($row[4]) ? $this->_getWebsiteId($row[4], true) : 0;
        $priceId = $this->_write->fetchOne("" . "select value_id from " . $t . " where entity_id='" . $pId . "' and customer_group_id='" . $gId . "' and website_id='" . $wId . "'");
        if( $priceId ) 
        {
            $this->_write->delete($t, "" . "value_id='" . $priceId . "'");
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPD($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/link");
        $tl = $this->_t("downloadable/link_title");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $linkId = $this->_write->fetchOne("" . "select t.link_id from " . $t . " t\n            left join " . $tl . " tl on tl.link_id=t.link_id and tl.store_id=0\n            where t.product_id=" . $pId . " and tl.title=" . $this->_write->quote($title));
        if( $linkId ) 
        {
            $this->_write->delete($t, "" . "link_id=" . $linkId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPDL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/link");
        $tl = $this->_t("downloadable/link_title");
        $pId = $this->_getIdBySku($row[1]);
        $defTitle = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $titleId = $this->_write->fetchOne("" . "select tl.title_id from " . $t . " t\n            inner join " . $tl . " td on td.link_id=t.link_id and td.store_id=0 and td.title=" . $this->_write->quote($defTitle) . "\n            inner join " . $tl . " tl on tl.link_id=t.link_id and tl.store_id=" . $storeId);
        if( $titleId ) 
        {
            $this->_write->delete($t, "" . "title_id=" . $titleId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPDP($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/link");
        $tl = $this->_t("downloadable/link_title");
        $tp = $this->_t("downloadable/link_price");
        $pId = $this->_getIdBySku($row[1]);
        $defTitle = $this->_convertEncoding($row[2]);
        $websiteId = $this->_getWebsiteId($row[3]);
        if( !$websiteId ) 
        {
            $this->_profile->getLogger()->setColumn(4);
            Mage::throwexception("Invalid website");
        }

        $priceId = $this->_write->fetchOne("" . "select tp.price_id from " . $t . " t\n            inner join " . $tl . " td on td.link_id=t.link_id and td.store_id=0 and td.title=" . $this->_write->quote($defTitle) . "\n            inner join " . $tp . " tp on tp.link_id=t.link_id and tp.website_id=" . $websiteId);
        if( $priceId ) 
        {
            $this->_write->delete($t, "" . "price_id=" . $priceId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPDS($row)
    {
        if( sizeof($row) < 3 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/sample");
        $tl = $this->_t("downloadable/sample_title");
        $pId = $this->_getIdBySku($row[1]);
        $title = $this->_convertEncoding($row[2]);
        $sampleId = $this->_write->fetchOne("" . "select t.sample_id from " . $t . " t\n            left join " . $tl . " tl on tl.sample_id=t.sample_id and tl.store_id=0\n            where t.product_id=" . $pId . " and tl.title=" . $this->_write->quote($title));
        if( $sampleId ) 
        {
            $this->_write->delete($t, "" . "sample_id=" . $linkId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _deleteRowCPDSL($row)
    {
        if( sizeof($row) < 4 ) 
        {
            Mage::throwexception($this->__("Invalid row format"));
        }

        $t = $this->_t("downloadable/sample");
        $tl = $this->_t("downloadable/sample_title");
        $pId = $this->_getIdBySku($row[1]);
        $defTitle = $this->_convertEncoding($row[2]);
        $storeId = $this->_getStoreId($row[3]);
        if( $this->_skipStore($storeId, 4) ) 
        {
            return self::IMPORT_ROW_RESULT_NOCHANGE;
        }

        $titleId = $this->_write->fetchOne("" . "select tl.title_id from " . $t . " t\n            inner join " . $tl . " td on td.sample_id=t.sample_id and td.store_id=0 and td.title=" . $this->_write->quote($defTitle) . "\n            inner join " . $tl . " tl on tl.sample_id=t.sample_id and tl.store_id=" . $storeId);
        if( $titleId ) 
        {
            $this->_write->delete($t, "" . "title_id=" . $titleId);
            return self::IMPORT_ROW_RESULT_SUCCESS;
        }

        return self::IMPORT_ROW_RESULT_NOCHANGE;
    }

    protected function _exportInitCP()
    {
    }

    protected function _exportInitCC()
    {
    }

    protected function _exportInitCCP()
    {
        $productTable = $this->_t("catalog/product");
        $categoryTable = $this->_t("catalog/category");
        $categoryProductTable = $this->_t("catalog/category_product");
        $upAttrId = $this->_getAttributeId("url_path", "catalog_category");
        $nameAttrId = $this->_getAttributeId("name", "catalog_category");
        $storeId = $this->_profile->getStoreId();
        $noUrlPath = Mage::helper("urapidflow")->hasMageFeature("no_url_path");
        $this->_upPrependRoot = $this->_profile->getData("options/" . $this->_profile->getProfileType() . "/urlpath_prepend_root");
        if( $storeId ) 
        {
            $this->_rootCatId = Mage::app()->getStore($storeId)->getGroup()->getRootCategoryId();
        }
        else
        {
            if( !$this->_upPrependRoot || $storeId == 0 ) 
            {
                $this->_rootCatId = $this->_read->fetchOne("" . "select g.root_category_id from " . $this->_t("core/website") . " w inner join " . $this->_t("core/store_group") . " g on g.group_id=w.default_group_id where w.is_default=1");
            }

        }

        $rootPath = $this->_rootCatId ? "1/" . $this->_rootCatId : "1";
        if( $this->_upPrependRoot ) 
        {
            $rootCatPathsSel = $this->_read->select()->from(array( "w" => $this->_t("core/website") ), array(  ))->join(array( "g" => $this->_t("core/store_group") ), "g.group_id=w.default_group_id", array(  ))->join(array( "e" => $categoryTable ), "e.entity_id=g.root_category_id", array( "concat('1/',e.entity_id)" ))->join(array( "name" => $categoryTable . "_varchar" ), "" . "name.entity_id=e.entity_id and name.attribute_id=" . $nameAttrId . " and name.value<>'' and name.value is not null and name.store_id=0", array( "value" ))->group("e.entity_id");
            if( $storeId ) 
            {
                $rootCatPathsSel->where("e.entity_id=?", $this->_rootCatId);
            }

            $this->_rootCatPaths = $this->_read->fetchPairs($rootCatPathsSel);
        }

        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "cp" => $categoryProductTable ), "cp.product_id=main.entity_id", array( "position" ))->join(array( "cat" => $categoryTable ), "cp.category_id=cat.entity_id", array( "path" ));
        if( !$noUrlPath ) 
        {
            $this->addUrlPath($categoryTable, $upAttrId, $storeId);
        }
        else
        {
            $this->addEe13UrlPath($categoryTable, $upAttrId, $storeId);
        }

        if( $this->_upPrependRoot && !empty($this->_rootCatPaths) ) 
        {
            $_rcPaths = array(  );
            foreach( $this->_rootCatPaths as $_rcPath => $_rcName ) 
            {
                $_rcPaths[] = $this->_read->quoteInto("path=?", $_rcPath);
                $_rcPaths[] = $this->_read->quoteInto("path like ?", $_rcPath . "/%");
            }
            $this->_select->where(implode(" OR ", $_rcPaths));
        }
        else
        {
            $this->_select->where($this->_read->quoteInto("path=?", $rootPath) . $this->_read->quoteInto(" OR path like ?", $rootPath . "/%"));
        }

        $this->_applyProductFilter();
    }

    protected function _exportLinkSelect($linkType)
    {
        $productTable = $this->_t("catalog/product");
        $linkTable = $this->_t("catalog/product_link");
        $linkAttrTable = $this->_t("catalog/product_link_attribute");
        $linkTypeId = $this->_getLinkTypeId($linkType);
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" => "sku" ))->join(array( "l" => $linkTable ), "l.product_id=main.entity_id", array(  ))->join(array( "lp" => $productTable ), "lp.entity_id=l.linked_product_id", array( "linked_sku" => "sku" ))->where("l.link_type_id=?", $linkTypeId);
        $attrs = $this->_getLinkAttr($linkTypeId);
        foreach( $attrs as $code => $r ) 
        {
            $alias = "a_" . $code;
            $this->_select->joinLeft(array( $alias => "" . $linkAttrTable . "_" . $r["data_type"] ), "" . $alias . ".link_id=l.link_id and " . $alias . ".product_link_attribute_id=" . $r["id"], array( $code => "value" ));
        }
        $this->_applyProductFilter();
    }

    protected function _exportInitCPRI()
    {
        $this->_exportLinkSelect("relation");
    }

    protected function _exportInitCPUI()
    {
        $this->_exportLinkSelect("up_sell");
    }

    protected function _exportInitCPXI()
    {
        $this->_exportLinkSelect("cross_sell");
    }

    protected function _exportInitCPGI()
    {
        $this->_exportLinkSelect("super");
    }

    protected function _exportInitCPCO()
    {
        $productTable = $this->_t("catalog/product");
        $optionTable = $this->_t("catalog/product_option");
        $optionTitleTable = $this->_t("catalog/product_option_title");
        $optionPriceTable = $this->_t("catalog/product_option_price");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "o" => $optionTable ), "o.product_id=main.entity_id", array( "type", "is_require", "option_sku" => "sku", "max_characters", "sort_order" ))->join(array( "ot" => $optionTitleTable ), "ot.option_id=o.option_id and ot.store_id=0", array( "default_title" => "title" ))->joinLeft(array( "op" => $optionPriceTable ), "op.option_id=o.option_id and op.store_id=0", array( "price", "price_type" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title" );
    }

    protected function _exportInitCPCOL()
    {
        $productTable = $this->_t("catalog/product");
        $storeTable = $this->_t("core/store");
        $optionTable = $this->_t("catalog/product_option");
        $optionTitleTable = $this->_t("catalog/product_option_title");
        $optionPriceTable = $this->_t("catalog/product_option_price");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "o" => $optionTable ), "o.product_id=main.entity_id", array(  ))->join(array( "ot" => $optionTitleTable ), "ot.option_id=o.option_id and ot.store_id=0", array( "default_title" => "title" ))->join(array( "otl" => $optionTitleTable ), "otl.option_id=o.option_id and otl.store_id<>0", array( "title" ))->join(array( "s" => $storeTable ), "s.store_id=otl.store_id", array( "store" => "code" ))->joinLeft(array( "opl" => $optionPriceTable ), "opl.option_id=o.option_id and opl.store_id=otl.store_id", array( "price", "price_type" ))->where("otl.option_id is not null or opl.option_id is not null");
        if( $this->_getStoreIds() ) 
        {
            $this->_select->where("otl.store_id in (?)", $this->_getStoreIds());
        }

        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title", "title" );
    }

    protected function _exportInitCPCOS()
    {
        $productTable = $this->_t("catalog/product");
        $optionTable = $this->_t("catalog/product_option");
        $optionTitleTable = $this->_t("catalog/product_option_title");
        $optionSelTable = $this->_t("catalog/product_option_type_value");
        $optionSelTitleTable = $this->_t("catalog/product_option_type_title");
        $optionSelPriceTable = $this->_t("catalog/product_option_type_price");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "o" => $optionTable ), "o.product_id=main.entity_id", array(  ))->join(array( "ot" => $optionTitleTable ), "ot.option_id=o.option_id and ot.store_id=0", array( "default_title" => "title" ))->join(array( "os" => $optionSelTable ), "os.option_id=o.option_id", array( "selection_sku" => "sku", "sort_order" ))->join(array( "ost" => $optionSelTitleTable ), "ost.option_type_id=os.option_type_id and ost.store_id=0", array( "selection_default_title" => "title" ))->joinLeft(array( "osp" => $optionSelPriceTable ), "osp.option_type_id=os.option_type_id and osp.store_id=0", array( "price", "price_type" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title", "selection_default_title" );
    }

    protected function _exportInitCPCOSL()
    {
        $productTable = $this->_t("catalog/product");
        $optionTable = $this->_t("catalog/product_option");
        $storeTable = $this->_t("core/store");
        $optionTitleTable = $this->_t("catalog/product_option_title");
        $optionSelTable = $this->_t("catalog/product_option_type_value");
        $optionSelTitleTable = $this->_t("catalog/product_option_type_title");
        $optionSelPriceTable = $this->_t("catalog/product_option_type_price");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "o" => $optionTable ), "o.product_id=main.entity_id", array(  ))->join(array( "ot" => $optionTitleTable ), "ot.option_id=o.option_id and ot.store_id=0", array( "default_title" => "title" ))->join(array( "os" => $optionSelTable ), "os.option_id=o.option_id", array(  ))->join(array( "ost" => $optionSelTitleTable ), "ost.option_type_id=os.option_type_id and ost.store_id=0", array( "selection_default_title" => "title" ))->join(array( "ostl" => $optionSelTitleTable ), "ostl.option_type_id=os.option_type_id and ostl.store_id<>0", array( "title" ))->join(array( "s" => $storeTable ), "s.store_id=ostl.store_id", array( "store" => "code" ))->joinLeft(array( "ospl" => $optionSelPriceTable ), "ospl.option_type_id=os.option_type_id and ospl.store_id=ostl.store_id", array( "price", "price_type" ));
        if( $this->_getStoreIds() ) 
        {
            $this->_select->where("ostl.store_id in (?)", $this->_getStoreIds());
        }

        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title", "selection_default_title", "title" );
    }

    protected function _exportInitCPBO()
    {
        $productTable = $this->_t("catalog/product");
        $bundleOptionTable = $this->_t("bundle/option");
        $bundleOptionValueTable = $this->_t("bundle/option_value");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "bo" => $bundleOptionTable ), "bo.parent_id=main.entity_id", array( "required", "position", "type" ))->join(array( "bov" => $bundleOptionValueTable ), "bov.option_id=bo.option_id and store_id=0", array( "default_title" => "title" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title" );
    }

    protected function _exportInitCPBOL()
    {
        $productTable = $this->_t("catalog/product");
        $storeTable = $this->_t("core/store");
        $bundleOptionTable = $this->_t("bundle/option");
        $bundleOptionValueTable = $this->_t("bundle/option_value");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "bo" => $bundleOptionTable ), "bo.parent_id=main.entity_id", array(  ))->join(array( "bov" => $bundleOptionValueTable ), "bov.option_id=bo.option_id and bov.store_id=0", array( "default_title" => "title" ))->join(array( "bovl" => $bundleOptionValueTable ), "bovl.option_id=bo.option_id and bovl.store_id<>0", array( "title" => "title" ))->join(array( "s" => $storeTable ), "s.store_id=bovl.store_id", array( "store" => "code" ));
        if( $this->_getStoreIds() ) 
        {
            $this->_select->where("bovl.store_id in (?)", $this->_getStoreIds());
        }

        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title", "title" );
    }

    protected function _exportInitCPBOS()
    {
        $productTable = $this->_t("catalog/product");
        $bundleOptionTable = $this->_t("bundle/option");
        $bundleOptionValueTable = $this->_t("bundle/option_value");
        $bundleSelectionTable = $this->_t("bundle/selection");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "bo" => $bundleOptionTable ), "bo.parent_id=main.entity_id", array(  ))->join(array( "bov" => $bundleOptionValueTable ), "bov.option_id=bo.option_id and bov.store_id=0", array( "default_title" => "title" ))->join(array( "bos" => $bundleSelectionTable ), "bos.option_id=bo.option_id", array( "position", "is_default", "selection_price_type", "selection_price_value", "selection_qty", "selection_can_change_qty" ))->join(array( "bp" => $productTable ), "bp.entity_id=bos.product_id", array( "selection_sku" => "sku" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title" );
    }

    protected function _exportInitCPSA()
    {
        $productTable = $this->_t("catalog/product");
        $attrTable = $this->_t("eav/attribute");
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superLabelTable = $this->_t("catalog/product_super_attribute_label");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "sa" => $superAttrTable ), "sa.product_id=main.entity_id", array( "position" ))->join(array( "a" => $attrTable ), "a.attribute_id=sa.attribute_id", array( "attribute_code" ))->join(array( "sl" => $superLabelTable ), "sl.product_super_attribute_id=sa.product_super_attribute_id and store_id=0", array( "label" => "value" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "label" );
    }

    protected function _exportInitCPSAL()
    {
        $storeIds = join(",", $this->_getStoreIds());
        $productTable = $this->_t("catalog/product");
        $attrTable = $this->_t("eav/attribute");
        $storeTable = $this->_t("core/store");
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superLabelTable = $this->_t("catalog/product_super_attribute_label");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "sa" => $superAttrTable ), "sa.product_id=main.entity_id", array(  ))->join(array( "a" => $attrTable ), "a.attribute_id=sa.attribute_id", array( "attribute_code" ))->join(array( "sl" => $superLabelTable ), "sl.product_super_attribute_id=sa.product_super_attribute_id and sl.store_id<>0", array( "label" => "value" ))->join(array( "s" => $storeTable ), "s.store_id=sl.store_id", array( "store" => "code" ));
        if( $this->_getStoreIds() ) 
        {
            $this->_select->where("sl.store_id in (?)", $this->_getStoreIds());
        }

        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "label" );
    }

    protected function _exportInitCPSAP()
    {
        $wIds = array( 0 );
        foreach( $this->_getStoreIds() as $sId ) 
        {
            $wId = Mage::app()->getStore($sId)->getWebsiteId();
            $wIds[$wId] = $wId;
        }
        $websiteIds = join(",", $wIds);
        $productTable = $this->_t("catalog/product");
        $attrTable = $this->_t("eav/attribute");
        $websiteTable = $this->_t("core/website");
        $superAttrTable = $this->_t("catalog/product_super_attribute");
        $superPricingTable = $this->_t("catalog/product_super_attribute_pricing");
        $w = Mage::helper("urapidflow")->hasMageFeature("cpsap.website_id");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "sa" => $superAttrTable ), "sa.product_id=main.entity_id", array(  ))->join(array( "a" => $attrTable ), "a.attribute_id=sa.attribute_id", array( "attribute_code" ))->join(array( "sp" => $superPricingTable ), "sp.product_super_attribute_id=sa.product_super_attribute_id\n                " . ($w ? "" . "and sp.website_id in (" . $websiteIds . ")" : ""), array( "value_index", "is_percent", "pricing_value" ));
        if( $w ) 
        {
            $this->_select->join(array( "w" => $websiteTable ), "w.website_id=sp.website_id", array( "website" => "code" ));
        }

        $this->_applyProductFilter();
    }

    protected function _exportCallbackLoadAttributeOptions(&$row)
    {
        $attrCode = $row["attribute_code"];
        $this->_fetchAttributeOptions($attrCode);
        $aId = $this->_getAttributeId($attrCode);
        if( !isset($this->_attrOptionsByValue[$aId][$row["value_index"]]) ) 
        {
            Mage::throwexception("Invalid attribute option value: " . $attrCode . ", " . $row["value_index"]);
        }

        $row["value_label"] = $this->_attrOptionsByValue[$aId][$row["value_index"]];
        if( !isset($row["website"]) ) 
        {
            $row["website"] = "";
        }

        return true;
    }

    protected function _exportInitCPSI()
    {
        $productTable = $this->_t("catalog/product");
        $superLinkTable = $this->_t("catalog/product_super_link");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "sl" => $superLinkTable ), "sl.parent_id=main.entity_id", array(  ))->join(array( "l" => $productTable ), "l.entity_id=sl.product_id", array( "linked_sku" => "sku" ));
        $this->_applyProductFilter();
    }

    protected function _exportCallbackCPI($row)
    {
        if( $this->_processImageFiles && !empty($row["image_url"]) ) 
        {
            $this->_profile->getLogger()->setColumn(3);
            $this->_copyImageFile($this->_imagesMediaDir, $this->_imagesTargetDir, $row["image_url"]);
        }

        return true;
    }

    protected function _exportInitCPI()
    {
        $productTable = $this->_t("catalog/product");
        $galleryTable = $this->_t("catalog/product_attribute_media_gallery");
        $galleryStoreTable = $this->_t("catalog/product_attribute_media_gallery_value");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "g" => $galleryTable ), "g.entity_id=main.entity_id", array( "image_url" => "value" ))->joinLeft(array( "gl" => $galleryStoreTable ), "gl.value_id=g.value_id", array( "label", "position", "disabled" ))->where("gl.store_id=0 or gl.store_id is null");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "image_url", "label" );
    }

    protected function _exportInitCPIL()
    {
        $storeTable = $this->_t("core/store");
        $productTable = $this->_t("catalog/product");
        $galleryTable = $this->_t("catalog/product_attribute_media_gallery");
        $galleryStoreTable = $this->_t("catalog/product_attribute_media_gallery_value");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "g" => $galleryTable ), "g.entity_id=main.entity_id", array( "image_url" => "value" ))->join(array( "gl" => $galleryStoreTable ), "gl.value_id=g.value_id", array( "label", "position", "disabled" ))->join(array( "s" => $storeTable ), "s.store_id=gl.store_id", array( "store" => "code" ))->where("gl.store_id<>0");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "image_url", "label" );
    }

    protected function _exportCallbackCCP(&$row)
    {
        $noUrlPath = Mage::helper("urapidflow")->hasMageFeature("no_url_path");
        if( $noUrlPath ) 
        {
            $value = $this->catBuildPath($row, $this->_catEntities);
            $row["url_path"] = $value;
        }

        $row["url_path"] = $this->_upPrependRoot($row, $row["url_path"]);
        return true;
    }

    protected function _exportCallbackCPPT(&$row)
    {
        if( $row["all_groups"] ) 
        {
            $row["customer_group"] = "*";
        }

        return true;
    }

    protected function _exportInitCPPT()
    {
        $productTable = $this->_t("catalog/product");
        $priceTable = $this->_t("catalog/product_attribute_tier_price");
        $cGroupTable = $this->_t("customer/customer_group");
        $websiteTable = $this->_t("core/website");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "tp" => $priceTable ), "tp.entity_id=main.entity_id", array( "qty", "price" => "value", "all_groups" ))->join(array( "cg" => $cGroupTable ), "cg.customer_group_id=tp.customer_group_id", array( "customer_group" => "customer_group_code" ))->join(array( "w" => $websiteTable ), "w.website_id=tp.website_id", array( "website" => "code" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "customer_group" );
    }

    protected function _exportInitCPPG()
    {
        $productTable = $this->_t("catalog/product");
        $priceTable = $this->_t("catalog/product_attribute_group_price");
        if( empty($priceTable) ) 
        {
            return NULL;
        }

        $cGroupTable = $this->_t("customer/customer_group");
        $websiteTable = $this->_t("core/website");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "gp" => $priceTable ), "gp.entity_id=main.entity_id", array( "price" => "value" ))->join(array( "cg" => $cGroupTable ), "cg.customer_group_id=gp.customer_group_id", array( "customer_group" => "customer_group_code" ))->join(array( "w" => $websiteTable ), "w.website_id=gp.website_id", array( "website" => "code" ));
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "customer_group" );
    }

    protected function _exportInitCPD()
    {
        $productTable = $this->_t("catalog/product");
        $dTable = $this->_t("downloadable/link");
        $dtTable = $this->_t("downloadable/link_title");
        $dpTable = $this->_t("downloadable/link_price");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "d" => $dTable ), "d.product_id=main.entity_id", array( "sort_order", "max_downloads" => "number_of_downloads", "is_shareable", "link_url", "link_file", "link_type", "sample_url", "sample_file", "sample_type" ))->join(array( "dt" => $dtTable ), "dt.link_id=d.link_id", array( "title" ))->join(array( "dp" => $dpTable ), "dp.link_id=d.link_id", array( "price" ))->where("dt.store_id=0 and dp.website_id=0");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "title" );
    }

    protected function _exportInitCPDL()
    {
        $productTable = $this->_t("catalog/product");
        $dTable = $this->_t("downloadable/link");
        $dtTable = $this->_t("downloadable/link_title");
        $storeTable = $this->_t("core/store");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "d" => $dTable ), "d.product_id=main.entity_id", array(  ))->join(array( "dt" => $dtTable ), "dt.link_id=d.link_id", array( "default_title" => "title" ))->join(array( "dl" => $dtTable ), "dl.link_id=d.link_id", array( "title" ))->join(array( "s" => $storeTable ), "s.store_id=dl.store_id", array( "store" => "code" ))->where("dt.store_id=0 and dl.store_id<>0");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title", "title" );
    }

    protected function _exportInitCPDP()
    {
        $productTable = $this->_t("catalog/product");
        $dTable = $this->_t("downloadable/link");
        $dtTable = $this->_t("downloadable/link_title");
        $dpTable = $this->_t("downloadable/link_price");
        $websiteTable = $this->_t("core/website");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "d" => $dTable ), "d.product_id=main.entity_id", array(  ))->join(array( "dt" => $dtTable ), "dt.link_id=d.link_id", array( "default_title" => "title" ))->join(array( "dp" => $dpTable ), "dp.link_id=d.link_id", array( "price" ))->join(array( "w" => $websiteTable ), "w.website_id=dp.website_id", array( "website" => "code" ))->where("dt.store_id=0 and dp.website_id<>0");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title" );
    }

    protected function _exportInitCPDS()
    {
        $productTable = $this->_t("catalog/product");
        $dTable = $this->_t("downloadable/sample");
        $dtTable = $this->_t("downloadable/sample_title");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "d" => $dTable ), "d.product_id=main.entity_id", array( "sort_order", "sample_url", "sample_file", "sample_type" ))->join(array( "dt" => $dtTable ), "dt.sample_id=d.sample_id", array( "title" ))->where("dt.store_id=0");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "title" );
    }

    protected function _exportInitCPDSL()
    {
        $productTable = $this->_t("catalog/product");
        $dTable = $this->_t("downloadable/sample");
        $dtTable = $this->_t("downloadable/sample_title");
        $storeTable = $this->_t("core/store");
        $this->_select = $this->_read->select()->from(array( "main" => $productTable ), array( "sku" ))->join(array( "d" => $dTable ), "d.product_id=main.entity_id", array( "sort_order", "sample_url", "sample_file", "sample_type" ))->join(array( "dt" => $dtTable ), "dt.sample_id=d.sample_id", array( "default_title" => "title" ))->join(array( "dl" => $dtTable ), "dl.sample_id=d.sample_id", array( "title" ))->join(array( "s" => $storeTable ), "s.store_id=dl.store_id", array( "store" => "code" ))->where("dt.store_id=0 and dl.store_id<>0");
        $this->_applyProductFilter();
        $this->_exportConvertFields = array( "default_title", "title" );
    }

    protected function _getLinkTypeId($linkType)
    {
        if( empty($this->_linkTypes) ) 
        {
            $s = $this->_read->select()->from($this->_t("catalog/product_link_type"), array( "code", "link_type_id" ));
            $this->_linkTypes = $this->_read->fetchPairs($s);
        }

        if( empty($this->_linkTypes[$linkType]) ) 
        {
            Mage::throwexception($this->__("Invalid product link type (%s)", $linkType));
        }

        return $this->_linkTypes[$linkType];
    }

    protected function _getLinkAttr($linkType, $linkAttr = null, $param = null)
    {
        if( !is_null($linkType) ) 
        {
            $linkTypeId = is_numeric($linkType) ? $linkType : $this->_getLinkTypeId($linkType);
        }

        if( empty($this->_linkAttrs) ) 
        {
            $attrs = $this->_read->fetchAll("" . "select * from " . $this->_t("catalog/product_link_attribute"));
            foreach( $attrs as $a ) 
            {
                $this->_linkAttrs[$a["link_type_id"]][$a["product_link_attribute_code"]] = array( "id" => $a["product_link_attribute_id"], "code" => $a["product_link_attribute_code"], "data_type" => $a["data_type"] );
                $this->_linkAttrIds[$a["product_link_attribute_id"]] = $a["product_link_attribute_code"];
            }
        }

        if( is_null($linkType) ) 
        {
            return $this->_linkAttrs;
        }

        if( empty($this->_linkAttrs[$linkTypeId]) ) 
        {
            Mage::throwexception($this->__("Invalid product link type (%s)", $linkType));
        }

        if( is_null($linkAttr) ) 
        {
            return $this->_linkAttrs[$linkTypeId];
        }

        if( is_numeric($linkAttr) && !empty($this->_linkAttrIds[$linkAttr]) ) 
        {
            $linkAttr = $this->_linkAttrIds[$linkAttr];
        }

        if( empty($this->_linkAttrs[$linkTypeId][$linkAttr]) ) 
        {
            Mage::throwexception($this->__("Invalid product link attribute (%s, %s)", $linkType, $linkAttr));
        }

        $attr = $this->_linkAttrs[$linkTypeId][$linkAttr];
        return is_null($param) ? $attr : $attr[$param];
    }

    protected function _upPrependRoot($row, $value)
    {
        if( $this->_upPrependRoot ) 
        {
            $_rootCat = explode("/", $row["path"], 3);
            unset($_rootCat[2]);
            $_rootCat = implode("/", $_rootCat);
            if( isset($this->_rootCatPaths[$_rootCat]) ) 
            {
                if( empty($value) ) 
                {
                    $value = $this->_rootCatPaths[$_rootCat];
                }
                else
                {
                    $value = $this->_rootCatPaths[$_rootCat] . "/" . $value;
                }

            }

        }

        return $value;
    }

    protected function addEe13UrlPath($categoryTable, $upAttrId, $storeId)
    {
        $this->_select->joinLeft(array( "catup" => $categoryTable . "_varchar" ), "" . "catup.entity_id=cat.entity_id and catup.attribute_id=" . $upAttrId . " and catup.store_id=0", array(  ));
        if( $storeId != 0 ) 
        {
            $this->_select->joinLeft(array( "catups" => $categoryTable . "_varchar" ), "" . "catups.entity_id=cat.entity_id and catups.attribute_id=" . $upAttrId . " and catups.store_id='" . $storeId . "'", array(  ));
            $this->_select->columns(array( "url_path" => "IFNULL(catups.value, catup.value)" ));
        }
        else
        {
            $this->_select->columns(array( "url_path" => "catup.value" ));
        }

        $ukAttrId = $this->_getAttributeId("url_key", "catalog_category");
        $this->_select->joinLeft(array( "catupk" => $categoryTable . "_url_key" ), "" . "catupk.entity_id=cat.entity_id and catupk.attribute_id=" . $ukAttrId . "\n                              and catupk.store_id=0", array(  ));
        if( $storeId != 0 ) 
        {
            $this->_select->joinLeft(array( "catupks" => $categoryTable . "_url_key" ), "" . "catupks.entity_id=cat.entity_id and catupks.attribute_id=" . $ukAttrId . "\n                                  and catupks.store_id=" . $storeId, array(  ));
            $this->_select->columns(array( "url_key" => "IFNULL(catupks.value, catupk.value)" ));
        }
        else
        {
            $this->_select->columns(array( "url_key" => "catupk.value" ));
        }

        $select = $this->_write->select()->from(array( "e" => $categoryTable ), array( "entity_id", "path" ))->join(array( "up" => $categoryTable . "_url_key" ), "" . "up.entity_id=e.entity_id and up.attribute_id=" . $ukAttrId . " and up.store_id=0", array(  ));
        if( $storeId != 0 ) 
        {
            $select->joinLeft(array( "ups" => $categoryTable . "_url_key" ), "" . "ups.entity_id=e.entity_id and ups.attribute_id=" . $ukAttrId . " and ups.store_id=" . $storeId, array(  ));
            $select->columns(array( "url_key" => "IFNULL(ups.value, up.value)" ));
        }
        else
        {
            $select->columns(array( "url_key" => "up.value" ));
        }

        Mage::log((bool) $select, null, "rf.log", true);
        $catEntities = $this->_read->fetchAll($select);
        foreach( $catEntities as $entity ) 
        {
            $this->_catEntities[$entity["entity_id"]] = $entity;
        }
    }

    protected function addUrlPath($categoryTable, $upAttrId, $storeId)
    {
        $this->_select->join(array( "catup" => $categoryTable . "_varchar" ), "" . "catup.entity_id=cat.entity_id and catup.attribute_id=" . $upAttrId . "\n                                and catup.value<>'' and catup.value is not null and catup.store_id=0", array(  ));
        if( $storeId != 0 ) 
        {
            $this->_select->joinLeft(array( "catups" => $categoryTable . "_varchar" ), "" . "catups.entity_id=cat.entity_id and catups.attribute_id=" . $upAttrId . "\n                                    and catups.value<>'' and catup.value is not null and catups.store_id='" . $storeId . "'", array(  ));
            $this->_select->columns(array( "url_path" => "IFNULL(catups.value, catup.value)" ));
        }
        else
        {
            $this->_select->columns(array( "url_path" => "catup.value" ));
        }

    }

}


