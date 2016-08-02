<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_CountryAddressTypes extends Fooman_Surcharge_Model_System_SurchargeAddressTypes
{

    /**
     * get address options for country surcharges
     *
     * @param bool $fieldType
     * @param bool $displayNo
     *
     * @return array
     */
    public function toOptionArray($fieldType = false, $displayNo = false)
    {
        return parent::toOptionArray($fieldType, $displayNo);
    }
}