<?php

/**
 * @author     Kristof Ringleff
 * @package    Fooman_PdfCustomiser
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Fooman_PdfCustomiser_Model_System_Customfont extends Mage_Adminhtml_Model_System_Config_Backend_File
{
    /**
     * supply allowed file extensions for fonts
     *
     * @return array
     */
    protected function _getAllowedExtensions()
    {
        return array('ttf');
    }

    /**
     * process uploaded font file and convert to format for use with tcpdf
     *
     * @return void
     */
    protected function _afterSave()
    {
        if ($this->getValue()) {
            $pdf = Mage::getModel(
                'pdfcustomiser/mypdf', array(
                    'P', 'mm', Mage::getStoreConfig('sales_pdf/all/allpagesize'), true, 'UTF-8', false, false
                )
            );
            $pdf->addTTFfont(Mage::getBaseDir('media') . DS . 'pdf-printouts' . DS . $this->getValue());

            //Alternative if encoding of font can't be determined correctly
            //$pdf->addTTFfont(Mage::getBaseDir('media') . DS . 'pdf-printouts' . DS . $this->getValue(),'TrueType','ansi');
        }
    }
}