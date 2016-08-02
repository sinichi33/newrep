<?php 

class Unirgy_RapidFlow_Model_Mysql4_Catalog_Product_Abstract extends Unirgy_RapidFlow_Model_Mysql4_Catalog_Abstract
{
    protected $_entityType = "catalog_product";
    protected $_entityTypeId = NULL;
    protected $_tplAttrSet = array(  );
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
    protected $_newDataTemplate = array(  );
    protected $_products = array(  );
    protected $_productIds = array(  );
    protected $_productIdsUpdated = array(  );
    protected $_valid = array(  );
    protected $_defaultUsed = array(  );
    protected $_skuLine = array(  );
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
    protected $_changeWebsite = array(  );
    protected $_changeStock = array(  );
    protected $_changeCategoryProduct = array(  );
    protected $_mediaChanges = array(  );
    protected $_insertStock = array(  );
    protected $_updateStock = array(  );
    protected $_deleteStock = array(  );
    protected $_rtIdxFlatAttrCodes = array(  );
    protected $_realtimeIdx = array( "cataloginventory_stock" => array(  ), "catalog_product_attribute" => array(  ), "catalog_product_price" => array(  ), "catalog_url" => array(  ), "catalogsearch_fulltext" => array(  ), "catalog_category_product" => array(  ), "catalog_product_flat" => array(  ), "tag_summary" => array(  ) );
    protected $_newData = array(  );
    protected $_skuIdx = array(  );
    protected $_startLine = NULL;
    protected $_isLastPage = false;
    protected $_fieldAttributes = array( "product.attribute_set" => "attribute_set_id", "product.type" => "type_id", "product.store" => "store_id", "product.entity_id" => "entity_id", "product.has_options" => "has_options", "product.required_options" => "required_options" );
    protected $_autoCategory = null;
    protected $_saveAttributesMethod = NULL;
    protected $_configurableParentSku = false;
    private static $_licenseIsValid = array(  );
    protected $_urlKeys = array(  );
    protected $_hlp = NULL;

    final public static function validateLicense($module)
    {
        return true;
    }

    protected function _exportConfigurableParentSku()
    {
        if( !$this->_configurableParentSku ) 
        {
            return $this;
        }

        $parentIds = $this->_read->fetchPairs($this->_read->select()->from($this->_t("catalog/product_super_link"), array( "product_id", "group_concat(parent_id)" ))->where("product_id IN(?)", array_keys($this->_products))->group("product_id"));
        if( empty($parentIds) ) 
        {
            return $this;
        }

        $allPids = array(  );
        foreach( $parentIds as $cId => &$pIds ) 
        {
            $pIds = explode(",", $pIds);
            $allPids = array_merge($allPids, $pIds);
        }
        $parentSkus = $this->_read->fetchPairs($this->_read->select()->from($this->_t("catalog/product"), array( "entity_id", "sku" ))->where("entity_id IN (?)", array_values($allPids)));
        foreach( $parentIds as $cId => &$pIds ) 
        {
            foreach( $pIds as $dummy => &$_pId ) 
            {
                $_pId = !empty($parentSkus[$_pId]) ? $parentSkus[$_pId] : $_pId;
            }
        }
        foreach( $this->_products as $id => &$prod ) 
        {
            if( !empty($parentIds[$id]) ) 
            {
                reset($parentIds[$id]);
                $separator = $this->_profile->getData("options/csv/multivalue_separator");
                if( !$separator ) 
                {
                    $separator = "; ";
                }

                if( !empty($this->_configurableParentSku["separator"]) ) 
                {
                    $separator = $this->_configurableParentSku["separator"];
                }

                if( isset($this->_configurableParentSku["format"]) && $this->_configurableParentSku["format"] == "single" ) 
                {
                    $prod[0]["product.configurable_parent_sku"] = current($parentIds[$id]);
                }
                else
                {
                    $prod[0]["product.configurable_parent_sku"] = implode($separator, $parentIds[$id]);
                }

            }

        }
        return $this;
    }

    protected function _exportProcessPrice()
    {
        self::validatelicense("Unirgy_RapidFlow");
        $profile = $this->_profile;
        $storeId = $profile->getStoreId();
        $useMinimalPrice = empty($this->_fieldsCodes) || array_key_exists("price.minimal", $this->_fieldsCodes);
        $useMaximumPrice = (empty($this->_fieldsCodes) || array_key_exists("price.maximum", $this->_fieldsCodes)) && $this->_hlp()->hasMageFeature("indexer_1.4");
        $useFinalPrice = empty($this->_fieldsCodes) || array_key_exists("price.final", $this->_fieldsCodes);
        $addTax = $profile->getData("options/export/add_tax");
        $markup = $profile->getData("options/export/markup") / 100;
        $loadProduct = $profile->getData("options/export/load_product");
        $p = false;
        if( $useMinimalPrice || $useFinalPrice || $useMaximumPrice || $addTax ) 
        {
            $collection = Mage::getresourcemodel("urapidflow/catalog_product_collection")->setStore($storeId)->addWebsiteFilter(Mage::app()->getStore($storeId)->getWebsiteId())->addAttributeToSelect("tax_class_id")->addIdFilter(array_keys($this->_products));
            if( $useMinimalPrice ) 
            {
                $collection->addMinimalPrice();
            }

            if( $useFinalPrice || $useMaximumPrice ) 
            {
                $collection->addFinalPrice();
            }

            if( $addTax ) 
            {
                $collection->addTaxPercents();
            }

        }

        foreach( $this->_products as $id => &$prod ) 
        {
            if( $useMinimalPrice || $useFinalPrice || $addTax ) 
            {
                $p = $collection->getItemById($id);
                if( null == $p && $loadProduct ) 
                {
                    $p = Mage::getmodel("catalog/product")->load($id);
                }

            }

            $price = 0;
            $sId = $storeId;
            if( $p && $useFinalPrice ) 
            {
                $finalPrice = $p->getCalculatedFinalPrice();
                if( !isset($finalPrice) ) 
                {
                    $finalPrice = $p->getFinalPrice();
                }

            }

            if( $p && $useMinimalPrice ) 
            {
                $minPrice = $p->getMinimalPrice();
            }

            if( $p && $useMaximumPrice ) 
            {
                $maxPrice = $p->getMaxPrice();
            }

            if( isset($prod[$storeId]["price"]) ) 
            {
                $price = $prod[$storeId]["price"];
            }
            else
            {
                if( isset($prod[0]["price"]) ) 
                {
                    $sId = 0;
                    $price = $prod[0]["price"];
                }

            }

            if( $p && $addTax ) 
            {
                $price *= 1 + $p->getTaxPercent() / 100;
                if( isset($finalPrice) ) 
                {
                    $finalPrice *= 1 + $p->getTaxPercent() / 100;
                }

                if( isset($minPrice) ) 
                {
                    $minPrice *= 1 + $p->getTaxPercent() / 100;
                }

                if( isset($maxPrice) ) 
                {
                    $maxPrice *= 1 + $p->getTaxPercent() / 100;
                }

            }

            if( $markup ) 
            {
                $price *= 1 + $markup;
                if( isset($finalPrice) ) 
                {
                    $finalPrice *= 1 + $markup;
                }

                if( isset($minPrice) ) 
                {
                    $minPrice *= 1 + $markup;
                }

                if( isset($maxPrice) ) 
                {
                    $maxPrice *= 1 + $markup;
                }

            }

            $prod[$sId]["price"] = $price;
            if( isset($finalPrice) ) 
            {
                $prod[$sId]["price.final"] = $finalPrice;
            }
            else
            {
                $prod[$sId]["price.final"] = $price;
            }

            if( isset($minPrice) ) 
            {
                $prod[$sId]["price.minimal"] = $minPrice;
            }
            else
            {
                $prod[$sId]["price.minimal"] = $price;
            }

            if( isset($maxPrice) ) 
            {
                $prod[$sId]["price.maximum"] = $maxPrice;
            }
            else
            {
                $prod[$sId]["price.maximum"] = $price;
            }

            unset($finalPrice);
            unset($minPrice);
            unset($maxPrice);
        }
        unset($prod);
    }

    protected function _importPrepareColumns()
    {
        self::validatelicense("Unirgy_RapidFlow");
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
            if( !is_array($f) ) 
            {
                continue;
            }

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
                    if( false !== strpos($f["field"], "category.") && !empty($f["separator"]) ) 
                    {
                        $f["default"] = explode($f["separator"], $f["default"]);
                    }
                    else
                    {
                        $f["default"] = explode(",", $f["default"]);
                    }

                }

