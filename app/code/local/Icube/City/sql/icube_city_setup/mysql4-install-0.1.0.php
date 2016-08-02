<?php

$installer=$this;
$installer->startSetup();
$installer->run("INSERT INTO `{$this->getTable('directory/country_region')}` (`country_id`,`code`,`default_name`) VALUES
	('ID', 'ID-BA', 'BALI'),
	('ID', 'ID-BB', 'BANGKA BELITUNG'),
	('ID', 'ID-BT', 'BANTEN'),
	('ID', 'ID-BE', 'BENGKULU'),
	('ID', 'ID-YO', 'D.I. YOGYAKARTA'),
	('ID', 'ID-JK', 'DKI JAKARTA'),
	('ID', 'ID-GO', 'GORONTALO'),
	('ID', 'ID-JA', 'JAMBI'),
	('ID', 'ID-JB', 'JAWA BARAT'),
	('ID', 'ID-JT', 'JAWA TENGAH'),
	('ID', 'ID-JI', 'JAWA TIMUR'),
	('ID', 'ID-KB', 'KALIMANTAN BARAT'),
	('ID', 'ID-KS', 'KALIMANTAN SELATAN'),
	('ID', 'ID-KT', 'KALIMANTAN TENGAH'),
	('ID', 'ID-KI', 'KALIMANTAN TIMUR'),
	('ID', 'ID-KR', 'KEPULAUAN RIAU'),
	('ID', 'ID-LA', 'LAMPUNG'),
	('ID', 'ID-MA', 'MALUKU'),
	('ID', 'ID-MU', 'MALUKU UTARA'),
	('ID', 'ID-AC', 'NAD'),
	('ID', 'ID-NB', 'NTB'),
	('ID', 'ID-NT', 'NTT'),
	('ID', 'ID-PA', 'PAPUA'),
	('ID', 'ID-PB', 'PAPUA BARAT'),
	('ID', 'ID-RI', 'RIAU'),
	('ID', 'ID-SR', 'SULAWESI BARAT'),
	('ID', 'ID-SN', 'SULAWESI SELATAN'),
	('ID', 'ID-ST', 'SULAWESI TENGAH'),
	('ID', 'ID-SG', 'SULAWESI TENGGARA'),
	('ID', 'ID-SA', 'SULAWESI UTARA'),
	('ID', 'ID-SB', 'SUMATERA BARAT'),
	('ID', 'ID-SS', 'SUMATERA SELATAN'),
	('ID', 'ID-SU', 'SUMATERA UTARA')
;");

$installer->endSetup();

?>