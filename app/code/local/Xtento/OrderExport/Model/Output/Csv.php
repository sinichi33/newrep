<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2013-02-10T17:01:41+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Output/Csv.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Output_Csv extends Xtento_OrderExport_Model_Output_Abstract
{
    public function convertData($exportArray)
    {
        return array(); // Not yet implemented.
        /*
        // Convert to XML first
        $convertedData = Mage::getModel('xtento_orderexport/output_xml', array('profile' => $this->getProfile()))->convertData($exportArray);
        // Get "first" file from returned data.
        $convertedXml = array_pop($convertedData);

        $filename = $this->_replaceFilenameVariables($profile->getFilename(), $exportArray);
        $charsetEncoding = $profile->getEncoding();
        $outputXml = $this->_changeEncoding($outputXml, $charsetEncoding);
        $outputData[$filename] = $outputXml;

        // Return data
        return $outputData;
        */
    }
}