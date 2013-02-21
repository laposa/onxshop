<?php
/** 
 * Copyright (c) 2005-2013 Laposa Ltd (http://laposa.co.uk)
 * Licensed under the New BSD License. See the file LICENSE.txt for details.
 * 
 */

class Onxshop_Controller_Bo_Component_Client_User_Prefs extends Onxshop_Controller {

	/**
	 * main action
	 */
	 
	public function mainAction() {
	
		require_once('models/client/client_customer.php');
		$Customer = new client_customer();
		$Customer->setCacheable(false);
		
		$customer_id = $this->GET['customer_id'];
		if (!is_numeric($customer_id)) return false;
		
		if ($_POST['save']) {
			$_POST['client']['customer']['id'] = $customer_id;
			
			if ($Customer->updateClient($_POST['client'])) {
				msg('Client Data Updated');
			} else {
				msg("Can't update client data", 'error');
			}
			
		}
		
		
		$client_data = $Customer->getClientData($customer_id);
		
		$client_data['customer']['newsletter'] = ($client_data['customer']['newsletter'] == 1) ? 'checked="checked" ' : '';
		
		$this->tpl->assign('CLIENT', $client_data);
		
		//allow to change group
		require_once('models/client/client_group.php');
		$ClientGroup = new client_group();
		$list = $ClientGroup->listGroups();

		foreach ($list as $item) {
		
			$this->tpl->assign('ITEM', $item);
			if ($item['id'] == $client_data['customer']['group_id']) $this->tpl->assign('SELECTED', 'selected="selected"');
			else $this->tpl->assign('SELECTED', '');
		
			$this->tpl->parse('content.status.group.item');
		}
		$this->tpl->parse('content.status.group');

		//allow to change status
		$this->tpl->assign("SELECTED_status_{$client_data['customer']['status']}", 'selected="selected"');
		$this->tpl->parse('content.status');

		//and allow to change account type
		$this->tpl->assign("SELECTED_account_type_{$client_data['customer']['account_type']}", 'selected="selected"');
		$this->tpl->parse('content.account_type');

		return true;
	}
}
