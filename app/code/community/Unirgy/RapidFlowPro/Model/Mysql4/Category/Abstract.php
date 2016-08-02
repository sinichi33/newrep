<?php 

class Unirgy_RapidFlowPro_Model_Mysql4_Category_Abstract extends Unirgy_RapidFlow_Model_Mysql4_Catalog_Abstract
{
    protected $_translateModule = "Unirgy_RapidFlowPro";
    protected $_entityType = "catalog_category";
    protected $_entityTypeId = NULL;
    protected $_attributeSetId = NULL;
    protected $_pathSuffix = NULL;
    protected $_rootCatId = NULL;
    protected $_rootCatIds = NULL;
    protected $_upPrependRoot = NULL;
    protected $_attributesById = array(  );
    protected $_attributesByCode = array(  );
    protected $_attributesByType = array(  );
    protected $_categories = array(  );
    protected $_categoriesByNamePath = array(  );
    protected $_attributeSetFields = array(  );
    protected $_attrDepends = array(  );
    protected $_attrJoined = array(  );
    protected $_storeId = NULL;
    protected $_websiteStores = array(  );
    protected $_websitesByStore = array(  );
    protected $_storesByWebsite = array(  );
    protected $_fields = array(  );
    protected $_fieldsCodes = array(  );
    protected $_entities = array(  );
    protected $_entityIds = array(  );
    protected $_entityIdsUpdated = array(  );
    protected $_defaultUsed = array(  );
    protected $_valid = array(  );
    protected $_pathLine = array(  );
    protected $_attrValueIds = array(  );
    protected $_attrValuesFetched = array(  );
    protected $_websiteScope = array(  );
    protected $_websiteScopeProducts = array(  );
    protected $_websiteScopeAttributes = array(  );
    protected $_insertEntity = array(  );
    protected $_updateEntity = array(  );
    protected $_changeAttr = array(  );
    protected $_insertAttr = array(  );
    protected $_updateAttr = array(  );
    protected $_deleteAttr = array(  );
    protected $_childrenCount = array(  );
    protected $_changeChildrenCount = array(  );
    protected $_urlPaths = array(  );
    protected $_parentPath = array(  );
    protected $_parentPathExtra = array(  );
    protected $_newDataTemplate = array(  );
    protected $_newData = array(  );
    protected $_pathIdx = null;
    protected $_startLine = NULL;
    protected $_isLastPage = false;
    protected $_fieldAttributes = array( "product.attribute_set" => "attribute_set_id", "product.type" => "type_id", "product.store" => "store_id", "category.entity_id" => "entity_id" );

    protected function _importPrepareColumns()
    {
        Unirgy_RapidFlow_Model_Mysql4_Catalog_Product_Abstract::validatelicense("Unirgy_RapidFlowPro");
        $profile = $this->_profile;
        $columns = (array) $profile->getColumns();
        $attrs = array(  );
        $dups = array(  );
        $alias = array(  );
        $this->_fields = array(  );
        $this->_newDataTemplate = array(  );
        $this->_fieldsCodes = array( "url_key" => 0 );
        foreach( $columns as $i => &$f ) 
        {
            if( !empty($f["alias"]) ) 
            {
                $aliasKey = strtolower(trim($f["alias"]));
                if( !isset($alias[$aliasKey]) ) 
                {
                    $alias[$aliasKey] = $f["field"];
                }
                else
                {
                    if( !is_array($alias[$aliasKey]) && $alias[$aliasKey] != $f["field"] ) 
                    {
                        $alias[$aliasKey] = array( $alias[$aliasKey], $f["field"] );
                    }
                    else
                    {
                        if( !in_array($f["field"], $alias[$aliasKey]) ) 
                        {
                            $alias[$aliasKey][] = $f["field"];
                        }

                    }

                }

            }

            if( !in_array($f["field"], array( "const.value", "const.function" )) && !empty($attrs[$f["field"]]) ) 
            {
                $dups[$f["field"]] = $f["field"];
            }

            $this->_fields[$f["field"]] = $f;
            $this->_fieldsCodes[$f["field"]] = 0;
            if( isset($f["default"]) && $f["default"] !== "" ) 
            {
                if( !empty($f["default_multiselect"]) ) 
                {
                    $f["default"] = explode(",", $f["default"]);
                }

                $this->_newDataTemplate[$f["field"]] = $f["default"];
            }

        }
        unset($f);
        if( $dups ) 
        {
            throw new Unirgy_RapidFlow_Exception($this->__("Duplicate attributes: %s", join(", ", $dups)));
        }

        $headers = $profile->ioRead();
        if( !$headers ) 
        {
            $profile->ioClose();
        }
        else
        {
            $this->_fieldsIdx = array(  );
            foreach( $headers as $i => $f ) 
            {
                if( $f === "" ) 
                {
                    $this->_fieldsIdx[$i] = false;
                    $profile->addValue("num_warnings");
                    $profile->getLogger()->setLine(2)->setColumn($i + 1)->warning($this->__("Empty title, the column will be ignored"));
                    continue;
                }

                $f = strtolower($f);
                $f = !empty($alias[$f]) ? $alias[$f] : $f;
                $this->_fieldsIdx[$i] = $f;
                foreach( (array) $f as $_ff ) 
                {
                    $this->_fieldsCodes[$_ff] = $i;
                }
            }
            if( !isset($this->_fieldsCodes["url_path"]) ) 
            {
                $profile->ioClose();
                throw new Unirgy_RapidFlow_Exception($this->__("Missing url_path column"));
            }

            $this->_pathIdx = $this->_fieldsCodes["url_path"];
        }

    }

