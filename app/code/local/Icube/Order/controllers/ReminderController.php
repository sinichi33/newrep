<?php
class Icube_Order_ReminderController extends Mage_Core_Controller_Front_Action
{

public function reminderAction(){
$helper = Mage::helper('Icube_order')->getReminderData();

echo $helper;
	//echo 'testing';

	}

}