                $this->_newDataTemplate[$f["field"]] = $f["default"];
            }

        }
        unset($f);
        if( $dups ) 
        {
            throw new Unirgy_RapidFlow_Exception($this->__("Duplicate attributes: %s", join(", ", $dups)));
        }

        $k = "product.websites";
        if( Mage::app()->isSingleStoreMode() && empty($this->_fields[$k]) ) 
        {
            $wId = Mage::app()->getDefaultStoreView()->getWebsiteId();
            $this->_fields[$k] = array( "field" => $k, "alias" => $k, "default_multiselect" => true, "default" => array( $wId ) );
            $this->_fieldsCodes[$k] = 0;
            $this->_newDataTemplate[$k] = array( $wId );
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
            if( !isset($this->_fieldsCodes["sku"]) ) 
            {
                $profile->ioClose();
                throw new Unirgy_RapidFlow_Exception($this->__("Missing SKU column"));
            }

            $this->_skuIdx = $this->_fieldsCodes["sku"];
        }

    }

    protected function _prepareAttributes($columns = null)
    {
        self::validatelicense("Unirgy_RapidFlow");
        $this->_attributesById = array(  );
        $this->_attributesByCode = array(  );
        $this->_attributesByType = array(  );
        $storeId = $this->_profile->getStoreId();
        $removeFields = array( "has_options", "required_options", "category_ids", "minimal_price" );
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

                if( is_array($f) && isset($f["field"]) && !empty($this->_attrDepends[$f["field"]]) ) 
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
        $prodTable = $this->_t("catalog/product");
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
            if( !empty($r["default_value"]) && !isset($this->_newDataTemplate[$r["attribute_code"]]) ) 
            {
                $this->_newDataTemplate[$r["attribute_code"]] = $r["default_value"];
            }

            if( $r["backend_model"] == "eav/entity_attribute_backend_array" ) 
            {
                $r["frontend_input"] = "multiselect";
            }

            $r["rtidx_eav"] = (!empty($r["is_filterable"]) || !empty($r["is_filterable_in_search"]) || !empty($r["is_visible_in_advanced_search"])) && ($r["backend_type"] == "int" && $r["frontend_input"] == "select" || $r["backend_type"] == "varchar" && $r["frontend_input"] == "multiselect" || $r["backend_type"] == "decimal") || $r["attribute_code"] == "price";
            $r["rtidx_price"] = in_array($r["attribute_code"], array( "price", "special_price", "special_from_date", "special_to_date", "tax_class_id", "status", "required_options" ));
            $r["rtidx_tag"] = in_array($r["attribute_code"], array( "visibility", "status" ));
            $r["rtidx_category"] = in_array($r["attribute_code"], array( "visibility", "status" ));
            $r["rtidx_stock"] = in_array($r["attribute_code"], array( "status" ));
            $r["rtidx_search"] = !empty($r["is_searchable"]);
            $r["rtidx_url"] = in_array($r["attribute_code"], array( "url_key" ));
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

            if( $r["backend_model"] == "catalog/product_attribute_backend_price" ) 
            {
                if( Mage::helper("catalog")->isPriceGlobal() ) 
                {
                    $r["is_global"] = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL;
                }
                else
                {
                    $r["is_global"] = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
                }

            }

            $this->_attributesById[$r["attribute_id"]] = $r;
            $this->_attributesByCode[$r["attribute_code"]] =& $this->_attributesById[$r["attribute_id"]];
            $aType = $this->getAttrType($r);
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
                }

                $text = strtolower(trim($r["value"]));
                if( empty($this->_attributesById[$r["attribute_id"]]["options_bytext"][$text]) ) 
                {
                    $this->_attributesById[$r["attribute_id"]]["options_bytext"][$text] = $r["option_id"];
                }

            }
        }

    }

    protected function _prepareSystemAttributes()
    {
        $rows = $this->_read->fetchAll("" . "select * from " . $this->_t("eav/attribute_set") . " where entity_type_id='" . $this->_entityTypeId . "' order by sort_order, attribute_set_name");
        $attr = array( "options" => array(  ), "options_bytext" => array(  ), "frontend_label" => "Attribute Set", "frontend_input" => "select", "backend_type" => "static", "force_field" => "attribute_set_id", "is_required" => 1 );
        foreach( $rows as $r ) 
        {
            $attr["options"][$r["attribute_set_id"]] = $r["attribute_set_name"];
            $attr["options_bytext"][strtolower($r["attribute_set_name"])] = $r["attribute_set_id"];
        }
        $this->_attributesByCode["product.attribute_set"] = $attr;
        $rows = Mage::getmodel("catalog/product_type")->getOptionArray();
        $attr = array( "options" => array(  ), "options_bytext" => array(  ), "frontend_label" => "Product Type", "frontend_input" => "select", "backend_type" => "static", "force_field" => "type_id", "is_required" => 1 );
        foreach( $rows as $k => $v ) 
        {
            $attr["options"][$k] = $k;
            $attr["options_bytext"][$k] = $k;
        }
        $this->_attributesByCode["product.type"] = $attr;
        $rows = Mage::app()->getWebsites(true);
        $attr = array( "options" => array(  ), "options_bytext" => array(  ), "frontend_label" => "Websites", "frontend_input" => "multiselect", "backend_type" => "static" );
        foreach( $rows as $k => $v ) 
        {
            if( !$k ) 
            {
                continue;
            }

            $attr["options"][$v->getId()] = $v->getCode();
            $attr["options_bytext"][strtolower($v->getCode())] = $v->getId();
        }
        $this->_attributesByCode["product.websites"] = $attr;
        $this->_attributesByCode["category.ids"] = array( "frontend_label" => "Category ID(s)", "frontend_input" => "multiselect" );
        $this->_attributesByCode["category.path"] = array( "frontend_label" => "Category Path(s)", "frontend_input" => "multiselect" );
        $this->_attributesByCode["category.name"] = array( "frontend_label" => "Category Name(s)", "frontend_input" => "multiselect" );
        $yesStr = $this->__("Yes");
        $noStr = $this->__("No");
        $pOptAttrs = array( "has_options" => $this->__("Has Options") );
        if( $this->_hlp()->hasMageFeature("product.required_options") ) 
        {
            $pOptAttrs["required_options"] = $this->__("Has Required Options");
        }

        foreach( $pOptAttrs as $pOpt => $pOptLbl ) 
        {
            $this->_attributesByCode["product." . $pOpt] = array( "frontend_label" => $pOptLbl, "frontend_input" => "select", "backend_type" => "static", "force_field" => $pOpt, "options" => array( $noStr, $yesStr ), "options_bytext" => array( strtolower($noStr) => 0, strtolower($yesStr) => 1 ) );
        }
        $inStockStr = $this->__("In Stock");
        $outOfStockStr = $this->__("Out of Stock");
        $this->_attributesByCode["stock.is_in_stock"] = array( "frontend_label" => $this->__("Is In Stock"), "frontend_input" => "select", "backend_type" => "int", "options" => array( $outOfStockStr, $inStockStr ), "options_bytext" => array( strtolower($noStr) => 0, strtolower($yesStr) => 1, strtolower($outOfStockStr) => 0, strtolower($inStockStr) => 1 ) );
        $noBackordersStr = $this->__("No Backorders");
        $allowQtyBelow0Str = $this->__("Allow Qty Below 0");
        $allowQtyBelow0andNotifyStr = $this->__("Allow Qty Below 0 and Notify Customer");
        $this->_attributesByCode["stock.backorders"] = array( "frontend_label" => $this->__("Backorders"), "frontend_input" => "select", "backend_type" => "int", "options" => array( $noBackordersStr, $allowQtyBelow0Str, $allowQtyBelow0andNotifyStr ), "options_bytext" => array( strtolower($noStr) => 0, strtolower($yesStr) => 1, strtolower($noBackordersStr) => 0, strtolower($allowQtyBelow0Str) => 1, strtolower($allowQtyBelow0andNotifyStr) => 2 ) );
        $yesno = array( "frontend_input" => "select", "backend_type" => "int", "options" => array( $noStr, $yesStr ), "options_bytext" => array( "" => 0, strtolower($noStr) => 0, strtolower($yesStr) => 1 ) );
        $fields = array( "manage_stock" => $this->__("Manage Stock"), "use_config_manage_stock" => $this->__("Use Config for Managing Stock"), "is_qty_decimal" => $this->__("Is quantity decimal"), "use_config_notify_stock_qty" => $this->__("Use Config for Stock Qty Notifications"), "use_config_min_qty" => $this->__("Use Config for Minimal Stock Qty"), "use_config_backorders" => $this->__("Use Config for Backorders"), "use_config_min_sale_qty" => $this->__("Use Config for Minimal Sale Qty"), "use_config_max_sale_qty" => $this->__("Use Config for Maximum Sale Qty"), "stock_status_changed_automatically" => $this->__("Stock Status Changed Automatically"), "use_config_enable_qty_increments" => $this->__("Use Config for Enable Qty Increments"), "enable_qty_increments" => $this->__("Enable Qty Increments"), "use_config_qty_increments" => $this->__("Use Config for Qty Increments") );
        foreach( $fields as $k => $l ) 
        {
            $this->_attributesByCode["stock." . $k] = $yesno + array( "frontend_label" => $l );
        }
        $fields = array( "qty" => $this->__("Qty in Stock"), "min_qty" => $this->__("Minimal Qty"), "min_sale_qty" => $this->__("Minimal Sale Qty"), "max_sale_qty" => $this->__("Maximum Sale Qty"), "notify_stock_qty" => $this->__("Notify Stock Qty"), "qty_increments" => $this->__("Qty Increments") );
        if( !isset($this->_profile) || $this->_profile->getProfileType() == "import" ) 
        {
            $fields["addqty"] = $this->__("Increment/Decrement Qty in Stock");
        }

        foreach( $fields as $k => $l ) 
        {
            $this->_attributesByCode["stock." . $k] = array( "frontend_label" => $l, "backend_type" => "decimal", "is_required" => $k == "qty" );
        }
        $fixedStr = $this->__("Fixed");
        $dynamicStr = $this->__("Dynamic");
        $fixedIdx = Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Extend::FIXED;
        $dynIdx = Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Extend::DYNAMIC;
        foreach( array( "price_type", "weight_type", "sku_type" ) as $f ) 
        {
            $this->_attributesByCode[$f]["frontend_input"] = "select";
            $this->_attributesByCode[$f]["options"] = array( $fixedIdx => $fixedStr, $dynIdx => $dynamicStr );
            $this->_attributesByCode[$f]["options_bytext"] = array( "" => 0, strtolower($fixedStr) => $fixedIdx, strtolower($dynamicStr) => $dynIdx );
        }
        $togetherStr = $this->__("Together");
        $separatelyStr = $this->__("Separately");
        $f = "shipment_type";
        $this->_attributesByCode[$f]["frontend_input"] = "select";
        $this->_attributesByCode[$f]["options"] = array( $togetherStr, $separatelyStr );
        $this->_attributesByCode[$f]["options_bytext"] = array( "" => 0, strtolower($togetherStr) => 0, strtolower($separatelyStr) => 1 );
        $this->_attributesByCode["visibility"]["options_bytext"]["nowhere"] = 1;
        if( !isset($this->_profile) || $this->_profile->getProfileType() == "export" ) 
        {
            $this->_attributesByCode["product.entity_id"] = array( "frontend_label" => "Entity ID", "frontend_input" => "text", "backend_type" => "static", "force_field" => "entity_id" );
            $this->_attributesByCode["product.configurable_parent_sku"] = array( "frontend_label" => "Configurable Parent Sku", "frontend_input" => "text", "backend_type" => "text" );
            $this->_attributesByCode["price.final"] = array( "attribute_code" => "price.final", "frontend_input" => "text", "frontend_label" => $this->__("Final Price"), "backend_type" => "decimal" );
            $this->_attributesByCode["price.minimal"] = array( "attribute_code" => "price.minimal", "frontend_input" => "text", "frontend_label" => $this->__("Minimal Price"), "backend_type" => "decimal" );
            if( $this->_hlp()->hasMageFeature("indexer_1.4") ) 
            {
                $this->_attributesByCode["price.maximum"] = array( "attribute_code" => "price.maximum", "frontend_input" => "text", "frontend_label" => $this->__("Maximum Price"), "backend_type" => "decimal" );
            }

        }

        if( Mage::app()->getDefaultStoreView() && Mage::helper("catalog/product_flat")->isEnabled(Mage::app()->getDefaultStoreView()->getId()) ) 
        {
            $this->_rtIdxFlatAttrCodes = Mage::getsingleton("catalog/product_flat_indexer")->getResource()->getAttributeCodes();
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

            $unknownField = false;
            if( is_array($f) ) 
            {
                $unknownField = true;
                foreach( $f as $_f ) 
                {
                    $unknownField = $unknownField && !isset($this->_attributesByCode[$_f]);
                }
            }

            if( $unknownField || !is_array($f) && !isset($this->_attributesByCode[$f]) ) 
            {
                $profile->addValue("num_warnings");
                $logger->setLine(2)->setColumn($i + 1)->warning($this->__("Unknown field, the column will be ignored"));
            }

        }
    }

    protected function _prepareCategories()
    {
        self::validatelicense("Unirgy_RapidFlow");
        $storeId = $this->_profile->getStoreId();
        $rootStr = $this->__("[ROOT]");
        $rootKeyStr = strtolower($rootStr);
        $rootCatId = $this->_getRootCatId();
        $rootPath = $rootCatId ? "1/" . $rootCatId . "/" : "1/";
        $eav = Mage::getsingleton("eav/config");
        $categories = array( $rootCatId => array( "name" => $rootStr, "name_path" => $rootStr, "path" => "1/" . $rootCatId, "parent_id" => 1 ) );
        $allCategories = array( 1 => 1, $rootCatId => $rootCatId );
        $this->_attributesByCode["category.name"]["options"][$rootCatId] = $rootStr;
        $this->_attributesByCode["category.name"]["options_bytext"][$rootKeyStr] = $rootCatId;
        $this->_attributesByCode["category.path"]["options"][$rootCatId] = $rootStr;
        $this->_attributesByCode["category.path"]["options_bytext"][$rootKeyStr] = $rootCatId;
        $suffix = Mage::getstoreconfig("catalog/seo/category_url_suffix", $storeId);
        $suffixLen = strlen($suffix);
        $table = $this->_t("catalog/category");
        $rows = $this->_read->fetchAll("" . "select entity_id, path, parent_id, position from " . $table);
        if( $rows ) 
        {
            $childrenMaxPos = array(  );
            foreach( $rows as $r ) 
            {
                $allCategories[$r["entity_id"]] = $r["entity_id"];
                if( strpos($r["path"], $rootPath) === 0 ) 
                {
                    $categories[$r["entity_id"]] = $r;
                    $childrenMaxPos[$r["parent_id"]] = isset($childrenMaxPos[$r["parent_id"]]) ? max($childrenMaxPos[$r["parent_id"]], $r["position"]) : $r["position"];
                }

            }
            $this->_attributesByCode["category.ids"]["children_max_pos"] = $childrenMaxPos;
            $this->_attributesByCode["category.ids"]["options"] = $allCategories;
            $this->_attributesByCode["category.ids"]["options_bytext"] = $allCategories;
            foreach( array( "name", "url_path" ) as $k ) 
            {
                $attrId = $eav->getAttribute("catalog_category", $k)->getAttributeId();
                $sql = $this->_read->quoteInto("" . "select entity_id, value from " . $table . "_varchar where attribute_id=" . $attrId . " and store_id in (0, " . $storeId . ") and entity_id in (?) order by store_id desc", array_keys($categories));
                $rows = $this->_read->fetchAll($sql);
                foreach( $rows as $r ) 
                {
                    if( empty($categories[$r["entity_id"]][$k]) ) 
                    {
                        $categories[$r["entity_id"]][$k] = $r["value"];
                    }

                }
            }
            $noUrlPath = $this->_hlp()->hasMageFeature("no_url_path");
            if( $noUrlPath ) 
            {
                $k = "url_key";
                $a = $eav->getAttribute("catalog_category", $k);
                $attrId = $a->getAttributeId();
                $attrTable = $a->getData("backend_table");
                $sql = $this->_read->quoteInto("" . "select entity_id, value from " . $attrTable . "\n                 where attribute_id=" . $attrId . " and store_id in (0, " . $storeId . ") and entity_id in (?)\n                 order by store_id desc", array_keys($categories));
                $rows = $this->_read->fetchAll($sql);
                foreach( $rows as $r ) 
                {
                    if( empty($categories[$r["entity_id"]][$k]) ) 
                    {
                        $categories[$r["entity_id"]][$k] = $r["value"];
                    }

                }
            }

            $delimiter = !empty($this->_fields["category.name"]["delimiter"]) ? $this->_fields["category.name"]["delimiter"] : " > ";
            foreach( $categories as $id => &$c ) 
            {
                if( $noUrlPath ) 
                {
                    $c["url_path"] = $this->catBuildPath($c, $categories);
                }

                $c["name_path"] = array(  );
                $key = array(  );
                $pathArr = explode("/", $c["path"]);
                foreach( $pathArr as $i ) 
                {
                    if( $i == 1 || $i == $rootCatId ) 
                    {
                        continue;
                    }

                    if( !empty($categories[$i]["name"]) ) 
                    {
                        $c["name_path"][] = $categories[$i]["name"];
                        $key[] = strtolower(trim($categories[$i]["name"]));
                    }

                }
                if( $key ) 
                {
                    $this->_attributesByCode["category.name"]["options"][$id] = join($delimiter, $c["name_path"]);
                    $this->_attributesByCode["category.name"]["options_bytext"][join(">", $key)] = $id;
                }

                if( !empty($c["url_path"]) ) 
                {
                    $this->_attributesByCode["category.path"]["options"][$id] = $c["url_path"];
                    $this->_attributesByCode["category.path"]["options_bytext"][$c["url_path"]] = $id;
                    if( $suffix ) 
                    {
                        $additionalKey = substr($c["url_path"], 0 - $suffixLen) == $suffix ? substr($c["url_path"], 0, strlen($c["url_path"]) - $suffixLen) : $c["url_path"] . $suffix;
                        $this->_attributesByCode["category.path"]["options_bytext"][$additionalKey] = $id;
                    }

                }

            }
            unset($c);
        }

        $this->_categories = $categories;
    }

    protected function _prepareWebsites()
    {
        self::validatelicense("Unirgy_RapidFlow");
        foreach( Mage::app()->getStores() as $sId => $store ) 
        {
            $wId = $store->getWebsiteId();
            foreach( Mage::app()->getWebsite($wId)->getStores() as $wsId => $s ) 
            {
                $this->_websiteStores[$sId][] = $wsId;
            }
            $this->_storesByWebsite[$wId][$sId] = $store->toArray();
            $this->_websitesByStore[$sId][$wId] = $store->getWebsite()->toArray();
        }
    }

    protected function _importFetchOldData()
    {
        $this->_skus = array(  );
        $this->_products = array(  );
        $this->_productIds = array(  );
        if( !$this->_newData ) 
        {
            return NULL;
        }

        $attributeFields = array_flip($this->_fieldAttributes);
        $skus = array(  );
        foreach( $this->_newData as $sku => $p ) 
        {
            $skus[] = $sku;
        }
        $select = $this->_write->select()->from($this->_t("catalog/product"))->where("sku in (?)", $skus);
        $productRows = $this->_write->fetchAll($select);
        unset($select);
        foreach( $productRows as $r ) 
        {
            $this->_skus[$r["sku"]] = $r["entity_id"];
            $r1 = array(  );
            foreach( $r as $k => $v ) 
            {
                if( !empty($attributeFields[$k]) ) 
                {
                    $r1[$attributeFields[$k]] = $v;
                }
                else
                {
                    $r1[$k] = $v;
                }

            }
            $this->_products[$r["entity_id"]][0] = $r1;
        }
        $this->_productIds = array_keys($this->_products);
    }

    protected function _importResetPageData()
    {
        $this->_isLastPage = false;
        $this->_products = array(  );
        $this->_skus = array(  );
        $this->_newData = array(  );
        $this->_valid = array(  );
        $this->_defaultUsed = array(  );
        $this->_skuLine = array(  );
        $this->_productIdsUpdated = array(  );
        $this->_attrValueIds = array(  );
        $this->_attrValuesFetched = array(  );
        $this->_websiteScope = array(  );
        $this->_websiteScopeProducts = array(  );
        $this->_websiteScopeAttributes = array(  );
        $this->_insertEntity = array(  );
        $this->_updateEntity = array(  );
        $this->_changeAttr = array(  );
        $this->_insertAttr = array(  );
        $this->_updateAttr = array(  );
        $this->_deleteAttr = array(  );
        $this->_changeWebsite = array(  );
        $this->_changeStock = array(  );
        $this->_insertStock = array(  );
        $this->_updateStock = array(  );
        $this->_deleteStock = array(  );
        $this->_changeCategoryProduct = array(  );
        $this->_mediaChanges = array(  );
        foreach( $this->_realtimeIdx as &$idx ) 
        {
            $idx = array(  );
        }
    }

    protected function _fetchAttributeValues($storeId, $defaults = false, $productIds = null, $limitAttrIds = null)
    {
        if( $this->_profile->getData("options/import/actions") == "create" ) 
        {
            return NULL;
        }

        if( !empty($this->_attrValuesFetched[$storeId]) ) 
        {
            return NULL;
        }

        if( $productIds === null ) 
        {
            $productIds = $this->_productIds;
        }

        $table = $this->_t("catalog/product");
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
            $rows = $this->_read->fetchAll($this->_read->select()->from($table . "_" . $type)->where("entity_id in (?)", $productIds)->where("attribute_id in (?)", $attrIds)->where("store_id in (0, ?)", $storeId));
            if( empty($rows) ) 
            {
                continue;
            }

            foreach( $rows as $r ) 
            {
                $attrCode = $this->_attr($r["attribute_id"], "attribute_code");
                if( empty($this->_products[$r["entity_id"]][$r["store_id"]][$attrCode]) ) 
                {
                    if( $this->_attr($r["attribute_id"], "frontend_input") == "multiselect" ) 
                    {
                        if( $r["value"] === "" || $r["value"] === null ) 
                        {
                            $r["value"] = array(  );
                        }
                        else
                        {
                            $r["value"] = array_unique(explode(",", $r["value"]));
                        }

                    }

                    $this->_products[$r["entity_id"]][$r["store_id"]][$attrCode] = $r["value"];
                }
                else
                {
                    if( !is_array($this->_products[$r["entity_id"]][$r["store_id"]][$attrCode]) ) 
                    {
                        if( $r["value"] != $this->_products[$r["entity_id"]][$r["store_id"]][$attrCode] ) 
                        {
                            $this->_products[$r["entity_id"]][$r["store_id"]][$attrCode] = array( $this->_products[$r["entity_id"]][$r["store_id"]][$attrCode], $r["value"] );
                        }

                    }
                    else
                    {
                        if( !in_array($r["value"], $this->_products[$r["entity_id"]][$r["store_id"]][$attrCode]) ) 
                        {
                            $this->_products[$r["entity_id"]][$r["store_id"]][$attrCode][] = $r["value"];
                        }

                    }

                }

                $this->_attrValueIds[$r["entity_id"]][$r["store_id"]][$attrCode] = $r["value_id"];
            }
            unset($rows);
        }
        if( $defaults ) 
        {
            $this->_attrValuesFetched[0] = true;
        }

        $this->_attrValuesFetched[$storeId] = true;
    }

    protected function _fetchWebsiteValues()
    {
        if( $this->_hasColumnsLike("product.websites") ) 
        {
            $sql = $this->_read->quoteInto("" . "select * from " . $this->_t("catalog/product_website") . " where website_id<>0 and product_id in (?)", $this->_productIds);
            if( $rows = $this->_read->fetchAll($sql) ) 
            {
                foreach( $rows as $r ) 
                {
                    $this->_products[$r["product_id"]][0]["product.websites"][] = $r["website_id"];
                }
            }

        }

    }

    protected function _fetchCategoryValues()
    {
        if( $this->_hasColumnsLike("category.") ) 
        {
            $sql = $this->_read->quoteInto("" . "select * from " . $this->_t("catalog/category_product") . " where product_id in (?)", $this->_productIds);
            if( $rows = $this->_read->fetchAll($sql) ) 
            {
                foreach( $rows as $r ) 
                {
                    $this->_products[$r["product_id"]][0]["category.ids"][] = $r["category_id"];
                    $this->_products[$r["product_id"]][0]["category.path"][] = $r["category_id"];
                    $this->_products[$r["product_id"]][0]["category.name"][] = $r["category_id"];
                    $this->_products[$r["product_id"]][0]["category.position"][$r["category_id"]] = $r["position"];
                }
            }

        }

    }

    protected function _fetchStockValues()
    {
        if( $this->_hasColumnsLike("stock.") ) 
        {
            $sql = $this->_read->quoteInto("" . "select * from " . $this->_t("cataloginventory/stock_item") . " where product_id in (?)", $this->_productIds);
            if( $rows = $this->_read->fetchAll($sql) ) 
            {
                foreach( $rows as $r ) 
                {
                    foreach( $r as $k => $v ) 
                    {
                        if( $k != "item_id" && $k != "product_id" && $k != "stock_id" ) 
                        {
                            $this->_products[$r["product_id"]][0]["stock." . $k] = $v;
                        }

                    }
                }
            }

        }

        if( $this->_profile && $this->_profile->getData("options/export/configurable_qty_as_sum") && (empty($this->_fieldsCodes) || array_key_exists("stock.qty", $this->_fieldsCodes)) ) 
        {
            $confSumQty = $this->_calculateConfigurableSumQty();
            foreach( $confSumQty as $pId => $sumQty ) 
            {
                $this->_products[$pId][0]["stock.qty"] = $sumQty;
            }
        }

    }

    protected function _calculateConfigurableSumQty()
    {
        return $this->_getReadAdapter()->fetchPairs($this->_getReadAdapter()->select()->from(array( "sp" => $this->_t("catalog/product") ), array(  ))->join(array( "psl" => $this->_t("catalog/product_super_link") ), "sp.entity_id=psl.parent_id", array(  ))->join(array( "csi" => $this->_t("cataloginventory/stock_item") ), "psl.product_id=csi.product_id", array(  ))->where("sp.entity_id in (?)", array_keys($this->_products))->group("sp.entity_id")->columns(array( "sp.entity_id", "sum(IF(csi.qty>0, csi.qty, 0))" )));
    }

    protected function _importProcessNewData()
    {
        foreach( $this->_newData as $sku => $p ) 
        {
            if( empty($this->_skus[$sku]) || isset($p["name"]) || isset($p["url_key"]) ) 
            {
                $urlKey = null;
                if( !empty($p["url_key"]) ) 
                {
                    $urlKey = $p["url_key"];
                }
                else
                {
                    if( !empty($p["name"]) && empty($this->_skus[$sku]) ) 
                    {
                        $urlKey = $p["name"];
                    }
                    else
                    {
                        if( !empty($this->_skus[$sku]) ) 
                        {
                            $pId = $this->_skus[$sku];
                            if( !empty($this->_products[$pId][0]["url_key"]) ) 
                            {
                                $urlKey = $this->_products[$pId][0]["url_key"];
                            }
                            else
                            {
                                if( !empty($this->_products[$pId][0]["name"]) ) 
                                {
                                    $urlKey = $this->_products[$pId][0]["name"];
                                }

                            }

                        }

                    }

                }

                $this->_newData[$sku]["url_key"] = $this->_createUrlKey($urlKey, $sku, $this->_t("catalog/product") . "_url_key");
            }

            if( !empty($p["category.name"]) ) 
            {
                $delimiter = !empty($this->_fields["category.name"]["delimiter"]) ? $this->_fields["category.name"]["delimiter"] : ">";
                foreach( $this->_newData[$sku]["category.name"] as $i => $v ) 
                {
                    $levels = explode($delimiter, $v);
                    $newArr = array(  );
                    foreach( $levels as $l ) 
                    {
                        $newArr[] = trim($l);
                    }
                    $this->_newData[$sku]["category.name"][$i] = join($delimiter, $newArr);
                }
            }

            if( !empty($this->_skus[$sku]) ) 
            {
                foreach( $this->_defaultUsed[$sku] as $k => $v ) 
                {
                    unset($this->_newData[$sku][$k]);
                }
            }

        }
    }

    protected function _importCreateAttributeSet($name)
    {
        $attr = "product.attribute_set";
        $name = trim($name);
        if( !empty($this->_attributesByCode[$attr]["options_bytext"][strtolower($name)]) ) 
        {
            return NULL;
        }

        $profile = $this->_profile;
        if( !$profile->getData("options/import/dryrun") ) 
        {
            $w = $this->_write;
            $gTable = $this->_t("eav/attribute_group");
            $eaTable = $this->_t("eav/entity_attribute");
            if( !$this->_tplAttrSet ) 
            {
                $tplId = (int) $profile->getData("options/import/create_attributeset_template");
                $this->_tplAttrSet = array( "groups" => $w->fetchAll("" . "select * from " . $gTable . " where attribute_set_id=" . $tplId), "attrs" => $w->fetchAll("" . "select * from " . $eaTable . " where attribute_set_id=" . $tplId) );
            }

            $this->_write->insert($this->_t("eav/attribute_set"), array( "entity_type_id" => $this->_entityTypeId, "attribute_set_name" => $name ));
            $asId = $w->lastInsertId();
            foreach( $this->_tplAttrSet["groups"] as $g ) 
            {
                $g1 = $g;
                $g1["attribute_set_id"] = $asId;
                unset($g1["attribute_group_id"]);
                $w->insert($gTable, $g1);
                $gId = $w->lastInsertId();
                foreach( $this->_tplAttrSet["attrs"] as $a ) 
                {
                    if( $a["attribute_group_id"] != $g["attribute_group_id"] ) 
                    {
                        continue;
                    }

                    unset($a["entity_attribute_id"]);
                    $a["attribute_set_id"] = $asId;
                    $a["attribute_group_id"] = $gId;
                    $w->insert($eaTable, $a);
                }
            }
        }
        else
        {
            $asId = 0;
            foreach( $this->_attributesByCode[$attr]["options"] as $k => $v ) 
            {
                $asId = max($asId, $k);
            }
            $asId++;
        }

        $this->_attributesByCode[$attr]["options"][$asId] = $name;
        $this->_attributesByCode[$attr]["options_bytext"][strtolower($name)] = $asId;
        return $asId;
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
            if( !empty($this->_attributesById[$aId]["options"]) ) 
            {
                $oId = 0;
                foreach( $this->_attributesById[$aId]["options"] as $k => $v ) 
                {
                    $oId = max($oId, $k);
                }
                $oId++;
            }
            else
            {
                $oId = 1;
            }

        }

        $this->_attributesById[$aId]["options"][$oId] = $name;
        $this->_attributesById[$aId]["options_bytext"][strtolower($name)] = $oId;
        return $oId;
    }

    protected function _importCreateCategory($name)
    {
        $profile = $this->_profile;
        $storeId = $this->_storeId;
        $attr = "category.name";
        if( !$profile->getData("options/import/dryrun") ) 
        {
            if( $this->_autoCategory === null ) 
            {
                $row = $this->_read->fetchRow("" . "select entity_type_id, default_attribute_set_id from " . $this->_t("eav/entity_type") . " where entity_type_code='catalog_category'");
                $eav = Mage::getsingleton("eav/config");
                $this->_autoCategory = array( "type_id" => $row["entity_type_id"], "attribute_set_id" => $row["default_attribute_set_id"], "suffix" => Mage::getstoreconfig("catalog/seo/category_url_suffix", $storeId), "default" => array( "is_active" => $profile->getData("options/import/create_categories_active"), "is_anchor" => $profile->getData("options/import/create_categories_anchor"), "display_mode" => $profile->getData("options/import/create_categories_display"), "name" => null, "url_key" => null, "url_path" => null ) );
                if( $this->_hlp()->hasMageFeature("category.include_in_menu") ) 
                {
                    $this->_autoCategory["default"]["include_in_menu"] = $profile->getData("options/import/create_categories_menu");
                }

                foreach( $this->_autoCategory["default"] as $a => $v ) 
                {
                    $a1 = $eav->getAttribute("catalog_category", $a);
                    $this->_autoCategory["attr"][$a] = array( "type" => $this->getAttrType($a1->getData(), "catalog/category"), "id" => $a1->getId() );
                }
            }

            $delimiter = !empty($this->_fields[$attr]["delimiter"]) ? $this->_fields[$attr]["delimiter"] : " > ";
            $path = "1/" . $this->_getRootCatId();
            $parentId = $this->_getRootCatId();
            $namePathArr = array(  );
            $urlPathArr = array(  );
            $level = 1;
            $table = $this->_t("catalog/category");
            $createdInPaths = array(  );
            $catNameArr = explode(trim($delimiter), $name);
            foreach( $catNameArr as $i => $catName ) 
            {
                $catName = trim($catName);
                $level++;
                $namePathArr[] = $catName;
                $namePath = join($delimiter, $namePathArr);
                $namePathKey = strtolower(join(">", $namePathArr));
                if( empty($this->_attributesByCode[$attr]["options_bytext"][$namePathKey]) ) 
                {
                    if( !isset($this->_attributesByCode["category.ids"]["children_max_pos"][$parentId]) ) 
                    {
                        $this->_attributesByCode["category.ids"]["children_max_pos"][$parentId] = 0;
                    }

                    $this->_write->insert($table, array( "entity_type_id" => $this->_autoCategory["type_id"], "attribute_set_id" => $this->_autoCategory["attribute_set_id"], "parent_id" => $parentId, "created_at" => now(), "updated_at" => now(), "path" => $path, "position" => ++$this->_attributesByCode["category.ids"]["children_max_pos"][$parentId], "level" => $level, "children_count" => 0 ));
                    $cId = $this->_write->lastInsertId();
                    $parentId = $cId;
                    $createdInPaths[] = $path;
                    $path .= "/" . $cId;
                    $this->_write->update($table, array( "path" => $path ), "" . "entity_id='" . $cId . "'");
                    $attrValues = $this->_autoCategory["default"];
                    $attrValues["name"] = $catName;
                    $attrValues["url_key"] = $this->_hlp()->formatUrlKey($catName);
                    $urlPathArr[] = $attrValues["url_key"];
                    $urlPath = join("/", $urlPathArr) . $this->_autoCategory["suffix"];
                    $attrValues["url_path"] = $urlPath;
                    foreach( $attrValues as $a => $v ) 
                    {
                        $a1 = $this->_autoCategory["attr"][$a];
                        $this->_write->insert($table . "_" . $a1["type"], array( "entity_type_id" => $this->_autoCategory["type_id"], "entity_id" => $cId, "attribute_id" => $a1["id"], "value" => $v ));
                    }
                    $this->_attributesByCode[$attr]["options"][$cId] = $namePath;
                    $this->_attributesByCode[$attr]["options_bytext"][$namePathKey] = $cId;
                    $this->_attributesByCode["category.path"]["options"][$cId] = $urlPath;
                    $this->_attributesByCode["category.path"]["options_bytext"][$urlPath] = $cId;
                }
                else
                {
                    $parentId = $this->_attributesByCode[$attr]["options_bytext"][$namePathKey];
                    $urlPathArr[] = $this->_write->fetchOne("" . "select `value` from " . $table . "_varchar where entity_id='" . $parentId . "' and attribute_id='" . $this->_autoCategory["attr"]["url_key"]["id"] . "' order by store_id");
                    $this->_attributesByCode["category.path"]["options"][$parentId];
                    $path .= "/" . $parentId;
                    $cId = $parentId;
                }

            }
            if( !empty($createdInPaths) ) 
            {
                $updateCountIds = array(  );
                foreach( $createdInPaths as $cInPath ) 
                {
                    foreach( explode("/", $cInPath) as $cCountId ) 
                    {
                        if( empty($updateCountIds[$cCountId]) ) 
                        {
                            $updateCountIds[$cCountId] = 0;
                        }

                        $updateCountIds[$cCountId]++;
                    }
                }
                foreach( $updateCountIds as $uCountId => $cAddCount ) 
                {
                    $this->_write->query("" . "update " . $table . " set children_count=children_count+" . intval($cAddCount) . " where entity_id=" . intval($uCountId));
                }
            }

        }
        else
        {
            if( !empty($this->_attributesByCode[$attr]["options"]) ) 
            {
                $cId = 0;
                foreach( $this->_attributesByCode[$attr]["options"] as $k => $v ) 
                {
                    $cId = max($cId, $k);
                }
                $cId++;
            }
            else
            {
                $cId = 1;
            }

            $this->_attributesByCode[$attr]["options"][$cId] = $name;
            $this->_attributesByCode[$attr]["options_bytext"][strtolower($name)] = $cId;
        }

        return $cId;
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

        foreach( $this->_changeAttr as $sku => $p ) 
        {
            $pId = $this->_skus[$sku];
            foreach( $p as $k => $v ) 
            {
                $attr = $this->_attr($k);
                if( !$attr ) 
                {
                    continue;
                }

                $aId = $attr["attribute_id"];
                $aType = $this->getAttrType($attr);
                if( is_array($v) ) 
                {
                    $v = join(",", $v);
                }

                $values = array(  );
                if( !empty($this->_products[$pId]) ) 
                {
                    foreach( $this->_products[$pId] as $sId => $sValues ) 
                    {
                        if( isset($sValues[$k]) ) 
                        {
                            $values[$sId] = $sValues[$k];
                        }

                    }
                }

                $sIds = !empty($this->_websiteScope[$sku][$aId]) ? $this->_websiteStores[$storeId] : array( $storeId );
                $sActions = array(  );
                if( $v !== null ) 
                {
                    if( !isset($this->_attrValueIds[$pId][0][$k]) && ($attr["is_required"] || $attr["is_global"] == 1) ) 
                    {
                        $sActions = array( "I" );
                    }

                    foreach( $sIds as $sId ) 
                    {
                        if( $attr["is_global"] == 1 && $sId != 0 && empty($sActions[0]) ) 
                        {
                            $sActions[0] = "U";
                            if( isset($this->_attrValueIds[$pId][$sId][$k]) ) 
                            {
                                $sActions[$sId] = "D";
                            }

                            continue;
                        }

                        if( isset($this->_attrValueIds[$pId][$sId][$k]) ) 
                        {
                            if( $sId != 0 && isset($values[0]) && $v == $values[0] && $sameAsDefault == "default" ) 
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
                else
                {
                    if( isset($this->_attrValueIds[$pId][0][$k]) && (!$storeId || $attr["is_global"] == 1) ) 
                    {
                        $sActions = array( "D" );
                    }
                    else
                    {
                        if( $storeId ) 
                        {
                            foreach( $sIds as $sId ) 
                            {
                                if( $sId && isset($this->_attrValueIds[$pId][$sId][$k]) ) 
                                {
                                    $sActions[$sId] = "D";
                                }

                            }
                        }

                    }

                }

                $this->_rtIdxRegisterAttrChange($sku, $k, $v);
                foreach( $sActions as $sId => $action ) 
                {
                    switch( $action ) 
                    {
                        case "I":
                            $this->_insertAttr[$aType][] = array( "entity_type_id" => $this->_entityTypeId, "attribute_id" => $aId, "store_id" => $sId, "entity_id" => $pId, "value" => $v );
                            break;
                        case "U":
                            $this->_updateAttr[$aType][$this->_attrValueIds[$pId][$sId][$k]] = $v;
                            break;
                        case "D":
                            $this->_deleteAttr[$aType][] = $this->_attrValueIds[$pId][$sId][$k];
                    }
                }
            }
        }
    }

    protected function _importCopyImageFiles()
    {
        return NULL;
    }

    protected function _importSaveEntities()
    {
        $logger = $this->_profile->getLogger()->setColumn(0);
        $table = $this->_t("catalog/product");
        foreach( $this->_insertEntity as $a ) 
        {
            $this->_write->insert($table, $a);
            $pId = $this->_write->lastInsertId();
            $this->_skus[$a["sku"]] = $pId;
            $this->_productIdsUpdated[$pId] = 1;
            $this->_rtIdxRegisterNewProduct($a["sku"]);
            $logger->setLine($this->_skuLine[$a["sku"]])->success(null, 1);
        }
        foreach( $this->_updateEntity as $pId => $a ) 
        {
            $this->_write->update($table, $a, "entity_id=" . $pId);
            $this->_productIdsUpdated[$pId] = 1;
        }
    }

    protected function _importSaveAttributeValues()
    {
        $table = $this->_t("catalog/product");
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
                $sqlValue = "" . "('" . $a["entity_type_id"] . "', '" . $a["attribute_id"] . "', '" . $a["store_id"] . "', '" . $a["entity_id"] . "', ?)";
                $value = $type == "varchar" ? substr($a["value"], 0, 255) : $a["value"];
                $sql = $this->_write->quoteInto($sqlValue, $value);
                if( $type == "text" && 4000 < strlen((bool) $value) ) 
                {
                    try
                    {
                        $this->_write->getConnection()->exec($sqlPrefix . $sql);
                    }
                    catch( Exception $e ) 
                    {
                        Mage::log($sqlPrefix . $sql, Zend_Log::DEBUG, "rf.log", true);
                        Mage::log($e->getTraceAsString(), Zend_Log::DEBUG, "rf.log", true);
                        $this->_profile->getLogger()->error($e->getMessage());
                    }
                }
                else
                {
                    $rows[] = $sql;
                }

            }
            $chunks = array_chunk($rows, $this->_insertAttrChunkSize);
            foreach( $chunks as $chunk ) 
            {
                try
                {
                    $this->_write->getConnection()->exec($sqlPrefix . join(",", $chunk));
                }
                catch( Exception $e ) 
                {
                    Mage::log($sqlPrefix . join(",", $chunk), Zend_Log::DEBUG, "rf.log", true);
                    Mage::log($e->getTraceAsString(), Zend_Log::DEBUG, "rf.log", true);
                    $this->_profile->getLogger()->error($e->getMessage());
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
                    Mage::log($table . "_" . $type, Zend_Log::DEBUG, "rf.log", true);
                    Mage::log(array( "value" => $type == "varchar" ? substr($v, 0, 255) : $v ), Zend_Log::DEBUG, "rf.log", true);
                    Mage::log("value_id=" . $k, Zend_Log::DEBUG, "rf.log", true);
                    Mage::log($e->getTraceAsString(), Zend_Log::DEBUG, "rf.log", true);
                    $this->_profile->getLogger()->error($e->getMessage());
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
                Mage::log($table . "_" . $type, Zend_Log::DEBUG, "rf.log", true);
                Mage::log("value_id in (" . join(",", $vIds) . ")", Zend_Log::DEBUG, "rf.log", true);
                Mage::log($e->getTraceAsString(), Zend_Log::DEBUG, "rf.log", true);
                $this->_profile->getLogger()->error($e->getMessage());
            }
        }
    }

    protected function _importSaveWebsiteValues()
    {
        if( $this->_changeWebsite ) 
        {
            $table = $this->_t("catalog/product_website");
            $insert = array(  );
            $delete = array(  );
            foreach( $this->_changeWebsite as $sku => $actions ) 
            {
                if( empty($this->_skus[$sku]) ) 
                {
                    continue;
                }

                $pId = $this->_skus[$sku];
                foreach( $actions["I"] as $wId ) 
                {
                    $insert[] = "" . "('" . $pId . "','" . $wId . "')";
                }
                foreach( $actions["D"] as $wId ) 
                {
                    $delete[] = "" . "(`product_id`='" . $pId . "' and `website_id`='" . $wId . "')";
                }
                $this->_rtIdxRegisterWebsiteChange($sku, $actions);
            }
            if( $insert ) 
            {
                $this->_write->query("" . "insert ignore into `" . $table . "` (`product_id`, `website_id`) values " . join(",", $insert));
            }

            if( $delete ) 
            {
                $this->_write->query("" . "delete from `" . $table . "` where " . join(" or ", $delete));
            }

        }

    }

    protected function _importSaveProductCategories()
    {
        if( $this->_changeCategoryProduct ) 
        {
            $table = $this->_t("catalog/category_product");
            $insert = array(  );
            $delete = array(  );
            foreach( $this->_changeCategoryProduct as $sku => $actions ) 
            {
                if( empty($this->_skus[$sku]) ) 
                {
                    continue;
                }

                $pId = $this->_skus[$sku];
                foreach( $actions["I"] as $cId => $pos ) 
                {
                    $insert[] = "" . "('" . $cId . "','" . $pId . "','" . $pos . "')";
                }
                foreach( $actions["D"] as $cId ) 
                {
                    $delete[] = "" . "(`product_id`='" . $pId . "' and `category_id`='" . $cId . "')";
                }
                $this->_rtIdxRegisterCategoryChange($sku, $actions);
            }
            if( $insert ) 
            {
                $insertSql = sprintf("insert into `%s` (`category_id`, `product_id`, `position`) values %s " . "on duplicate key update position=values(position)", $table, join(",", $insert));
                $this->_write->query($insertSql);
            }

            if( $delete ) 
            {
                $this->_write->query("" . "delete from `" . $table . "` where " . join(" or ", $delete));
            }

        }

    }

    protected function _importSaveStockValues()
    {
        if( $this->_changeStock ) 
        {
            $table = $this->_t("cataloginventory/stock_item");
            $siColumns = $this->_write->describeTable($table);
            foreach( $this->_changeStock as $sku => $_r ) 
            {
                if( empty($this->_skus[$sku]) ) 
                {
                    continue;
                }

                $r = array(  );
                foreach( $_r as $_rK => $_rV ) 
                {
                    if( array_key_exists($_rK, $siColumns) ) 
                    {
                        $r[$_rK] = $_rV;
                    }

                }
                $pId = $this->_skus[$sku];
                if( empty($r) && (!isset($this->_products[$pId][0]["stock.is_in_stock"]) || empty($_r["addqty"])) ) 
                {
                    continue;
                }

                $this->_rtIdxRegisterStockChange($sku, $r);
                if( !isset($this->_products[$pId][0]["stock.is_in_stock"]) ) 
                {
                    $r["stock_id"] = 1;
                    $r["product_id"] = $pId;
                    $this->_write->insert($table, $r);
                }
                else
                {
                    if( empty($_r["qty"]) && !empty($_r["addqty"]) ) 
                    {
                        $r["qty"] = $this->_products[$pId][0]["stock.qty"] + (double) $_r["addqty"];
                    }

                    $this->_write->update($table, $r, "" . "stock_id=1 and product_id='" . $pId . "'");
                }

            }
        }

    }

    protected function _importReindexProducts()
    {
        return NULL;
    }

    protected function _importRefreshRewrites()
    {
        return NULL;
    }

    protected function _afterImport()
    {
    }

    protected function _importUpdateImageGallery()
    {
        if( !$this->_productIdsUpdated ) 
        {
            return NULL;
        }

        $sql = "" . "\ninsert into " . $this->_t("catalog_product_entity_media_gallery") . " (attribute_id, entity_id, `value`)\nselect ga.attribute_id, v.entity_id, v.value\nfrom " . $this->_t("catalog_product_entity_varchar") . " v\n    inner join " . $this->_t("eav_attribute") . " va on va.frontend_input='media_image' and va.attribute_id=v.attribute_id\n    inner join " . $this->_t("eav_attribute") . " ga on ga.attribute_code='media_gallery'\nleft join " . $this->_t("catalog_product_entity_media_gallery") . " g on g.entity_id=v.entity_id and binary g.value=v.value\nwhere v.value<>'no_selection' and v.value<>'' and g.value is null\n    and va.entity_type_id=" . $this->_entityTypeId . " and ga.entity_type_id=" . $this->_entityTypeId . "\n    and v.entity_id in (" . join(",", array_keys($this->_productIdsUpdated)) . ")\n    group by v.entity_id, binary v.value\n    ";
        $this->_write->query($sql);
        if( $this->_deleteOldImage && !empty($this->_mediaChanges) ) 
        {
            $imagesToDelete = array(  );
            $delWhere = array(  );
            foreach( $this->_mediaChanges as $mc ) 
            {
                $imagesToDelete[] = $mc[1];
                $delWhere[] = implode(" AND ", array( $this->_write->quoteInto("entity_id=?", $this->_skus[$mc[2]]), $this->_write->quoteInto("value=?", $mc[1]) ));
            }
            $delWhere = implode(" OR ", $delWhere);
            $delWhere = "" . "(" . $delWhere . ") AND attribute_id=" . $this->_getGalleryAttrId();
            $this->_write->delete($this->_t("catalog_product_entity_media_gallery"), $delWhere);
            if( !$this->_deleteOldImageSkipUsageCheck ) 
            {
                $imgNoToDel = $this->_write->fetchCol("" . "select value from " . $this->_t("catalog_product_entity_media_gallery") . " where value in (" . $this->_write->quote($imagesToDelete) . ")");
                if( !empty($imgNoToDel) ) 
                {
                    $imagesToDelete = array_diff($imagesToDelete, $imgNoToDel);
                }

            }

            if( !empty($imagesToDelete) ) 
            {
                foreach( $imagesToDelete as $_imageToDelete ) 
                {
                    @unlink(@@Mage::getsingleton("catalog/product_media_config")->getMediaPath($_imageToDelete));
                }
            }

        }

    }

    protected function _applyCatalogRules($pIds)
    {
        $rules = Mage::getresourcemodel("urapidflow/catalogRule_collection")->addIsActiveFilter();
        $crAppliedPids = array(  );
        foreach( $rules as $rule ) 
        {
            $rule->getResource()->updateRuleMultiProductData($rule, $pIds);
            if( $rule->getFromDate() <= now(true) ) 
            {
                $matchPids = $rule->getMatchingMultiProductIds($pIds);
                foreach( $matchPids as $matchPid ) 
                {
                    $crAppliedPids[$matchPid] = true;
                }
            }

        }
        if( !empty($crAppliedPids) ) 
        {
            Mage::getmodel("urapidflow/catalogRule")->applyAllByPids(array_keys($crAppliedPids));
        }

        return array_keys($crAppliedPids);
    }

    protected function _importRealtimeReindex()
    {
        if( $this->_hlp()->hasMageFeature("indexer_1.4") && $this->_profile->getData("options/import/reindex_type") == "realtime" ) 
        {
            $indexer = Mage::getsingleton("index/indexer");
            $pAction = Mage::getmodel("catalog/product_action");
            $idxEvent = Mage::getmodel("index/event")->setEntity(Mage_Catalog_Model_Product::ENTITY)->setType(Mage_Index_Model_Event::TYPE_MASS_ACTION)->setDataObject($pAction);
            $pAction->setWebsiteIds(array( 0 ));
            $crAppliedPids = $this->_applyCatalogRules(array_values($this->_skus));
            if( !empty($crAppliedPids) ) 
            {
                foreach( $crAppliedPids as $craPid ) 
                {
                    $this->_realtimeIdx["catalog_product_price"][$craPid] = true;
                }
            }

            Mage::getresourcesingleton("urapidflow/ProductIndexerPrice")->prepareWebsiteDateTable();
            if( !Mage::helper("cataloginventory")->isShowOutOfStock() ) 
            {
                foreach( array( "catalog_product_attribute", "catalog_product_price", "tag_summary", "catalog_category_product" ) as $idxKey ) 
                {
                    $this->_realtimeIdx[$idxKey] = $this->_realtimeIdx[$idxKey] + $this->_realtimeIdx["cataloginventory_stock"];
                }
            }

            foreach( array( "cataloginventory_stock", "catalog_product_attribute", "catalog_product_price", "tag_summary", "catalog_category_product" ) as $idxKey ) 
            {
                if( empty($this->_realtimeIdx[$idxKey]) || !$indexer->getProcessByCode($idxKey) ) 
                {
                    continue;
                }

                $pAction->setProductIds(array_keys($this->_realtimeIdx[$idxKey]));
                $indexer->getProcessByCode($idxKey)->register($idxEvent)->processEvent($idxEvent);
            }
            if( !empty($this->_realtimeIdx["catalogsearch_fulltext"]) ) 
            {
                $exPids = array(  );
                if( !empty($this->_realtimeIdx["catalogsearch_fulltext"]["full"]["C"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalogsearch_fulltext"]["full"]["C"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        $exPids = array_unique(array_merge($exPids, $pIds));
                        foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                        {
                            Mage::getsingleton("catalogsearch/fulltext")->rebuildIndex($sId, $pIds);
                        }
                    }
                }

                if( !empty($this->_realtimeIdx["catalogsearch_fulltext"]["website"]["D"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalogsearch_fulltext"]["website"]["D"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                        {
                            Mage::getsingleton("catalogsearch/fulltext")->cleanIndex($sId, $pIds);
                        }
                    }
                }

                if( !empty($this->_realtimeIdx["catalogsearch_fulltext"]["website"]["I"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalogsearch_fulltext"]["website"]["I"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        if( $pIds = array_diff($pIds, $exPids) ) 
                        {
                            foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                            {
                                Mage::getsingleton("catalogsearch/fulltext")->rebuildIndex($sId, $pIds);
                            }
                        }

                    }
                }

            }

            if( !empty($this->_realtimeIdx["catalog_url"]) ) 
            {
                $exPids = array(  );
                $urlModel = Mage::getsingleton("catalog/url");
                if( !empty($this->_realtimeIdx["catalog_url"]["full"]["C"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_url"]["full"]["C"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        $exPids = array_unique(array_merge($exPids, $pIds));
                        foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                        {
                            foreach( $pIds as $pId ) 
                            {
                                $urlModel->refreshProductRewrite($pId, $sId);
                            }
                        }
                    }
                }

                if( !empty($this->_realtimeIdx["catalog_url"]["website"]["I"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_url"]["website"]["I"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        if( $pIds = array_diff($pIds, $exPids) ) 
                        {
                            foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                            {
                                foreach( $pIds as $pId ) 
                                {
                                    $urlModel->refreshProductRewrite($pId, $sId);
                                }
                            }
                        }

                    }
                }

                if( !empty($this->_realtimeIdx["catalog_url"]["website"]["D"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_url"]["website"]["D"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                        {
                            foreach( $pIds as $pId ) 
                            {
                                $urlModel->refreshProductRewrite($pId, $sId);
                            }
                        }
                    }
                }

            }

            if( !empty($this->_realtimeIdx["catalog_product_flat"]) ) 
            {
                $idxProdFlat = Mage::getsingleton("catalog/product_flat_indexer");
                $exPids = array(  );
                if( !empty($this->_realtimeIdx["catalog_product_flat"]["full"]["C"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_product_flat"]["full"]["C"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        $exPids = array_unique(array_merge($exPids, $pIds));
                        foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                        {
                            $idxProdFlat->updateProduct($pIds, $sId);
                        }
                    }
                }

                if( !empty($this->_realtimeIdx["catalog_product_flat"]["status"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_product_flat"]["status"] as $statusVal => $wData ) 
                    {
                        if( !empty($wData["C"]) ) 
                        {
                            foreach( $wData["C"] as $wId => $_pIds ) 
                            {
                                $pIds = array_keys($_pIds);
                                if( $pIds = array_diff($pIds, $exPids) ) 
                                {
                                    foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                                    {
                                        $idxProdFlat->updateProductStatus($pIds, $statusVal, $sId);
                                    }
                                }

                            }
                        }

                    }
                }

                if( !empty($this->_realtimeIdx["catalog_product_flat"]["by_attr"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_product_flat"]["by_attr"] as $attrCode => $wData ) 
                    {
                        if( !empty($wData["C"]) ) 
                        {
                            foreach( $wData["C"] as $wId => $_pIds ) 
                            {
                                $pIds = array_keys($_pIds);
                                if( $pIds = array_diff($pIds, $exPids) ) 
                                {
                                    foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                                    {
                                        $idxProdFlat->updateAttribute($attrCode, $sId, $pIds);
                                    }
                                }

                            }
                        }

                    }
                }

                if( !empty($this->_realtimeIdx["catalog_product_flat"]["website"]["I"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_product_flat"]["website"]["I"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        if( $pIds = array_diff($pIds, $exPids) ) 
                        {
                            foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                            {
                                $idxProdFlat->updateProduct($pIds, $sId);
                            }
                        }

                    }
                }

                if( !empty($this->_realtimeIdx["catalog_product_flat"]["website"]["D"]) ) 
                {
                    foreach( $this->_realtimeIdx["catalog_product_flat"]["website"]["D"] as $wId => $_pIds ) 
                    {
                        $pIds = array_keys($_pIds);
                        foreach( $this->_storesByWebsite[$wId] as $sId => $sData ) 
                        {
                            $idxProdFlat->removeProduct($pIds, $sId);
                        }
                    }
                }

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

    protected function _hasColumnsLike($prefix)
    {
        if( empty($this->_fieldsCodes) ) 
        {
            return true;
        }

        foreach( $this->_fieldsCodes as $k => $v ) 
        {
            if( strpos($k, $prefix) === 0 ) 
            {
                return true;
            }

        }
        return false;
    }

    protected function _createUrlKey($urlKey, $sku, $entityTable)
    {
        if( empty($urlKey) ) 
        {
            return $urlKey;
        }

        $hlp = $this->_hlp();
        $urlKey = $hlp->formatUrlKey($urlKey);
        if( !$hlp->hasMageFeature("no_url_path") ) 
        {
            return $urlKey;
        }

        if( !$this->_profile->getData("options/import/increment_url_key") ) 
        {
            return $urlKey;
        }

        if( !isset($this->_urlKeys[$entityTable]) ) 
        {
            $this->_loadUrlKeys($entityTable);
        }

        $pId = isset($this->_skus[$sku]) ? $this->_skus[$sku] : null;
        if( $pId && isset($this->_urlKeys[$entityTable]["id_key"][$pId]) ) 
        {
            return $this->_urlKeys[$entityTable]["id_key"][$pId];
        }

        $exists = isset($this->_urlKeys[$entityTable]["key_id"][$urlKey]);
        if( $exists ) 
        {
            $idx = 1;
            while( $exists ) 
            {
                $tmpKey = $urlKey . "-" . $idx++;
                $exists = isset($this->_urlKeys[$entityTable]["key_id"][$tmpKey]);
                if( $idx == 100 && $exists ) 
                {
                    $this->_profile->getLogger()->warning(sprintf("Failed to increment url_key in 100 attempts for SKU: %s", $sku));
                    $tmpKey = null;
                    break;
                }

            }
            if( !empty($tmpKey) ) 
            {
                $urlKey = $tmpKey;
            }

        }

        if( $urlKey ) 
        {
            $idx = $pId ? $pId : $sku;
            $this->_urlKeys[$entityTable]["id_key"][$idx] = $urlKey;
            $this->_urlKeys[$entityTable]["key_id"][$urlKey] = $idx;
        }

        return $urlKey;
    }

    protected function _loadUrlKeys($entityTable)
    {
        $select = $this->_read->select()->from($entityTable, array( "entity_id", "value" ));
        $rows = $this->_read->fetchAll($select);
        $temp = array(  );
        foreach( $rows as $r ) 
        {
            $temp[$r["entity_id"]] = $r["value"];
        }
        $this->_urlKeys[$entityTable]["id_key"] = array_unique($temp);
        $this->_urlKeys[$entityTable]["key_id"] = array_flip($temp);
    }

    protected function _hlp()
    {
        if( !$this->_hlp ) 
        {
            $this->_hlp = Mage::helper("urapidflow");
        }

        return $this->_hlp;
    }

}


