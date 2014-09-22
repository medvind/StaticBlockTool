<?php

/**
 * Adminhtml cms blocks content block
 *
 * @package    Medvind_StaticBlockTool
 * @author     Péter Tóth <peter@petertoth.se>
 */
class Medvind_StaticBlockTool_Block_Adminhtml_Cms_Block extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_cms';
        $this->_headerText = Mage::helper('cms')->__('Static Blocks');
        $this->_addButtonLabel = Mage::helper('cms')->__('Add New Block');
        parent::__construct();
    }

}
