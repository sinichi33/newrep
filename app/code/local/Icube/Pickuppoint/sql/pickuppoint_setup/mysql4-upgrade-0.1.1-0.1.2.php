<?php
$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE `pointofsale`
      ADD COLUMN `manager_email` VARCHAR(50) NOT NULL
      AFTER `pickup_location_label`;
");

$installer->run('
	update pointofsale set manager_email="mgr_simpanglima@acehardware.co.id" where place_id=3;
    update pointofsale set manager_email="mgr_lenmarc@acehardware.co.id" where place_id=4;
    update pointofsale set manager_email="mgr_ibcc@acehardware.co.id" where place_id=5;
    update pointofsale set manager_email="mgr_latanete@acehardware.co.id" where place_id=6;
    update pointofsale set manager_email="mgr_centrepoint@acehardware.co.id" where place_id=7;
    update pointofsale set manager_email="mgr_alamsutera@acehardware.co.id" where place_id=8;
    update pointofsale set manager_email="mgr_ayb@acehardware.co.id" where place_id=10;
    update pointofsale set manager_email="mgr_gandaria@acehardware.co.id" where place_id=11;
    update pointofsale set manager_email="mgr.simpanglima@homecenter.co.id" where place_id=12;
    update pointofsale set manager_email="mgr.lenmarc@homecenter.co.id" where place_id=13;
    update pointofsale set manager_email="mgr.ibcc@homecenter.co.id" where place_id=14;
    update pointofsale set manager_email="mgr.latanete@homecenter.co.id" where place_id=15;
    update pointofsale set manager_email="mgr.centrepoint@homecenter.co.id" where place_id=16;
    update pointofsale set manager_email="ahmad.sofyan@homecenter.co.id" where place_id=18;
    update pointofsale set manager_email="mgr.ayb@homecenter.co.id" where place_id=19;
    update pointofsale set manager_email="mgr.gandaria@homecenter.co.id" where place_id=20;
    update pointofsale set manager_email="mgrtoys.simpanglima@toyskingdom.co.id" where place_id=21;
    update pointofsale set manager_email="mgrtoys.lenmarc@toyskingdom.co.id" where place_id=22;
    update pointofsale set manager_email="mgrtoys.ibcc@toyskingdom.co.id" where place_id=23;
    update pointofsale set manager_email="mgrtoys.latanete@toyskingdom.co.id" where place_id=24;
    update pointofsale set manager_email="mgrtoys.centrepoint@toyskingdom.co.id" where place_id=25;
    update pointofsale set manager_email="mgrtoys.alamsutera@toyskingdom.co.id" where place_id=26;
    update pointofsale set manager_email="mgrtoys.mag@toyskingdom.co.id" where place_id=27;
    update pointofsale set manager_email="mgrtoys.ayb@toyskingdom.co.id" where place_id=28;
    update pointofsale set manager_email="mgrtoys.gandaria@toyskingdom.co.id" where place_id=29;
');

$installer->endSetup();


