<?php
class Icube_Vpayment_Block_Adminhtml_Promo_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('promo_edit_form');
        $this->setTitle($this->__('Promo'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('promo');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('Promo'),
        ));
     
        if ($model->getId()) {
            $fieldset->addField('promo_code', 'hidden', array(
                'name' => 'promo_code',
                'label'     => $this->__('Promo Code'),
            ));
        }

        $fieldset->addField('promo_name', 'text', array(
            'name'      => 'promo_name',
            'label'     => $this->__('Name'),
            'required'  => true,
        ));

        $fieldset->addField('program_type', 'select', array(
            'name'      => 'program_type',
            'label'     => $this->__('Type'),
            'required'  => true,
            'values'    => array('bin_filter'=>'BIN Filter'),
        ));

        $val_value_hint = '<p class="nm"><small>' . 'BIN Filter' . '</small></p>';
        $fieldset->addField('validation_value', 'textarea', array(
            'name'      => 'validation_value',
            'label'     => $this->__('Validation Value'),
            'required'  => true,
            'after_element_html' => $val_value_hint,
        ));

        $fieldset->addField('start_date', 'date', array(
            'name'      => 'start_date',
            'label'     => $this->__('Start Date'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => 'M/d/yyyy',
        ));

        $fieldset->addField('end_date', 'date', array(
            'name'      => 'end_date',
            'label'     => $this->__('End Date'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => 'M/d/yyyy',
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }  
}