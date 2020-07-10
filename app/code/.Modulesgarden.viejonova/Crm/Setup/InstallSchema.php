<?php

namespace Modulesgarden\Crm\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup,
            ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        try {
            //install database schema
            $schema = file_get_contents(dirname(__DIR__) . '/database/1.0.0/schema.sql');
            $relations = file_get_contents(dirname(__DIR__) . '/database/1.0.0/relations.sql');
            $data = file_get_contents(dirname(__DIR__) . '/database/1.0.0/data.sql');
            $tables = explode(';', $schema);
            $installer->getConnection()->beginTransaction();
            foreach ($tables as $table) {
                if ($table != null || $table != '') {
                    $installer->getConnection()->query($table);
                }
            }

            //install database relations
            $queries = explode(';', $relations);
            foreach ($queries as $relation) {
                if ($relation != null || $relation != '') {
                    $installer->getConnection()->query($relation);
                }
            }

            //insert predefined data
            $queries = explode(';', $data);
            foreach ($queries as $query) {
                if ($query != null || $query != '') {
                    $installer->getConnection()->query($query);
                }
            }
            $installer->getConnection()->commit();
        } catch (Exception $e) {
            echo 'Installation fails. Error Message: ' . $e->getMessage();
        }

        $installer->endSetup();
    }

}
