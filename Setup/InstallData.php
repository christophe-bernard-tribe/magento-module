<?php
namespace Ubista\Ubista\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $columns = [];
        $columns = ['name', 'value', 'status', 'created_time', 'update_time'];
        $data = [];
        $data = [['custom_param', '1', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]];

        $setup->getConnection()
            ->insertArray($setup->getTable('ubista_parameters'), $columns, $data);

        /**
         * Prepare database after install
         */
        $setup->endSetup();
    }

}
