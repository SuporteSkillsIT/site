<?php
namespace KJ\MageMail\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;

        $installer->startSetup();

        $installer->getConnection()
            ->addColumn($installer->getTable('cataloginventory_stock_item'),'updated_at', array(
                'type'      => Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'default'   => Table::TIMESTAMP_INIT_UPDATE,
                'comment'   => 'Updated At'
            ));

        $installer->getConnection()
            ->addColumn($installer->getTable('newsletter_subscriber'),'magemail_source', array(
                'type'      => Table::TYPE_TEXT,
                'nullable'  => true,
                'length'    => 100,
                'comment'   => 'Magemail Source'
            ));

        $installer->getConnection()
            ->addColumn($installer->getTable('newsletter_subscriber'),'magemail_created_at', array(
                'type'      => Table::TYPE_TIMESTAMP,
                'nullable'  => true,
                'comment'   => 'Magemail Source',
                'default'   => Table::TIMESTAMP_INIT,
            ));

        $installer->getConnection()
            ->addColumn($installer->getTable('newsletter_subscriber'),'magemail_updated_at', array(
                'type'      => Table::TYPE_TIMESTAMP,
                'nullable'  => true,
                'comment'   => 'Magemail Source',
            ));

        $table = $installer->getTable('newsletter_subscriber');

        $sql1= "ALTER TABLE `{$table}` ADD INDEX IDX_NEWSLETTER_SUBSCRIBER_MAGEMAIL_UPDATED_AT (`magemail_updated_at`);";
        $sql2 = "CREATE TRIGGER newsletter_subscriber_updated_at_before_insert BEFORE INSERT ON {$table} FOR EACH ROW SET NEW.magemail_updated_at = CURRENT_TIMESTAMP;";
        $sql3 = "CREATE TRIGGER newsletter_subscriber_updated_at_before_update BEFORE UPDATE ON {$table} FOR EACH ROW SET NEW.magemail_updated_at = CURRENT_TIMESTAMP;";

        try {
            $installer->run($sql1);
            $installer->run($sql2);
            $installer->run($sql3);
        } catch (\PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') === false) {
                throw $e;
            }
        }


        $installer->endSetup();

    }
}
