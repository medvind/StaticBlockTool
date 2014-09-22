<?php
/**
 * Adminhtml cms blocks grid
 *
 * @package    Medvind_StaticBlockTool
 * @author     Péter Tóth <peter@petertoth.se>
 */
class Medvind_StaticBlockTool_Block_Adminhtml_Cms_Block_Grid extends Mage_Adminhtml_Block_Cms_Block_Grid
{

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('block_id');
        $this->getMassactionBlock()->setFormFieldName('block_id');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('export_sql', array(
            'label' => 'Export as SQL',
            'url' => $this->getUrl('*/*/exportSql')
        ));
        $this->getMassactionBlock()->addItem('export_json', array(
            'label' => 'Export as JSON',
            'url' => $this->getUrl('*/*/exportJson')
        ));
        $this->getMassactionBlock()->addItem('export_migration', array(
            'label' => 'Export as migration',
            'url' => $this->getUrl('*/*/exportMigration')
        ));

        return $this;
    }
}
