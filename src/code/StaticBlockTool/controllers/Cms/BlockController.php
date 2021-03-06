<?php
require_once 'Mage/Adminhtml/controllers/Cms/BlockController.php';

/**
 * Class Medvind_StaticBlockTool_Cms_BlockController
 *
 * @package    Medvind_StaticBlockTool
 * @author     Péter Tóth <peter@petertoth.se>
 */
class Medvind_StaticBlockTool_Cms_BlockController extends Mage_Adminhtml_Cms_BlockController
{

    /**
     * Retrieve block data to export
     *
     * @param bool $includeStores
     * @return array
     */
    private function _getBlockData($includeStores = false)
    {
        $blockIds = Mage::app()->getRequest()->getPost('block_id');
        if (!$blockIds) {
            return;
        }

        $collection = Mage::getModel('cms/block')
            ->getCollection()
            ->addFieldToFilter('block_id', array('in', $blockIds))
            ->load();

        if ($items = $collection->getItems()) {
            $data = array();
            foreach ($items as $item) {
                if ($includeStores) {
                    $thisBlock = Mage::getModel('cms/block')->load($item['identifier']);
                    $data[] = $thisBlock->getData();
                } else {
                    $data[] = $item->getData();
                }
                continue;
            }
            return $data;
        }
    }

    public function exportSqlAction()
    {
        if ($blocks = $this->_getBlockData(true)) {
            $data = "-- Static block export file generated by Medvind_StaticBlockTool";
            foreach ($blocks as $block) {
                $content = addslashes($block['content']);
                $data .= "INSERT INTO cms_block (block_id, title, identifier, content, creation_time, update_time, is_active) VALUES ('{$block['block_id']}', '{$block['title']}', '{$block['identifier']}', '{$content}', '{$block['creation_time']}', '{$block['update_time']}', '{$block['is_active']}');\n";
                foreach ($block['stores'] as $storeId) {
                    $data .= "INSERT INTO cms_block_store (block_id, store_id) VALUES ('{$block['block_id']}', '{$storeId}');\n";
                }
            }

            $fileName = 'staticblocks-export.sql';
            header('Content-Type: text/x-sql');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"{$fileName}\"");
            echo $data;
        }
    }

    public function exportJsonAction()
    {
        $data = $this->_getBlockData();

        if ($data) {
            $fileName = 'staticblocks.json';
            header('Content-Type: application/json');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"{$fileName}\"");
            echo json_encode($data);
        }
    }

    public function exportMigrationAction()
    {
        if ($blocks = $this->_getBlockData(true)) {
$data = <<<'EOT'
<?php
/*
 * Database migration file generated with Medvind_StaticBlockTool
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
$blockModel = Mage::getModel('cms/block');

EOT;

            foreach ($blocks as $block) {
$data .= '
$content = <<<EOT
'. $block['content'] .'
EOT;

$stores = array(' . implode(",", $block['stores']) . ');

$blockModel->setContent("$content")
    ->setCreationTime("'. $block['creation_time'] .'")
    ->setIdentifier("'. $block['identifier'] .'")
    ->setIsActive("'. $block['is_active'] .'")
    ->setStores($stores)
    ->setTitle("'. $block['title'] .'")
    ->setUpdateTime("'. $block['update_time'] .'")
    ->save();

$blockModel = new $blockModel;
';
            }

            $data .= '$installer->endSetup();';
            $fileName = 'staticblocks-migration.php';
            header('Content-Type: text/x-php');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"{$fileName}\"");
            echo $data;
        }
    }
}