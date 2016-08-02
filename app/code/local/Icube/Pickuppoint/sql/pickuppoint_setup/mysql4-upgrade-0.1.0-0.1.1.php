<?php
$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('pickuppoint')}
		ADD COLUMN `pic_email` VARCHAR(50) NOT NULL
		AFTER `geocode`;
");
$installer->run("
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.simpanglima@ruparupa.com',name='Mall Living Plaza Semarang Lt. Dasar' WHERE pickup_id=1;
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.lenmarc@ruparupa.com',name='Mall Lenmarc Surabaya Lt. UG'  WHERE pickup_id=2;
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.ibcc@ruparupa.com',name='Mall IBCC Bandung' WHERE pickup_id=3;
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.alamsutera@ruparupa.com',name='Mall Living World Tanggerang Lt. UG' WHERE pickup_id=6;
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.mag@ruparupa.com',name='Mall Artha Gading Jakarta Lt. 3' WHERE pickup_id=7;
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.ayb@ruparupa.com',name='Mall Living Plaza Bekasi Lt. Dasar' WHERE pickup_id=8;
	UPDATE {$this->getTable('pickuppoint')} SET pic_email ='cs.gandaria@ruparupa.com',name='Mall Gandaria City Jakarta Lt. LG' WHERE pickup_id=9;
");

$installer->endSetup();