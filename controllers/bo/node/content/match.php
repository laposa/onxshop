<?php
/** 
 * Copyright (c) 2018 Laposa Ltd (http://laposa.co.uk)
 * Licensed under the New BSD License. See the file LICENSE.txt for details.
 *
 */

require_once('controllers/bo/node/content/default.php');
require_once('models/common/common_taxonomy.php');

class Onxshop_Controller_Bo_Node_Content_Match extends Onxshop_Controller_Bo_Node_Content_Default {

    const ONXSHOP_COMPETITION_ROOT_TAXONOMY_TREE_ID = 633;
    const ONXSHOP_ROUND_ROOT_TAXONOMY_TREE_ID = 635;
    const ONXSHOP_VENUE_ROOT_TAXONOMY_TREE_ID = 636;
    const ONXSHOP_TEAM_ROOT_TAXONOMY_TREE_ID = 634;

    const ONXSHOP_RESULTS_PAGE_NODE_ID = 21751;
    
    /**
     * pre action
     */

    function pre() {
    
        parent::pre();
    }
    
    /**
     * post action
     */
     
    function post() {
    

        if (trim($this->node_data['component']['date']) == '') $this->node_data['component']['date'] = strftime('%Y-%m-%d');
        
        $this->parseCategoryDropdown(self::ONXSHOP_COMPETITION_ROOT_TAXONOMY_TREE_ID, 'competition');
        $this->parseCategoryDropdown(self::ONXSHOP_ROUND_ROOT_TAXONOMY_TREE_ID, 'round');
        $this->parseCategoryDropdown(self::ONXSHOP_VENUE_ROOT_TAXONOMY_TREE_ID, 'venue');
        $this->parseCategoryDropdown(self::ONXSHOP_TEAM_ROOT_TAXONOMY_TREE_ID, 'home_team');
        $this->parseCategoryDropdown(self::ONXSHOP_TEAM_ROOT_TAXONOMY_TREE_ID, 'away_team');
    
        parent::post();    
    }

    /**
     * save
     *
     */
     
    function save() {

        if (!empty($_POST['node']['component']['home_team_result'])) {
            $_POST['node']['parent'] = self::ONXSHOP_RESULTS_PAGE_NODE_ID;
        }

        parent::save();

    }
    
    /**
     * parseCategoryDropdown
     * @param int $parent_category_id
     * @param string $item_name
     * @returns boolean
     */
     
    public function parseCategoryDropdown($parent_category_id, $item_name)
    {
        if (!is_numeric($parent_category_id)) return false;
        if (trim($item_name) == '') return false;
        
        $Taxonomy = new common_taxonomy();
        $list = $Taxonomy->getChildren($parent_category_id);
        foreach ($list as $item) {
            $item['selected'] = $this->node_data['component'][$item_name] == $item['id'] ? 'selected="selected"' : '';
            $this->tpl->assign('ITEM', $item);
            $this->tpl->parse('content.'.$item_name);
        }
        
        return true;
    }

}