    protected function _prepareAttributes($columns = null)
    {
        Unirgy_RapidFlow_Model_Mysql4_Catalog_Product_Abstract::validatelicense("Unirgy_RapidFlowPro");
        $this->_attributesById = array(  );
        $this->_attributesByCode = array(  );
        $this->_attributesByType = array(  );
        $storeId = $this->_profile->getStoreId();
        $removeFields = array( "children", "children_count", "all_children", "path_in_store", "path", "parent_id", "level" );
        if( $this->_profile->getProfileType() == "import" ) 
        {
            $removeFields = array_merge($removeFields, array( "created_at", "updated_at" ));
        }

        $select = $this->_read->select()->from(array( "a" => $this->_t("eav/attribute") ))->where("entity_type_id=?", $this->_entityTypeId)->where("frontend_input <> 'gallery' OR frontend_input IS NULL")->where("attribute_code not in (?)", $removeFields);
        if( $columns ) 
        {
            $attrCodes = array(  );
            foreach( $columns as $f ) 
            {
                if( strpos($f, ".") === false ) 
                {
                    $attrCodes[$f] = $f;
                }

                if( is_array($f) && !empty($this->_attrDepends[$f["field"]]) ) 
                {
                    foreach( (array) $this->_attrDepends[$f["field"]] as $v ) 
                    {
                        $attrCodes[$v] = $v;
                    }
                }

            }
            $attrCodes[] = "url_key";
            array_unique($attrCodes);
            $select->where("is_required=1 or default_value<>'' or attribute_code in (?)", $attrCodes);
            if( $catalogAttrTable = $this->_t("catalog/eav_attribute") ) 
            {
                $select->join(array( "c" => $catalogAttrTable ), "c.attribute_id=a.attribute_id");
            }

        }

        $rows = $this->_read->fetchAll($select);
        $catTable = $this->_t("catalog/category");
        foreach( $rows as $r ) 
        {
            $a = array(  );
            if( !empty($r["apply_to"]) ) 
            {
                foreach( explode(",", $r["apply_to"]) as $t ) 
                {
                    $a[$t] = true;
                }
            }

            $r["apply_to"] = $a;
            if( $r["default_value"] !== "" && !isset($this->_newDataTemplate[$r["attribute_code"]]) ) 
            {
                $this->_newDataTemplate[$r["attribute_code"]] = $r["default_value"];
            }

            if( !empty($r["source_model"]) && $r["source_model"] != "eav/entity_attribute_source_table" ) 
            {
                $model = Mage::getsingleton($r["source_model"]);
                if( $model && is_callable(array( $model, "getAllOptions" )) && ($options = $model->getAllOptions()) ) 
                {
                    $r["options"] = array(  );
                    foreach( $options as $o ) 
                    {
                        if( is_array($o["value"]) ) 
                        {
                            foreach( $o["value"] as $o1 ) 
                            {
                                $r["options"][$o1["value"]] = $o["label"] . " - " . $o1["label"];
                                $r["options_bytext"][strtolower(trim($o["label"] . " - " . $o1["label"]))] = $o1["value"];
                            }
                            continue;
                        }

                        $r["options"][$o["value"]] = $o["label"];
                        $r["options_bytext"][strtolower(trim($o["label"]))] = $o["value"];
                    }
                }

            }

            $this->_attributesById[$r["attribute_id"]] = $r;
            $this->_attributesByCode[$r["attribute_code"]] =& $this->_attributesById[$r["attribute_id"]];
            $aType = $this->getAttrType($r, "catalog/category");
            $this->_attributesByType[$aType][$r["attribute_id"]] =& $this->_attributesById[$r["attribute_id"]];
        }
        $sql = $this->_read->quoteInto("" . "select o.attribute_id, o.option_id, v.value from " . $this->_t("eav/attribute_option_value") . " v inner join " . $this->_t("eav/attribute_option") . " o using(option_id) where v.store_id in (0, " . $storeId . ") and o.attribute_id in (?) order by v.store_id desc", array_keys($this->_attributesById));
        $rows = $this->_read->fetchAll($sql);
        if( $rows ) 
        {
            foreach( $rows as $r ) 
            {
                if( empty($this->_attributesById[$r["attribute_id"]]["options"][$r["option_id"]]) ) 
                {
                    $this->_attributesById[$r["attribute_id"]]["options"][$r["option_id"]] = $r["value"];
                    $this->_attributesById[$r["attribute_id"]]["options_bytext"][strtolower(trim($r["value"]))] = $r["option_id"];
                }

            }
        }

    }

    protected function _prepareSystemAttributes()
    {
        foreach( array( "custom_design_apply", "available_sort_by", "landing_page", "custom_design", "default_sort_by", "page_layout" ) as $k ) 
        {
            if( !empty($this->_attributesByCode[$k]["is_required"]) ) 
            {
                $this->_attributesByCode[$k]["is_required"] = false;
            }

        }
        $this->_attributesByCode["custom_design_apply"]["options_bytext"]["all"] = 1;
        if( !isset($this->_profile) || $this->_profile->getProfileType() == "export" ) 
        {
            $this->_attributesByCode["category.entity_id"] = array( "frontend_label" => "Entity ID", "frontend_input" => "text", "backend_type" => "static", "force_field" => "entity_id" );
        }

    }

    protected function _importValidateColumns()
    {
        $profile = $this->_profile;
        $logger = $profile->getLogger();
        foreach( $this->_fieldsIdx as $i => $f ) 
        {
            if( $f === false ) 
            {
                continue;
            }

            if( !isset($this->_attributesByCode[$f]) ) 
            {
                $profile->addValue("num_warnings");
                $logger->setLine(2)->setColumn($i + 1)->warning($this->__("Unknown field, the column will be ignored"));
            }

        }
    }

    protected function _prepareCategories()
    {
        Unirgy_RapidFlow_Model_Mysql4_Catalog_Product_Abstract::validatelicense("Unirgy_RapidFlowPro");
        $storeId = $this->_profile->getStoreId();
        $suffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $storeId);
        if( strpos($suffix, ".") !== 0 ) 
        {
            $suffix = "." . $suffix;
        }

        $suffixLen = strlen($suffix);
        $this->_upPrependRoot = $this->_profile->getData("options/import/urlpath_prepend_root");
        $table = $this->_t("catalog/category");
        $eav = Mage::getsingleton("eav/config");
        $rootCatId = $this->_getRootCatId();
        $rootPath = $rootCatId ? "1/" . $rootCatId : "1";
        if( $this->_upPrependRoot ) 
        {
            $nameAttrId = $this->_attr("name", "attribute_id");
            $rootCatPathsSel = $this->_read->select()->from(array( "w" => $this->_t("core/website") ), array(  ))->join(array( "g" => $this->_t("core/store_group") ), "g.group_id=w.default_group_id", array(  ))->join(array( "e" => $table ), "e.entity_id=g.root_category_id", array( "concat('1/',e.entity_id)" ))->join(array( "name" => $table . "_varchar" ), "" . "name.entity_id=e.entity_id and name.attribute_id=" . $nameAttrId . " and name.value<>'' and name.value is not null and name.store_id=0", array( "value" ))->group("e.entity_id");
            if( $storeId ) 
            {
                $rootCatPathsSel->where("e.entity_id=?", $rootCatId);
            }

            $this->_rootCatPaths = $this->_read->fetchPairs($rootCatPathsSel);
        }

