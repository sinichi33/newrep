<?php

class Icube_Vpayment_Block_Adminhtml_Promo_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('promo_grid');
        $this->setDefaultSort('promo_code');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('vpayment/program')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('promo_code',
            array(
                'header' => 'Promo Code',
                'align' =>'right',
                'width' => '50px',
                'index' => 'promo_code',
            ));
        $this->addColumn('promo_name',
            array(
                'header' => 'Promo Name',
                'align' =>'right',
                'width' => '50px',
                'index' => 'promo_name',
            ));
        $this->addColumn('validation_value',
            array(
                'header' => 'Validation Value',
                'align' =>'right',
                'width' => '50px',
                'index' => 'validation_value',
            ));
        $this->addColumn('start_date',
            array(
                'header' => 'Start Date',
                'align' =>'right',
                'width' => '50px',
                'index' => 'start_date',
            ));
        $this->addColumn('end_date',
            array(
                'header' => 'End Date',
                'align' =>'right',
                'width' => '50px',
                'index' => 'end_date',
            ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

//    protected function _prepareMassaction()
//    {
//        $this->setMassactionIdField('promo_code');
//        $this->getMassactionBlock()->setFormFieldName('promo');
//        $this->getMassactionBlock()->addItem('delete', array(
//            'label'=> $this->__('Delete'),
//            'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
//            'confirm' => Mage::helper('tax')->__('Are you sure?')
//        ));
//        return $this;
//    }
}