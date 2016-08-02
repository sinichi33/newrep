<?php
$installer = $this;
$installer->startSetup();

//migrate old config values to new paths
$oldToNewMap = array(

    'fooman_surcharge_group/groupdiscountenabled'=>'fooman_surcharge_group/enabled',
    'fooman_surcharge_group/groupamount'=>'fooman_surcharge_group/fixed',
    'fooman_surcharge_group/grouprate'=>'fooman_surcharge_group/rate',
    'fooman_surcharge_group/groupdescription'=>'fooman_surcharge_group/description',
    'fooman_surcharge_group/groupsurchargehandlingtype'=>'fooman_surcharge_group/handlingtype',
    'fooman_surcharge_group/groupselection'=>'fooman_surcharge_group/group',

    'fooman_surcharge_group2/groupdiscountenabled'=>'fooman_surcharge_group2/enabled',
    'fooman_surcharge_group2/groupamount'=>'fooman_surcharge_group2/fixed',
    'fooman_surcharge_group2/grouprate'=>'fooman_surcharge_group2/rate',
    'fooman_surcharge_group2/groupdescription'=>'fooman_surcharge_group2/description',
    'fooman_surcharge_group2/groupsurchargehandlingtype'=>'fooman_surcharge_group2/handlingtype',
    'fooman_surcharge_group2/groupselection'=>'fooman_surcharge_group2/group',

    'fooman_surcharge_group3/groupdiscountenabled'=>'fooman_surcharge_group3/enabled',
    'fooman_surcharge_group3/groupamount'=>'fooman_surcharge_group3/fixed',
    'fooman_surcharge_group3/grouprate'=>'fooman_surcharge_group3/rate',
    'fooman_surcharge_group3/groupdescription'=>'fooman_surcharge_group3/description',
    'fooman_surcharge_group3/groupsurchargehandlingtype'=>'fooman_surcharge_group3/handlingtype',
    'fooman_surcharge_group3/groupselection'=>'fooman_surcharge_group3/group',

    'fooman_surcharge_minfee/minfeeenabled'=>'fooman_surcharge_minfee/enabled',
    'fooman_surcharge_minfee/minfeegroup'=>'fooman_surcharge_minfee/group',
    'fooman_surcharge_minfee/minfeeapplygroupfilter'=>'fooman_surcharge_minfee/applygroupfilter',
    'fooman_surcharge_minfee/minfee'=>'fooman_surcharge_minfee/fixed',
    'fooman_surcharge_minfee/minfeedescription'=>'fooman_surcharge_minfee/description',

    'fooman_surcharge_minenforced/minenabled'=>'fooman_surcharge_minenforced/enabled',
    'fooman_surcharge_minenforced/minforcegroup'=>'fooman_surcharge_minenforced/group',
    'fooman_surcharge_minenforced/minforceapplygroupfilter'=>'fooman_surcharge_minenforced/applygroupfilter',
    'fooman_surcharge_minenforced/mindescription'=>'fooman_surcharge_minenforced/description',

    'fooman_surcharge_cc/enableccsurcharge'=>'fooman_surcharge_cc/enabled',
    'fooman_surcharge_cc/ccsurchargehandlingtype'=>'fooman_surcharge_cc/handlingtype',
    'fooman_surcharge_cc/ccsurchargefixed'=>'fooman_surcharge_cc/fixed',
    'fooman_surcharge_cc/ccsurchargerate'=>'fooman_surcharge_cc/rate',
    'fooman_surcharge_cc/ccsurchargedescription'=>'fooman_surcharge_cc/description',

    'fooman_surcharge_cc2/enableccsurcharge'=>'fooman_surcharge_cc2/enabled',
    'fooman_surcharge_cc2/ccsurchargehandlingtype'=>'fooman_surcharge_cc2/handlingtype',
    'fooman_surcharge_cc2/ccsurchargefixed'=>'fooman_surcharge_cc2/fixed',
    'fooman_surcharge_cc2/ccsurchargerate'=>'fooman_surcharge_cc2/rate',
    'fooman_surcharge_cc2/ccsurchargedescription'=>'fooman_surcharge_cc2/description',
    
    'fooman_surcharge_method/enablemethodsurcharge'=>'fooman_surcharge_method/enabled',
    'fooman_surcharge_method/methodsurchargehandlingtype'=>'fooman_surcharge_method/handlingtype',
    'fooman_surcharge_method/methodsurchargefixed'=>'fooman_surcharge_method/fixed',
    'fooman_surcharge_method/methodsurchargerate'=>'fooman_surcharge_method/rate',
    'fooman_surcharge_method/methodsurchargedescription'=>'fooman_surcharge_method/description',

    'fooman_surcharge_method2/enablemethodsurcharge'=>'fooman_surcharge_method2/enabled',
    'fooman_surcharge_method2/methodsurchargehandlingtype'=>'fooman_surcharge_method2/handlingtype',
    'fooman_surcharge_method2/methodsurchargefixed'=>'fooman_surcharge_method2/fixed',
    'fooman_surcharge_method2/methodsurchargerate'=>'fooman_surcharge_method2/rate',
    'fooman_surcharge_method2/methodsurchargedescription'=>'fooman_surcharge_method2/description',

    'fooman_surcharge_method3/enablemethodsurcharge'=>'fooman_surcharge_method3/enabled',
    'fooman_surcharge_method3/methodsurchargehandlingtype'=>'fooman_surcharge_method3/handlingtype',
    'fooman_surcharge_method3/methodsurchargefixed'=>'fooman_surcharge_method3/fixed',
    'fooman_surcharge_method3/methodsurchargerate'=>'fooman_surcharge_method3/rate',
    'fooman_surcharge_method3/methodsurchargedescription'=>'fooman_surcharge_method3/description',

    'fooman_surcharge_method4/enablemethodsurcharge'=>'fooman_surcharge_method4/enabled',
    'fooman_surcharge_method4/methodsurchargehandlingtype'=>'fooman_surcharge_method4/handlingtype',
    'fooman_surcharge_method4/methodsurchargefixed'=>'fooman_surcharge_method4/fixed',
    'fooman_surcharge_method4/methodsurchargerate'=>'fooman_surcharge_method4/rate',
    'fooman_surcharge_method4/methodsurchargedescription'=>'fooman_surcharge_method4/description',

    'fooman_surcharge_method5/enablemethodsurcharge'=>'fooman_surcharge_method5/enabled',
    'fooman_surcharge_method5/methodsurchargehandlingtype'=>'fooman_surcharge_method5/handlingtype',
    'fooman_surcharge_method5/methodsurchargefixed'=>'fooman_surcharge_method5/fixed',
    'fooman_surcharge_method5/methodsurchargerate'=>'fooman_surcharge_method5/rate',
    'fooman_surcharge_method5/methodsurchargedescription'=>'fooman_surcharge_method5/description',

    'fooman_surcharge_country/addresstype'=>'fooman_surcharge_country/applycountryfilter',

);

foreach ($oldToNewMap as $old => $new) {
    $installer->run(
        "UPDATE {$this->getTable('core_config_data')}
        SET `path`='surcharge/{$new}' WHERE `path`='surcharge/{$old}';"
    );
}
$installer->run(
    "UPDATE {$this->getTable('core_config_data')}
    SET `value`='1' WHERE `value`='shipping' AND `path`='surcharge/fooman_surcharge_country/applycountryfilter';"
);
$installer->run(
    "UPDATE {$this->getTable('core_config_data')}
    SET `value`='2' WHERE `value`='billing' AND `path`='surcharge/fooman_surcharge_country/applycountryfilter';"
);

$installer->endSetup();