        $_rcPaths = array(  );
        if( $this->_upPrependRoot && !empty($this->_rootCatPaths) ) 
        {
            foreach( $this->_rootCatPaths as $_rcPath => $_rcName ) 
            {
                $_rcPaths[] = $this->_read->quoteInto("path=?", $_rcPath);
                $_rcPaths[] = $this->_read->quoteInto("path like ?", $_rcPath . "/%");
            }
        }
        else
        {
            $_rcPaths[] = $this->_read->quoteInto("path=?", $rootPath);
            $_rcPaths[] = $this->_read->quoteInto("path like ?", $rootPath . "/%");
        }

        $sql = "" . "select entity_id, path, children_count from " . $table . " where entity_id=1 or " . implode(" OR ", $_rcPaths);
        $rows = $this->_read->fetchAll($sql);
        if( $rows ) 
        {
            $this->_categories = array(  );
            foreach( $rows as $r ) 
            {
                $this->_categories[$r["entity_id"]] = $r;
            }
            $rows = $this->getUrlPaths($storeId, $table);
            foreach( $rows as $r ) 
            {
                if( empty($this->_categories[$r["entity_id"]][$r["attribute_code"]]) ) 
                {
                    $this->_categories[$r["entity_id"]][$r["attribute_code"]] = $r["value"];
                }

            }
            foreach( $this->_categories as $id => &$c ) 
            {
                $c["url_path"] = $this->_upPrependRoot($c, $c["url_path"]);
                if( empty($c["url_path"]) ) 
                {
                    $this->_childrenCount[$id] = $c["children_count"];
                    continue;
                }

                $this->_urlPaths[$c["url_path"]] = $id;
                $this->_childrenCount[$c["url_path"]] = $c["children_count"];
                if( $suffix ) 
                {
                    $additionalKey = substr($c["url_path"], 0 - $suffixLen) == $suffix ? substr($c["url_path"], 0, strlen($c["url_path"]) - $suffixLen) : $c["url_path"] . $suffix;
                    $this->_urlPaths[$additionalKey] = $id;
                    $this->_childrenCount[$additionalKey] = $c["children_count"];
                }

            }
            unset($c);
        }

    }

    protected function _importFetchNewData()
    {
        $profile = $this->_profile;
        $logger = $profile->getLogger();
        $defaultSeparator = $profile->getData("options/csv/multivalue_separator");
        if( !$defaultSeparator ) 
        {
            $defaultSeparator = ";";
        }

        $this->_newData = array(  );
        for( $i1 = 0; $i1 < $this->_pageRowCount; $i1++ ) 
        {
            $error = false;
            $row = $profile->ioRead();
            if( !$row ) 
            {
                $this->_isLastPage = true;
                return true;
            }

            $empty = true;
            foreach( $row as $v ) 
            {
                if( trim($v) !== "" ) 
                {
                    $empty = false;
                    break;
                }

            }
            if( $empty ) 
            {
                $profile->addValue("rows_empty");
                continue;
            }

            $profile->addValue("rows_processed");
            $logger->setLine($this->_startLine + $i1);
            if( empty($row[$this->_pathIdx]) || !empty($this->_newData[$row[$this->_pathIdx]]) ) 
            {
                $profile->addValue("rows_errors")->addValue("num_errors");
                $logger->setColumn($this->_pathIdx + 1)->error(empty($row[$this->_pathIdx]) ? $this->__("Empty URL Path") : $this->__("Duplicate URL Path"));
                continue;
            }

            $urlPath = trim($row[$this->_pathIdx]);
            $this->_pathLine[$urlPath] = $this->_startLine + $i1;
            $this->_newData[$urlPath] = $this->_newDataTemplate;
            $this->_defaultUsed[$urlPath] = $this->_newDataTemplate;
            $error = false;
            foreach( $row as $col => $v ) 
            {
                if( !isset($this->_fieldsIdx[$col]) && $v !== "" ) 
                {
                    $profile->addValue("num_warnings");
                    $logger->setColumn($col + 1)->warning($this->__("Column is out of boundaries, ignored"));
                    continue;
                }

                $k = $this->_fieldsIdx[$col];
                if( $k === false || $k == "const.value" ) 
                {
                    continue;
                }

                $input = $this->_attr($k, "frontend_input");
                $multiselect = $input == "multiselect";
                $separator = trim(!empty($this->_fields[$k]["separator"]) ? $this->_fields[$k]["separator"] : $defaultSeparator);
                try
                {
                    $v = $this->_convertEncoding($v);
                }
                catch( Exception $e ) 
                {
                    $profile->addValue("num_warnings");
                    $logger->setColumn($col + 1)->warning($e);
                }
                if( $v !== "" ) 
                {
                    if( $input == "select" ) 
                    {
                        $v = trim($v);
                    }
                    else
                    {
                        if( $multiselect ) 
                        {
                            $values = explode($separator, $v);
                            $v = array(  );
                            foreach( $values as $v1 ) 
                            {
                                $v[] = $v1;
                            }
                        }

                    }

                }

                if( !isset($this->_defaultUsed[$urlPath][$k]) || $v !== "" && $v !== array(  ) ) 
                {
                    $this->_newData[$urlPath][$k] = $v;
                    unset($this->_defaultUsed[$urlPath][$k]);
                }

            }
            if( $error ) 
            {
                unset($this->_newData[$urlPath]);
            }

        }
        return false;
    }

    protected function _importFetchOldData()
    {
        $attributeFields = array_flip($this->_fieldAttributes);
        $table = $this->_t("catalog/category");
        $upAttrId = $this->_attr("url_path", "attribute_id");
        $ukAttrId = $this->_attr("url_key", "attribute_id");
        $storeId = $this->_profile->getStoreId();
        $noUrlPath = Mage::helper("urapidflow")->hasMageFeature("no_url_path");
        $suffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $this->_storeId);
        $suffixLen = strlen($suffix);
        $keys = array_keys($this->_newData);
        if( $suffix ) 
        {
            foreach( array_keys($this->_newData) as $key ) 
            {
                $keys[] = substr($key, 0 - $suffixLen) == $suffix ? substr($key, 0, strlen($key) - $suffixLen) : $key . $suffix;
            }
        }

        $entityIds = array(  );
        foreach( $keys as $key ) 
        {
            if( isset($this->_urlPaths[$key]) ) 
            {
                $entityIds[] = $this->_urlPaths[$key];
            }

        }
        $select = $this->_write->select()->from(array( "e" => $table ));
        if( $noUrlPath ) 
        {
            $ukStore = $this->_read->quoteInto("uk.store_id in (?)", array( 0, $storeId ));
            $upStore = $this->_read->quoteInto("up.store_id in (?)", array( 0, $storeId ));
            $vcStore = $this->_read->quoteInto("vc.store_id in (?)", array( 0, $storeId ));
            $select->joinLeft(array( "uk" => "" . $table . "_url_key" ), "" . "uk.entity_id = e.entity_id AND uk.attribute_id=" . $ukAttrId . " AND " . $ukStore, array(  ))->joinLeft(array( "up" => "" . $table . "_varchar" ), "" . "up.entity_id = e.entity_id AND up.attribute_id=" . $upAttrId . " AND " . $upStore, array( "value_id", "url_path" => "value" ))->joinLeft(array( "vc" => "" . $table . "_varchar" ), "" . "vc.entity_id = e.entity_id AND vc.attribute_id=" . $ukAttrId . " AND " . $vcStore, array(  ))->joinLeft(array( "a" => $this->_t("eav/attribute") ), "a.attribute_id=uk.attribute_id", null)->joinLeft(array( "av" => $this->_t("eav/attribute") ), "av.attribute_id=vc.attribute_id", null)->columns(array( "attribute_code" => "IFNULL(a.attribute_code, av.attribute_code)", "url_key" => "IFNULL(uk.value, vc.value)" ))->where("e.entity_id in (?)", $entityIds)->order("uk.store_id desc");
        }
        else
        {
            $select->joinLeft(array( "up" => $table . "_varchar" ), "" . "up.entity_id=e.entity_id and up.attribute_id=" . $upAttrId, array( "value_id", "url_path" => "value" ))->where("e.entity_id in (?)", $entityIds)->where("up.store_id in (0, ?)", $storeId);
        }

        $this->_attrJoined = array( $upAttrId );
        $productRows = $this->_write->fetchAll($select);
        unset($select);
        $this->_entities = array(  );
        foreach( $productRows as $r ) 
        {
            if( $noUrlPath ) 
            {
                $r["url_path"] = isset($this->_categories[$r["entity_id"]]["url_path"]) ? $this->_categories[$r["entity_id"]]["url_path"] : $this->catBuildPath($r, $this->_categories);
            }

            $r1 = array(  );
            foreach( $r as $k => $v ) 
            {
                if( $k == "value_id" ) 
                {
                    continue;
                }

                if( !empty($attributeFields[$k]) ) 
                {
                    $r1[$attributeFields[$k]] = $v;
                }
                else
                {
                    $r1[$k] = $v;
                }

            }
            $r1["url_path"] = $this->_upPrependRoot($r1, $r1["url_path"]);
            $this->_attrValueIds[$r["entity_id"]][0]["url_path"] = $r["value_id"];
            $this->_entities[$r["entity_id"]][0] = $r1;
        }
        $this->_entityIds = array_keys($this->_entities);
    }

    protected function _importResetPageData()
    {
        $this->_isLastPage = false;
        $this->_entities = array(  );
        $this->_newData = array(  );
        $this->_defaultUsed = array(  );
        $this->_valid = array(  );
        $this->_pathLine = array(  );
        $this->_entityIdsUpdated = array(  );
        $this->_attrValueIds = array(  );
        $this->_attrValuesFetched = array(  );
        $this->_insertEntity = array(  );
        $this->_updateEntity = array(  );
        $this->_changeAttr = array(  );
        $this->_insertAttr = array(  );
        $this->_updateAttr = array(  );
        $this->_deleteAttr = array(  );
        $this->_changeChildrenCount = array(  );
    }

    protected function _fetchAttributeValues($storeId, $defaults = false, $entityIds = null, $limitAttrIds = null)
    {
        if( !empty($this->_attrValuesFetched[$storeId]) ) 
        {
            return NULL;
        }

        if( $this->_profile->getData("options/import/actions") == "create" ) 
        {
            return NULL;
        }

        if( is_null($entityIds) ) 
        {
            $entityIds = $this->_entityIds;
        }

        $table = $this->_t("catalog/category");
        foreach( $this->_attributesByType as $type => $attrs ) 
        {
            if( $type == "static" ) 
            {
                continue;
            }

            foreach( $this->_attrJoined as $id ) 
            {
                unset($attrs[$id]);
            }
            if( $limitAttrIds && is_array($limitAttrIds) ) 
            {
                $oldAttrs = $attrs;
                foreach( $oldAttrs as $id => $a ) 
                {
                    if( !in_array($id, $limitAttrIds) ) 
                    {
                        unset($attrs[$id]);
                    }

                }
            }

            $attrIds = array_keys($attrs);
            $rows = $this->_read->fetchAll($this->_read->select()->from($table . "_" . $type)->where("entity_id in (?)", $entityIds)->where("attribute_id in (?)", $attrIds)->where("store_id in (0, ?)", $storeId));
            if( empty($rows) ) 
            {
                continue;
            }

            foreach( $rows as $r ) 
            {
                $attrCode = $this->_attr($r["attribute_id"], "attribute_code");
                if( $attrCode == "url_path" ) 
                {
                    $r["value"] = $this->_upPrependRoot($this->_entities[$r["entity_id"]][0], $r["value"]);
                }

                if( empty($this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode]) ) 
                {
                    if( $this->_attr($r["attribute_id"], "frontend_input") == "multiselect" ) 
                    {
                        if( $r["value"] === "" || is_null($r["value"]) ) 
                        {
                            $r["value"] = array(  );
                        }
                        else
                        {
                            $r["value"] = explode(",", $r["value"]);
                        }

                    }

                    $this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode] = $r["value"];
                }
                else
                {
                    if( !is_array($this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode]) ) 
                    {
                        if( $r["value"] != $this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode] ) 
                        {
                            $this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode] = array( $this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode], $r["value"] );
                        }

                    }
                    else
                    {
                        if( !in_array($r["value"], $this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode]) ) 
                        {
                            $this->_entities[$r["entity_id"]][$r["store_id"]][$attrCode][] = $r["value"];
                        }

                    }

                }

                $this->_attrValueIds[$r["entity_id"]][$r["store_id"]][$attrCode] = $r["value_id"];
            }
        }
        if( $defaults ) 
        {
            $this->_attrValuesFetched[0] = true;
        }

        $this->_attrValuesFetched[$storeId] = true;
    }

    protected function _importProcessNewData()
    {
        $suffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $this->_storeId);
        foreach( $this->_newData as $urlPath => &$p ) 
        {
            if( empty($this->_urlPaths[$urlPath]) ) 
            {
                if( !empty($p["url_key"]) ) 
                {
                    $urlKey = $p["url_key"];
                }
                else
                {
                    $urlKey = basename($urlPath, $suffix);
                }

                $p["url_key"] = Mage::helper("urapidflow")->formatUrlKey($urlKey);
            }

            if( !empty($this->_urlPaths[$urlPath]) ) 
            {
                foreach( $this->_defaultUsed[$urlPath] as $k => $v ) 
                {
                    unset($p[$k]);
                }
            }

        }
        unset($p);
    }

    protected function _importValidateNewData()
    {
        $profile = $this->_profile;
        $logger = $profile->getLogger();
        $storeId = $this->_storeId;
        $autoCreateOptions = $profile->getData("options/import/create_options");
        $actions = $profile->getData("options/import/actions");
        $allowSelectIds = $profile->getData("options/import/select_ids");
        foreach( $this->_newData as $urlPath => $p ) 
        {
            $logger->setLine($this->_pathLine[$urlPath]);
            $isNew = empty($this->_urlPaths[$urlPath]);
            if( $isNew && $actions == "update" || !$isNew && $actions == "create" ) 
            {
                $profile->addValue("rows_nochange");
                $this->_valid[$urlPath] = false;
                continue;
            }

            $this->_valid[$urlPath] = true;
            foreach( $this->_attributesByCode as $k => $attr ) 
            {
                if( isset($p[$k]) || empty($attr["is_required"]) || !$isNew ) 
                {
                    continue;
                }

                $profile->addValue("num_errors");
                $logger->setColumn(1);
                $logger->error($this->__("Missing required value for '%s'", $k));
                $this->_valid[$urlPath] = false;
            }
            if( $isNew ) 
            {
                if( strpos($urlPath, "/") === false ) 
                {
                    $this->_parentPath[$urlPath] = "";
                    $this->_urlPaths[$urlPath] = true;
                }
                else
                {
                    $parentPath = preg_replace("#/[^/]+\$#", "", $urlPath);
                    $parentPathOrig = $parentPath;
                    if( empty($this->_urlPaths[$parentPath]) ) 
                    {
                        $parentPath = $this->_addPathSuffix($parentPath);
                    }

                    if( empty($this->_urlPaths[$parentPath]) ) 
                    {
                        $profile->addValue("num_errors");
                        $logger->setColumn($this->_pathIdx + 1);
                        $logger->error($this->__("Invalid parent path '%s'", $parentPathOrig));
                        $this->_valid[$urlPath] = false;
                    }
                    else
                    {
                        $this->_parentPath[$urlPath] = $parentPath;
                        $this->_urlPaths[$urlPath] = true;
                    }

                    while( preg_match("#/[^/]+\$#", $parentPath) && ($parentPath = preg_replace("#/[^/]+\$#", "", $parentPath)) ) 
                    {
                        $parentPathOrig = $parentPath;
                        if( empty($this->_urlPaths[$parentPath]) ) 
                        {
                            $parentPath = $this->_addPathSuffix($parentPath);
                        }

                        if( !empty($this->_urlPaths[$parentPath]) ) 
                        {
                            if( empty($this->_parentPathExtra[$urlPath]) ) 
                            {
                                $this->_parentPathExtra[$urlPath] = array(  );
                            }

                            $this->_parentPathExtra[$urlPath][] = $parentPath;
                        }

                    }
                }

            }

            foreach( $p as $k => $newValue ) 
            {
                $attr = $this->_attr($k);
                $logger->setColumn(isset($this->_fieldsCodes[$k]) ? $this->_fieldsCodes[$k] + 1 : 0 - 1);
                $empty = is_null($newValue) || $newValue === "" || $newValue === array(  );
                $required = !empty($attr["is_required"]);
                $selectable = !empty($attr["frontend_input"]) && ($attr["frontend_input"] == "select" || $attr["frontend_input"] == "multiselect");
                if( $empty && $required ) 
                {
                    $profile->addValue("num_errors");
                    $logger->error($this->__("Missing required value for '%s'", $k));
                    $this->_valid[$urlPath] = false;
                    continue;
                }

                if( $selectable && !$empty ) 
                {
                    foreach( (array) $newValue as $i => $v ) 
                    {
                        $vLower = strtolower(trim($v));
                        if( isset($this->_defaultUsed[$urlPath][$k]) ) 
                        {
                        }
                        else
                        {
                            if( isset($attr["options_bytext"][$vLower]) ) 
                            {
                                if( is_array($newValue) ) 
                                {
                                    $this->_newData[$urlPath][$k][$i] = $attr["options_bytext"][$vLower];
                                }
                                else
                                {
                                    $this->_newData[$urlPath][$k] = $attr["options_bytext"][$vLower];
                                }

                            }
                            else
                            {
                                if( $allowSelectIds && isset($attr["options"][$v]) ) 
                                {
                                }
                                else
                                {
                                    if( $autoCreateOptions && !empty($attr["attribute_id"]) && (empty($attr["source_model"]) || $attr["source_model"] == "eav/entity_attribute_source_table") ) 
                                    {
                                        $this->_importCreateAttributeOption($attr, $v);
                                        $profile->addValue("num_warnings");
                                        $logger->warning($this->__("Created a new option '%s' for attribute '%s'", $v, $k));
                                    }
                                    else
                                    {
                                        $profile->addValue("num_errors");
                                        $logger->error($this->__("Invalid option '%s'", $v));
                                        $this->_valid[$urlPath] = false;
                                    }

                                }

                            }

                        }

                    }
                }

            }
            if( !$this->_valid[$urlPath] ) 
            {
                $profile->addValue("rows_errors");
            }

        }
        unset($p);
    }

    protected function _importCreateAttributeOption($attr, $name)
    {
        $aId = $attr["attribute_id"];
        $name = trim($name);
        if( !empty($this->_attributesById[$aId]["options_bytext"][strtolower($name)]) ) 
        {
            return NULL;
        }

        $profile = $this->_profile;
        if( !$profile->getData("options/import/dryrun") ) 
        {
            $this->_write->insert($this->_t("eav/attribute_option"), array( "attribute_id" => $aId ));
            $oId = $this->_write->lastInsertId();
            $this->_write->insert($this->_t("eav/attribute_option_value"), array( "option_id" => $oId, "store_id" => 0, "value" => $name ));
            $vId = $this->_write->lastInsertId();
        }
        else
        {
            $oId = 0;
            foreach( $this->_attributesByCode[$aId]["options"] as $k => $v ) 
            {
                $oId = max($oId, $k);
            }
        }

        $this->_attributesById[$aId]["options"][$oId] = $name;
        $this->_attributesById[$aId]["options_bytext"][strtolower($name)] = $oId;
    }

    protected function _importProcessDataDiff()
    {
        $profile = $this->_profile;
        $logger = $profile->getLogger();
        $storeId = $this->_storeId;
        $oldValues = array(  );
        foreach( $this->_newData as $urlPath => $p ) 
        {
            if( !$this->_valid[$urlPath] ) 
            {
                continue;
            }

            $logger->setLine($this->_pathLine[$urlPath]);
            $isNew = empty($this->_urlPaths[$urlPath]) || $this->_urlPaths[$urlPath] === true;
            $isUpdated = false;
            if( $isNew ) 
            {
                $this->_insertEntity[$urlPath] = array( "entity_type_id" => $this->_entityTypeId, "attribute_set_id" => $this->_attributeSetId, "created_at" => now(), "updated_at" => now(), "position" => isset($p["position"]) ? $p["position"] : 0 );
                $cId = null;
                $parentPath = $this->_parentPath[$urlPath];
                $this->_changeChildrenCount[$urlPath] = 0;
                if( $parentPath ) 
                {
                    if( !isset($this->_changeChildrenCount[$parentPath]) ) 
                    {
                        $count = !empty($this->_childrenCount[$parentPath]) ? $this->_childrenCount[$parentPath] : 0;
                        $this->_changeChildrenCount[$parentPath] = $count + 1;
                    }
                    else
                    {
                        $this->_changeChildrenCount[$parentPath]++;
                    }

                }

                if( !empty($this->_parentPathExtra[$urlPath]) ) 
                {
                    foreach( $this->_parentPathExtra[$urlPath] as $parentPath ) 
                    {
                        if( !isset($this->_changeChildrenCount[$parentPath]) ) 
                        {
                            $count = !empty($this->_childrenCount[$parentPath]) ? $this->_childrenCount[$parentPath] : 0;
                            $this->_changeChildrenCount[$parentPath] = $count + 1;
                        }
                        else
                        {
                            $this->_changeChildrenCount[$parentPath]++;
                        }

                    }
                }

            }
            else
            {
                $cId = $this->_urlPaths[$urlPath];
                $urlPath = $this->_entities[$cId][0]["url_path"];
                if( isset($p["position"]) && $p["position"] !== "" && $p["position"] != $this->_entities[$cId][0]["position"] ) 
                {
                    $this->_updateEntity[$cId]["position"] = $p["position"];
                    $isUpdated = true;
                }

            }

            foreach( $p as $k => $newValue ) 
            {
                $logger->setColumn(isset($this->_fieldsCodes[$k]) ? $this->_fieldsCodes[$k] + 1 : 0 - 1);
                $oldValue = !$cId ? null : isset($this->_entities[$cId][$storeId][$k]) ? $this->_entities[$cId][$storeId][$k] : isset($this->_entities[$cId][0][$k]) ? $this->_entities[$cId][0][$k] : null;
                $attr = $this->_attr($k);
                $this->_cleanupValues($attr, $oldValue, $newValue);
                if( empty($attr["attribute_id"]) || empty($attr["backend_type"]) || $attr["backend_type"] == "static" ) 
                {
                    continue;
                }

                $isValueChanged = false;
                if( is_array($newValue) ) 
                {
                    $isValueChanged = array_diff($newValue, $oldValue) || array_diff($oldValue, $newValue);
                }
                else
                {
                    $isValueChanged = $newValue !== $oldValue;
                }

                $empty = $newValue === "" || is_null($newValue) || $newValue === array(  );
                if( $isNew && !$empty || $isValueChanged ) 
                {
                    $oldValues[$urlPath][$k] = $oldValue;
                    if( $k == "url_path" && $this->_upPrependRoot ) 
                    {
                        $_up = explode("/", $newValue, 2);
                        array_shift($_up);
                        reset($_up);
                        $newValue = current($_up);
                    }

                    $this->_changeAttr[$urlPath][$k] = $newValue;
                    if( !$isNew ) 
                    {
                        $logger->setColumn(isset($this->_fieldsCodes[$k]) ? $this->_fieldsCodes[$k] + 1 : 0 - 1)->success(null, null, $newValue, $oldValue);
                    }

                    $isUpdated = true;
                }

            }
            if( $isUpdated ) 
            {
                $profile->addValue("rows_success");
                if( !$isNew ) 
                {
                    $this->_updateEntity[$cId]["updated_at"] = now();
                }

            }
            else
            {
                $profile->addValue("rows_nochange");
            }

        }
    }

    protected function _importGenerateAttributeValues()
    {
        $profile = $this->_profile;
        $logger = $profile->getLogger();
        $storeId = $this->_storeId;
        $sameAsDefault = $profile->getData("options/import/store_value_same_as_default");
        if( !empty($this->_websiteScope) ) 
        {
            $websiteProductIds = array_keys($this->_websiteScopeProducts);
            $websiteAttrIds = array_keys($this->_websiteScopeAttributes);
            foreach( $this->_websiteStores[$storeId] as $sId ) 
            {
                $this->_fetchAttributeValues($sId, false, $websiteProductIds, $websiteAttrIds);
            }
        }

        foreach( $this->_changeAttr as $urlPath => $p ) 
        {
            $cId = $this->_urlPaths[$urlPath];
            foreach( $p as $k => $v ) 
            {
                $attr = $this->_attr($k);
                if( !$attr ) 
                {
                    continue;
                }

                $aId = $attr["attribute_id"];
                $aType = $this->getAttrType($attr, "catalog/category");
                if( is_array($v) ) 
                {
                    $v = join(",", $v);
                }

                $values = array(  );
                if( !empty($this->_entities[$cId]) ) 
                {
                    foreach( $this->_entities[$cId] as $sId => $sValues ) 
                    {
                        if( isset($sValues[$k]) ) 
                        {
                            $values[$sId] = $sValues[$k];
                        }

                    }
                }

                $sActions = array(  );
                if( !is_null($v) ) 
                {
                    if( !isset($this->_attrValueIds[$cId][0][$k]) ) 
                    {
                        $sActions = array( "I" );
                    }
                    else
                    {
                        if( !$storeId ) 
                        {
                            $sActions = array( "U" );
                        }
                        else
                        {
                            if( $attr["is_global"] == 1 ) 
                            {
                                $sActions = array( "U" );
                            }
                            else
                            {
                                $sIds = array( $this->_storeId );
                                foreach( $sIds as $sId ) 
                                {
                                    if( isset($this->_attrValueIds[$cId][$sId][$k]) ) 
                                    {
                                        if( isset($values[0]) && $v == $values[0] && $sameAsDefault == "default" ) 
                                        {
                                            $sActions[$sId] = "D";
                                        }
                                        else
                                        {
                                            $sActions[$sId] = "U";
                                        }

                                    }
                                    else
                                    {
                                        $sActions[$sId] = "I";
                                    }

                                }
                            }

                        }

                    }

                }
                else
                {
                    if( isset($this->_attrValueIds[$cId][0][$k]) && !$storeId ) 
                    {
                        $sActions = array( "D" );
                    }
                    else
                    {
                        if( $attr["is_global"] == 1 ) 
                        {
                            $sActions = array( "D" );
                        }
                        else
                        {
                            if( $storeId && isset($this->_attrValueIds[$cId][$storeId][$k]) ) 
                            {
                                $sActions[$sId] = "D";
                            }

                        }

                    }

                }

                foreach( $sActions as $sId => $action ) 
                {
                    switch( $action ) 
                    {
                        case "I":
                            $a = array( "entity_type_id" => $this->_entityTypeId, "attribute_id" => $aId, "store_id" => $sId, "entity_id" => $cId, "value" => $v );
                            if( $cId === true ) 
                            {
                                $a["url_path"] = $urlPath;
                            }

                            $this->_insertAttr[$aType][] = $a;
                            break;
                        case "U":
                            $this->_updateAttr[$aType][$this->_attrValueIds[$cId][$sId][$k]] = $v;
                            break;
                        case "D":
                            $this->_deleteAttr[$aType][] = $this->_attrValueIds[$cId][$sId][$k];
                    }
                }
            }
        }
    }

    protected function _importSaveEntities()
    {
        $profile = $this->_profile;
        $logger = $profile->getLogger();
        $suffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $this->_storeId);
        $suffixLen = strlen($suffix);
        $table = $this->_t("catalog/category");
        foreach( $this->_changeChildrenCount as $urlPath => $count ) 
        {
            if( strpos($urlPath, "/") === false ) 
            {
                $_incVal = $count;
                if( !empty($this->_childrenCount[$urlPath]) ) 
                {
                    $_incVal -= $this->_childrenCount[$urlPath];
                }
                else
                {
                    $_incVal++;
                }

                if( !empty($this->_rootCatId) ) 
                {
                    if( !isset($this->_childrenCount[$this->_rootCatId]) ) 
                    {
                        $this->_childrenCount[$this->_rootCatId] = 0;
                    }

                    $this->_childrenCount[$this->_rootCatId] += $_incVal;
                    $this->_updateEntity[$this->_rootCatId]["children_count"] = $this->_childrenCount[$this->_rootCatId];
                }

                if( !isset($this->_childrenCount[1]) ) 
                {
                    $this->_childrenCount[1] = 0;
                }

                $this->_childrenCount[1] += $_incVal;
                $this->_updateEntity[1]["children_count"] = $this->_childrenCount[1];
            }

        }
        foreach( $this->_insertEntity as $urlPath => $a ) 
        {
            $logger->setLine($this->_pathLine[$urlPath]);
            $parentPath = !empty($this->_parentPath[$urlPath]) ? $this->_parentPath[$urlPath] : false;
            if( !$parentPath ) 
            {
                $a["parent_id"] = $this->_rootCatId;
                $a["path"] = "1/" . $this->_rootCatId . "/";
                $a["level"] = 2;
            }
            else
            {
                if( $this->_urlPaths[$parentPath] === true ) 
                {
                    $logger->setColumn($this->_pathIdx + 1)->error($this->__("Parent category wasn't created due to error"));
                    $profile->addValue("num_errors")->addValue("rows_errors");
                    continue;
                }

                $a["parent_id"] = $this->_urlPaths[$parentPath];
                $a["path"] = $this->_categories[$this->_urlPaths[$parentPath]]["path"] . "/";
                $a["level"] = sizeof(explode("/", $a["path"])) - 1;
            }

            if( !empty($this->_changeChildrenCount[$urlPath]) ) 
            {
                $a["children_count"] = $this->_changeChildrenCount[$urlPath];
                unset($this->_changeChildrenCount[$urlPath]);
            }
            else
            {
                $a["children_count"] = 0;
            }

            $this->_write->insert($table, $a);
            $cId = $this->_write->lastInsertId();
            $this->_updateEntity[$cId] = array( "path" => $a["path"] . $cId );
            $this->_categories[$cId] = array( "entity_id" => $cId, "path" => $a["path"] . $cId, "url_path" => $urlPath );
            $this->_urlPaths[$urlPath] = $cId;
            if( $suffix ) 
            {
                $additionalKey = substr($urlPath, 0 - $suffixLen) == $suffix ? substr($urlPath, 0, strlen($urlPath) - $suffixLen) : $urlPath . $suffix;
                $this->_urlPaths[$additionalKey] = $cId;
            }

            $this->_childrenCount[$urlPath] = $a["children_count"];
            if( !empty($additionalKey) ) 
            {
                $this->_childrenCount[$additionalKey] = $a["children_count"];
            }

            $this->_entityIdsUpdated[$cId] = 1;
            $logger->setColumn(0)->success(null, 1);
        }
        foreach( $this->_changeChildrenCount as $urlPath => $count ) 
        {
            $this->_updateEntity[$this->_urlPaths[$urlPath]]["children_count"] = $count;
            $this->_childrenCount[$urlPath] = $count;
        }
        foreach( $this->_updateEntity as $cId => $a ) 
        {
            $this->_write->update($table, $a, "entity_id=" . $cId);
            $this->_entityIdsUpdated[$cId] = 1;
        }
    }

    protected function _importSaveAttributeValues()
    {
        $table = $this->_t("catalog/category");
        foreach( $this->_insertAttr as $type => $attrs ) 
        {
            if( $type == "static" ) 
            {
                continue;
            }

            $rows = array(  );
            $i = 0;
            $j = 0;
            $sqlPrefix = "" . "insert into `" . $table . "_" . $type . "` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) values ";
            foreach( $attrs as $a ) 
            {
                if( isset($a["url_path"]) ) 
                {
                    if( $this->_urlPaths[$a["url_path"]] === true ) 
                    {
                        continue;
                    }

                    $a["entity_id"] = $this->_urlPaths[$a["url_path"]];
                    unset($a["url_path"]);
                }

                $sqlValue = "" . "('" . $a["entity_type_id"] . "', '" . $a["attribute_id"] . "', '" . $a["store_id"] . "', '" . $a["entity_id"] . "', ?)";
                $value = $type == "varchar" ? substr($a["value"], 0, 255) : $a["value"];
                $sql = $this->_write->quoteInto($sqlValue, $value);
                if( $type == "text" && 4000 < strlen((bool) $value) ) 
                {
                    $this->_write->getConnection()->exec($sqlPrefix . $sql);
                }
                else
                {
                    $rows[] = $sql;
                }

            }
            $chunks = array_chunk($rows, 100);
            foreach( $chunks as $chunk ) 
            {
                try
                {
                    $this->_write->getConnection()->exec($sqlPrefix . join(",", $chunk));
                }
                catch( Exception $e ) 
                {
                    $this->_profile->getLogger()->error("Unirgy_RapidFlowPro_Model_Mysql4_Category_Abstract::_importSaveAttributeValues" . ":" . 1238 . ":" . $e->getMessage());
                }
            }
        }
        foreach( $this->_updateAttr as $type => $attrs ) 
        {
            if( $type == "static" ) 
            {
                continue;
            }

            foreach( $attrs as $k => $v ) 
            {
                try
                {
                    $this->_write->update($table . "_" . $type, array( "value" => $type == "varchar" ? substr($v, 0, 255) : $v ), "value_id=" . $k);
                }
                catch( Exception $e ) 
                {
                    $this->_profile->getLogger()->error("Unirgy_RapidFlowPro_Model_Mysql4_Category_Abstract::_importSaveAttributeValues" . ":" . 1252 . ":" . $e->getMessage());
                }
            }
        }
        foreach( $this->_deleteAttr as $type => $vIds ) 
        {
            if( $type == "static" ) 
            {
                continue;
            }

            try
            {
                $this->_write->delete($table . "_" . $type, "value_id in (" . join(",", $vIds) . ")");
            }
            catch( Exception $e ) 
            {
                $this->_profile->getLogger()->error("Unirgy_RapidFlowPro_Model_Mysql4_Category_Abstract::_importSaveAttributeValues" . ":" . 1263 . ":" . $e->getMessage());
            }
        }
    }

    protected function _getAttributeSetFields($attrSet)
    {
        if( $attrSet && !is_numeric($attrSet) ) 
        {
            $attrSet = $this->_attr("product.attribute_set", "options_bytext", strtolower($attrSet));
        }

        if( !$attrSet ) 
        {
            Mage::throwexception($this->__("Invalid attribute set"));
        }

        if( empty($this->_attributeSetFields[$attrSet]) ) 
        {
            $this->_attributeSetFields[$attrSet] = $this->_write->fetchPairs("" . "select a.attribute_code, a.attribute_id from " . $this->_t("eav/entity_attribute") . " ea inner join " . $this->_t("eav/attribute") . " a on a.attribute_id=ea.attribute_id where attribute_set_id=" . $attrSet);
        }

        return $this->_attributeSetFields[$attrSet];
    }

    protected function _addPathSuffix($path)
    {
        if( is_null($this->_pathSuffix) ) 
        {
            $this->_pathSuffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $this->_storeId);
        }

        return trim($path, "/") . $this->_pathSuffix;
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

    protected function getUrlPaths($storeId, $table)
    {
        $noUrlPath = Mage::helper("urapidflow")->hasMageFeature("no_url_path");
        $ukAttrId = $this->_attr("url_key", "attribute_id");
        $select = $this->_read->select();
        if( $noUrlPath ) 
        {
            $ukStore = $this->_read->quoteInto("uk.store_id in (?)", array( 0, $storeId ));
            $vcStore = $this->_read->quoteInto("vc.store_id in (?)", array( 0, $storeId ));
            $select->from(array( "e" => $table ), array( "entity_id", "path" ))->joinLeft(array( "uk" => "" . $table . "_url_key" ), "" . "uk.entity_id = e.entity_id AND uk.attribute_id=" . $ukAttrId . " AND " . $ukStore, array(  ))->joinLeft(array( "vc" => "" . $table . "_varchar" ), "" . "vc.entity_id = e.entity_id AND vc.attribute_id=" . $ukAttrId . " AND " . $vcStore, array(  ))->joinLeft(array( "a" => $this->_t("eav/attribute") ), "a.attribute_id=uk.attribute_id", null)->joinLeft(array( "av" => $this->_t("eav/attribute") ), "av.attribute_id=vc.attribute_id", null)->columns(array( "attribute_code" => "IFNULL(a.attribute_code, av.attribute_code)", "url_key" => "IFNULL(uk.value, vc.value)" ))->where("e.entity_id in (?)", array_keys($this->_categories))->order("uk.store_id desc");
        }
        else
        {
            $select->from(array( "v" => "" . $table . "_varchar" ), array( "entity_id", "value" ))->joinLeft(array( "a" => $this->_t("eav/attribute") ), "a.attribute_id=v.attribute_id", "attribute_code")->where("v.store_id in (?)", array( 0, $storeId ))->where("v.entity_id in (?)", array_keys($this->_categories))->where("a.entity_type_id=?", $this->_entityTypeId)->where("a.attribute_code=?", "url_path")->order("v.store_id desc");
        }

        $rows = array(  );
        foreach( $this->_read->fetchAll($select) as $r ) 
        {
            $rows[$r["entity_id"]] = $r;
        }
        if( $noUrlPath ) 
        {
            foreach( $rows as &$r ) 
            {
                $urlPath = $this->catBuildPath($r, $rows, "url_key", "url_key");
                $r["attribute_code"] = "url_path";
                $r["value"] = $urlPath;
            }
        }

        return $rows;
    }

}


