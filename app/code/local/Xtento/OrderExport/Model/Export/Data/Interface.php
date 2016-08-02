<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2012-11-18T20:56:11+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Interface.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

interface Xtento_OrderExport_Model_Export_Data_Interface {
    public function getExportData($entityType, $collectionItem);
    public function getConfiguration();
}