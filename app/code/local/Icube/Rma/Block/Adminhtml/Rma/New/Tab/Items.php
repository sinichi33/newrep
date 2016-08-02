<?php
/**
 * Items Tab in Edit RMA form
 *
 */
class Icube_Rma_Block_Adminhtml_Rma_New_Tab_Items extends Enterprise_Rma_Block_Adminhtml_Rma_New_Tab_Items
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * Prepare form before rendering HTML
     *
     * @return Enterprise_Rma_Block_Adminhtml_Rma_New_Tab_Items
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'rma_properties_';
        $form->setHtmlIdPrefix($htmlIdPrefix);

        $model = Mage::registry('current_rma');

        $fieldset = $form->addFieldset('rma_item_fields', array());

        $fieldset->addField('product_name', 'text', array(
            'label'=> Mage::helper('enterprise_rma')->__('Product Name'),
            'name' => 'product_name',
            'required'  => false
        ));

        $fieldset->addField('product_sku', 'text', array(
            'label'=> Mage::helper('enterprise_rma')->__('SKU'),
            'name' => 'product_sku',
            'required'  => false
        ));

        //Renderer puts available quantity instead of order_item_id
        $fieldset->addField('qty_ordered', 'text', array(
            'label'=> Mage::helper('enterprise_rma')->__('Remaining Qty'),
            'name' => 'qty_ordered',
            'required'  => false,
        ));

        $fieldset->addField('qty_requested', 'text', array(
            'label'=> Mage::helper('enterprise_rma')->__('Requested Qty'),
            'name' => 'qty_requested',
            'required' => false,
            'class' => 'validate-greater-than-zero'
        ));

        $reasonOtherAttribute =
            Mage::getModel('enterprise_rma/item_form')->setFormCode('default')->getAttribute('reason_other');

        $fieldset->addField('reason_other', 'text', array(
            'label'     => $reasonOtherAttribute->getStoreLabel(),
            'name'      => 'reason_other',
            'maxlength' => 255,
            'required'  => false
        ));

        $fieldset->addField('reason', 'select', array(
            'label'=> Mage::helper('enterprise_rma')->__('Reason to Return'),
            'options' => array(''=>'')
                + Mage::helper('enterprise_rma/eav')->getAttributeOptionValues('reason')
                + array('other' => $reasonOtherAttribute->getStoreLabel()),
            'name' => 'reason',
            'required' => false
        ))->setRenderer(
            $this->getLayout()->createBlock('enterprise_rma/adminhtml_rma_new_tab_items_renderer_reason')
        );

        $fieldset->addField('delete_link', 'label', array(
            'label'=> Mage::helper('enterprise_rma')->__('Delete'),
            'name' => 'delete_link',
            'required' => false
        ));

        $fieldset->addField('add_details_link', 'label', array(
            'label'=> Mage::helper('enterprise_rma')->__('Add Details'),
            'name' => 'add_details_link',
            'required' => false
        ));

        $this->setForm($form);

        return $this;
    }
}
