<?php

/**
 * Product:       Xtento_XtCore (1.1.7)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2013-10-30T18:32:49+01:00
 * File:          app/code/local/Xtento/XtCore/Model/Observer/Cron.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Model_Observer_Cron
{
    public function run()
    {
        return true;
    }

    public function getLastExecution()
    {
        return Mage::getResourceModel('xtcore/config')->getConfigValue('xtcore/crontest/last_execution');
    }

    public function getTimestamp()
    {
        return (string)time();
    }

    public function checkCronjob()
    {
        $lastExecution = $this->getLastExecution();
        if (empty($lastExecution)) {
            return false;
        }
        $differenceInSeconds = $this->getTimestamp() - $lastExecution;
        // If the cronjob has been executed within the last 15 minutes, return true
        return $differenceInSeconds < (60 * 15);
    }
}
