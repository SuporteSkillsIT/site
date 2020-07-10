<?php
namespace KJ\MageMail\Model\Observer\Catalog;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Magento Bug: Quote items updated_at field not set at item creation
 * Magento 2.1.4 and previous versions don't update the field updated_at in the table catalog_product_entity
 * when a product is updated. This simple fix uses raw SQL to force update the field.
 *
 * Class ProductUpdateTimestamp
 * @package KJ\MageMail\Model\Observer\Catalog
 */

class QuoteItemUpdateTimestamp implements ObserverInterface
{
    private $resource;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource)
    {
        $this->resource = $resource;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quoteItem = $observer->getItem();

        if ($quoteItem && $quoteItem->getId()) {

            $itemId = $quoteItem->getId();

            $connection = $this->resource->getConnection();
            $tableName = $this->resource->getTableName('quote_item');
            $connection->query(
                "UPDATE `" . $tableName . "` SET `updated_at` = NOW() WHERE `item_id` =  '" . $itemId . "' LIMIT 1"
            );
        }
    }
}