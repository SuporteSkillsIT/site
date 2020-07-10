<?php
namespace KJ\MageMail\Model\Observer\Catalog;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Magento Bug: Quotes updated_at field not set at item creation
 * Magento 2.1.4 and previous versions don't update the field updated_at in the table catalog_product_entity
 * when a product is updated. This simple fix uses raw SQL to force update the field.
 *
 * Class QuoteUpdateTimestamp
 * @package KJ\MageMail\Model\Observer\Catalog
 */

class QuoteUpdateTimestamp implements ObserverInterface
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
        $quote = $observer->getQuote();

        if ($quote && $quote->getId()) {

            $quoteId = $quote->getId();

            $connection = $this->resource->getConnection();
            $tableName = $this->resource->getTableName('quote');
            $connection->query(
                "UPDATE `" . $tableName . "` SET `updated_at` = NOW() WHERE `entity_id` =  '" . $quoteId . "' LIMIT 1"
            );
        }
    }
}